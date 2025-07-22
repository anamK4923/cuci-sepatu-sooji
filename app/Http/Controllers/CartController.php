<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Add a service to the cart (session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $serviceId = $request->input('service_id');
        $cart = $request->session()->get('cart', []);

        // Check if service already in cart to prevent duplicates for simplicity
        // For more complex carts, you might increment quantity
        if (!in_array($serviceId, array_column($cart, 'service_id'))) {
            $cart[] = ['service_id' => $serviceId];
            $request->session()->put('cart', $cart);
            return redirect()->route('member.cart.index')->with('success', 'Layanan berhasil ditambahkan ke keranjang!');
        }

        return redirect()->route('member.cart.index')->with('info', 'Layanan sudah ada di keranjang.');
    }

    /**
     * Display the cart contents.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cartItems = $request->session()->get('cart', []);
        $services = collect();
        $totalCartPrice = 0;
        $servicePrices = []; // Tambahkan ini

        if (!empty($cartItems)) {
            $serviceIds = array_column($cartItems, 'service_id');
            $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');

            foreach ($cartItems as $item) {
                if ($services->has($item['service_id'])) {
                    $service = $services[$item['service_id']];
                    $totalCartPrice += $service->price;
                    $servicePrices[$service->id] = $service->price; // Tambahkan ini
                }
            }
        }

        return view('member.cart.index', compact('services', 'cartItems', 'totalCartPrice', 'servicePrices'));
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $serviceIdToRemove = $request->input('service_id');
        $cart = $request->session()->get('cart', []);

        $updatedCart = array_filter($cart, function ($item) use ($serviceIdToRemove) {
            return $item['service_id'] != $serviceIdToRemove;
        });

        $request->session()->put('cart', array_values($updatedCart)); // Re-index array
        return redirect()->route('member.cart.index')->with('success', 'Layanan berhasil dihapus dari keranjang.');
    }

    /**
     * Process selected cart items into orders (checkout).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'selected_services' => 'required|array|min:1',
            'selected_services.*' => 'exists:services,id', // Ensure selected service IDs exist
            'delivery_method' => ['required', Rule::in([Order::DELIVERY_ANTAR_JEMPUT, Order::DELIVERY_DROP_OFF])],
            'alamat_pickup' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|string|max:1000',
            'pickup_schedule' => [
                'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT,
                'nullable',
                Rule::in(['12:00', '18:00'])
            ],
            'notes' => 'nullable|string|max:1000',
        ], [
            'selected_services.required' => 'Pilih setidaknya satu layanan untuk checkout.',
            'selected_services.*.exists' => 'Layanan yang dipilih tidak valid.',
            'delivery_method.required' => 'Metode pengiriman harus dipilih.',
            'delivery_method.in' => 'Metode pengiriman tidak valid.',
            'alamat_pickup.required_if' => 'Alamat penjemputan wajib diisi untuk metode antar jemput.',
            'alamat_pickup.max' => 'Alamat penjemputan maksimal 1000 karakter.',
            'pickup_schedule.required_if' => 'Jadwal penjemputan wajib diisi untuk metode antar jemput.',
            'pickup_schedule.in' => 'Jadwal penjemputan tidak valid. Pilih salah satu dari jadwal yang tersedia.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $selectedServiceIds = $validated['selected_services'];
            $servicesToOrder = Service::whereIn('id', $selectedServiceIds)->get();

            if ($servicesToOrder->isEmpty()) {
                return back()->withErrors(['error' => 'Tidak ada layanan yang valid untuk dipesan.']);
            }

            foreach ($servicesToOrder as $service) {
                $pickupDateTime = null;
                if ($validated['delivery_method'] === Order::DELIVERY_ANTAR_JEMPUT && $validated['pickup_schedule']) {
                    $today = now()->format('Y-m-d');
                    $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i', $today . ' ' . $validated['pickup_schedule']);
                    if ($pickupDateTime->lt(now())) {
                        $pickupDateTime->addDay();
                    }
                }

                Order::create([
                    'user_id' => Auth::id(),
                    'service_id' => $service->id,
                    'delivery_method' => $validated['delivery_method'],
                    'alamat_pickup' => $validated['alamat_pickup'] ?? null,
                    'pickup_schedule' => $pickupDateTime,
                    'notes' => $validated['notes'] ?? null,
                    'total_price' => $service->price, // Use actual service price
                    'status' => Order::STATUS_WAITING_PICKUP,
                    'payment_status' => Order::PAYMENT_PENDING,
                    'midtrans_order_id' => $this->generateMidtransOrderId($service->id), // Implement this method
                ]);

                // Remove the ordered service from the session cart
                $cart = $request->session()->get('cart', []);
                $updatedCart = array_filter($cart, function ($item) use ($service) {
                    return $item['service_id'] != $service->id;
                });
                $request->session()->put('cart', array_values($updatedCart));
            }

            DB::commit();

            return redirect()->route('member.orders.status') // Assuming this is your order status page
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran untuk melanjutkan proses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // You need to implement this method in your Order model or a helper
    // For demonstration, a simple placeholder
    private function generateMidtransOrderId($serviceId)
    {
        return 'ORDER-' . time() . '-' . $serviceId . '-' . Auth::id();
    }
}
