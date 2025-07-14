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
            Log::info('Payment creation started', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id
            ]);

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

            // Cek konfigurasi Midtrans
            if (empty(config('midtrans.server_key')) || empty(config('midtrans.client_key'))) {
                Log::error('Midtrans configuration missing');
                return response()->json(['error' => 'Konfigurasi pembayaran tidak lengkap'], 500);
            }

            Log::info('Creating payment transaction', [
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id,
                'total_price' => $order->total_price,
                'user_id' => $order->user_id
            ]);

            $transaction = $this->midtransService->createTransaction($order);

            // Update payment status setelah berhasil create transaction
            // $order->update([
            //     'payment_status' => Order::PAYMENT_PAID
            // ]);

            Log::info('Payment transaction created successfully', [
                'order_id' => $order->id,
                'snap_token' => substr($transaction->token ?? '', 0, 10) . '...' // Log partial token for security
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
     * Handle notifikasi dari Midtrans (IMPROVED)
     */
    public function notification(Request $request)
    {
        try {
            $notification = $request->all();

            Log::info('Midtrans notification received', [
                'notification' => $notification,
                'headers' => $request->headers->all(),
                'ip' => $request->ip(),
            ]);

            dd($notification);

            $orderId = $notification['order_id'] ?? null;
            $transactionStatus = $notification['transaction_status'] ?? null;
            $fraudStatus = $notification['fraud_status'] ?? '';
            $paymentType = $notification['payment_type'] ?? '';
            $grossAmount = $notification['gross_amount'] ?? null;
            $signatureKey = $notification['signature_key'] ?? null;
            $statusCode = $notification['status_code'] ?? null;

            if (!$orderId || !$transactionStatus) {
                Log::error('Invalid Midtrans notification data', $notification);
                return response('Invalid notification data', 400);
            }

            // Validasi Signature (opsional tapi direkomendasikan)
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

            if ($signatureKey !== $expectedSignature) {
                Log::warning('Invalid signature key received', [
                    'order_id' => $orderId,
                    'provided' => $signatureKey,
                    'expected' => $expectedSignature,
                ]);
                return response('Invalid signature', 403);
            }

            $order = Order::where('midtrans_order_id', $orderId)->first();

            if (!$order) {
                Log::error("Order not found for Midtrans order ID: {$orderId}");
                return response('Order not found', 404);
            }

            DB::beginTransaction();

            $oldPaymentStatus = $order->payment_status;
            $newPaymentStatus = $this->determinePaymentStatus($transactionStatus, $fraudStatus, $paymentType);

            if ($newPaymentStatus && $newPaymentStatus !== $oldPaymentStatus) {
                $order->update([
                    'payment_status' => $newPaymentStatus,
                    'paid_at' => in_array($newPaymentStatus, [Order::PAYMENT_PAID]) ? now() : null
                ]);

                Log::info('Order payment status updated via Midtrans webhook', [
                    'order_id' => $order->id,
                    'midtrans_order_id' => $order->midtrans_order_id,
                    'old_status' => $oldPaymentStatus,
                    'new_status' => $newPaymentStatus,
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'payment_type' => $paymentType
                ]);
            } else {
                Log::info('No payment status update needed', [
                    'order_id' => $order->id,
                    'current_status' => $order->payment_status,
                    'transaction_status' => $transactionStatus
                ]);
            }

            DB::commit();

            return response('OK', 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to process Midtrans webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'notification' => $request->all()
            ]);

            return response('Internal Server Error', 500);
        }
    }



    /**
     * Determine payment status based on Midtrans response
     */
    private function determinePaymentStatus($transactionStatus, $fraudStatus, $paymentType)
    {
        if (in_array($transactionStatus, ['settlement', 'capture']) && $fraudStatus !== 'deny') {
            return Order::PAYMENT_PAID;
        }

        if ($transactionStatus === 'pending') {
            return Order::PAYMENT_PENDING;
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return Order::PAYMENT_FAILED;
        }

        return null; // Unknown status
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
                // Auto-update payment status jika belum terupdate
                if ($order->payment_status === Order::PAYMENT_PENDING) {
                    try {
                        $status = $this->midtransService->getTransactionStatus($order->midtrans_order_id);
                        $newStatus = $this->determinePaymentStatus(
                            $status->transaction_status ?? '',
                            $status->fraud_status ?? '',
                            $status->payment_type ?? ''
                        );

                        if ($newStatus === Order::PAYMENT_PAID) {
                            $order->update(['payment_status' => $newStatus]);
                            Log::info('Payment status auto-updated on finish', [
                                'order_id' => $order->id,
                                'new_status' => $newStatus
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to auto-update on finish', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }

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
     * Cek status pembayaran (IMPROVED)
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

            // Pastikan $status adalah object
            if (is_array($status)) {
                $status = (object) $status;
            }

            // Auto-update status jika pembayaran berhasil tapi belum terupdate
            if (isset($status->transaction_status) && $order->payment_status === Order::PAYMENT_PENDING) {
                $newStatus = $this->determinePaymentStatus(
                    $status->transaction_status,
                    $status->fraud_status ?? '',
                    $status->payment_type ?? ''
                );

                if ($newStatus === Order::PAYMENT_PAID) {
                    $order->update(['payment_status' => $newStatus]);

                    Log::info('Payment status auto-updated during status check', [
                        'order_id' => $order->id,
                        'old_status' => Order::PAYMENT_PENDING,
                        'new_status' => $newStatus,
                        'midtrans_status' => $status->transaction_status
                    ]);
                }
            }

            return response()->json([
                'transaction_status' => $status->transaction_status ?? 'unknown',
                'payment_type' => $status->payment_type ?? 'unknown',
                'transaction_time' => $status->transaction_time ?? 'unknown',
                'order_id' => $order->id,
                'midtrans_order_id' => $order->midtrans_order_id,
                'fraud_status' => $status->fraud_status ?? '',
                'current_payment_status' => $order->fresh()->payment_status // Get fresh data
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

    /**
     * Tambahkan method untuk testing pembayaran manual
     */
    public function testPayment(Order $order)
    {
        try {
            // Hanya untuk testing - jangan gunakan di production
            if (app()->environment('local')) {
                $order->update([
                    'payment_status' => Order::PAYMENT_PAID
                ]);

                Log::info('Test payment completed', ['order_id' => $order->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Test payment completed successfully'
                ]);
            }

            return response()->json(['error' => 'Test payment only available in local environment'], 403);
        } catch (\Exception $e) {
            Log::error('Test payment failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Test payment failed'], 500);
        }
    }

    public function markPaid(Order $order)
    {
        if ($order->payment_status === Order::PAYMENT_PAID) {
            return response()->json(['message' => 'Order already paid'], 200);
        }

        $order->update([
            'payment_status' => Order::PAYMENT_PAID,
            'paid_at' => now()
        ]);

        Log::info("Order {$order->id} manually marked as PAID from frontend");

        return response()->json(['message' => 'Payment status updated'], 200);
    }
}
