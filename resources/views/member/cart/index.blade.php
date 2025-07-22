@extends('layouts.app')

@section('subtitle', 'Keranjang Belanja')
@section('content_header_title', 'Keranjang')
@section('content_header_subtitle', 'Item di Keranjang Anda')

@section('content_body')
<div class="row justify-content-center">
    <div class="col-md-12">
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
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
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

        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Keranjang Belanja Anda
                </h3>
            </div>
            <form action="{{ route('member.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="card-body">
                    @if($services->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Keranjang Anda Kosong</h5>
                        <p class="text-muted">Tambahkan layanan dari halaman <a href="{{ route('member.services.index') }}">Layanan</a>.</p>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAllItems">
                                    </th>
                                    <th>Layanan</th>
                                    <th width="150">Harga</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                @php
                                $service = $services->get($item['service_id']);
                                @endphp
                                @if($service)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_services[]" class="item-checkbox" value="{{ $service->id }}" checked>
                                    </td>
                                    <td>
                                        <strong>{{ $service->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $service->description }}</small>
                                    </td>
                                    <td class="text-right">Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                                    <td>
                                        <form action="{{ route('member.cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Harga Terpilih:</th>
                                    <th class="text-right" id="totalSelectedPrice">Rp{{ number_format($totalCartPrice, 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <hr>

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
                        <select name="pickup_schedule"
                            id="pickup_schedule"
                            class="form-control @error('pickup_schedule') is-invalid @enderror">
                            <option value="">Pilih Jadwal Penjemputan</option>
                            <option value="12:00" {{ old('pickup_schedule') == '12:00' ? 'selected' : '' }}>
                                Jam 12.00 WIB - Siang
                            </option>
                            <option value="18:00" {{ old('pickup_schedule') == '18:00' ? 'selected' : '' }}>
                                Jam 18.00 WIB - Sore
                            </option>
                        </select>
                        @error('pickup_schedule')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Jadwal penjemputan untuk hari transaksi
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
                    @endif
                </div>
                <!-- Card Footer -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('member.services.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Lanjutkan Belanja
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block" id="checkoutBtn" {{ $services->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-cash-register mr-2"></i>
                                Checkout
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
        // Service prices from backend
        const servicePrices = @json($servicePrices);

        // Function to update total selected price
        function updateSelectedPrice() {
            let total = 0;
            $('.item-checkbox:checked').each(function() {
                const serviceId = parseInt($(this).val());
                if (servicePrices[serviceId]) {
                    total += parseInt(servicePrices[serviceId]); // Pastikan konversi ke integer
                }
            });

            // Format angka dengan pemisah ribuan
            const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
            $('#totalSelectedPrice').text('Rp' + formattedTotal);

            // Enable/disable checkout button based on selected items
            if ($('.item-checkbox:checked').length > 0) {
                $('#checkoutBtn').prop('disabled', false);
            } else {
                $('#checkoutBtn').prop('disabled', true);
            }
        }

        // Initial calculation on page load
        updateSelectedPrice();

        // Select all checkbox
        $('#selectAllItems').on('change', function() {
            $('.item-checkbox').prop('checked', $(this).prop('checked'));
            updateSelectedPrice();
        });

        // Individual checkbox change
        $('.item-checkbox').on('change', function() {
            updateSelectedPrice();
            // Update select all checkbox state
            const totalCheckboxes = $('.item-checkbox').length;
            const checkedCheckboxes = $('.item-checkbox:checked').length;
            $('#selectAllItems').prop('checked', totalCheckboxes === checkedCheckboxes);
        });

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

        // Initialize on page load for delivery method fields
        $('input[name="delivery_method"]:checked').trigger('change');

        // Checkout form submission
        $('#checkoutForm').on('submit', function(e) {
            const selectedItemsCount = $('.item-checkbox:checked').length;
            if (selectedItemsCount === 0) {
                Swal.fire('Error', 'Pilih setidaknya satu layanan untuk checkout.', 'error');
                e.preventDefault(); // Prevent form submission
                return;
            }

            $('#checkoutBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Memproses Checkout...');
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