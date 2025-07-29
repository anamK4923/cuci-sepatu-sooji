<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        // Default period: last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = [
            'summary' => $this->getSummaryData($startDate, $endDate),
            'charts' => $this->getChartData($startDate, $endDate),
            'trends' => $this->getTrendData($startDate, $endDate),
            'services' => $this->getServiceAnalysis($startDate, $endDate),
            'customers' => $this->getCustomerAnalysis($startDate, $endDate),
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1
            ]
        ];

        return view('admin.reports.index', $data);
    }

    /**
     * Export report to PDF
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $data = [
            'summary' => $this->getSummaryData($startDate, $endDate),
            'charts' => $this->getChartData($startDate, $endDate),
            'trends' => $this->getTrendData($startDate, $endDate),
            'services' => $this->getServiceAnalysis($startDate, $endDate),
            'customers' => $this->getCustomerAnalysis($startDate, $endDate),
            'orders' => Order::with(['user', 'service'])
                ->whereBetween('created_at', [$start, $end])
                ->orderBy('created_at', 'desc')
                ->get(),
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'formatted_period' => Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y'),
                'days' => Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1
            ],
            'generated_at' => Carbon::now()->format('d M Y H:i:s')
        ];

        $pdf = PDF::loadView('admin.reports.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan_Transaksi_' . Carbon::parse($startDate)->format('d-m-Y') . '_' . Carbon::parse($endDate)->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get summary data
     */
    private function getSummaryData($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ])->get();

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('payment_status', Order::PAYMENT_PAID)->sum('total_price'),
            'completed_orders' => $orders->where('status', Order::STATUS_COMPLETED)->count(),
            'pending_orders' => $orders->where('status', '!=', Order::STATUS_COMPLETED)
                ->where('status', '!=', Order::STATUS_CANCELLED)->count(),
            'cancelled_orders' => $orders->where('status', Order::STATUS_CANCELLED)->count(),
            'average_order_value' => $orders->where('payment_status', Order::PAYMENT_PAID)->avg('total_price') ?? 0,
            'conversion_rate' => $orders->count() > 0 ?
                ($orders->where('payment_status', Order::PAYMENT_PAID)->count() / $orders->count()) * 100 : 0,
        ];
    }


    /**
     * Get chart data for revenue and orders - IMPROVED
     */
    private function getChartData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();


        // Daily revenue and orders
        $dailyData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_price ELSE 0 END) as revenue')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with zero values
        $period = Carbon::parse($start)->daysUntil(Carbon::parse($end));
        $chartData = [];

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $dayData = $dailyData->firstWhere('date', $dateStr);

            $chartData[] = [
                'date' => $dateStr,
                'formatted_date' => $date->format('d M'),
                'total_orders' => $dayData ? $dayData->total_orders : 0,
                'revenue' => $dayData ? $dayData->revenue : 0
            ];
        }

        // Status distribution
        $statusData = Order::select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'status_label' => $this->getStatusLabel($item->status),
                    'count' => $item->count,
                    'color' => $this->getStatusColor($item->status)
                ];
            });

        // Payment status distribution
        $paymentData = Order::select('payment_status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('payment_status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->payment_status,
                    'status_label' => $this->getPaymentStatusLabel($item->payment_status),
                    'count' => $item->count,
                    'color' => $this->getPaymentStatusColor($item->payment_status)
                ];
            });

        // Delivery method distribution
        $deliveryData = Order::select('delivery_method', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('delivery_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => $item->delivery_method,
                    'method_label' => $item->delivery_method == 'antar_jemput' ? 'Antar Jemput' : 'Drop Off',
                    'count' => $item->count,
                    'color' => $item->delivery_method == 'antar_jemput' ? '#17a2b8' : '#6c757d'
                ];
            });

        // Service performance chart
        $serviceChart = [
            'labels' => $this->getServiceAnalysis($startDate, $endDate)->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $this->getServiceAnalysis($startDate, $endDate)->pluck('revenue')->toArray(),
                    'backgroundColor' => [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6f42c1',
                        '#20c997',
                        '#fd7e14'
                    ],
                    'hoverOffset' => 10
                ]
            ]
        ];

        return [
            'daily' => $chartData,
            'status' => $statusData,
            'payment' => $paymentData,
            'delivery' => $deliveryData,
            'service_chart' => $serviceChart,
            // Prepared data for Chart.js
            'revenue_chart' => [
                'labels' => collect($chartData)->pluck('formatted_date')->toArray(),
                'datasets' => [
                    [
                        'label' => 'Pemasukan (Rp)',
                        'data' => collect($chartData)->pluck('revenue')->toArray(),
                        'borderColor' => 'rgb(75, 192, 192)',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.1)',
                        'yAxisID' => 'y'
                    ],
                    [
                        'label' => 'Pemesanan',
                        'data' => collect($chartData)->pluck('total_orders')->toArray(),
                        'borderColor' => 'rgb(255, 99, 132)',
                        'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                        'yAxisID' => 'y1'
                    ]
                ]
            ],
            'status_chart' => [
                'labels' => $statusData->pluck('status_label')->toArray(),
                'datasets' => [
                    [
                        'data' => $statusData->pluck('count')->toArray(),
                        'backgroundColor' => $statusData->pluck('color')->toArray()
                    ]
                ]
            ],
            'payment_chart' => [
                'labels' => $paymentData->pluck('status_label')->toArray(),
                'datasets' => [
                    [
                        'data' => $paymentData->pluck('count')->toArray(),
                        'backgroundColor' => $paymentData->pluck('color')->toArray()
                    ]
                ]
            ]
        ];
    }

    /**
     * Get trend data (comparison with previous period)
     */
    private function getTrendData($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $days = Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1;
        $prevStartDate = Carbon::parse($start)->subDays($days)->format('Y-m-d');
        $prevEndDate = Carbon::parse($start)->subDay()->format('Y-m-d');

        $current = $this->getSummaryData($startDate, $endDate);
        $previous = $this->getSummaryData($prevStartDate, $prevEndDate);

        return [
            'revenue_growth' => $this->calculateGrowth($current['total_revenue'], $previous['total_revenue']),
            'orders_growth' => $this->calculateGrowth($current['total_orders'], $previous['total_orders']),
            'conversion_growth' => $this->calculateGrowth($current['conversion_rate'], $previous['conversion_rate']),
            'aov_growth' => $this->calculateGrowth($current['average_order_value'], $previous['average_order_value']),
        ];
    }

    /**
     * Get service analysis
     */
    private function getServiceAnalysis($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        return Order::select(
            'services.name',
            'services.id',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(CASE WHEN orders.payment_status = "paid" THEN orders.total_price ELSE 0 END) as revenue'),
            DB::raw('AVG(orders.total_price) as avg_price')
        )
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('services.id', 'services.name')
            ->orderBy('revenue', 'desc')
            ->get();
    }

    /**
     * Get customer analysis
     */
    private function getCustomerAnalysis($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $topCustomers = Order::select(
            'users.name',
            'users.email',
            'users.id',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(CASE WHEN orders.payment_status = "paid" THEN orders.total_price ELSE 0 END) as total_spent'),
            DB::raw('AVG(orders.total_price) as avg_order_value')
        )
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get();

        $newCustomers = User::whereBetween('created_at', [$start, $end])
            ->where('role', 'member')
            ->count();

        $returningCustomers = Order::select('user_id')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        return [
            'top_customers' => $topCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'customer_retention_rate' => $newCustomers > 0 ?
                round(($returningCustomers / $newCustomers) * 100, 2) : 0
        ];
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
     * Get status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            Order::STATUS_WAITING_PICKUP => 'Menunggu Penjemputan',
            Order::STATUS_PICKED_UP => 'Sudah Dijemput',
            Order::STATUS_IN_PROCESS => 'Sedang Diproses',
            Order::STATUS_READY => 'Siap Diambil',
            Order::STATUS_COMPLETED => 'Selesai',
            Order::STATUS_CANCELLED => 'Dibatalkan',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            Order::STATUS_WAITING_PICKUP => '#ffc107',
            Order::STATUS_PICKED_UP => '#17a2b8',
            Order::STATUS_IN_PROCESS => '#007bff',
            Order::STATUS_READY => '#28a745',
            Order::STATUS_COMPLETED => '#28a745',
            Order::STATUS_CANCELLED => '#dc3545',
        ];

        return $colors[$status] ?? '#6c757d';
    }

    /**
     * Get payment status label
     */
    private function getPaymentStatusLabel($status)
    {
        $labels = [
            Order::PAYMENT_PENDING => 'Menunggu Pembayaran',
            Order::PAYMENT_PAID => 'Sudah Dibayar',
            Order::PAYMENT_FAILED => 'Pembayaran Gagal',
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get payment status color
     */
    private function getPaymentStatusColor($status)
    {
        $colors = [
            Order::PAYMENT_PENDING => '#ffc107',
            Order::PAYMENT_PAID => '#28a745',
            Order::PAYMENT_FAILED => '#dc3545',
        ];

        return $colors[$status] ?? '#6c757d';
    }

    /**
     * Export detailed report
     */
    public function exportReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $data = [
            'summary' => $this->getSummaryData($startDate, $endDate),
            'charts' => $this->getChartData($startDate, $endDate),
            'services' => $this->getServiceAnalysis($startDate, $endDate),
            'customers' => $this->getCustomerAnalysis($startDate, $endDate),
            'orders' => Order::with(['user', 'service'])
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at', 'desc')
                ->get(),
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'formatted_period' => Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y')
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get analytics data for AJAX calls
     */
    public function analytics(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        return response()->json([
            'success' => true,
            'data' => [
                'charts' => $this->getChartData($startDate, $endDate),
                'summary' => $this->getSummaryData($startDate, $endDate),
                'trends' => $this->getTrendData($startDate, $endDate)
            ]
        ]);
    }
}
