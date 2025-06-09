<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
    Route::get('/services-admin', [ServicesController::class, 'indexAdmin'])->name('services.admin');
});

Route::middleware(['member'])->group(function () {
    Route::get('/dashboard-member', [DashboardController::class, 'indexMember'])->name('dashboard.member');
    Route::get('/services-member', [ServicesController::class, 'indexMember'])->name('services.member');
});

Route::get(
    'notifications/get',
    [NotificationsController::class, 'getNotificationsData']
)->name('notifications.get');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified   '])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
