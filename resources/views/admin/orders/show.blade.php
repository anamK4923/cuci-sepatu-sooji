@extends('layouts.app')

@section('subtitle', 'Detail Pesanan')
@section('content_header_title', 'Pesanan')
@section('content_header_subtitle', 'Detail Pesanan #' . $order->id)

@section('content_body')

<div class="row">
    <div class="col-md-8">
        <!-- Order Details Card -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Detail Pesanan #{{ $order->id }}
                </h3>
                <div class="card-tools">
                    <span class="badge badge-{{ getStatusBadgeClass($order->status) }} badge-lg">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <h5 class="text-primary">
                            <i class="fas fa-user mr-2"></i>
                            Informasi Customer
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Nama:</strong></td>
                                <td>{{ $order->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $order->user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon:</strong></td>
                                <td>{{ $order->user->phone ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Service Information -->
                    <div class="col-md-6">
                        <h5 class="text-success">
                            <i class="fas fa-concierge-bell mr-2"></i>
                            Informasi Layanan
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Layanan:</strong></td>
                                <td>{{ $order->service->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Harga:</strong></td>
                                <td>Rp{{ number_format($order->service->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total:</strong></td>
                                <td><strong class="text-success">Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Delivery Information -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-info">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            Informasi Pengiriman
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Metode:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $order->delivery_method == 'antar_jemput' ? 'info' : 'secondary' }}">
                                        {{ $order->delivery_method_label }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->alamat_pickup)
                            <tr>
                                <td><strong>Alamat Pickup:</strong></td>
                                <td>{{ $order->alamat_pickup }}</td>
                            </tr>
                            @endif
                            @if($order->pickup_schedule)
                            <tr>
                                <td><strong>Jadwal Pickup:</strong></td>
                                <td>
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $order->pickup_schedule->format('d M Y, H:i') }}
                                    <small class="text-muted">
                                        ({{ $order->pickup_schedule->diffForHumans() }})
                                    </small>
                                </td>
                            </tr>
                            @endif
                            @if($order->notes)
                            <tr>
                                <td><strong>Catatan:</strong></td>
                                <td>{{ $order->notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Payment Information -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-warning">
                            <i class="fas fa-credit-card mr-2"></i>
                            Informasi Pembayaran
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->midtrans_order_id)
                            <tr>
                                <td><strong>ID Transaksi:</strong></td>
                                <td><code>{{ $order->midtrans_order_id }}</code></td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $order->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Image Card -->
        @if($order->service->image)
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-image mr-2"></i>
                    Gambar Layanan
                </h3>
            </div>
            <div class="card-body text-center">
                <img src="{{ asset('storage/' . $order->service->image) }}"
                    alt="{{ $order->service->name }}"
                    class="img-fluid img-thumbnail"
                    style="max-height: 300px;">
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Quick Actions Card -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tools mr-2"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="card-body">
                <!-- Update Status -->
                <div class="form-group">
                    <label>Update Status Pesanan</label>
                    <select class="form-control" id="statusSelect">
                        <option value="">Pilih Status Baru</option>
                        @php
                        $statuses = [
                        'waiting_pickup' => 'Menunggu Penjemputan',
                        'picked_up' => 'Sudah Dijemput',
                        'in_process' => 'Sedang Diproses',
                        'ready' => 'Siap Diambil',
                        'done' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        ];
                        @endphp
                        @foreach($statuses as $key => $label)
                        @if($key != $order->status)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endif
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm mt-2" onclick="updateStatus()">
                        <i class="fas fa-sync mr-1"></i>
                        Update Status
                    </button>
                </div>

                <hr>

                <!-- Update Payment Status -->
                <div class="form-group">
                    <label>Status Pembayaran</label>
                    <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                        <label class="btn btn-outline-warning {{ $order->payment_status == 'pending' ? 'active' : '' }}">
                            <input type="radio" name="payment_status" value="pending"
                                {{ $order->payment_status == 'pending' ? 'checked' : '' }}>
                            Pending
                        </label>
                        <label class="btn btn-outline-success {{ $order->payment_status == 'paid' ? 'active' : '' }}">
                            <input type="radio" name="payment_status" value="paid"
                                {{ $order->payment_status == 'paid' ? 'checked' : '' }}>
                            Paid
                        </label>
                    </div>
                    <button class="btn btn-success btn-sm mt-2" onclick="updatePaymentStatus()">
                        <i class="fas fa-credit-card mr-1"></i>
                        Update Pembayaran
                    </button>
                </div>

                <hr>

                <!-- Other Actions -->
                <div class="d-grid gap-2">
                    <button class="btn btn-info btn-block" onclick="printOrder()">
                        <i class="fas fa-print mr-2"></i>
                        Print Pesanan
                    </button>
                    <!-- <button class="btn btn-secondary btn-block" onclick="sendNotification()">
                        <i class="fas fa-envelope mr-2"></i>
                        Kirim Notifikasi
                    </button> -->
                    <button class="btn btn-danger btn-block" onclick="deleteOrder()">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Pesanan
                    </button>
                </div>
            </div>
        </div>

        <!-- Order Timeline Card -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Timeline Pesanan
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    @php
                    $timelineSteps = [
                    'waiting_pickup' => ['icon' => 'clock', 'color' => 'warning', 'title' => 'Menunggu Penjemputan'],
                    'picked_up' => ['icon' => 'truck', 'color' => 'info', 'title' => 'Sudah Dijemput'],
                    'in_process' => ['icon' => 'cogs', 'color' => 'primary', 'title' => 'Sedang Diproses'],
                    'ready' => ['icon' => 'check', 'color' => 'success', 'title' => 'Siap Diambil'],
                    'done' => ['icon' => 'check-circle', 'color' => 'success', 'title' => 'Selesai'],
                    ];
                    $currentStepIndex = array_search($order->status, array_keys($timelineSteps));
                    @endphp

                    @foreach($timelineSteps as $stepKey => $step)
                    @php
                    $stepIndex = array_search($stepKey, array_keys($timelineSteps));
                    $isCompleted = $stepIndex <= $currentStepIndex;
                        $isCurrent=$stepKey==$order->status;
                        @endphp

                        <div class="time-label">
                            <span class="bg-{{ $isCompleted ? $step['color'] : 'secondary' }}">
                                <i class="fas fa-{{ $step['icon'] }}"></i>
                            </span>
                        </div>
                        <div>
                            <i class="fas fa-{{ $step['icon'] }} bg-{{ $isCompleted ? $step['color'] : 'secondary' }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header {{ $isCurrent ? 'text-bold' : '' }}">
                                    {{ $step['title'] }}
                                    @if($isCurrent)
                                    <span class="badge badge-primary ml-2">Current</span>
                                    @endif
                                </h3>
                                @if($isCompleted)
                                <div class="timeline-body">
                                    Status ini telah tercapai
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="row mt-3">
    <div class="col-12">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pesanan
        </a>
    </div>
</div>

@stop

@section('js')
<script>
    function updateStatus() {
        var newStatus = $('#statusSelect').val();
        if (!newStatus) {
            Swal.fire('Error', 'Pilih status yang akan diupdate', 'error');
            return;
        }

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
                    url: '{{ route("admin.orders.update-status", $order) }}',
                    method: 'PATCH',
                    data: {
                        status: newStatus,
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
                        Swal.fire('Error', 'Gagal mengubah status', 'error');
                    }
                });
            }
        });
    }

    function updatePaymentStatus() {
        var paymentStatus = $('input[name="payment_status"]:checked').val();

        $.ajax({
            url: '{{ route("admin.orders.update-payment-status", $order) }}',
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

    function printOrder() {
        window.print();
    }

    function sendNotification() {
        Swal.fire({
            title: 'Kirim Notifikasi',
            text: 'Fitur notifikasi akan segera tersedia!',
            icon: 'info'
        });
    }

    function deleteOrder() {
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
                    url: '{{ route("admin.orders.destroy", $order) }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire('Berhasil', 'Pesanan berhasil dihapus', 'success').then(() => {
                            window.location.href = '{{ route("admin.orders.index") }}';
                        });
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus pesanan', 'error');
                    }
                });
            }
        });
    }
</script>
@stop

@section('css')
<style>
    .timeline-inverse {
        margin: 0;
    }

    .timeline-inverse .time-label>span {
        border-radius: 50%;
        padding: 5px 10px;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .card-tools .badge {
        font-size: 1rem;
    }

    @media print {

        .card-tools,
        .btn,
        .timeline {
            display: none !important;
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