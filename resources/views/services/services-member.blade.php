@extends('layouts.app')

@section('subtitle', 'Services Member')
@section('content_header_title', 'Services')
@section('content_header_subtitle', 'Sneat - Member Services')

@section('content_body')
<div class="container-fluid">
    <div class="row">

        @php
        $services = [
        [
        'id' => 1,
        'name' => 'Cuci Sepatu Reguler',
        'description' => 'Layanan cuci sepatu standar untuk sehari-hari.',
        'price' => 20000,
        'image' => '/images/reguler_clean.jpeg'
        ],
        [
        'id' => 2,
        'name' => 'Cuci Sepatu Premium',
        'description' => 'Layanan premium dengan pembersih khusus dan perawatan ekstra.',
        'price' => 35000,
        'image' => '/images/premium_clean.jpeg'
        ],
        [
        'id' => 3,
        'name' => 'Deep Cleaning Sepatu',
        'description' => 'Pembersihan mendalam untuk noda membandel dan perawatan maksimal.',
        'price' => 50000,
        'image' => '/images/deep_clean.jpeg'
        ],
        ];
        @endphp

        @foreach($services as $service)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $service['image'] }}" class="card-img-top" alt="{{ $service['name'] }}" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $service['name'] }}</h5>
                    <p class="card-text">{{ $service['description'] }}</p>
                    <p class="card-text"><strong>Harga:</strong> Rp{{ number_format($service['price'], 0, ',', '.') }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="#" class="btn btn-primary btn-block order-btn"
                        data-toggle="modal"
                        data-target="#orderModal"
                        data-service="{{ $service['name'] }}"
                        data-service-id="{{ $service['id'] }}"
                        data-price="{{ $service['price'] }}">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Modal Form Pemesanan -->
        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="orderForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel">Form Pemesanan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Service Info -->
                            <div class="form-group">
                                <label for="serviceName">Layanan</label>
                                <input type="text" class="form-control" id="serviceName" name="service_name" readonly>
                            </div>

                            <div class="form-group">
                                <label for="totalPriceDisplay">Total Harga</label>
                                <input type="text" class="form-control" id="totalPriceDisplay" readonly>
                                <input type="hidden" id="totalPrice" name="total_price">
                            </div>

                            <!-- Customer Info -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customerName">Nama Pemesan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="customerName" name="customer_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customerPhone">No. Telepon <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="customerPhone" name="customer_phone" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Delivery Method -->
                            <div class="form-group">
                                <label for="deliveryMethod">Metode Pengiriman <span class="text-danger">*</span></label>
                                <select class="form-control" id="deliveryMethod" name="delivery_method" required>
                                    <option value="">Pilih Metode Pengiriman</option>
                                    <option value="pickup">Pickup - Kami Jemput</option>
                                    <option value="drop_off">Drop Off - Antar Sendiri</option>
                                </select>
                            </div>

                            <!-- Pickup Address (conditional) -->
                            <div class="form-group" id="pickupAddressGroup" style="display: none;">
                                <label for="alamatPickup">Alamat Pickup <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alamatPickup" name="alamat_pickup" rows="3" placeholder="Masukkan alamat lengkap untuk pickup"></textarea>
                            </div>

                            <!-- Pickup Schedule (conditional) -->
                            <div class="form-group" id="pickupScheduleGroup" style="display: none;">
                                <label for="pickupSchedule">Jadwal Pickup <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="pickupSchedule" name="pickup_schedule">
                            </div>

                            <!-- Notes -->
                            <div class="form-group">
                                <label for="notes">Catatan Tambahan</label>
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
</div>

<!-- Success Alert -->
<div class="alert alert-success alert-dismissible fade" id="successAlert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Berhasil!</strong> <span id="successMessage"></span>
</div>

<!-- Error Alert -->
<div class="alert alert-danger alert-dismissible fade" id="errorAlert" style="display: none;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>Error!</strong> <span id="errorMessage"></span>
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
                $('#pickupAddressGroup').show();
                $('#pickupScheduleGroup').show();
                $('#alamatPickup').prop('required', true);
                $('#pickupSchedule').prop('required', true);
            } else {
                $('#pickupAddressGroup').hide();
                $('#pickupScheduleGroup').hide();
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