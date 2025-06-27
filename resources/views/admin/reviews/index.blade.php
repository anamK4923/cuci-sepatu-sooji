@extends('adminlte::page')

@section('title', 'Kelola Rating & Ulasan')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Kelola Rating & Ulasan</h1>
    <div>
        <!-- <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
            <i class="fas fa-trash"></i> Hapus Terpilih
        </button> -->
        <!-- <button class="btn btn-success btn-sm" onclick="exportData()">
            <i class="fas fa-download"></i> Export
        </button> -->
    </div>
</div>
@stop

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total_reviews'] }}</h3>
                <p>Total Ulasan</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['average_rating'] }}<small>/5</small></h3>
                <p>Rating Rata-rata</p>
            </div>
            <div class="icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['recent_reviews'] }}</h3>
                <p>Ulasan Minggu Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-week"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['monthly_reviews'] }}</h3>
                <p>Ulasan Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Rating Distribution Chart -->
<!-- <div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Distribusi Rating</h3>
            </div>
            <div class="card-body">
                <canvas id="ratingChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Rating Breakdown</h3>
            </div>
            <div class="card-body">
                @for($i = 5; $i >= 1; $i--)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        @for($j = 1; $j <= $i; $j++)
                            <i class="fas fa-star text-warning"></i>
                            @endfor
                            @for($j = $i + 1; $j <= 5; $j++)
                                <i class="far fa-star text-muted"></i>
                                @endfor
                    </div>
                    <div class="progress flex-grow-1 mx-3" style="height: 20px;">
                        <div class="progress-bar bg-warning"
                            style="width: {{ $stats['total_reviews'] > 0 ? ($stats['rating_distribution'][$i] / $stats['total_reviews']) * 100 : 0 }}%">
                        </div>
                    </div>
                    <span class="badge badge-secondary">{{ $stats['rating_distribution'][$i] ?? 0 }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div> -->

<!-- Filters -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filter Ulasan</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control">
                        <option value="">Semua Rating</option>
                        @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Bintang
                        </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, email, layanan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Reviews Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Ulasan</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Rating</th>
                    <th>Ulasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>
                        <input type="checkbox" class="review-checkbox" value="{{ $review->id }}">
                    </td>
                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div>
                            <strong>{{ $review->user->name }}</strong><br>
                            <small class="text-muted">{{ $review->user->email }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $review->order->service->name }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                    @else
                                    <i class="far fa-star text-muted"></i>
                                    @endif
                                    @endfor
                            </div>
                            <span class="badge badge-secondary">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td>
                        <div style="max-width: 200px;">
                            {{ Str::limit($review->comment, 50) }}
                        </div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <!-- <button class="btn btn-danger btn-sm" onclick="deleteReview('{{ $review->id }}')">
                                <i class="fas fa-trash"></i>
                            </button> -->
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada ulasan ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div class="card-footer">
        {{ $reviews->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@stop

@section('css')
<style>
    .small-box .inner h3 {
        font-size: 2.2rem;
    }

    .progress {
        background-color: #f4f4f4;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Rating Distribution Chart
    const ratingCtx = document.getElementById('ratingChart').getContext('2d');
    const ratingChart = new Chart(ratingCtx, {
        type: 'doughnut',
        data: {
            labels: ['5 Bintang', '4 Bintang', '3 Bintang', '2 Bintang', '1 Bintang'],
            datasets: [{
                data: [{
                        {
                            $stats['rating_distribution'][5] ?? 0
                        }
                    },
                    {
                        {
                            $stats['rating_distribution'][4] ?? 0
                        }
                    },
                    {
                        {
                            $stats['rating_distribution'][3] ?? 0
                        }
                    },
                    {
                        {
                            $stats['rating_distribution'][2] ?? 0
                        }
                    },
                    {
                        {
                            $stats['rating_distribution'][1] ?? 0
                        }
                    }
                ],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Select All Checkbox
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.review-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkDeleteButton();
    });

    // Individual Checkboxes
    document.querySelectorAll('.review-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDeleteButton);
    });

    function toggleBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        if (checkedBoxes.length > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // Bulk Delete
    document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
        const reviewIds = Array.from(checkedBoxes).map(cb => cb.value);

        if (reviewIds.length === 0) {
            alert('Pilih ulasan yang akan dihapus');
            return;
        }

        if (confirm(`Yakin ingin menghapus ${reviewIds.length} ulasan?`)) {
            fetch('{{ route("admin.reviews.bulk-destroy") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        review_ids: reviewIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }
    });

    // Delete Single Review
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
                        location.reload();
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

    // Export Function
    function exportData() {
        // This would implement export functionality
        alert('Export functionality will be implemented');
    }
</script>
@stop