@extends('layouts.app')

@section('subtitle', 'Layanan Kami')
@section('content_header_title', 'Layanan')
@section('content_header_subtitle', 'Pilih Layanan yang Anda Butuhkan')

@section('content_body')

<!-- Welcome Banner -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="text-white mb-2">
                            <i class="fas fa-hand-sparkles mr-2"></i>
                            Selamat Datang di Soooji!
                        </h3>
                        <p class="text-white mb-0">
                            Pilih layanan cuci sepatu terbaik untuk kebutuhan Anda.
                            Kami siap memberikan pelayanan terbaik dengan kualitas premium.
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <i class="fas fa-shoe-prints fa-3x text-white opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Grid -->
<div class="row">
    @forelse($services as $service)
    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card service-card h-100 shadow-sm">
            <!-- Service Image -->
            <div class="service-image-container">
                @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}"
                    class="card-img-top service-image"
                    alt="{{ $service->name }}">
                @else
                <div class="no-image-placeholder">
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="text-muted mt-2">No Image Available</p>
                </div>
                @endif

                <!-- Price Badge -->
                <div class="price-badge">
                    <span class="badge badge-success badge-lg">
                        Rp{{ number_format($service->price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Service Content -->
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-primary font-weight-bold">
                    {{ $service->name }}
                </h5>

                <p class="card-text text-muted flex-grow-1">
                    {{ $service->description ?: 'Layanan berkualitas tinggi dengan hasil yang memuaskan.' }}
                </p>

                <!-- Service Features -->
                <div class="service-features mb-3">
                    <small class="text-muted">
                        <i class="fas fa-check-circle text-success mr-1"></i>
                        Kualitas Terjamin
                    </small><br>
                    <small class="text-muted">
                        <i class="fas fa-clock text-info mr-1"></i>
                        Pengerjaan Cepat
                    </small><br>
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-warning mr-1"></i>
                        Garansi Kepuasan
                    </small>
                </div>

                <!-- Action Button -->
                <div class="mt-auto">
                    <a href="{{ route('member.orders.create', $service->id) }}"
                        class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State -->
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">Belum Ada Layanan Tersedia</h4>
                <p class="text-muted">Maaf, saat ini belum ada layanan yang tersedia. Silakan coba lagi nanti.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Info Section -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Layanan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                        <h6>Antar Jemput</h6>
                        <small class="text-muted">Kami akan mengambil dan mengantar sepatu Anda</small>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-store fa-2x text-success mb-2"></i>
                        <h6>Drop Off</h6>
                        <small class="text-muted">Anda bisa langsung datang ke toko kami</small>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <i class="fas fa-mobile-alt fa-2x text-warning mb-2"></i>
                        <h6>Tracking Online</h6>
                        <small class="text-muted">Pantau status pesanan Anda secara real-time</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
    .service-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        overflow: hidden;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .service-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .service-card:hover .service-image {
        transform: scale(1.05);
    }

    .no-image-placeholder {
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .price-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 2;
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
    }

    .service-features {
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }

    .opacity-50 {
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .service-card {
            margin-bottom: 20px;
        }

        .price-badge {
            top: 10px;
            right: 10px;
        }
    }
</style>
@stop