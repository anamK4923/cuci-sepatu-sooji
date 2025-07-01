<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'service']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('delivery_method')) {
            $query->where('delivery_method', $request->delivery_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('midtrans_order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = $this->getOrderStatistics();

        // Get filter options
        $services = Service::orderBy('name')->get();
        $statuses = [
            Order::STATUS_WAITING_PICKUP => 'Menunggu Penjemputan',
            Order::STATUS_PICKED_UP => 'Sudah Dijemput',
            Order::STATUS_IN_PROCESS => 'Sedang Diproses',
            Order::STATUS_READY => 'Siap Diambil',
            Order::STATUS_COMPLETED => 'Selesai',
            Order::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'services', 'statuses'));
    }

    /**
     * Get export data for PDF generation
     */
    public function getExportData(Request $request)
    {
        try {
            // Build the same query as index method
            $query = Order::with(['user', 'service']);

            // Apply the same filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            if ($request->filled('delivery_method')) {
                $query->where('delivery_method', $request->delivery_method);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                        ->orWhere('midtrans_order_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('service', function ($serviceQuery) use ($search) {
                            $serviceQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // Get all orders (not paginated for export)
            $orders = $query->orderBy('created_at', 'desc')->get();

            // Prepare status labels
            $statusLabels = [
                Order::STATUS_WAITING_PICKUP => 'Menunggu Penjemputan',
                Order::STATUS_PICKED_UP => 'Sudah Dijemput',
                Order::STATUS_IN_PROCESS => 'Sedang Diproses',
                Order::STATUS_READY => 'Siap Diambil',
                Order::STATUS_COMPLETED => 'Selesai',
                Order::STATUS_CANCELLED => 'Dibatalkan',
            ];

            $paymentStatusLabels = [
                Order::PAYMENT_PENDING => 'Menunggu Pembayaran',
                Order::PAYMENT_PAID => 'Sudah Dibayar',
                Order::PAYMENT_FAILED => 'Pembayaran Gagal',
            ];

            // Prepare export data
            $exportData = [
                'orders' => $orders->map(function ($order) use ($statusLabels, $paymentStatusLabels) {
                    return [
                        'id' => $order->id,
                        'user' => [
                            'name' => optional($order->user)->name ?? '-',
                            'email' => optional($order->user)->email ?? '-',
                        ],
                        'service' => [
                            'name' => optional($order->service)->name ?? '-',
                            'price' => optional($order->service)->price ?? 0,
                        ],
                        'delivery_method' => $order->delivery_method,
                        'delivery_method_label' => $order->delivery_method_label,
                        'total_price' => $order->total_price,
                        'status' => $order->status,
                        'status_label' => $statusLabels[$order->status] ?? $order->status,
                        'payment_status' => $order->payment_status,
                        'payment_status_label' => $paymentStatusLabels[$order->payment_status] ?? $order->payment_status,
                        'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                        'created_at_formatted' => $order->created_at->format('d/m/Y H:i'),
                    ];
                }),
                'filters' => [
                    'has_filters' => $request->hasAny(['status', 'payment_status', 'delivery_method', 'date_from', 'date_to', 'search']),
                    'status' => $request->status,
                    'status_label' => $request->status ? ($statusLabels[$request->status] ?? $request->status) : null,
                    'payment_status' => $request->payment_status,
                    'payment_status_label' => $request->payment_status ? ucfirst($request->payment_status) : null,
                    'delivery_method' => $request->delivery_method,
                    'delivery_method_label' => $request->delivery_method == 'antar_jemput' ? 'Antar Jemput' : ($request->delivery_method == 'drop_off' ? 'Drop Off' : null),
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                    'search' => $request->search,
                ],
                'summary' => [
                    'total_orders' => $orders->count(),
                    'total_revenue' => $orders->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
                    'export_date' => now()->format('Y-m-d'),
                    'export_datetime' => now()->format('d/m/Y H:i:s'),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $exportData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data export: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'service']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', [
                Order::STATUS_WAITING_PICKUP,
                Order::STATUS_PICKED_UP,
                Order::STATUS_IN_PROCESS,
                Order::STATUS_READY,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if trying to set status to 'done' but payment is not 'paid'
        if ($request->status === Order::STATUS_COMPLETED && $order->payment_status !== Order::PAYMENT_PAID) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengubah status menjadi selesai karena pembayaran belum lunas'
            ], 400);
        }

        try {
            $oldStatus = $order->status;

            $order->update([
                'status' => $request->status
            ]);

            // Log status change (you can create a separate model for this)
            // OrderStatusLog::create([...]);

            // Send notification to customer (implement as needed)
            // $this->sendStatusUpdateNotification($order, $oldStatus, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate',
                'new_status' => $order->status_label
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan'
            ], 500);
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid'
        ]);

        try {
            $order->update([
                'payment_status' => $request->payment_status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pembayaran'
            ], 500);
        }
    }

    /**
     * Bulk update orders
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'action' => 'required|in:update_status,update_payment_status,delete',
            'status' => 'required_if:action,update_status',
            'payment_status' => 'required_if:action,update_payment_status'
        ]);

        try {
            DB::beginTransaction();

            $orders = Order::whereIn('id', $request->order_ids);

            switch ($request->action) {
                case 'update_status':
                    $orders->update(['status' => $request->status]);
                    $message = 'Status pesanan berhasil diupdate secara bulk';
                    break;

                case 'update_payment_status':
                    $orders->update(['payment_status' => $request->payment_status]);
                    $message = 'Status pembayaran berhasil diupdate secara bulk';
                    break;

                case 'delete':
                    $orders->delete();
                    $message = 'Pesanan berhasil dihapus secara bulk';
                    break;
            }

            DB::commit();

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal melakukan bulk update']);
        }
    }

    /**
     * Export orders
     */
    public function export(Request $request)
    {
        // This would implement Excel/PDF export
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Export functionality will be implemented'
        ]);
    }

    /**
     * Get order statistics
     */
    private function getOrderStatistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_completed_orders' => Order::whereDate('created_at', $today)
                ->where('status', Order::STATUS_COMPLETED)->count(),
            'pending_orders' => Order::where('status', '!=', Order::STATUS_COMPLETED)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'total_revenue' => Order::where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'monthly_revenue' => Order::where('payment_status', Order::PAYMENT_PAID)
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_price'),
            'pending_payments' => Order::where('payment_status', Order::PAYMENT_PENDING)->count(),
            'status_breakdown' => Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
        ];
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pesanan'
            ], 500);
        }
    }

    /**
     * Get order statistics for API
     */
    public function getStats()
    {
        return response()->json($this->getOrderStatistics());
    }
}
