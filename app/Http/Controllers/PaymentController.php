<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Membuat token pembayaran untuk order
     */
    public function createPayment(Order $order)
    {
        try {
            // Pastikan user hanya bisa bayar order miliknya sendiri
            if ($order->user_id !== Auth::id()) {
                Log::warning('Unauthorized payment attempt', [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'order_user_id' => $order->user_id
                ]);
                return response()->json(['error' => 'Tidak diizinkan mengakses pesanan ini'], 403);
            }

            // Cek apakah order bisa dibayar
            if ($order->payment_status !== Order::PAYMENT_PENDING) {
                Log::info('Order cannot be paid', [
                    'order_id' => $order->id,
                    'payment_status' => $order->payment_status
                ]);
                return response()->json(['error' => 'Pesanan tidak dapat dibayar. Status: ' . $order->payment_status_label], 400);
            }

            // Cek apakah order sudah dibatalkan
            if ($order->status === Order::STATUS_CANCELLED) {
                return response()->json(['error' => 'Pesanan sudah dibatalkan'], 400);
            }

            // Load relasi yang diperlukan
            $order->load(['user', 'service']);

            // Validasi data yang diperlukan
            if (!$order->service) {
                Log::error('Order service not found', ['order_id' => $order->id]);
                return response()->json(['error' => 'Data layanan tidak ditemukan'], 400);
            }

            if (!$order->user) {
                Log::error('Order user not found', ['order_id' => $order->id]);
                return response()->json(['error' => 'Data pengguna tidak ditemukan'], 400);
            }

            Log::info('Creating payment transaction', [
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id,
                'total_price' => $order->total_price,
                'user_id' => $order->user_id
            ]);

            $transaction = $this->midtransService->createTransaction($order);

            Log::info('Payment transaction created successfully', [
                'order_id' => $order->id,
                'snap_token' => substr($transaction->token, 0, 10) . '...' // Log partial token for security
            ]);

            return response()->json([
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create payment transaction', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle notifikasi dari Midtrans
     */
    public function notification(Request $request)
    {
        try {
            $notification = $request->all();

            Log::info('Midtrans notification received', $notification);

            $orderId = $notification['order_id'] ?? null;
            $transactionStatus = $notification['transaction_status'] ?? null;
            $fraudStatus = $notification['fraud_status'] ?? '';

            if (!$orderId || !$transactionStatus) {
                Log::error('Invalid notification data', $notification);
                return response('Invalid notification data', 400);
            }

            // Cari order berdasarkan midtrans_order_id
            $order = Order::where('midtrans_order_id', $orderId)->first();

            if (!$order) {
                Log::error('Order not found for Midtrans order ID: ' . $orderId);
                return response('Order not found', 404);
            }

            DB::beginTransaction();

            $oldPaymentStatus = $order->payment_status;

            // Update status pembayaran berdasarkan status transaksi
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $order->update(['payment_status' => Order::PAYMENT_PENDING]);
                    } else if ($fraudStatus == 'accept') {
                        $order->update(['payment_status' => Order::PAYMENT_PAID]);
                    }
                    break;

                case 'settlement':
                    $order->update(['payment_status' => Order::PAYMENT_PAID]);
                    break;

                case 'pending':
                    $order->update(['payment_status' => Order::PAYMENT_PENDING]);
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $order->update(['payment_status' => Order::PAYMENT_FAILED]);
                    break;
            }

            DB::commit();

            Log::info('Order payment status updated', [
                'order_id' => $order->id,
                'old_status' => $oldPaymentStatus,
                'new_status' => $order->payment_status,
                'transaction_status' => $transactionStatus
            ]);

            return response('OK', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to handle Midtrans notification', [
                'error' => $e->getMessage(),
                'notification' => $request->all()
            ]);
            return response('Error processing notification', 500);
        }
    }

    /**
     * Handle pembayaran selesai dengan sukses
     */
    public function finish(Request $request)
    {
        $orderId = $request->get('order_id');

        Log::info('Payment finish callback', ['order_id' => $orderId]);

        if ($orderId) {
            $order = Order::where('midtrans_order_id', $orderId)->first();

            if ($order && $order->user_id === Auth::id()) {
                return redirect()->route('member.orders.status')
                    ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
            }
        }

        return redirect()->route('member.orders.status')
            ->with('info', 'Status pembayaran sedang diverifikasi.');
    }

    /**
     * Handle pembayaran yang belum selesai
     */
    public function unfinish(Request $request)
    {
        Log::info('Payment unfinish callback', $request->all());

        return redirect()->route('member.orders.status')
            ->with('warning', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran.');
    }

    /**
     * Handle error pembayaran
     */
    public function error(Request $request)
    {
        Log::error('Payment error callback', $request->all());

        return redirect()->route('member.orders.status')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.');
    }

    /**
     * Cek status pembayaran
     */
    public function checkStatus(Order $order)
    {
        try {
            // Pastikan user hanya bisa cek order miliknya sendiri
            if ($order->user_id !== Auth::id()) {
                return response()->json(['error' => 'Tidak diizinkan'], 403);
            }

            Log::info('Checking payment status', [
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id
            ]);

            $status = $this->midtransService->getTransactionStatus($order->midtrans_order_id);

            return response()->json([
                'transaction_status' => $status->transaction_status ?? 'unknown',
                'payment_type' => $status->payment_type ?? 'unknown',
                'transaction_time' => $status->transaction_time ?? 'unknown',
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to check payment status', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Gagal mengecek status pembayaran',
                'transaction_status' => 'error',
                'payment_type' => 'unknown',
                'transaction_time' => 'unknown'
            ], 500);
        }
    }
}
