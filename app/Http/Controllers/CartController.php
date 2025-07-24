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
     * Add a service with full order details to the cart (session).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'delivery_method' => ['required', Rule::in([Order::DELIVERY_ANTAR_JEMPUT, Order::DELIVERY_DROP_OFF])],
            'alamat_pickup' => 'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT . '|nullable|string|max:1000',
            'pickup_schedule' => [
                'required_if:delivery_method,' . Order::DELIVERY_ANTAR_JEMPUT,
                'nullable',
                Rule::in(['12:00', '18:00'])
            ],
            'notes' => 'nullable|string|max:1000',
            'total_price' => 'required|numeric|min:0', // Ensure total_price is passed from create.blade.php
        ], [
            'service_id.required' => 'Layanan harus dipilih.',
            'service_id.exists' => 'Layanan yang dipilih tidak valid.',
            'delivery_method.required' => 'Metode pengiriman harus dipilih.',
            'delivery_method.in' => 'Metode pengiriman tidak valid.',
            'alamat_pickup.required_if' => 'Alamat penjemputan wajib diisi untuk metode antar jemput.',
            'alamat_pickup.max' => 'Alamat penjemputan maksimal 1000 karakter.',
            'pickup_schedule.required_if' => 'Jadwal penjemputan wajib diisi untuk metode antar jemput.',
            'pickup_schedule.in' => 'Jadwal penjemputan tidak valid. Pilih salah satu dari jadwal yang tersedia.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
            'total_price.required' => 'Total harga harus ada.',
            'total_price.numeric' => 'Total harga harus berupa angka.',
            'total_price.min' => 'Total harga tidak boleh negatif.',
        ]);

        $cart = $request->session()->get('cart', []);

        // Add a unique identifier for each cart item
        $cartItem = [
            'unique_id' => uniqid(), // Generate a unique ID for this specific cart entry
            'service_id' => $validated['service_id'],
            'delivery_method' => $validated['delivery_method'],
            'alamat_pickup' => $validated['alamat_pickup'] ?? null,
            'pickup_schedule' => $validated['pickup_schedule'] ?? null, // Store as string, convert to Carbon at checkout
            'notes' => $validated['notes'] ?? null,
            'total_price' => $validated['total_price'],
        ];

        $cart[] = $cartItem;
        $request->session()->put('cart', $cart);

        return redirect()->route('member.cart.index')->with('success', 'Layanan berhasil ditambahkan ke keranjang!');
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
        $servicePrices = []; // This will now store total_price from cart items

        if (!empty($cartItems)) {
            // Get all service IDs from cart items to fetch services efficiently
            $serviceIds = array_column($cartItems, 'service_id');
            $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');

            foreach ($cartItems as $item) {
                if ($services->has($item['service_id'])) {
                    // Sum the total_price stored in each cart item
                    $totalCartPrice += $item['total_price'];
                    // Store the total_price for JS calculation if needed, keyed by unique_id
                    $servicePrices[$item['unique_id']] = $item['total_price'];
                }
            }
        }

        return view('member.cart.index', compact('services', 'cartItems', 'totalCartPrice', 'servicePrices'));
    }

    /**
     * Remove an item from the cart by its unique ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $request->validate([
            'unique_id' => 'required|string', // Now removing by unique_id
        ]);

        $uniqueIdToRemove = $request->input('unique_id');
        $cart = $request->session()->get('cart', []);

        $updatedCart = array_filter($cart, function ($item) use ($uniqueIdToRemove) {
            return $item['unique_id'] != $uniqueIdToRemove;
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
            'selected_cart_items' => 'required|array|min:1', // Now expecting unique_ids of cart items
            'selected_cart_items.*' => 'string', // Each element is a unique_id string
        ], [
            'selected_cart_items.required' => 'Pilih setidaknya satu layanan untuk checkout.',
            'selected_cart_items.*.string' => 'Item keranjang yang dipilih tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $selectedUniqueIds = $validated['selected_cart_items'];
            $cart = $request->session()->get('cart', []);
            $ordersCreatedCount = 0;

            $newCart = []; // To store items that were NOT checked out

            foreach ($cart as $cartItem) {
                if (in_array($cartItem['unique_id'], $selectedUniqueIds)) {
                    // This item is selected for checkout
                    $pickupDateTime = null;
                    if ($cartItem['delivery_method'] === Order::DELIVERY_ANTAR_JEMPUT && $cartItem['pickup_schedule']) {
                        $today = now()->format('Y-m-d');
                        $pickupDateTime = Carbon::createFromFormat('Y-m-d H:i', $today . ' ' . $cartItem['pickup_schedule']);
                        if ($pickupDateTime->lt(now())) {
                            $pickupDateTime->addDay();
                        }
                    }

                    Order::create([
                        'user_id' => Auth::id(),
                        'service_id' => $cartItem['service_id'],
                        'delivery_method' => $cartItem['delivery_method'],
                        'alamat_pickup' => $cartItem['alamat_pickup'],
                        'pickup_schedule' => $pickupDateTime,
                        'notes' => $cartItem['notes'],
                        'total_price' => $cartItem['total_price'],
                        'status' => Order::STATUS_WAITING_PICKUP,
                        'payment_status' => Order::PAYMENT_PENDING,
                        'midtrans_order_id' => $this->generateMidtransOrderId($cartItem['service_id']),
                    ]);
                    $ordersCreatedCount++;
                } else {
                    // This item was not selected, keep it in the cart
                    $newCart[] = $cartItem;
                }
            }

            $request->session()->put('cart', array_values($newCart)); // Update session cart

            DB::commit();

            if ($ordersCreatedCount > 0) {
                return redirect()->route('member.orders.status')
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran untuk melanjutkan proses.');
            } else {
                return back()->withErrors(['error' => 'Tidak ada layanan yang valid untuk dipesan.'])
                    ->withInput();
            }
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
