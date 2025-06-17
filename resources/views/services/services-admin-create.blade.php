@extends('layouts.app')

@section('subtitle', 'Services Admin')
@section('content_header_title', 'Services')
@section('content_header_subtitle', 'Soooji - Create Services')

@section('content_body')

<div class="row justify-content-center w-full">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Layanan Baru
                </h3>
            </div>

            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                value="{{ old('name') }}"
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
                            placeholder="Masukkan deskripsi layanan (opsional)">{{ old('description') }}</textarea>
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
                                value="{{ old('price') }}"
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
                            Masukkan harga dalam Rupiah
                        </small>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group">
                        <label for="image">
                            <i class="fas fa-image text-warning mr-1"></i>
                            Gambar Layanan
                        </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    name="image"
                                    id="image"
                                    class="custom-file-input @error('image') is-invalid @enderror"
                                    accept="image/*">
                                <label class="custom-file-label" for="image">Pilih gambar...</label>
                                @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-file-image mr-1"></i>
                            Format yang didukung: JPG, PNG, GIF (Maks. 2MB)
                        </small>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <div class="text-center">
                                <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                <p class="text-muted mt-2">Preview Gambar</p>
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
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Layanan
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
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            // Image preview
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#imagePreview').show();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Format price input
        $('#price').on('input', function() {
            let value = $(this).val();
            if (value) {
                // Add thousand separators for better readability
                $(this).attr('title', 'Rp ' + parseInt(value).toLocaleString('id-ID'));
            }
        });

        // Auto-resize textarea
        $('#description').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
</script>
@stop

@section('css')
<style>
    .card-primary:not(.card-outline)>.card-header {
        background: linear-gradient(45deg, #007bff, #0056b3);
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
        background: #007bff;
        border-color: #007bff;
        color: white;
    }

    .img-thumbnail {
        border: 2px dashed #007bff;
        background-color: #f8f9fa;
    }

    .btn-block {
        font-weight: 600;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-8 {
            margin: 0 15px;
        }

        .card-footer .row .col-md-6 {
            margin-bottom: 10px;
        }
    }
</style>
@stop