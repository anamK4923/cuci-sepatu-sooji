@extends('layouts.app')

@section('subtitle', 'Detail User')
@section('content_header_title', 'Detail User')
@section('content_header_subtitle', 'Informasi Lengkap Pengguna')

@section('content_body')

<div class="row">
    <div class="col-md-4">
        <!-- User Profile Card -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <div class="profile-user-img img-fluid img-circle mb-3">
                        <img src="{{ $user->image ? asset('storage/profile/' . $user->image) : asset('images/ame.jpg') }}"
                            alt="User Image"
                            class="img-circle"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-muted text-center">
                        @if($user->role == 'admin')
                        <span class="badge badge-primary badge-lg">
                            <i class="fas fa-user-shield mr-1"></i>
                            Administrator
                        </span>
                        @else
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-user mr-1"></i>
                            Member
                        </span>
                        @endif
                    </p>
                </div>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b><i class="fas fa-envelope mr-2 text-info"></i>Email</b>
                        <span class="float-right">{{ $user->email }}</span>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-phone mr-2 text-success"></i>No HP</b>
                        <span class="float-right">{{ $user->no_hp ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-calendar-plus mr-2 text-warning"></i>Terdaftar</b>
                        <span class="float-right">{{ $user->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-clock mr-2 text-danger"></i>Terakhir Update</b>
                        <span class="float-right">{{ $user->updated_at->format('d M Y') }}</span>
                    </li>
                </ul>

                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>
                    </div>
                    <div class="col-6">
                        @if($user->role == 'admin' && $user->id != auth()->id())
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-block">
                            <i class="fas fa-edit mr-1"></i>
                            Edit
                        </a>
                        @else
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-lock mr-1"></i>
                            {{ $user->role == 'member' ? 'View Only' : 'Current User' }}
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Account Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Akun
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-user"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Nama Lengkap</span>
                                <span class="info-box-number">{{ $user->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Email Address</span>
                                <span class="info-box-number" style="font-size: 14px;">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning">
                                <i class="fas fa-phone"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">No Telepon</span>
                                <span class="info-box-number">{{ $user->no_hp ?? 'Tidak ada' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-{{ $user->role == 'admin' ? 'primary' : 'success' }}">
                                <i class="fas fa-{{ $user->role == 'admin' ? 'user-shield' : 'user' }}"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Role</span>
                                <span class="info-box-number">{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2"></i>
                    Timeline Aktivitas
                </h3>
            </div>
            <div class="card-body">
                <div class="timeline timeline-inverse">
                    <!-- Account Created -->
                    <div class="time-label">
                        <span class="bg-success">
                            {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-user-plus bg-success"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="far fa-clock"></i> {{ $user->created_at->format('H:i') }}
                            </span>
                            <h3 class="timeline-header">
                                <strong>Akun Dibuat</strong>
                            </h3>
                            <div class="timeline-body">
                                User {{ $user->name }} terdaftar sebagai {{ $user->role }} pada sistem.
                            </div>
                        </div>
                    </div>

                    <!-- Last Update -->
                    @if($user->updated_at != $user->created_at)
                    <div class="time-label">
                        <span class="bg-warning">
                            {{ $user->updated_at->format('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <i class="fas fa-edit bg-warning"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <i class="far fa-clock"></i> {{ $user->updated_at->format('H:i') }}
                            </span>
                            <h3 class="timeline-header">
                                <strong>Profil Diupdate</strong>
                            </h3>
                            <div class="timeline-body">
                                Data profil user telah diperbarui.
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- End timeline -->
                    <div>
                        <i class="far fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Card (for members) -->
        <!-- @if($user->role == 'member')
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Kebijakan Member
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                    Data member hanya dapat <strong>dilihat</strong> oleh admin.
                    Admin tidak dapat mengedit atau menghapus data member untuk menjaga integritas data pelanggan.
                </div>

                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success mr-2"></i> Dapat melihat detail profil</li>
                    <li><i class="fas fa-times text-danger mr-2"></i> Tidak dapat mengedit data</li>
                    <li><i class="fas fa-times text-danger mr-2"></i> Tidak dapat menghapus akun</li>
                    <li><i class="fas fa-info text-info mr-2"></i> Member dapat mengubah profil sendiri</li>
                </ul>
            </div>
        </div>
        @endif -->
    </div>
</div>

@stop

@section('css')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 0.75rem;
    }

    .profile-user-img {
        border: none !important;
    }

    .info-box {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .info-box-icon {
        border-radius: 8px 0 0 8px;
    }

    .card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 10px;
    }

    .card-primary.card-outline {
        border-top: 3px solid #007bff;
    }

    .timeline-inverse .timeline-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .timeline-inverse .timeline-item .timeline-header {
        color: #495057;
    }

    .list-group-item {
        border: none;
        padding: 0.75rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@stop