@extends('layouts.app')

@section('subtitle', 'Riwayat Pemesanan')
@section('content_header_title', 'Riwayat Pemesanan')
@section('content_header_subtitle', 'Transaksi yang Telah Selesai')

@section('content_body')
<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_completed'] }}</h3>
                        <p>Pesanan Selesai</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_reviewed'] }}</h3>
                        <p>Review Diberikan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '0' }}</h3>
                        <p>Rating Rata-rata</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($stats['total_spent'], 0, ',', '.') }}</h3>
                        <p>Total Pengeluaran</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Riwayat Transaksi
                </h3>
            </div>

            <!-- Filters -->
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="service_id">Layanan</label>
                                <select name="service_id" id="service_id" class="form-control">
                                    <option value="">Semua Layanan</option>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_from">Dari Tanggal</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="date_to">Sampai Tanggal</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="has_review">Status Review</label>
                                <select name="has_review" id="has_review" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="yes" {{ request('has_review') == 'yes' ? 'selected' : '' }}>Sudah Review</option>
                                    <option value="no" {{ request('has_review') == 'no' ? 'selected' : '' }}>Belum Review</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-search mr-1"></i>
                                        Filter
                                    </button>
                                    <a href="{{ route('member.history.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-undo mr-1"></i>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Orders List -->
                @forelse($orders as $order)
                <div class="card mb-3 order-card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Order Info -->
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-1">
                                        <i class="fas fa-concierge-bell text-primary mr-2"></i>
                                        {{ $order->service->name }}
                                    </h5>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Selesai
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y') }}
                                        </p>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-truck mr-1"></i>
                                            <strong>Metode:</strong> {{ $order->delivery_method_label }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            <strong>Total:</strong> {{ $order->formatted_total_price }}
                                        </p>
                                        <p class="text-muted mb-1">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            <strong>Order ID:</strong> #{{ $order->id }}
                                        </p>
                                    </div>
                                </div>
                                @if($order->notes)
                                <p class="text-muted mb-2">
                                    <i class="fas fa-sticky-note mr-1"></i>
                                    <strong>Catatan:</strong> {{ $order->notes }}
                                </p>
                                @endif
                            </div>

                            <!-- Review Section -->
                            <div class="col-md-4">
                                <div class="review-section">
                                    @if($order->hasReview())
                                    <!-- Existing Review -->
                                    <div class="existing-review" id="review-display-{{ $order->id }}">
                                        <h6 class="text-success mb-2">
                                            <i class="fas fa-star mr-1"></i>
                                            Review Anda
                                        </h6>
                                        <div class="rating-display mb-2">
                                            {!! $order->review->rating_stars !!}
                                            <span class="ml-2 text-muted">({{ $order->review->rating_text }})</span>
                                        </div>
                                        @if($order->review->comment)
                                        <p class="review-comment">{{ $order->review->comment }}</p>
                                        @endif
                                        <small class="text-muted">{{ $order->review->created_at->format('d M Y, H:i') }}</small>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-outline-warning" onclick="editReview('{{ $order->id }}')">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit Review
                                            </button>
                                        </div>
                                        <!-- Hidden data for JavaScript -->
                                        <div class="d-none review-data"
                                            data-rating="{{ $order->review->rating }}"
                                            data-comment="{{ $order->review->comment }}">
                                        </div>
                                    </div>
                                    @else
                                    <!-- No Review Yet -->
                                    <div class="no-review" id="no-review-{{ $order->id }}">
                                        <h6 class="text-warning mb-2">
                                            <i class="fas fa-star-half-alt mr-1"></i>
                                            Belum Ada Review
                                        </h6>
                                        <p class="text-muted mb-2">Bagikan pengalaman Anda dengan layanan ini</p>
                                        <button class="btn btn-warning btn-sm" onclick="openReviewModal('{{ $order->id }}')">
                                            <i class="fas fa-star mr-1"></i>
                                            Beri Review
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-history fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">Belum Ada Riwayat Transaksi</h4>
                    <p class="text-muted">Anda belum memiliki transaksi yang selesai atau sesuai filter.</p>
                    <a href="{{ route('member.services.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Mulai Pesan Layanan
                    </a>
                </div>
                @endforelse

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark" id="reviewModalLabel">
                    <i class="fas fa-star mr-2"></i>
                    Berikan Review
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reviewForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="order_id" name="order_id">
                    <input type="hidden" id="is_edit" name="is_edit" value="false">

                    <!-- Rating -->
                    <div class="form-group">
                        <label for="rating">
                            <i class="fas fa-star text-warning mr-1"></i>
                            Rating
                        </label>
                        <div class="rating-input">
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="star5">
                                <label for="star5" title="Sangat Baik"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" title="Baik"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" title="Cukup"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" title="Buruk"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" title="Sangat Buruk"><i class="fas fa-star"></i></label>
                            </div>
                            <div class="rating-text mt-2">
                                <span id="rating-text" class="text-muted">Pilih rating</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comment -->
                    <div class="form-group">
                        <label for="comment">
                            <i class="fas fa-comment text-info mr-1"></i>
                            Komentar (Opsional)
                        </label>
                        <textarea name="comment"
                            id="comment"
                            class="form-control"
                            rows="4"
                            placeholder="Ceritakan pengalaman Anda dengan layanan ini..."></textarea>
                        <small class="form-text text-muted">Maksimal 1000 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i>
                        <span id="submit-text">Simpan Review</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Rating text mapping
    const ratingTexts = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };

    // Open review modal for new review
    function openReviewModal(orderId) {
        document.getElementById('order_id').value = orderId;
        document.getElementById('is_edit').value = 'false';
        document.getElementById('reviewModalLabel').innerHTML = '<i class="fas fa-star mr-2"></i>Berikan Review';
        document.getElementById('submit-text').textContent = 'Simpan Review';

        // Reset form
        document.getElementById('reviewForm').reset();
        document.getElementById('rating-text').textContent = 'Pilih rating';

        $('#reviewModal').modal('show');
    }

    // Edit existing review - FIXED VERSION
    function editReview(orderId) {
        // Get existing review data from hidden data attributes
        const reviewDisplay = document.getElementById(`review-display-${orderId}`);
        const reviewData = reviewDisplay.querySelector('.review-data');

        if (!reviewData) {
            console.error('Review data not found');
            return;
        }

        const rating = reviewData.getAttribute('data-rating');
        const comment = reviewData.getAttribute('data-comment') || '';

        // Set form data
        document.getElementById('order_id').value = orderId;
        document.getElementById('is_edit').value = 'true';
        document.getElementById('reviewModalLabel').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Review';
        document.getElementById('submit-text').textContent = 'Update Review';

        // Set rating
        const ratingInput = document.querySelector(`input[name="rating"][value="${rating}"]`);
        if (ratingInput) {
            ratingInput.checked = true;
            document.getElementById('rating-text').textContent = ratingTexts[rating];
        }

        // Set comment
        document.getElementById('comment').value = comment;

        $('#reviewModal').modal('show');
    }

    // Handle rating selection
    document.querySelectorAll('input[name="rating"]').forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            document.getElementById('rating-text').textContent = ratingTexts[rating];
        });
    });

    // Handle form submission - IMPROVED VERSION
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const orderId = formData.get('order_id');
        const isEdit = formData.get('is_edit') === 'true';
        const rating = formData.get('rating');

        if (!rating) {
            Swal.fire({
                title: 'Error!',
                text: 'Silakan pilih rating terlebih dahulu.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: isEdit ? 'Mengupdate Review...' : 'Menyimpan Review...',
            text: 'Sedang memproses permintaan',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        // Prepare request data
        const requestData = {
            rating: rating,
            comment: formData.get('comment') || '',
            _token: '{{ csrf_token() }}'
        };

        // Determine URL and method
        const url = `/member/history/${orderId}/review`;
        const method = isEdit ? 'PUT' : 'POST';

        // Add method override for PUT requests
        if (isEdit) {
            requestData._method = 'PUT';
        }

        // Send request
        fetch(url, {
                method: 'POST', // Always use POST, Laravel will handle method override
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#reviewModal').modal('hide');
                        updateReviewDisplay(orderId, data.review, isEdit);
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memproses review.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
    });

    // Update review display after successful submission - IMPROVED VERSION
    function updateReviewDisplay(orderId, reviewData, isEdit) {
        const reviewHtml = `
            <h6 class="text-success mb-2">
                <i class="fas fa-star mr-1"></i>
                Review Anda
            </h6>
            <div class="rating-display mb-2">
                ${reviewData.rating_stars}
                <span class="ml-2 text-muted">(${reviewData.rating_text})</span>
            </div>
            ${reviewData.comment ? `<p class="review-comment">${reviewData.comment}</p>` : ''}
            <small class="text-muted">${reviewData.created_at}</small>
            <div class="mt-2">
                <button class="btn btn-sm btn-outline-warning" onclick="editReview('${orderId}')">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Review
                </button>
            </div>
            <!-- Hidden data for JavaScript -->
            <div class="d-none review-data" 
                 data-rating="${reviewData.rating}" 
                 data-comment="${reviewData.comment || ''}">
            </div>
        `;

        if (isEdit) {
            // Update existing review display
            const reviewDisplayElement = document.getElementById(`review-display-${orderId}`);
            if (reviewDisplayElement) {
                reviewDisplayElement.innerHTML = reviewHtml;
            }
        } else {
            // Replace no-review with review display
            const noReviewElement = document.getElementById(`no-review-${orderId}`);
            if (noReviewElement) {
                noReviewElement.innerHTML = reviewHtml;
                noReviewElement.id = `review-display-${orderId}`;
                noReviewElement.className = 'existing-review';
            }
        }
    }
</script>
@stop

@section('css')
<style>
    .order-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        transition: transform 0.2s;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .small-box {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .small-box .icon {
        top: 10px;
        right: 15px;
    }

    .review-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
    }

    .rating-display .fas.fa-star {
        color: #ffc107;
    }

    .rating-display .far.fa-star {
        color: #dee2e6;
    }

    .review-comment {
        background: white;
        padding: 0.5rem;
        border-radius: 4px;
        border-left: 3px solid #17a2b8;
        margin: 0.5rem 0;
        font-style: italic;
    }

    /* Star Rating Input */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 0.2rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s;
    }

    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #ffc107;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        border-radius: 10px 10px 0 0 !important;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header.bg-warning {
        border-radius: 15px 15px 0 0;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
</style>
@stop