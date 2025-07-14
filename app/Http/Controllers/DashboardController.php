<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function indexAdmin(): View
    {
        // Get date ranges
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Main Statistics
        $stats = [
            // Orders Statistics
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'yesterday_orders' => Order::whereDate('created_at', $yesterday)->count(),
            'pending_orders' => Order::whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('status', Order::STATUS_CANCELLED)->count(),

            // Revenue Statistics
            'total_revenue' => Order::where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'today_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'yesterday_revenue' => Order::whereDate('created_at', $yesterday)
                ->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'monthly_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'last_month_revenue' => Order::whereBetween('created_at', [$lastMonth, $thisMonth])
                ->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),

            // Customer Statistics
            'total_customers' => User::where('role', 'member')->count(),
            'new_customers_today' => User::where('role', 'member')->whereDate('created_at', $today)->count(),
            'new_customers_this_week' => User::where('role', 'member')->where('created_at', '>=', $thisWeek)->count(),
            'new_customers_this_month' => User::where('role', 'member')->where('created_at', '>=', $thisMonth)->count(),

            // Service Statistics
            'total_services' => Service::count(),
            'average_order_value' => Order::where('payment_status', Order::PAYMENT_PAID)->avg('total_price') ?? 0,
            'total_reviews' => Review::count(),
            'average_rating' => Review::avg('rating') ?? 0,

            // Payment Statistics
            'pending_payments' => Order::where('payment_status', Order::PAYMENT_PENDING)->count(),
            'paid_orders' => Order::where('payment_status', Order::PAYMENT_PAID)->count(),
            'failed_payments' => Order::where('payment_status', Order::PAYMENT_FAILED)->count(),
        ];

        // Calculate growth percentages
        $growth = [
            'orders_growth' => $this->calculateGrowth($stats['today_orders'], $stats['yesterday_orders']),
            'revenue_growth' => $this->calculateGrowth($stats['today_revenue'], $stats['yesterday_revenue']),
            'monthly_revenue_growth' => $this->calculateGrowth($stats['monthly_revenue'], $stats['last_month_revenue']),
        ];

        // Recent Orders
        $recentOrders = Order::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Orders by Status for Chart
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        // Daily Orders for the last 7 days
        $dailyOrders = [];
        $dailyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyOrders[] = [
                'date' => $date->format('M d'),
                'count' => Order::whereDate('created_at', $date)->count()
            ];
            $dailyRevenue[] = [
                'date' => $date->format('M d'),
                'revenue' => Order::whereDate('created_at', $date)
                    ->where('payment_status', Order::PAYMENT_PAID)
                    ->sum('total_price')
            ];
        }

        // Top Services
        $topServices = Service::select('services.*', DB::raw('COUNT(orders.id) as orders_count'), DB::raw('SUM(CASE WHEN orders.payment_status = "paid" THEN orders.total_price ELSE 0 END) as total_revenue'))
            ->leftJoin('orders', 'services.id', '=', 'orders.service_id')
            ->groupBy('services.id', 'services.name', 'services.price', 'services.description', 'services.image', 'services.created_at', 'services.updated_at')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        // Top Customers
        $topCustomers = User::select('users.*', DB::raw('COUNT(orders.id) as orders_count'), DB::raw('SUM(CASE WHEN orders.payment_status = "paid" THEN orders.total_price ELSE 0 END) as total_spent'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('users.role', 'member')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.image', 'users.role', 'users.no_hp', 'users.dark_mode', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        // Recent Reviews
        $recentReviews = Review::with(['user', 'order.service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Monthly comparison data
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'orders' => Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
                'revenue' => Order::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('payment_status', Order::PAYMENT_PAID)
                    ->sum('total_price')
            ];
        }

        // System alerts
        $alerts = $this->getSystemAlerts($stats);

        // Prepare chart data for JavaScript
        $chartData = [
            'dailyOrders' => [
                'labels' => collect($dailyOrders)->pluck('date')->toArray(),
                'data' => collect($dailyOrders)->pluck('count')->toArray()
            ],
            'dailyRevenue' => [
                'labels' => collect($dailyRevenue)->pluck('date')->toArray(),
                'data' => collect($dailyRevenue)->pluck('revenue')->toArray()
            ],
            'ordersByStatus' => [
                'labels' => $ordersByStatus->keys()->map(function ($status) {
                    return ucfirst(str_replace('_', ' ', $status));
                })->toArray(),
                'data' => $ordersByStatus->values()->toArray(),
                'colors' => [
                    '#ffc107', // waiting_pickup
                    '#17a2b8', // picked_up  
                    '#007bff', // in_process
                    '#28a745', // ready
                    '#28a745', // done
                    '#dc3545'  // cancelled
                ]
            ],
            'monthlyRevenue' => [
                'labels' => collect($monthlyData)->pluck('month')->toArray(),
                'revenueData' => collect($monthlyData)->pluck('revenue')->toArray(),
                'ordersData' => collect($monthlyData)->pluck('orders')->toArray()
            ]
        ];

        return view('dashboard.dashboard-admin', compact(
            'stats',
            'growth',
            'recentOrders',
            'ordersByStatus',
            'dailyOrders',
            'dailyRevenue',
            'topServices',
            'topCustomers',
            'recentReviews',
            'monthlyData',
            'alerts',
            'chartData'
        ));
    }

    public function indexMember(): View
    {
        return view('dashboard.dashboard-member');
    }

    /**
     * Calculate growth percentage
     */
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Get system alerts based on current statistics
     */
    private function getSystemAlerts($stats)
    {
        $alerts = [];

        // Check for pending payments
        if ($stats['pending_payments'] > 10) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Pembayaran Tertunda',
                'message' => "Ada {$stats['pending_payments']} pembayaran yang masih tertunda",
                'action' => route('admin.orders.index', ['payment_status' => 'pending'])
            ];
        }

        // Check for failed payments
        if ($stats['failed_payments'] > 5) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-times-circle',
                'title' => 'Pembayaran Gagal',
                'message' => "Ada {$stats['failed_payments']} pembayaran yang gagal",
                'action' => route('admin.orders.index', ['payment_status' => 'failed'])
            ];
        }

        // Check for orders needing attention
        if ($stats['pending_orders'] > 20) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-clock',
                'title' => 'Pesanan Menunggu',
                'message' => "Ada {$stats['pending_orders']} pesanan yang perlu diproses",
                'action' => route('admin.orders.index', ['status' => 'waiting_pickup'])
            ];
        }

        // Check for low service count
        if ($stats['total_services'] < 3) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-plus-circle',
                'title' => 'Layanan Terbatas',
                'message' => "Hanya ada {$stats['total_services']} layanan aktif",
                'action' => route('services.create')
            ];
        }

        return $alerts;
    }
}
