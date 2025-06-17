@extends('layouts.app')

@section('subtitle', 'Services Member')
@section('content_header_title', 'Services')
@section('content_header_subtitle', 'Soooji - Member Services')

@push('css')
<style>
    .content-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        min-height: 100vh;
    }

    .services-container {
        padding: 2rem 1rem;
        position: relative;
    }

    .services-header {
        text-align: center;
        margin-bottom: 3rem;
        color: black;
        padding-top: 1rem;
    }

    .services-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .services-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    .service-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        margin-bottom: 2rem;
        border: none;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        z-index: 1;
    }

    .service-image {
        position: relative;
        overflow: hidden;
        height: 200px;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .service-card:hover .service-image img {
        transform: scale(1.05);
    }

    .service-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .service-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: calc(100% - 200px);
    }

    .service-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .service-title i {
        color: #667eea;
        font-size: 1.1rem;
    }

    .service-description {
        color: #718096;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex-grow: 1;
    }

    .service-features {
        list-style: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .service-features li {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #4a5568;
        font-size: 0.85rem;
        margin-bottom: 0.4rem;
    }

    .service-features li i {
        color: #48bb78;
        font-size: 0.8rem;
    }

    .price-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding: 0.8rem;
        background: #f7fafc;
        border-radius: 12px;
        border-left: 4px solid #667eea;
    }

    .price-label {
        color: #718096;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .price-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #667eea;
    }

    .order-btn {
        width: 100%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.95rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-top: auto;
    }

    .order-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .order-btn:hover::before {
        left: 100%;
    }

    .order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .modal-content {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 1.5rem 2rem;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        padding: 4px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-top: 1rem;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
    }

    .alert-success {
        background: linear-gradient(45deg, #48bb78, #38a169);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(45deg, #f56565, #e53e3e);
        color: white;
    }

    @media (max-width: 768px) {
        .services-header h1 {
            font-size: 1.8rem;
        }

        .services-header p {
            font-size: 1rem;
        }

        .service-content {
            padding: 1.2rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .services-container {
            padding: 1rem 0.5rem;
        }
    }

    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
        top: 0;
        left: 0;
    }

    .floating-elements::before,
    .floating-elements::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }

    .floating-elements::before {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }

    .floating-elements::after {
        bottom: 10%;
        right: 10%;
        animation-delay: 3s;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    /* Override AdminLTE styles */
    .content-wrapper,
    .content-wrapper>.content {
        background: transparent !important;
    }
</style>
@endpush

@section('content_body')
<div class="services-container">
    <div class="floating-elements"></div>

    <div class="services-header">
        <h1><i class="fas fa-shoe-prints"></i> Layanan Cuci Sepatu Premium</h1>
        <p>Pilih layanan terbaik untuk sepatu kesayangan Anda dengan teknologi pembersihan modern dan ramah lingkungan</p>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            @php
            $services = [
            [
            'id' => 1,
            'name' => 'Cuci Sepatu Reguler',
            'description' => 'Layanan cuci sepatu standar untuk sehari-hari dengan pembersihan menyeluruh.',
            'price' => 20000,
            'image' => '/images/reguler_clean.jpeg',
            'badge' => 'POPULER',
            'icon' => 'fas fa-star',
            'features' => [
            'Pembersihan luar dan dalam',
            'Pengeringan alami',
            'Waktu pengerjaan 1-2 hari',
            'Garansi kepuasan'
            ]
            ],
            [
            'id' => 2,
            'name' => 'Cuci Sepatu Premium',
            'description' => 'Layanan premium dengan pembersih khusus dan perawatan ekstra untuk hasil maksimal.',
            'price' => 35000,
            'image' => '/images/premium_clean.jpeg',
            'badge' => 'PREMIUM',
            'icon' => 'fas fa-crown',
            'features' => [
            'Deep cleaning technology',
            'Conditioning & protection',
            'Express service 24 jam',
            'Free pickup & delivery'
            ]
            ],
            [
            'id' => 3,
            'name' => 'Deep Cleaning Sepatu',
            'description' => 'Pembersihan mendalam untuk noda membandel dan perawatan maksimal dengan teknologi terdepan.',
            'price' => 50000,
            'image' => '/images/deep_clean.jpeg',
            'badge' => 'ULTIMATE',
            'icon' => 'fas fa-gem',
            'features' => [
            'Ultrasonic cleaning',
            'Stain removal specialist',
            'Color restoration',
            'Premium packaging'
            ]
            ],
            ];
            @endphp

            @foreach($services as $service)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card service-card">
                    <div class="service-image">
                        <img src="{{ $service['image'] }}" alt="{{ $service['name'] }}">
                        <div class="service-badge">{{ $service['badge'] }}</div>
                    </div>

                    <div class="service-content">
                        <h5 class="service-title">
                            <i class="{{ $service['icon'] }}"></i>
                            {{ $service['name'] }}
                        </h5>

                        <p class="service-description">{{ $service['description'] }}</p>

                        <ul class="service-features">
                            @foreach($service['features'] as $feature)
                            <li><i class="fas fa-check-circle"></i> {{ $feature }}</li>
                            @endforeach
                        </ul>

                        <div class="price-section">
                            <span class="price-label">Harga Mulai</span>
                            <span class="price-value">Rp{{ number_format($service['price'], 0, ',', '.') }}</span>
                        </div>

                        <button class="btn order-btn"
                            data-toggle="modal"
                            data-target="#orderModal"
                            data-service="{{ $service['name'] }}"
                            data-service-id="{{ $service['id'] }}"
                            data-price="{{ $service['price'] }}">
                            <i class="fas fa-shopping-cart"></i> Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Form Pemesanan -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="orderForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel">
                            <i class="fas fa-clipboard-list"></i> Form Pemesanan Layanan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Service Info -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="serviceName"><i class="fas fa-cog"></i> Layanan</label>
                                    <input type="text" class="form-control" id="serviceName" name="service_name" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="totalPriceDisplay"><i class="fas fa-money-bill-wave"></i> Total Harga</label>
                                    <input type="text" class="form-control" id="totalPriceDisplay" readonly>
                                    <input type="hidden" id="totalPrice" name="total_price">
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customerName"><i class="fas fa-user"></i> Nama Pemesan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customerName" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customerPhone"><i class="fas fa-phone"></i> No. Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="customerPhone" name="customer_phone" required>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="form-group">
                            <label for="deliveryMethod"><i class="fas fa-truck"></i> Metode Pengiriman <span class="text-danger">*</span></label>
                            <select class="form-control" id="deliveryMethod" name="delivery_method" required>
                                <option value="">Pilih Metode Pengiriman</option>
                                <option value="pickup">🚚 Pickup - Kami Jemput</option>
                                <option value="drop_off">🏪 Drop Off - Antar Sendiri</option>
                            </select>
                        </div>

                        <!-- Pickup Address (conditional) -->
                        <div class="form-group" id="pickupAddressGroup" style="display: none;">
                            <label for="alamatPickup"><i class="fas fa-map-marker-alt"></i> Alamat Pickup <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamatPickup" name="alamat_pickup" rows="3" placeholder="Masukkan alamat lengkap untuk pickup"></textarea>
                        </div>

                        <!-- Pickup Schedule (conditional) -->
                        <div class="form-group" id="pickupScheduleGroup" style="display: none;">
                            <label for="pickupSchedule"><i class="fas fa-calendar-alt"></i> Jadwal Pickup <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="pickupSchedule" name="pickup_schedule">
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes"><i class="fas fa-sticky-note"></i> Catatan Tambahan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan khusus untuk pesanan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Kirim Pesanan
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Alert -->
<div class="alert alert-success alert-dismissible fade" id="successAlert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong><i class="fas fa-check-circle"></i> Berhasil!</strong> <span id="successMessage"></span>
</div>

<!-- Error Alert -->
<div class="alert alert-danger alert-dismissible fade" id="errorAlert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong><i class="fas fa-exclamation-triangle"></i> Error!</strong> <span id="errorMessage"></span>
</div>

@stop

@push('js')
<script>
    $(document).ready(function() {
        // Handle modal show event
        $('#orderModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var service = button.data('service');
            var serviceId = button.data('service-id');
            var price = button.data('price');

            var modal = $(this);
            modal.find('#serviceName').val(service);
            modal.find('#totalPrice').val(price);
            modal.find('#totalPriceDisplay').val('Rp' + Number(price).toLocaleString('id-ID'));
        });

        // Handle delivery method change
        $('#deliveryMethod').on('change', function() {
            var method = $(this).val();
            if (method === 'pickup') {
                $('#pickupAddressGroup').slideDown();
                $('#pickupScheduleGroup').slideDown();
                $('#alamatPickup').prop('required', true);
                $('#pickupSchedule').prop('required', true);
            } else {
                $('#pickupAddressGroup').slideUp();
                $('#pickupScheduleGroup').slideUp();
                $('#alamatPickup').prop('required', false);
                $('#pickupSchedule').prop('required', false);
            }
        });

        // Handle form submission
        $('#orderForm').on('submit', function(e) {
            e.preventDefault();

            var submitBtn = $('#submitBtn');
            var originalText = submitBtn.html();

            // Show loading state
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

            // Hide previous alerts
            $('#successAlert, #errorAlert').hide();

            // Get form data
            var formData = $(this).serialize();

            console.log('Sending data:', formData);

            // Submit via AJAX
            $.ajax({
                url: '{{ route("orders.store") }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Success response:', response);

                    // Hide modal
                    $('#orderModal').modal('hide');

                    // Reset form
                    $('#orderForm')[0].reset();
                    $('#pickupAddressGroup').hide();
                    $('#pickupScheduleGroup').hide();

                    // Show success message
                    $('#successMessage').text(response.message);
                    $('#successAlert').show().addClass('show');

                    // Auto hide after 5 seconds
                    setTimeout(function() {
                        $('#successAlert').fadeOut();
                    }, 5000);
                },
                error: function(xhr) {
                    console.log('Error response:', xhr.responseJSON);

                    var message = 'Terjadi kesalahan';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join(', ');
                        }
                    }

                    $('#errorMessage').text(message);
                    $('#errorAlert').show().addClass('show');
                },
                complete: function() {
                    // Restore button state
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Reset form when modal is hidden
        $('#orderModal').on('hidden.bs.modal', function() {
            $('#orderForm')[0].reset();
            $('#pickupAddressGroup').hide();
            $('#pickupScheduleGroup').hide();
        });
    });
</script>
@endpush