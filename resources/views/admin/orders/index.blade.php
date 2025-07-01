@extends('layouts.app')

@section('subtitle', 'Kelola Pesanan')
@section('content_header_title', 'Pesanan')
@section('content_header_subtitle', 'Kelola Semua Pesanan Customer')

@section('content_body')

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_orders'] }}</h3>
                <p>Total Pesanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['today_completed_orders'] }}</h3>
                <p>Pesanan Selesai Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-2"></i>
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    {{ $errors->first() }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<!-- Main Card -->
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-2"></i>
            Daftar Pesanan
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-control form-control-sm">
                        <option value="">Status Pembayaran</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="delivery_method" class="form-control form-control-sm">
                        <option value="">Metode Pengiriman</option>
                        <option value="antar_jemput" {{ request('delivery_method') == 'antar_jemput' ? 'selected' : '' }}>
                            Antar Jemput
                        </option>
                        <option value="drop_off" {{ request('delivery_method') == 'drop_off' ? 'selected' : '' }}>
                            Drop Off
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}" placeholder="Dari Tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
                </div>
                <div class="col-md-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Cari...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times mr-1"></i>
                        Reset Filter
                    </a>
                    <button type="button" class="btn btn-info btn-sm ml-2" onclick="exportData()">
                        <i class="fas fa-file-pdf mr-1"></i>
                        Export PDF
                    </button>
                </div>
            </div>
        </form>

        <!-- Bulk Actions -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="bulk-actions" style="display: none;">
                    <form id="bulkForm" method="POST" action="{{ route('admin.orders.bulk-update') }}">
                        @csrf
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <select name="action" class="form-control" required>
                                <option value="">Pilih Aksi</option>
                                <option value="update_status">Update Status</option>
                                <option value="update_payment_status">Update Status Pembayaran</option>
                                <option value="delete">Hapus</option>
                            </select>
                            <div class="input-group-append ml-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-play mr-1"></i>
                                    Jalankan
                                </button>
                            </div>
                        </div>
                        <div id="bulkOptions" class="mt-2" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-responsive" id="exportTable">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th width="80">ID</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Metode</th>
                        <th width="120">Total</th>
                        <th width="120">Status</th>
                        <th width="100">Pembayaran</th>
                        <th width="100">Tanggal</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                        </td>
                        <td>
                            <span class="badge badge-secondary">#{{ $order->id }}</span>
                        </td>
                        <td>
                            <strong>{{ $order->user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $order->service->name ?? 'Layanan telah dihapus'}}</strong>
                            <br>
                            <small class="text-muted">Rp{{ number_format($order->service->price, 0, ',', '.') }}</small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->delivery_method == 'antar_jemput' ? 'info' : 'secondary' }}">
                                {{ $order->delivery_method_label }}
                            </span>
                            @if($order->pickup_schedule)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $order->pickup_schedule->format('d/m H:i') }}
                            </small>
                            @endif
                        </td>
                        <td class="text-right">
                            <span class="badge badge-success badge-lg">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-{{ getStatusBadgeClass($order->status) }} dropdown-toggle"
                                    type="button" data-toggle="dropdown">
                                    {{ $order->status_label }}
                                </button>
                                <div class="dropdown-menu">
                                    @foreach($statuses as $statusKey => $statusLabel)
                                    @if($statusKey != $order->status)
                                    <a class="dropdown-item" href="#"
                                        onclick="updateStatus('{{ $order->id }}', '{{ $statusKey }}')">
                                        {{ $statusLabel }}
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }} dropdown-toggle"
                                    type="button" data-toggle="dropdown">
                                    {{ $order->payment_status_label }}
                                </button>
                                <div class="dropdown-menu">
                                    @if($order->payment_status == 'pending')
                                    <a class="dropdown-item" href="#"
                                        onclick="updatePaymentStatus('{{ $order->id }}', 'paid')">
                                        Tandai Sudah Dibayar
                                    </a>
                                    @else
                                    <a class="dropdown-item" href="#"
                                        onclick="updatePaymentStatus('{{ $order->id }}', 'pending')">
                                        Tandai Belum Dibayar
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <small>{{ $order->created_at->format('d/m/Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                    class="btn btn-info btn-sm" data-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="deleteOrder('{{ $order->id }}')"
                                    data-toggle="tooltip" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak Ada Pesanan</h5>
                            <p class="text-muted">Belum ada pesanan yang masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="row mt-3">
            <div class="col-md-6">
                <p class="text-muted">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }}
                    dari {{ $orders->total() }} pesanan
                </p>
            </div>
            <div class="col-md-6">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<!-- jsPDF CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Select all checkbox
        $('#selectAll').on('change', function() {
            $('.order-checkbox').prop('checked', $(this).prop('checked'));
            toggleBulkActions();
        });

        // Individual checkbox
        $('.order-checkbox').on('change', function() {
            toggleBulkActions();

            // Update select all checkbox
            var totalCheckboxes = $('.order-checkbox').length;
            var checkedCheckboxes = $('.order-checkbox:checked').length;
            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        });

        // Bulk form submission
        $('#bulkForm').on('submit', function(e) {
            e.preventDefault();

            var selectedIds = $('.order-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                Swal.fire('Error', 'Pilih minimal satu pesanan', 'error');
                return;
            }

            // Add selected IDs to form
            selectedIds.forEach(function(id) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'order_ids[]',
                    value: id
                }).appendTo('#bulkForm');
            });

            // Add additional fields based on action
            var action = $('select[name="action"]').val();
            if (action === 'update_status') {
                var status = $('#bulkStatus').val();
                if (!status) {
                    Swal.fire('Error', 'Pilih status yang akan diupdate', 'error');
                    return;
                }
                $('<input>').attr({
                    type: 'hidden',
                    name: 'status',
                    value: status
                }).appendTo('#bulkForm');
            }

            // Confirm and submit
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin menjalankan aksi ini pada ${selectedIds.length} pesanan?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Jalankan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        // Show bulk options based on selected action
        $('select[name="action"]').on('change', function() {
            var action = $(this).val();
            var optionsHtml = '';

            if (action === 'update_status') {
                optionsHtml = `
                <select name="bulk_status" id="bulkStatus" class="form-control form-control-sm" required>
                    <option value="">Pilih Status</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            `;
            } else if (action === 'update_payment_status') {
                optionsHtml = `
                <select name="bulk_payment_status" class="form-control form-control-sm" required>
                    <option value="">Pilih Status Pembayaran</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </select>
            `;
            }

            $('#bulkOptions').html(optionsHtml).toggle(optionsHtml !== '');
        });
    });

    function toggleBulkActions() {
        var checkedCount = $('.order-checkbox:checked').length;
        $('.bulk-actions').toggle(checkedCount > 0);
    }

    function updateStatus(orderId, status) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Yakin ingin mengubah status pesanan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/orders-admin/${orderId}/update-status`,
                    method: 'PATCH',
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal mengubah status';

                        // Check if there's a specific error message from server
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }
        });
    }

    function updatePaymentStatus(orderId, paymentStatus) {
        $.ajax({
            url: `/orders-admin/${orderId}/update-payment-status`,
            method: 'PATCH',
            data: {
                payment_status: paymentStatus,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal mengubah status pembayaran', 'error');
            }
        });
    }

    function deleteOrder(orderId) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/orders-admin/${orderId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire('Berhasil', 'Pesanan berhasil dihapus', 'success').then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus pesanan', 'error');
                    }
                });
            }
        });
    }

    function exportData() {
        // Show loading
        Swal.fire({
            title: 'Memproses Export PDF...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Get current filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const filterParams = {};

        // Collect all filter parameters
        ['status', 'payment_status', 'delivery_method', 'date_from', 'date_to', 'search'].forEach(param => {
            if (urlParams.has(param)) {
                filterParams[param] = urlParams.get(param);
            }
        });

        // Fetch export data from controller
        $.ajax({
            url: '{{ route("admin.orders.export-data") }}',
            method: 'GET',
            data: filterParams,
            success: function(response) {
                if (response.success) {
                    generatePDF(response.data);
                } else {
                    Swal.close();
                    Swal.fire('Error', 'Gagal mengambil data export', 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire('Error', 'Gagal mengambil data export: ' + (xhr.responseJSON?.message || 'Unknown error'), 'error');
            }
        });
    }

    function generatePDF(exportData) {
        try {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('l', 'mm', 'a4'); // landscape orientation

            // Set font
            pdf.setFont('helvetica');

            // Header
            pdf.setFontSize(20);
            pdf.setTextColor(40, 40, 40);
            pdf.text('LAPORAN DATA PESANAN', pdf.internal.pageSize.getWidth() / 2, 20, {
                align: 'center'
            });

            pdf.setFontSize(16);
            pdf.text('CUCI SEPATU SOOOJI', pdf.internal.pageSize.getWidth() / 2, 30, {
                align: 'center'
            });

            pdf.setFontSize(12);
            pdf.text(`Tanggal Export: ${exportData.summary.export_date}`, pdf.internal.pageSize.getWidth() / 2, 40, {
                align: 'center'
            });

            // Filter info if any
            let yPosition = 50;

            if (exportData.filters.has_filters) {
                pdf.setFontSize(10);
                pdf.text('Filter yang Diterapkan:', 20, yPosition);
                yPosition += 7;

                if (exportData.filters.status) {
                    pdf.text(`• Status: ${exportData.filters.status_label}`, 25, yPosition);
                    yPosition += 5;
                }
                if (exportData.filters.payment_status) {
                    pdf.text(`• Status Pembayaran: ${exportData.filters.payment_status_label}`, 25, yPosition);
                    yPosition += 5;
                }
                if (exportData.filters.delivery_method) {
                    pdf.text(`• Metode Pengiriman: ${exportData.filters.delivery_method_label}`, 25, yPosition);
                    yPosition += 5;
                }
                if (exportData.filters.date_from) {
                    pdf.text(`• Dari Tanggal: ${exportData.filters.date_from}`, 25, yPosition);
                    yPosition += 5;
                }
                if (exportData.filters.date_to) {
                    pdf.text(`• Sampai Tanggal: ${exportData.filters.date_to}`, 25, yPosition);
                    yPosition += 5;
                }
                if (exportData.filters.search) {
                    pdf.text(`• Pencarian: "${exportData.filters.search}"`, 25, yPosition);
                    yPosition += 5;
                }
                yPosition += 10;
            } else {
                yPosition += 10;
            }

            // Table headers
            const headers = ['ID', 'Customer', 'Email', 'Layanan', 'Metode', 'Total', 'Status', 'Pembayaran', 'Tanggal'];
            const colWidths = [10, 35, 45, 35, 25, 25, 20, 30, 30];
            let xPosition = 20;

            pdf.setFontSize(9);
            pdf.setTextColor(255, 255, 255);
            pdf.setFillColor(60, 60, 60);

            // Draw header background
            pdf.rect(20, yPosition - 5, 255, 10, 'F');

            // Draw headers
            headers.forEach((header, index) => {
                pdf.text(header, xPosition + 2, yPosition, {
                    maxWidth: colWidths[index] - 4
                });
                xPosition += colWidths[index];
            });

            yPosition += 10;
            pdf.setTextColor(40, 40, 40);

            // Table data
            exportData.orders.forEach((order, index) => {
                if (yPosition > 180) { // New page if needed
                    pdf.addPage();
                    yPosition = 20;
                }

                xPosition = 20;

                // Alternate row colors
                if (index % 2 === 0) {
                    pdf.setFillColor(248, 249, 250);
                    pdf.rect(20, yPosition - 5, 255, 10, 'F');
                }

                const rowData = [
                    `#${order.id}`,
                    order.user.name.substring(0, 20) + (order.user.name.length > 20 ? '...' : ''),
                    order.user.email.substring(0, 25) + (order.user.email.length > 25 ? '...' : ''),
                    order.service.name.substring(0, 20) + (order.service.name.length > 20 ? '...' : ''),
                    order.delivery_method_label,
                    `Rp${new Intl.NumberFormat('id-ID').format(order.total_price)}`,
                    order.status_label,
                    order.payment_status_label,
                    order.created_at_formatted
                ];

                rowData.forEach((data, colIndex) => {
                    pdf.text(data, xPosition + 2, yPosition, {
                        maxWidth: colWidths[colIndex] - 4
                    });
                    xPosition += colWidths[colIndex];
                });

                yPosition += 10;
            });

            // Footer
            yPosition += 10;
            pdf.setFontSize(10);
            pdf.text(`Total Pesanan: ${exportData.summary.total_orders}`, 20, yPosition);
            pdf.text(`Total Revenue: Rp${new Intl.NumberFormat('id-ID').format(exportData.summary.total_revenue)}`, 20, yPosition + 7);
            pdf.text(`Dicetak pada: ${exportData.summary.export_datetime}`, 20, yPosition + 14);

            // Generate filename
            const fileName = `laporan-pesanan-${exportData.summary.export_date}.pdf`;

            // Save the PDF
            pdf.save(fileName);

            Swal.close();
            Swal.fire('Berhasil', 'PDF berhasil diunduh', 'success');

        } catch (error) {
            console.error('PDF generation error:', error);
            Swal.close();
            Swal.fire('Error', 'Gagal membuat PDF: ' + error.message, 'error');
        }
    }
</script>
@stop

@section('css')
<style>
    .badge-lg {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .small-box:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }

    .table th {
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }

    .bulk-actions {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.8rem;
        }

        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 2px;
        }
    }
</style>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

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