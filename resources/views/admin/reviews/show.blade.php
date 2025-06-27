@extends('adminlte::page')

@section('title', 'Detail Ulasan')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Detail Ulasan</h1>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Ulasan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Tanggal Ulasan:</strong>
                        <p class="text-muted">{{ $review->created_at->format('d F Y, H:i') }}</p>

                        <strong>Rating:</strong>
                        <div class="mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=$review->rating)
                                <i class="fas fa-star text-warning"></i>
                                @else
                                <i class="far fa-star text-muted"></i>
                                @endif
                                @endfor
                                <span class="ml-2 badge badge-secondary">{{ $review->rating }}/5</span>
                                <span class="ml-2 text-muted">({{ $review->rating_text }})</span>
                        </div>

                        <strong>Layanan:</strong>
                        <p class="text-muted">
                            <span class="badge badge-info">{{ $review->order->service->name }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>ID Pesanan:</strong>
                        <p class="text-muted">
                            <a href="{{ route('admin.orders.show', $review->order) }}" class="text-primary">
                                #{{ $review->order->id }}
                            </a>
                        </p>

                        <strong>Status Pesanan:</strong>
                        <p class="text-muted">
                            <span class="badge badge-{{ $review->order->status === 'done' ? 'success' : 'warning' }}">
                                {{ $review->order->status_label }}
                            </span>
                        </p>

                        <strong>Total Pesanan:</strong>
                        <p class="text-muted">Rp {{ number_format($review->order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <hr>

                <strong>Komentar:</strong>
                <div class="mt-2 p-3 bg-light rounded">
                    <p class="mb-0">{{ $review->comment ?: 'Tidak ada komentar' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pelanggan</h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=007bff&color=fff&size=100"
                        alt="Avatar" class="img-circle" width="100">
                </div>

                <strong>Nama:</strong>
                <p class="text-muted">{{ $review->user->name }}</p>

                <strong>Email:</strong>
                <p class="text-muted">{{ $review->user->email }}</p>

                <strong>No. HP:</strong>
                <p class="text-muted">{{ $review->user->no_hp ?: 'Tidak tersedia' }}</p>

                <strong>Bergabung:</strong>
                <p class="text-muted">{{ $review->user->created_at->format('d F Y') }}</p>

                <hr>

                <strong>Statistik Pelanggan:</strong>
                <div class="mt-2">
                    @php
                    $userOrders = $review->user->orders()->count();
                    $userReviews = $review->user->reviews()->count();
                    $avgRating = $review->user->reviews()->avg('rating');
                    @endphp

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Pesanan:</span>
                        <span class="badge badge-primary">{{ $userOrders }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Ulasan:</span>
                        <span class="badge badge-info">{{ $userReviews }}</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Rata-rata Rating:</span>
                        <span class="badge badge-warning">{{ $avgRating ? round($avgRating, 1) : 'N/A' }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                <button class="btn btn-danger btn-block" onclick="deleteReview({{ $review->id }})">
                    <i class="fas fa-trash"></i> Hapus Ulasan
                </button>
            </div>
        </div> -->
    </div>
</div>
@stop

@section('css')
<style>
    .bg-light {
        background-color: #f8f9fa !important;
    }

    .img-circle {
        border-radius: 50%;
    }
</style>
@stop

@section('js')
<script>
    function deleteReview(reviewId) {
        if (confirm('Yakin ingin menghapus ulasan ini?')) {
            fetch(`/admin/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("admin.reviews.index") }}';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    }
</script>
@stop