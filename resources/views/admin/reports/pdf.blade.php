<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - {{ $period['formatted_period'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }

        .header .period {
            color: #999;
            font-size: 14px;
            margin-top: 10px;
        }

        .summary-section {
            margin-bottom: 30px;
        }

        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            border-left: 4px solid #007bff;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .summary-item .label {
            color: #666;
            font-size: 11px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-primary {
            background-color: #007bff;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .chart-placeholder {
            width: 100%;
            height: 200px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            margin: 20px 0;
        }

        .status-distribution {
            display: table;
            width: 100%;
        }

        .status-item {
            display: table-row;
        }

        .status-item>div {
            display: table-cell;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .column:last-child {
            padding-right: 0;
            padding-left: 15px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>CUCI SEPATU SOOOJI</h1>
        <h2>Laporan Transaksi & Analisis Bisnis</h2>
        <div class="period">
            Periode: {{ $period['formatted_period'] }}<br>
            Total {{ $period['days'] }} hari | Dibuat: {{ $generated_at }}
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="section-title">RINGKASAN TRANSAKSI</div>

        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-item">
                    <div class="value">{{ number_format($summary['total_orders']) }}</div>
                    <div class="label">Total Pesanan</div>
                </div>
                <div class="summary-item">
                    <div class="value">Rp{{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                    <div class="label">Total Revenue</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ number_format($summary['conversion_rate'], 1) }}%</div>
                    <div class="label">Conversion Rate</div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-item">
                    <div class="value">{{ number_format($summary['completed_orders']) }}</div>
                    <div class="label">Pesanan Selesai</div>
                </div>
                <div class="summary-item">
                    <div class="value">{{ number_format($summary['pending_orders']) }}</div>
                    <div class="label">Pesanan Pending</div>
                </div>
                <div class="summary-item">
                    <div class="value">Rp{{ number_format($summary['average_order_value']) }}</div>
                    <div class="label">Rata-rata Order</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="summary-section">
        <div class="section-title">DISTRIBUSI STATUS PESANAN</div>

        <div class="status-distribution">
            @foreach($charts['status'] as $status)
            <div class="status-item">
                <div style="width: 40%;">{{ $status['status_label'] }}</div>
                <div style="width: 30%; text-align: center;">
                    <span class="badge badge-primary">{{ $status['count'] }}</span>
                </div>
                <div style="width: 30%; text-align: right;">
                    {{ number_format(($status['count'] / $summary['total_orders']) * 100, 1) }}%
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Service Analysis -->
    <div class="summary-section">
        <div class="section-title">ANALISIS LAYANAN</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th class="text-center">Total Pesanan</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">Rata-rata Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->name }}</td>
                    <td class="text-center">{{ $service->total_orders }}</td>
                    <td class="text-right">Rp{{ number_format($service->revenue, 0, ',', '.') }}</td>
                    <td class="text-right">Rp{{ number_format($service->avg_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>

    <!-- Top Customers -->
    <div class="summary-section">
        <div class="section-title">TOP 10 PELANGGAN</div>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Email</th>
                    <th class="text-center">Total Pesanan</th>
                    <th class="text-right">Total Belanja</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers['top_customers'] as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td class="text-center">{{ $customer->total_orders }}</td>
                    <td class="text-right">Rp{{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Customer Statistics -->
    <div class="summary-section">
        <div class="section-title">STATISTIK PELANGGAN</div>

        <div class="two-column">
            <div class="column">
                <table class="table">
                    <tr>
                        <td><strong>Pelanggan Baru</strong></td>
                        <td class="text-right">{{ number_format($customers['new_customers']) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pelanggan Berulang</strong></td>
                        <td class="text-right">{{ number_format($customers['returning_customers']) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Retention Rate</strong></td>
                        <td class="text-right">{{ $customers['customer_retention_rate'] }}%</td>
                    </tr>
                </table>
            </div>
            <div class="column">
                <table class="table">
                    @foreach($charts['payment'] as $payment)
                    <tr>
                        <td>{{ $payment['status_label'] }}</td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ $payment['count'] }}</span>
                        </td>
                        <td class="text-right">
                            {{ number_format(($payment['count'] / $summary['total_orders']) * 100, 1) }}%
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Orders -->
    @if($orders->count() > 0)
    <div class="page-break"></div>
    <div class="summary-section">
        <div class="section-title">DETAIL TRANSAKSI ({{ $orders->count() }} Pesanan)</div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->take(50) as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->service->name ?? 'Layanan telah dihapus'}}</td>
                    <td>
                        <span class="badge 
                            @if($order->status == 'completed') badge-success
                            @elseif($order->status == 'cancelled') badge-danger
                            @elseif(in_array($order->status, ['waiting_pickup', 'picked_up'])) badge-warning
                            @else badge-info
                            @endif">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <span class="badge 
                            @if($order->payment_status == 'paid') badge-success
                            @elseif($order->payment_status == 'failed') badge-danger
                            @else badge-warning
                            @endif">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="text-right">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                @if($orders->count() > 50)
                <tr>
                    <td colspan="7" class="text-center" style="font-style: italic; color: #666;">
                        ... dan {{ $orders->count() - 50 }} pesanan lainnya
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div>
            Laporan ini dibuat secara otomatis oleh sistem Cuci Sepatu Soooji<br>
            Dicetak pada: {{ $generated_at }} | Halaman <span class="pagenum"></span>
        </div>
    </div>
</body>

</html>