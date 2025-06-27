@extends('layouts.app')

@section('subtitle', 'Tambah Admin')
@section('content_header_title', 'Tambah Admin')
@section('content_header_subtitle', 'Buat Akun Administrator Baru')

@section('content_body')

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-plus mr-2"></i>
                    Form Tambah Admin
                </h3>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Image -->
                <div class="form-group" style="margin: 5px 20px 0px 20px;">
                    <label for="image">
                        <i class="fas fa-image text-info mr-1"></i>
                        Foto Profil
                    </label>
                    <div class="text-center mb-3">
                        <img id="profile-preview"
                            src="{{ asset('images/users/default.png') }}"
                            alt="Profile Image"
                            class="img-circle elevation-2"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="custom-file">
                        <input type="file"
                            class="custom-file-input @error('image') is-invalid @enderror"
                            id="image"
                            name="image"
                            accept="image/*"
                            onchange="previewImage(this)">
                        <label class="custom-file-label" for="image">Pilih foto...</label>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">
                        Format: JPG, PNG, GIF. Maksimal 2MB.
                    </small>
                </div>

                <div class="card-body">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user text-primary mr-1"></i>
                            Nama Lengkap
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope text-info mr-1"></i>
                            Email Address
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="admin@example.com"
                                value="{{ old('email') }}"
                                required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="no_hp">
                            <i class="fas fa-phone text-success mr-1"></i>
                            No Telepon
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>
                            <input type="text"
                                name="no_hp"
                                id="no_hp"
                                class="form-control @error('no_hp') is-invalid @enderror"
                                placeholder="08123456789"
                                value="{{ old('no_hp') }}">
                            @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Opsional - Nomor telepon untuk kontak
                        </small>
                    </div>

                    <!-- Role (Hidden - Always Admin) -->
                    <input type="hidden" name="role" value="admin">

                    <div class="form-group">
                        <label>
                            <i class="fas fa-user-shield text-warning mr-1"></i>
                            Role
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" value="Administrator" readonly>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            User baru akan dibuat sebagai Administrator
                        </small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock text-danger mr-1"></i>
                            Password
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock text-danger mr-1"></i>
                            Konfirmasi Password
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password"
                                required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Admin
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
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak sama!');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Password minimal 8 karakter!');
            return false;
        }
    });


    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);

            // Update label nama file
            var fileName = input.files[0].name;
            input.nextElementSibling.innerHTML = fileName;
        }
    }

    // Custom file input label update (biar aman kalau lewat file manager)
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
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

    .btn-block {
        font-weight: 600;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .input-group-append .btn {
        border-left: none;
    }

    .input-group-append .btn:focus {
        box-shadow: none;
    }
</style>
@stop