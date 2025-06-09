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
        'name' => 'Cuci Sepatu Reguler',
        'description' => 'Layanan cuci sepatu standar untuk sehari-hari.',
        'price' => 20000,
        'image' => '/images/reguler_clean.jpeg'
        ],
        [
        'name' => 'Cuci Sepatu Premium',
        'description' => 'Layanan premium dengan pembersih khusus dan perawatan ekstra.',
        'price' => 35000,
        'image' => '/images/premium_clean.jpeg'
        ],
        [
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
                    <!-- Ubah data-bs-toggle menjadi data-toggle dan data-bs-target menjadi data-target -->
                    <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#orderModal" data-service="{{ $service['name'] }}" data-price="{{ $service['price'] }}">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Modal Form Pemesanan -->
        <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="orderForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="orderModalLabel">Form Pemesanan</h5>
                            <!-- Ubah data-bs-dismiss menjadi data-dismiss -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="serviceName" class="form-label">Layanan</label>
                                <input type="text" class="form-control" id="serviceName" name="serviceName" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="servicePrice" class="form-label">Harga</label>
                                <input type="text" class="form-control" id="servicePrice" name="servicePrice" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Nama Pemesan</label>
                                <input type="text" class="form-control" id="customerName" name="customerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan (opsional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Kirim Pesanan</button>
                            <!-- Ubah data-bs-dismiss menjadi data-dismiss -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@push('js')
<script>
    // Gunakan jQuery yang sudah tersedia di AdminLTE
    $('#orderModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button yang memicu modal
        var service = button.data('service'); // Ambil data dari data-service
        var price = button.data('price'); // Ambil data dari data-price

        var modal = $(this);
        modal.find('#serviceName').val(service);
        modal.find('#servicePrice').val('Rp' + Number(price).toLocaleString('id-ID'));
    });

    // Handle form submission
    $('#orderForm').on('submit', function(e) {
        e.preventDefault();

        // Ambil data form
        var formData = {
            service: $('#serviceName').val(),
            price: $('#servicePrice').val(),
            customerName: $('#customerName').val(),
            notes: $('#notes').val()
        };

        // Di sini Anda bisa menambahkan logic untuk mengirim data ke server
        console.log('Data pesanan:', formData);

        // Tutup modal setelah submit
        $('#orderModal').modal('hide');

        // Reset form
        $('#orderForm')[0].reset();

        // Tampilkan pesan sukses (opsional)
        alert('Pesanan berhasil dikirim!');
    });
</script>
@endpush