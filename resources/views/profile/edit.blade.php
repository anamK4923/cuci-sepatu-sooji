@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-user-circle mr-2"></i>Profile Saya</h1>
    <!-- <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.member') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Profile</li>
        </ol>
    </nav> -->
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Profile Information Card -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Informasi Profile
                </h3>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fas fa-check"></i>
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Profile Image -->
                    <div class="form-group text-center">
                        <label>Foto Profile</label>
                        <div class="mb-3">
                            <img id="profile-preview"
                                src="{{ $user->image ? asset('storage/profile/' . $user->image) : asset('images/ame.jpg') }}"
                                alt="Profile Image"
                                class="img-circle elevation-2"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="custom-file" style="max-width: 300px; margin: 0 auto;">
                            <input type="file" class="custom-file-input @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*" onchange="previewImage(this)">
                            <label class="custom-file-label" for="image">Pilih foto...</label>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Email -->
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror"
                                    id="no_hp"
                                    name="no_hp"
                                    value="{{ old('no_hp', $user->no_hp) }}"
                                    placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Role (Read Only) -->
                            <div class="form-group">
                                <label for="role">Role</label>
                                <input type="text"
                                    class="form-control"
                                    value="{{ ucfirst($user->role) }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route(Auth::user()->role === 'admin' ? 'dashboard.admin' : 'dashboard.member') }}"
                        class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Change Password Card -->
        <!-- <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key mr-1"></i>
                    Ubah Password
                </h3>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    <div class="form-group">
                        <label for="current_password">Password Lama <span class="text-danger">*</span></label>
                        <input type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            id="current_password"
                            name="current_password">
                        @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru <span class="text-danger">*</span></label>
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key mr-1"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div> -->

        <!-- Account Information Card -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Informasi Akun
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Bergabung:</strong></td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terakhir Update:</strong></td>
                        <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($user->role === 'member')
                    <tr>
                        <td><strong>Total Pesanan:</strong></td>
                        <td>{{ $user->orders->count() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Review:</strong></td>
                        <td>{{ $user->reviews->count() }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .profile-preview {
        transition: all 0.3s ease;
    }

    .profile-preview:hover {
        transform: scale(1.05);
    }

    .card-outline {
        border-top: 3px solid;
    }
</style>
@stop

@section('js')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#profile-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);

            // Update label
            var fileName = input.files[0].name;
            $(input).next('.custom-file-label').html(fileName);
        }
    }

    // Custom file input label update
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Form validation
    $('form').on('submit', function(e) {
        var passwordFields = ['current_password', 'password', 'password_confirmation'];
        var hasPasswordField = false;

        passwordFields.forEach(function(field) {
            if ($('#' + field).val()) {
                hasPasswordField = true;
            }
        });

        if (hasPasswordField) {
            var allPasswordFieldsFilled = passwordFields.every(function(field) {
                return $('#' + field).val();
            });

            if (!allPasswordFieldsFilled) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jika ingin mengubah password, semua field password harus diisi!'
                });
                return false;
            }

            if ($('#password').val() !== $('#password_confirmation').val()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Konfirmasi password tidak cocok!'
                });
                return false;
            }
        }
    });
</script>
@stop