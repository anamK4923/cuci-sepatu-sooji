@extends('layouts.app')

@section('subtitle', 'Status Pemesanan')
@section('content_header_title', 'Status Pemesanan')
@section('content_header_subtitle', 'Pantau Perkembangan Pesanan Anda')

@section('content_body')

<div class="row">
    <div class="col-12">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <!-- Orders List -->
        @forelse($orders as $order)
        <div class="card mb-4">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt mr-2"></i>
                            Order #{{ $order->id }}
                        </h5>
                        <small class="text-muted">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                    <div class="col-md-6 text-right">
                        <!-- Status Badge -->
                        @php
                        $statusConfig = [
                        'waiting_pickup' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Menunggu Penjemputan'],
                        'picked_up' => ['class' => 'info', 'icon' => 'truck', 'text' => 'Sudah Dijemput'],
                        'in_process' => ['class' => 'primary', 'icon' => 'cogs', 'text' => 'Sedang Diproses'],
                        'ready' => ['class' => 'success', 'icon' => 'check', 'text' => 'Siap Diambil'],
                        'completed' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Selesai'],
                        'cancelled' => ['class' => 'danger', 'icon' => 'times', 'text' => 'Dibatalkan']
                        ];
                        $status = $statusConfig[$order->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                        @endphp

                        <span class="badge badge-{{ $status['class'] }} badge-lg">
                            <i class="fas fa-{{ $status['icon'] }} mr-1"></i>
                            {{ $status['text'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Service Info -->
                    <div class="col-md-4">
                        <h6 class="text-primary">
                            <i class="fas fa-concierge-bell mr-1"></i>
                            Layanan
                        </h6>
                        <p class="mb-2">{{ $order->service->name }}</p>

                        <h6 class="text-success">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            Total Harga
                        </h6>
                        <p class="mb-2">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>

                    <!-- Delivery Info -->
                    <div class="col-md-4">
                        <h6 class="text-info">
                            <i class="fas fa-shipping-fast mr-1"></i>
                            Metode Pengiriman
                        </h6>
                        <p class="mb-2">
                            {{ $order->delivery_method == 'antar_jemput' ? 'Antar Jemput' : 'Drop Off' }}
                        </p>

                        @if($order->delivery_method == 'antar_jemput' && $order->pickup_schedule)
                        <h6 class="text-warning">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Jadwal Penjemputan
                        </h6>
                        <p class="mb-2">{{ $order->pickup_schedule->format('d M Y, H:i') }}</p>
                        @endif
                    </div>

                    <!-- Payment Status -->
                    <div class="col-md-4">
                        <h6 class="text-danger">
                            <i class="fas fa-credit-card mr-1"></i>
                            Status Pembayaran
                        </h6>
                        <p class="mb-2">
                            @if($order->payment_status == 'pending')
                            <span class="badge badge-warning">Menunggu Pembayaran</span>
                            @else
                            <span class="badge badge-success">Sudah Dibayar</span>
                            @endif
                        </p>

                        @if($order->midtrans_order_id)
                        <h6 class="text-muted">
                            <i class="fas fa-hashtag mr-1"></i>
                            ID Transaksi
                        </h6>
                        <p class="mb-2">
                            <code>{{ $order->midtrans_order_id }}</code>
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Additional Info -->
                @if($order->alamat_pickup)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-dark">
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            Alamat Penjemputan
                        </h6>
                        <p class="text-muted">{{ $order->alamat_pickup }}</p>
                    </div>
                </div>
                @endif

                @if($order->notes)
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-dark">
                            <i class="fas fa-sticky-note mr-1"></i>
                            Catatan
                        </h6>
                        <p class="text-muted">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Progress Timeline -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-dark mb-3">
                            <i class="fas fa-tasks mr-1"></i>
                            Progress Pesanan
                        </h6>

                        <div class="timeline">
                            @php
                            $steps = [
                            'waiting_pickup' => 'Menunggu Penjemputan',
                            'picked_up' => 'Sudah Dijemput',
                            'in_process' => 'Sedang Diproses',
                            'ready' => 'Siap Diambil',
                            'completed' => 'Selesai'
                            ];
                            $currentStepIndex = array_search($order->status, array_keys($steps));
                            @endphp

                            @foreach($steps as $stepKey => $stepName)
                            @php
                            $stepIndex = array_search($stepKey, array_keys($steps));
                            $isActive = $stepIndex <= $currentStepIndex;
                                $isCurrent=$stepKey==$order->status;
                                @endphp

                                <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                    <div class="timeline-marker">
                                        @if($isActive)
                                        <i class="fas fa-check"></i>
                                        @else
                                        <i class="fas fa-circle"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <span class="{{ $isCurrent ? 'font-weight-bold text-primary' : '' }}">
                                            {{ $stepName }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-6">
                        @if($order->payment_status == 'pending')
                        <button class="btn btn-warning" onclick="payOrder('{{ $order->id }}')">
                            <i class="fas fa-credit-card mr-2"></i>
                            Bayar Sekarang
                        </button>
                        @endif
                    </div>
                    <div class="col-md-6 text-right">
                        <small class="text-muted">
                            Terakhir update: {{ $order->updated_at->format('d M Y, H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Belum Ada Pesanan</h4>
                <p class="text-muted">Anda belum memiliki pesanan. Mulai dengan memilih layanan yang tersedia.</p>
                <a href="{{ route('member.services.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Buat Pesanan Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>

@stop

@section('js')
<script>
    function payOrder(orderId) {
        // Placeholder for payment integration
        Swal.fire({
            title: 'Pembayaran',
            text: 'Fitur pembayaran akan segera tersedia!',
            icon: 'info',
            confirmButtonText: 'OK'
        });
    }

    // Auto refresh every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@stop

@section('css')
<style>
    .badge-lg {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .timeline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        margin: 20px 0;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #dee2e6;
        z-index: 1;
    }

    .timeline-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .timeline-marker {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 12px;
        margin-bottom: 8px;
    }

    .timeline-item.active .timeline-marker {
        background-color: #28a745;
        color: white;
    }

    .timeline-item.current .timeline-marker {
        background-color: #007bff;
        color: white;
        animation: pulse 2s infinite;
    }

    .timeline-content {
        text-align: center;
        font-size: 12px;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    @media (max-width: 768px) {
        .timeline {
            flex-direction: column;
            align-items: flex-start;
        }

        .timeline::before {
            width: 2px;
            height: 100%;
            left: 15px;
            top: 0;
        }

        .timeline-item {
            flex-direction: row;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }

        .timeline-marker {
            margin-right: 15px;
            margin-bottom: 0;
        }

        .timeline-content {
            text-align: left;
        }
    }
</style>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush