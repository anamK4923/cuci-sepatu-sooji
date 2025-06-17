@extends('layouts.app')

@section('subtitle', 'Buat Pesanan')
@section('content_header_title', 'Pemesanan')
@section('content_header_subtitle', 'Buat Pesanan Baru')

@section('content_body')

<div class="row justify-content-center">
    <div class="col-md-8">
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

        <!-- Order Form -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    Form Pemesanan
                </h3>
            </div>

            <form action="{{ route('member.orders.store') }}" method="POST" id="orderForm">
                @csrf
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <input type="hidden" name="total_price" value="{{ $service->price }}">

                <div class="card-body">
                    <!-- Delivery Method -->
                    <div class="form-group">
                        <label class="font-weight-bold">
                            <i class="fas fa-shipping-fast text-primary mr-1"></i>
                            Metode Pengiriman
                        </label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input"
                                        type="radio"
                                        id="antar_jemput"
                                        name="delivery_method"
                                        value="antar_jemput"
                                        {{ old('delivery_method') == 'antar_jemput' ? 'checked' : '' }}>
                                    <label for="antar_jemput" class="custom-control-label">
                                        <strong>Antar Jemput</strong>
                                        <br><small class="text-muted">Kami akan mengambil dan mengantar sepatu Anda</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input"
                                        type="radio"
                                        id="drop_off"
                                        name="delivery_method"
                                        value="drop_off"
                                        {{ old('delivery_method') == 'drop_off' ? 'checked' : '' }}>
                                    <label for="drop_off" class="custom-control-label">
                                        <strong>Drop Off</strong>
                                        <br><small class="text-muted">Anda datang langsung ke toko kami</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('delivery_method')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Pickup Address (for antar_jemput) -->
                    <div class="form-group" id="pickup_address_group" style="display: none;">
                        <label for="alamat_pickup" class="font-weight-bold">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                            Alamat Penjemputan
                        </label>
                        <textarea name="alamat_pickup"
                            id="alamat_pickup"
                            class="form-control @error('alamat_pickup') is-invalid @enderror"
                            rows="3"
                            placeholder="Masukkan alamat lengkap untuk penjemputan...">{{ old('alamat_pickup') }}</textarea>
                        @error('alamat_pickup')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Berikan alamat yang jelas dan mudah ditemukan
                        </small>
                    </div>

                    <!-- Pickup Schedule (for antar_jemput) -->
                    <div class="form-group" id="pickup_schedule_group" style="display: none;">
                        <label for="pickup_schedule" class="font-weight-bold">
                            <i class="fas fa-calendar-alt text-warning mr-1"></i>
                            Jadwal Penjemputan
                        </label>
                        <input type="datetime-local"
                            name="pickup_schedule"
                            id="pickup_schedule"
                            class="form-control @error('pickup_schedule') is-invalid @enderror"
                            value="{{ old('pickup_schedule') }}"
                            min="{{ date('Y-m-d\TH:i', strtotime('+2 hours')) }}">
                        @error('pickup_schedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Minimal 2 jam dari sekarang
                        </small>
                    </div>

                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes" class="font-weight-bold">
                            <i class="fas fa-sticky-note text-info mr-1"></i>
                            Catatan Tambahan
                        </label>
                        <textarea name="notes"
                            id="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            rows="3"
                            placeholder="Catatan khusus untuk pesanan Anda (opsional)...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Contoh: kondisi sepatu, permintaan khusus, dll.
                        </small>
                    </div>

                    <!-- Order Summary -->
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt mr-2"></i>
                                Ringkasan Pesanan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Layanan:</strong>
                                </div>
                                <div class="col-6 text-right">
                                    {{ $service->name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Harga:</strong>
                                </div>
                                <div class="col-6 text-right">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong class="text-success">Total:</strong>
                                </div>
                                <div class="col-6 text-right">
                                    <strong class="text-success">Rp{{ number_format($service->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <button type="submit" class="btn btn-success btn-block" id="submitBtn">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Buat Pesanan
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
        // Toggle pickup fields based on delivery method
        $('input[name="delivery_method"]').on('change', function() {
            if ($(this).val() === 'antar_jemput') {
                $('#pickup_address_group, #pickup_schedule_group').show();
                $('#alamat_pickup').attr('required', true);
                $('#pickup_schedule').attr('required', true);
            } else {
                $('#pickup_address_group, #pickup_schedule_group').hide();
                $('#alamat_pickup').attr('required', false);
                $('#pickup_schedule').attr('required', false);
            }
        });

        // Initialize on page load
        $('input[name="delivery_method"]:checked').trigger('change');

        // Form submission
        $('#orderForm').on('submit', function(e) {
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...');
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