@extends('layouts.app')

@section('subtitle', 'Laporan Transaksi')
@section('content_header_title', 'Laporan')
@section('content_header_subtitle', 'Analisis Transaksi & Performa Bisnis')

@section('content_body')

<!-- Period Filter -->
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calendar mr-2"></i>
            Filter Periode
        </h3>
    </div>
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-3">
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control"
                    value="{{ $period['start_date'] }}">
            </div>
            <div class="col-md-3">
                <label>Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control"
                    value="{{ $period['end_date'] }}">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i>
                        Filter
                    </button>
                    <button type="button" class="btn btn-danger ml-2" onclick="exportPDF()">
                        <i class="fas fa-file-pdf mr-1"></i>
                        Export PDF
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <div class="text-right">
                    <small class="text-muted">
                        Periode: {{ $period['days'] }} hari<br>
                        {{ Carbon\Carbon::parse($period['start_date'])->format('d M Y') }} -
                        {{ Carbon\Carbon::parse($period['end_date'])->format('d M Y') }}
                    </small>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($summary['total_orders']) }}</h3>
                <p>Total Pesanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            @if(isset($trends['orders_growth']))
            <div class="small-box-footer">
                <span class="badge badge-{{ $trends['orders_growth'] >= 0 ? 'success' : 'danger' }}">
                    <i class="fas fa-arrow-{{ $trends['orders_growth'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($trends['orders_growth']) }}%
                </span>
                vs periode sebelumnya
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            @if(isset($trends['revenue_growth']))
            <div class="small-box-footer">
                <span class="badge badge-{{ $trends['revenue_growth'] >= 0 ? 'success' : 'danger' }}">
                    <i class="fas fa-arrow-{{ $trends['revenue_growth'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($trends['revenue_growth']) }}%
                </span>
                vs periode sebelumnya
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($summary['conversion_rate'], 1) }}%</h3>
                <p>Conversion Rate</p>
            </div>
            <div class="icon">
                <i class="fas fa-percentage"></i>
            </div>
            @if(isset($trends['conversion_growth']))
            <div class="small-box-footer">
                <span class="badge badge-{{ $trends['conversion_growth'] >= 0 ? 'success' : 'danger' }}">
                    <i class="fas fa-arrow-{{ $trends['conversion_growth'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($trends['conversion_growth']) }}%
                </span>
                vs periode sebelumnya
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp{{ number_format($summary['average_order_value'], 0, ',', '.') }}</h3>
                <p>Rata-rata Order</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            @if(isset($trends['aov_growth']))
            <div class="small-box-footer">
                <span class="badge badge-{{ $trends['aov_growth'] >= 0 ? 'success' : 'danger' }}">
                    <i class="fas fa-arrow-{{ $trends['aov_growth'] >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($trends['aov_growth']) }}%
                </span>
                vs periode sebelumnya
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2"></i>
                    Trend Revenue & Pesanan
                </h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Status Pesanan
                </h3>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Second Charts Row -->
<div class="row">
    <!-- Payment Status Chart -->
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-credit-card mr-2"></i>
                    Status Pembayaran
                </h3>
            </div>
            <div class="card-body">
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Delivery Method Chart -->
    <div class="col-md-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-truck mr-2"></i>
                    Metode Pengiriman
                </h3>
            </div>
            <div class="card-body">
                <canvas id="deliveryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Analysis Tables -->
<div class="row">
    <!-- Service Analysis -->
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star mr-2"></i>
                    Performa Layanan
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th class="text-center">Orders</th>
                                <th class="text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                            <tr>
                                <td>{{ $service->name }}</td>
                                <td class="text-center">
                                    <span class="badge badge-primary">{{ $service->total_orders }}</span>
                                </td>
                                <td class="text-right">
                                    <strong>Rp{{ number_format($service->revenue, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Top Customers
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-center">Orders</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers['top_customers'] as $customer)
                            <tr>
                                <td>
                                    <strong>{{ $customer->name }}</strong><br>
                                    <small class="text-muted">{{ $customer->email }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">{{ $customer->total_orders }}</span>
                                </td>
                                <td class="text-right">
                                    <strong>Rp{{ number_format($customer->total_spent, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Data dari controller - sudah siap pakai
    const chartData = @json($charts);

    // Revenue Chart - menggunakan data yang sudah disiapkan controller
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: chartData.revenue_chart,
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: chartData.status_chart,
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

    // Payment Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'pie',
        data: chartData.payment_chart,
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

    // Delivery Chart
    const deliveryCtx = document.getElementById('deliveryChart').getContext('2d');
    const deliveryChart = new Chart(deliveryCtx, {
        type: 'bar',
        data: {
            labels: chartData.delivery.map(item => item.method_label),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: chartData.delivery.map(item => item.count),
                backgroundColor: chartData.delivery.map(item => item.color),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pesanan'
                    }
                }
            }
        }
    });

    function exportPDF() {
        Swal.fire({
            title: 'Export PDF',
            text: 'Memproses export laporan ke PDF...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        const params = {
            start_date: urlParams.get('start_date') || '{{ $period["start_date"] }}',
            end_date: urlParams.get('end_date') || '{{ $period["end_date"] }}'
        };

        // Create form and submit for PDF download
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.reports.pdf") }}';

        Object.keys(params).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = params[key];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);

        // Close loading after a short delay
        setTimeout(() => {
            Swal.close();
            Swal.fire({
                title: 'Berhasil!',
                text: 'Laporan PDF sedang diunduh...',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }, 1000);
    }
</script>
@stop

@section('css')
<style>
    .small-box-footer {
        padding: 3px 10px;
        color: rgba(255, 255, 255, .8);
        font-size: 12px;
        background: rgba(0, 0, 0, .1);
    }

    .small-box:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }

    canvas {
        max-height: 400px;
    }
</style>
@stop