<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log request data untuk debugging
            Log::info('Order request data:', $request->all());

            // Validasi input
            $validatedData = $request->validate([
                // 'service_name' => 'required|string|max:255',
                // 'customer_name' => 'required|string|max:255',
                // 'customer_phone' => 'required|string|max:20',
                'delivery_method' => 'required|in:pickup,drop_off',
                'alamat_pickup' => 'nullable|string|required_if:delivery_method,pickup',
                'pickup_schedule' => 'nullable|date|required_if:delivery_method,pickup',
                'notes' => 'nullable|string',
                'total_price' => 'required|numeric|min:0',
            ]);

            // Generate unique order ID untuk Midtrans
            $midtransOrderId = 'ORDER-' . Str::upper(Str::random(8)) . '-' . time();

            // Buat order baru menggunakan model
            $order = Order::create([
                'user_id' => 1, // Default ke 1 jika tidak ada auth
                // 'service_name' => $validatedData['service_name'],
                // 'customer_name' => $validatedData['customer_name'],
                // 'customer_phone' => $validatedData['customer_phone'],
                'delivery_method' => 'drop_off',
                'alamat_pickup' => $validatedData['alamat_pickup'],
                'pickup_schedule' => $validatedData['pickup_schedule'],
                'notes' => $validatedData['notes'],
                'status' => 'in_process',
                'payment_status' => 'paid',
                'total_price' => $validatedData['total_price'],
                'midtrans_order_id' => $midtransOrderId,
            ]);

            Log::info('Order created successfully:', ['order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => [
                    'order_id' => $order->id,
                    'midtrans_order_id' => $order->midtrans_order_id,
                    'status' => $order->status,
                    'total_price' => $order->formatted_price
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation error:', $e->errors());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Order creation error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $order->load('user') // Load relationship jika diperlukan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan'
            ], 404);
        }
    }

    public function index()
    {
        try {
            $orders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pesanan'
            ], 500);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
                'payment_status' => 'sometimes|in:unpaid,paid,refunded'
            ]);

            $order->update($request->only(['status', 'payment_status']));

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan'
            ], 500);
        }
    }
}
