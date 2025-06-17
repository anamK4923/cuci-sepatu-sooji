@extends('layouts.app')

@section('subtitle', 'Services Admin')
@section('content_header_title', 'Services')
@section('content_header_subtitle', 'Soooji - Edit Services')

@section('content_body')

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Layanan: {{ $service->name }}
                </h3>
            </div>

            <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body">
                    <!-- Service Name -->
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-tag text-primary mr-1"></i>
                            Nama Layanan
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-concierge-bell"></i>
                                </span>
                            </div>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama layanan"
                                value="{{ old('name', $service->name) }}"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left text-info mr-1"></i>
                            Deskripsi
                        </label>
                        <textarea name="description"
                            id="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Masukkan deskripsi layanan (opsional)">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Jelaskan detail layanan yang ditawarkan
                        </small>
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label for="price">
                            <i class="fas fa-money-bill-wave text-success mr-1"></i>
                            Harga
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <strong>Rp</strong>
                                </span>
                            </div>
                            <input type="number"
                                name="price"
                                id="price"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="0"
                                value="{{ old('price', $service->price) }}"
                                min="0"
                                step="1000"
                                required>
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-calculator mr-1"></i>
                            Harga saat ini: <strong>Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                        </small>
                    </div>

                    <!-- Current Image Display -->
                    @if($service->image)
                    <div class="form-group">
                        <label>
                            <i class="fas fa-image text-info mr-1"></i>
                            Gambar Saat Ini
                        </label>
                        <div class="current-image-container">
                            <div class="card card-outline card-info">
                                <div class="card-body text-center p-3">
                                    <img src="{{ asset('storage/' . $service->image) }}"
                                        alt="Gambar Layanan"
                                        class="img-fluid img-thumbnail current-service-image"
                                        style="max-height: 200px;">
                                    <p class="text-muted mt-2 mb-0">
                                        <small>
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Gambar akan tetap sama jika tidak diubah
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="image">
                            <i class="fas fa-camera text-warning mr-1"></i>
                            {{ $service->image ? 'Ganti Gambar' : 'Upload Gambar' }}
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    name="image"
                                    id="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept="image/*">
                                <label class="custom-file-label" for="image">
                                    {{ $service->image ? 'Pilih gambar baru...' : 'Pilih gambar...' }}
                                </label>
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-file-image mr-1"></i>
                            Format yang didukung: JPG, PNG, GIF (Maks. 2MB)
                            @if($service->image)
                            <br><i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                            <strong>Opsional:</strong> Kosongkan jika tidak ingin mengubah gambar
                            @endif
                        </small>

                        <!-- New Image Preview -->
                        <div id="newImagePreview" class="mt-3" style="display: none;">
                            <div class="card card-outline card-warning">
                                <div class="card-header py-2">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-eye mr-1"></i>
                                        Preview Gambar Baru
                                    </h6>
                                </div>
                                <div class="card-body text-center p-3">
                                    <img id="newPreview" src="#" alt="Preview Baru" class="img-fluid img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Buttons -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('services.admin') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save mr-2"></i>
                                Update Layanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Service Info Card -->
        <div class="card card-info mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Layanan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-plus mr-1"></i> Dibuat:</strong>
                        <p class="text-muted">{{ $service->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-edit mr-1"></i> Terakhir Diupdate:</strong>
                        <p class="text-muted">{{ $service->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
    $(document).ready(function() {
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName || 'Pilih gambar baru...');

            // New image preview
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#newPreview').attr('src', e.target.result);
                    $('#newImagePreview').show();
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#newImagePreview').hide();
            }
        });

        // Format price input with current value display
        $('#price').on('input', function() {
            let value = $(this).val();
            if (value) {
                $(this).attr('title', 'Rp ' + parseInt(value).toLocaleString('id-ID'));
            }
        });

        // Auto-resize textarea
        $('#description').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Initialize textarea height
        $('#description').trigger('input');

        // Confirmation before leaving with unsaved changes
        let formChanged = false;
        $('input, textarea').on('change input', function() {
            formChanged = true;
        });

        $(`a[href="{{ route('services.admin') }}"]`).on('click', function(e) {
            if (formChanged) {
                if (!confirm('Ada perubahan yang belum disimpan. Yakin ingin keluar?')) {
                    e.preventDefault();
                }
            }
        });

        // Reset form changed flag on submit
        $('form').on('submit', function() {
            formChanged = false;
        });
    });
</script>
@stop

@section('css')
<style>
    .card-warning:not(.card-outline)>.card-header {
        background: linear-gradient(45deg, #ffc107, #e0a800);
        color: #212529;
    }

    .card-warning:not(.card-outline)>.card-header .card-title {
        color: #212529;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .custom-file-label::after {
        content: "Browse";
        background: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .current-service-image {
        border: 2px solid #17a2b8;
        background-color: #f8f9fa;
        transition: transform 0.2s;
    }

    .current-service-image:hover {
        transform: scale(1.05);
        cursor: pointer;
    }

    .img-thumbnail {
        border: 2px dashed #ffc107;
        background-color: #f8f9fa;
    }

    .btn-block {
        font-weight: 600;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .current-image-container .card {
        margin-bottom: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-8 {
            margin: 0 15px;
        }

        .card-footer .row .col-md-6 {
            margin-bottom: 10px;
        }

        .current-service-image {
            max-width: 100%;
        }
    }

    /* Animation for new image preview */
    #newImagePreview {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@stop