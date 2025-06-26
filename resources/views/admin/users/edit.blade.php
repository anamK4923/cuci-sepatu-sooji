@extends('layouts.app')

@section('subtitle', 'Edit Admin')
@section('content_header_title', 'Edit Admin')
@section('content_header_subtitle', 'Update Data Administrator')

@section('content_body')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Admin: {{ $user->name }}
                </h3>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

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
                                value="{{ old('name', $user->name) }}"
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
                                value="{{ old('email', $user->email) }}"
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
                                value="{{ old('no_hp', $user->no_hp) }}">
                            @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Opsional - Nomor telepon untuk kontak
                        </small>
                    </div>

                    <!-- Password (Optional) -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock text-danger mr-1"></i>
                            Password Baru
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
                                placeholder="Kosongkan jika tidak ingin mengubah">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.
                        </small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="password_confirmation">
                            <i class="fas fa-lock text-danger mr-1"></i>
                            Konfirmasi Password Baru
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
                                placeholder="Ulangi password baru">
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
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save mr-2"></i>
                                Update Admin
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- User Info Card -->
        <div class="card card-info mt-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi User
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-plus mr-1"></i> Terdaftar:</strong>
                        <p class="text-muted">{{ $user->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong><i class="fas fa-calendar-edit mr-1"></i> Terakhir Update:</strong>
                        <p class="text-muted">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
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

        // Only validate if password is being changed
        if (password || confirmPassword) {
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
        }
    });

    // Confirmation before leaving with unsaved changes
    let formChanged = false;
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('change', () => {
            formChanged = true;
        });
    });

    document.querySelector('a[href*="admin.users.index"]').addEventListener('click', function(e) {
        if (formChanged) {
            if (!confirm('Ada perubahan yang belum disimpan. Yakin ingin keluar?')) {
                e.preventDefault();
            }
        }
    });

    // Reset form changed flag on submit
    document.querySelector('form').addEventListener('submit', function() {
        formChanged = false;
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

    .btn-block {
        font-weight: 600;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }

    .input-group-append .btn {
        border-left: none;
    }

    .input-group-append .btn:focus {
        box-shadow: none;
    }
</style>
@stop