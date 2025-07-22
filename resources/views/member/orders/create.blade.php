@extends('layouts.app')

@section('subtitle', 'Detail Layanan')
@section('content_header_title', 'Layanan')
@section('content_header_subtitle', 'Detail Layanan ' . $service->name)

@section('content_body')
<div class="row justify-content-center">
    <div class="col-md-12">
        <!-- Service Info Card -->
        <div class="card card-primary mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Layanan yang Dipilih
                </h3>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}"
                            class="img-fluid img-thumbnail"
                            alt="{{ $service->name }}">
                        @else
                        <div class="no-image-placeholder-small">
                            <i class="fas fa-image fa-2x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4 class="text-primary">{{ $service->name }}</h4>
                        <p class="text-muted mb-2">{{ $service->description }}</p>
                        <h5 class="text-success">
                            <i class="fas fa-tag mr-1"></i>
                            Rp{{ number_format($service->price, 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add to Cart Form -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cart-plus mr-2"></i>
                    Tambahkan ke Keranjang
                </h3>
            </div>
            <form action="{{ route('member.cart.add') }}" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="card-body">
                    <p class="text-muted">Klik tombol di bawah untuk menambahkan layanan ini ke keranjang belanja Anda.</p>
                    <!-- You can add quantity input here if needed, but for now, it's 1 per add -->
                </div>
                <!-- Card Footer -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('member.services.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Layanan
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-block" id="addToCartBtn">
                                <i class="fas fa-cart-plus mr-2"></i>
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#addToCartForm').on('submit', function(e) {
            $('#addToCartBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...');
        });
    });
</script>
@stop

@section('css')
<style>
    .no-image-placeholder-small {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
    }

    .custom-control-label {
        cursor: pointer;
    }

    .card-outline {
        border-width: 2px;
    }

    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 10px;
        }
    }
</style>
@stop