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
            {{-- Form action changed to CartController@checkout --}}
            <form action="{{ route('member.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="card-body">
                    @if(empty($cartItems)) {{-- Check if cartItems is empty --}}
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
                                    <th>Layanan & Detail Pesanan</th>
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
                                        {{-- Checkbox value is now the unique_id of the cart item --}}
                                        <input type="checkbox" name="selected_cart_items[]" class="item-checkbox" value="{{ $item['unique_id'] }}" checked>
                                    </td>
                                    <td>
                                        <strong>{{ $service->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $service->description }}</small>
                                        <div class="mt-2">
                                            <small>
                                                <strong>Metode Pengiriman:</strong>
                                                {{ $item['delivery_method'] == 'antar_jemput' ? 'Antar Jemput' : 'Drop Off' }}
                                            </small>
                                            @if($item['delivery_method'] == 'antar_jemput')
                                            <br>
                                            <small>
                                                <strong>Alamat Pickup:</strong> {{ $item['alamat_pickup'] }}
                                            </small>
                                            <br>
                                            <small>
                                                <strong>Jadwal Pickup:</strong> {{ $item['pickup_schedule'] }} WIB
                                            </small>
                                            @endif
                                            @if($item['notes'])
                                            <br>
                                            <small>
                                                <strong>Catatan:</strong> {{ $item['notes'] }}
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right">Rp{{ number_format($item['total_price'], 0, ',', '.') }}</td>
                                    <td>
                                        {{-- Form action for remove now uses unique_id --}}
                                        <form action="{{ route('member.cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="unique_id" value="{{ $item['unique_id'] }}">
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

                    {{-- Removed Delivery Method, Pickup Address, Pickup Schedule, and Notes forms --}}
                    {{-- These are now handled in create.blade.php --}}

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
                            <button type="submit" class="btn btn-primary btn-block" id="checkoutBtn" {{ empty($cartItems) ? 'disabled' : '' }}>
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
        // Service prices from backend (now keyed by unique_id)
        const servicePrices = @json($servicePrices);

        // Function to update total selected price
        function updateSelectedPrice() {
            let total = 0;
            $('.item-checkbox:checked').each(function() {
                const uniqueId = $(this).val(); // Get unique_id
                if (servicePrices[uniqueId]) {
                    total += parseInt(servicePrices[uniqueId]); // Sum based on unique_id
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

        // Removed toggle pickup fields as they are no longer on this page

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