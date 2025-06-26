@extends('layouts.app')

@section('subtitle', 'Kelola Users')
@section('content_header_title', 'Kelola Users')
@section('content_header_subtitle', 'Manajemen Data Pengguna')

@section('content_body')

<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total_users'] }}</h3>
                        <p>Total Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['total_admins'] }}</h3>
                        <p>Admin Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['total_members'] }}</h3>
                        <p>Member Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['recent_registrations'] }}</h3>
                        <p>Registrasi 7 Hari</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Daftar Users
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Admin
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Pencarian</label>
                                <input type="text"
                                    name="search"
                                    id="search"
                                    class="form-control"
                                    placeholder="Nama, email, atau no HP..."
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
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
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-undo mr-1"></i>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Nama</th>
                                <th width="20%">Email</th>
                                <th width="15%">No HP</th>
                                <th width="10%">Role</th>
                                <th width="15%">Terdaftar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar mr-2">
                                            <i class="fas fa-user-circle fa-2x text-{{ $user->role == 'admin' ? 'primary' : 'success' }}"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->no_hp ?? '-' }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                    <span class="badge badge-primary">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Admin
                                    </span>
                                    @else
                                    <span class="badge badge-success">
                                        <i class="fas fa-user mr-1"></i>
                                        Member
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $user->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- View Button -->
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="btn btn-info btn-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($user->role == 'admin')
                                        <!-- Edit Button (Admin only) -->
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-warning btn-sm"
                                            title="Edit Admin">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete Button (Admin only, not self) -->
                                        @if($user->id != auth()->id())
                                        <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')"
                                            title="Hapus Admin">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        @else
                                        <!-- Member - View Only -->
                                        <span class="btn btn-secondary btn-sm disabled" title="Member - View Only">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data user</h5>
                                    <p class="text-muted">Belum ada user yang terdaftar atau sesuai filter.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Delete user function
    function deleteUser(userId, userName) {
        Swal.fire({
            title: 'Hapus User?',
            text: `Yakin ingin menghapus admin "${userName}"? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses permintaan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // Send delete request
                fetch(`/admin/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
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
                                location.reload();
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
                            text: 'Terjadi kesalahan saat menghapus user.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    }

    // Show success/error messages
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('
        success ') }}',
        icon: 'success',
        confirmButtonText: 'OK'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('
        error ') }}',
        icon: 'error',
        confirmButtonText: 'OK'
    });
    @endif
</script>
@stop

@section('css')
<style>
    .user-avatar {
        flex-shrink: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .small-box {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .small-box .icon {
        top: 10px;
        right: 15px;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }

    .table-responsive {
        border-radius: 8px;
    }

    .card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
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
</style>
@stop