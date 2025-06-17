@extends('layouts.app')

@section('subtitle', 'Services Admin')
@section('content_header_title', 'Services')
@section('content_header_subtitle', 'Soooji - Admin Services')

@section('content_body')

<!-- Statistics Cards -->
<!-- <div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $services->count() }}</h3>
                <p>Total Layanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-concierge-bell"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>Rp{{ number_format($services->sum('price'), 0, ',', '.') }}</h3>
                <p>Total Nilai Layanan</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $services->where('image', '!=', null)->count() }}</h3>
                <p>Dengan Gambar</p>
            </div>
            <div class="icon">
                <i class="fas fa-image"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>Rp{{ $services->count() > 0 ? number_format($services->avg('price'), 0, ',', '.') : '0' }}</h3>
                <p>Rata-rata Harga</p>
            </div>
            <div class="icon">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>
</div> -->

<!-- Success Alert -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>
    <strong>Berhasil!</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Main Card -->
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list mr-2"></i>
            Daftar Layanan
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('services.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Layanan Baru
                </a>
                <button class="btn btn-secondary ml-2" onclick="refreshTable()">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
            <div class="col-md-6 text-right">
                <div class="btn-group" role="group">
                    <!-- <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportData('excel')">
                        <i class="fas fa-file-excel mr-1"></i>
                        Excel
                    </button> -->
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf mr-1"></i>
                        PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="table-responsive">
            <table id="servicesTable" class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th width="50">#</th>
                        <th width="100" class="text-center">Gambar</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th width="120" class="text-right">Harga</th>
                        <th width="100" class="text-center">Status</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td class="text-center">
                            <span class="badge badge-secondary">{{ $loop->iteration }}</span>
                        </td>
                        <td class="text-center">
                            @if($service->image)
                            <div class="image-container">
                                <img src="{{ asset('storage/' . $service->image) }}"
                                    alt="{{ $service->name }}"
                                    class="service-thumbnail img-thumbnail"
                                    data-toggle="modal"
                                    data-target="#imageModal"
                                    data-image="{{ asset('storage/' . $service->image) }}"
                                    data-title="{{ $service->name }}">
                            </div>
                            @else
                            <div class="no-image-placeholder">
                                <i class="fas fa-image text-muted"></i>
                                <small class="text-muted d-block">No Image</small>
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $service->name }}</strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $service->created_at->format('d M Y') }}
                            </small>
                        </td>
                        <td>
                            @if($service->description)
                            <div class="description-text">
                                {{ Str::limit($service->description, 100) }}
                                @if(strlen($service->description) > 100)
                                <a href="#" class="text-primary" data-toggle="tooltip"
                                    title="{{ $service->description }}">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-muted font-italic">Tidak ada deskripsi</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <span class="badge badge-success badge-lg">
                                Rp{{ number_format($service->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($service->image && $service->description)
                            <span class="badge badge-success">
                                <i class="fas fa-check mr-1"></i>
                                Lengkap
                            </span>
                            @else
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Perlu Dilengkapi
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('services.edit', $service->id) }}"
                                    class="btn btn-warning btn-sm"
                                    data-toggle="tooltip" title="Edit Layanan">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                    class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#viewModal"
                                    data-service="{{ json_encode($service) }}"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button"
                                    class="btn btn-danger btn-sm delete-btn"
                                    data-id="{{ $service->id }}"
                                    data-name="{{ $service->name }}"
                                    data-toggle="tooltip" title="Hapus Layanan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Layanan</h5>
                                <p class="text-muted">Mulai dengan menambahkan layanan pertama Anda</p>
                                <a href="{{ route('services.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Layanan
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image mr-2"></i>
                    <span id="imageModalTitle">Gambar Layanan</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="/placeholder.svg" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">
                    <i class="fas fa-info-circle mr-2"></i>
                    Detail Layanan
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="serviceDetails">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="editServiceBtn" href="#" class="btn btn-warning">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Layanan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@stop

@section('js')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#servicesTable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            },
            columnDefs: [{
                    orderable: false,
                    targets: [1, 6]
                }, // Disable sorting for image and action columns
                {
                    searchable: false,
                    targets: [0, 1, 6]
                } // Disable search for #, image, and action columns
            ],
            order: [
                [2, 'asc']
            ], // Sort by name by default
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ]
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Image modal
        $('#imageModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var imageSrc = button.data('image');
            var title = button.data('title');

            $('#modalImage').attr('src', imageSrc);
            $('#imageModalTitle').text(title);
        });

        // View detail modal
        $('#viewModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var service = button.data('service');

            var detailsHtml = `
            <div class="row">
                <div class="col-md-4">
                    ${service.image ? 
                        `<img src="{{ asset('storage/') }}/${service.image}" class="img-fluid img-thumbnail">` : 
                        `<div class="no-image-placeholder-large text-center p-4">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada gambar</p>
                        </div>`
                    }
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nama Layanan:</strong></td>
                            <td>${service.name}</td>
                        </tr>
                        <tr>
                            <td><strong>Harga:</strong></td>
                            <td><span class="badge badge-success">Rp${parseInt(service.price).toLocaleString('id-ID')}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Deskripsi:</strong></td>
                            <td>${service.description || '<em class="text-muted">Tidak ada deskripsi</em>'}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>${new Date(service.created_at).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Update:</strong></td>
                            <td>${new Date(service.updated_at).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;

            $('#serviceDetails').html(detailsHtml);
            $('#editServiceBtn').attr('href', `/services/${service.id}/edit`);
        });

        // Delete confirmation
        $('.delete-btn').on('click', function() {
            var serviceId = $(this).data('id');
            var serviceName = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                html: `Apakah Anda yakin ingin menghapus layanan <strong>"${serviceName}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#deleteForm').attr('action', `/services/${serviceId}`).submit();
                }
            });
        });
    });

    // Utility functions
    function refreshTable() {
        $('#servicesTable').DataTable().ajax.reload();
        location.reload(); // Simple refresh for now
    }

    function exportData(format) {
        // Placeholder for export functionality
        Swal.fire({
            title: 'Export Data',
            text: `Fitur export ${format.toUpperCase()} akan segera tersedia!`,
            icon: 'info'
        });
    }
</script>
@stop

@section('css')
<style>
    /* Service thumbnail styling */
    .service-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .service-thumbnail:hover {
        transform: scale(1.1);
    }

    .no-image-placeholder {
        width: 60px;
        height: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 4px;
    }

    .no-image-placeholder-large {
        background-color: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
    }

    /* Description text styling */
    .description-text {
        max-width: 300px;
        word-wrap: break-word;
    }

    /* Empty state styling */
    .empty-state {
        padding: 40px 20px;
    }

    /* Badge styling */
    .badge-lg {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }

    /* Statistics cards hover effect */
    .small-box:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }

    /* Table responsive improvements */
    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
        }

        .btn-group .btn {
            margin-bottom: 2px;
        }

        .service-thumbnail {
            width: 40px;
            height: 40px;
        }
    }

    /* DataTable custom styling */
    .dataTables_wrapper .dataTables_length select {
        padding: 4px 8px;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 4px 8px;
        margin-left: 8px;
    }

    /* Card tools styling */
    .card-tools .btn-tool {
        color: rgba(255, 255, 255, .8);
    }

    .card-tools .btn-tool:hover {
        color: #fff;
    }
</style>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush