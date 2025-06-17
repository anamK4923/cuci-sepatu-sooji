<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display customer's orders
     */
    public function index()
    {
        $orders = Order::with(['service'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create(Service $service)
    {
        return view('member.orders.create', compact('service'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'delivery_method' => ['required', Rule::in([Order::DELIVERY_ANTAR_JEMPUT, Order::DELIVERY_DROP_OFF])],
            'alamat_pickup' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|string|max:1000',
            'pickup_schedule' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|date|after:+2 hours',
            'notes' => 'nullable|string|max:1000',
            'total_price' => 'required|numeric|min:0',
        ], [
            'service_id.required' => 'Layanan harus dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'delivery_method.required' => 'Metode pengiriman harus dipilih.',
            'delivery_method.in' => 'Metode pengiriman tidak valid.',
            'alamat_pickup.required_if' => 'Alamat penjemputan wajib diisi untuk metode antar jemput.',
            'alamat_pickup.max' => 'Alamat penjemputan maksimal 1000 karakter.',
            'pickup_schedule.required_if' => 'Jadwal penjemputan wajib diisi untuk metode antar jemput.',
            'pickup_schedule.date' => 'Format jadwal penjemputan tidak valid.',
            'pickup_schedule.after' => 'Jadwal penjemputan minimal 2 jam dari sekarang.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
            'total_price.required' => 'Total harga harus ada.',
            'total_price.numeric' => 'Total harga harus berupa angka.',
            'total_price.min' => 'Total harga tidak boleh negatif.',
        ]);

        try {
            DB::beginTransaction();

            // Get service to verify price
            $service = Service::findOrFail($validated['service_id']);

            // Verify total price matches service price
            if ($validated['total_price'] != $service->price) {
                return back()->withErrors(['total_price' => 'Harga tidak sesuai dengan layanan yang dipilih.']);
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'service_id' => $validated['service_id'],
                'delivery_method' => $validated['delivery_method'],
                'alamat_pickup' => $validated['alamat_pickup'],
                'pickup_schedule' => $validated['pickup_schedule'],
                'notes' => $validated['notes'],
                'total_price' => $validated['total_price'],
                'status' => Order::STATUS_WAITING_PICKUP,
                'payment_status' => Order::PAYMENT_PENDING,
                'midtrans_order_id' => $this->generateMidtransOrderId($service->id),
            ]);

            DB::commit();

            return redirect()->route('member.orders.status')
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran untuk melanjutkan proses.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Display order status page
     */
    public function status()
    {
        $orders = Order::with(['service'])
            ->forUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('member.orders.status', compact('orders'));
    }

    /**
     * Show the specified order
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['service']);

        return view('member.orders.show', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        // Check if order can be cancelled
        if (in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])) {
            return back()->withErrors(['error' => 'Pesanan tidak dapat dibatalkan.']);
        }

        try {
            $order->update([
                'status' => Order::STATUS_CANCELLED
            ]);

            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membatalkan pesanan.']);
        }
    }

    /**
     * Generate Midtrans order ID
     */
    private function generateMidtransOrderId($serviceId): string
    {
        return 'SOOOJI-' . $serviceId . '-' . time() . '-' . Auth::id();
    }
}
