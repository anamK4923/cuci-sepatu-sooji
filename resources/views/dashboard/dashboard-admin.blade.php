@extends('layouts.app')

@section('subtitle', 'Dashboard Admin')
@section('content_header_title', 'Dashboard')
@section('content_header_subtitle', 'Ringkasan Performa Bisnis')

@section('content_body')

<!-- System Alerts -->
@if(count($alerts) > 0)
<div class="row mb-3">
    <div class="col-12">
        @foreach($alerts as $alert)
        <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show">
            <i class="{{ $alert['icon'] }} mr-2"></i>
            <strong>{{ $alert['title'] }}:</strong> {{ $alert['message'] }}
            @if(isset($alert['action']))
            <a href="{{ $alert['action'] }}" class="btn btn-sm btn-outline-{{ $alert['type'] }} ml-2">
                Lihat Detail
            </a>
            @endif
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Main Statistics Cards -->
<div class="row">
    <!-- Total Orders -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($stats['total_orders']) }}</h3>
                <p>Total Pesanan</p>
                <div class="progress mb-2" style="height: 4px;">
                    <div class="progress-bar" style="width: {{ min(($stats['completed_orders'] / max($stats['total_orders'], 1)) * 100, 100) }}%"></div>
                </div>
                <small>{{ $stats['completed_orders'] }} selesai</small>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Today's Orders -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($stats['today_orders']) }}</h3>
                <p>Pesanan Hari Ini</p>
                @if($growth['orders_growth'] != 0)
                <div class="d-flex align-items-center">
                    <span class="badge badge-{{ $growth['orders_growth'] >= 0 ? 'light' : 'warning' }}">
                        <i class="fas fa-arrow-{{ $growth['orders_growth'] >= 0 ? 'up' : 'down' }}"></i>
                        {{ abs($growth['orders_growth']) }}%
                    </span>
                    <small class="ml-2">vs kemarin</small>
                </div>
                @endif
            </div>
            <div class="icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <a href="{{ route('admin.orders.index', ['date_from' => now()->format('Y-m-d')]) }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>Rp{{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                <p>Total Revenue</p>
                <div class="progress mb-2" style="height: 4px;">
                    <div class="progress-bar bg-white" style="width: {{ min(($stats['monthly_revenue'] / max($stats['total_revenue'], 1)) * 100, 100) }}%"></div>
                </div>
                <small>Rp{{ number_format($stats['monthly_revenue'], 0, ',', '.') }} bulan ini</small>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="small-box-footer">
                Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ number_format($stats['total_customers']) }}</h3>
                <p>Total Customer</p>
                <div class="d-flex align-items-center">
                    <span class="badge badge-light">
                        <i class="fas fa-plus"></i>
                        {{ $stats['new_customers_this_month'] }}
                    </span>
                    <small class="ml-2">baru bulan ini</small>
                </div>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Kelola Users <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pesanan Pending</span>
                <span class="info-box-number">{{ number_format($stats['pending_orders']) }}</span>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ min(($stats['pending_orders'] / max($stats['total_orders'], 1)) * 100, 100) }}%"></div>
                </div>
                <span class="progress-description">
                    {{ number_format(($stats['pending_orders'] / max($stats['total_orders'], 1)) * 100, 1) }}% dari total
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pesanan Selesai</span>
                <span class="info-box-number">{{ number_format($stats['completed_orders']) }}</span>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: {{ min(($stats['completed_orders'] / max($stats['total_orders'], 1)) * 100, 100) }}%"></div>
                </div>
                <span class="progress-description">
                    {{ number_format(($stats['completed_orders'] / max($stats['total_orders'], 1)) * 100, 1) }}% completion rate
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rata-rata Order</span>
                <span class="info-box-number">Rp{{ number_format($stats['average_order_value'], 0, ',', '.') }}</span>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                    AOV Performance
                </span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-star"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Rating Rata-rata</span>
                <span class="info-box-number">{{ number_format($stats['average_rating'], 1) }}</span>
                <div class="progress">
                    <div class="progress-bar bg-danger" style="width: {{ ($stats['average_rating'] / 5) * 100 }}%"></div>
                </div>
                <span class="progress-description">
                    {{ $stats['total_reviews'] }} total reviews
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Daily Orders Chart -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Pesanan 7 Hari Terakhir
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="dailyOrdersChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Status Pesanan
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Revenue Chart -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-area mr-2"></i>
                    Trend Revenue 6 Bulan Terakhir
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Pesanan Terbaru
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-tool">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Layanan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-primary">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $order->user->name }}</strong><br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td>{{ $order->service->name ?? 'Layanan telah dihapus' }}</td>
                                <td>
                                    <span class="badge badge-success">
                                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ getStatusBadgeClass($order->status) }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $order->created_at->format('d/m H:i') }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Belum ada pesanan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="col-md-4">
        <!-- Top Services -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star mr-2"></i>
                    Layanan Terpopuler
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($topServices as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $service->name }}</strong><br>
                            <small class="text-muted">{{ $service->orders_count }} pesanan</small>
                        </div>
                        <span class="badge badge-warning badge-pill">
                            Rp{{ number_format($service->total_revenue, 0, ',', '.') }}
                        </span>
                    </li>
                    @empty
                    <li class="list-group-item text-center">
                        <small class="text-muted">Belum ada data</small>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-comments mr-2"></i>
                    Review Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentReviews as $review)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name }}</strong>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                            </div>
                        </div>
                        <small class="text-muted">{{ $review->order->service->name ?? 'Layanan telah dihapus' }}</small>
                        @if($review->comment)
                        <p class="mb-1 mt-2">{{ Str::limit($review->comment, 80) }}</p>
                        @endif
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @empty
                    <div class="list-group-item text-center">
                        <small class="text-muted">Belum ada review</small>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('services.create') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Layanan
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.orders.index', ['status' => 'waiting_pickup']) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-clock mr-2"></i>
                            Pesanan Pending
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-success btn-block">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Lihat Laporan
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-info btn-block">
                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Get chart data from controller
    const chartData = @json($chartData);

    // Daily Orders Chart
    const dailyOrdersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
    const dailyOrdersChart = new Chart(dailyOrdersCtx, {
        type: 'line',
        data: {
            labels: chartData.dailyOrders.labels,
            datasets: [{
                label: 'Pesanan',
                data: chartData.dailyOrders.data,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.ordersByStatus.labels,
            datasets: [{
                data: chartData.ordersByStatus.data,
                backgroundColor: chartData.ordersByStatus.colors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueChart = new Chart(monthlyRevenueCtx, {
        type: 'bar',
        data: {
            labels: chartData.monthlyRevenue.labels,
            datasets: [{
                label: 'Revenue',
                data: chartData.monthlyRevenue.revenueData,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }, {
                label: 'Orders',
                data: chartData.monthlyRevenue.ordersData,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rp)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            }
        }
    });

    // Auto refresh data every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
</script>
@stop

@section('css')
<style>
    .small-box:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease;
    }

    .info-box:hover {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.2s ease;
    }

    .progress {
        height: 4px;
    }

    .badge-light {
        background-color: rgba(255, 255, 255, 0.8) !important;
        color: #333 !important;
    }

    .list-group-item {
        border-left: none;
        border-right: none;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .small-box .inner h3 {
            font-size: 1.5rem;
        }

        .info-box-number {
            font-size: 1.2rem;
        }
    }
</style>
@stop

@php
function getStatusBadgeClass($status) {
$classes = [
'waiting_pickup' => 'warning',
'picked_up' => 'info',
'in_process' => 'primary',
'ready' => 'success',
'done' => 'success',
'cancelled' => 'danger',
];
return $classes[$status] ?? 'secondary';
}
@endphp