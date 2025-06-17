<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
    // Route::get('/services-admin', [ServiceController::class, 'indexAdmin'])->name('services.admin');
    Route::get('/services-admin', [ServiceController::class, 'index'])->name('services.admin');
    Route::get('/services-admin/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services-admin', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services-admin/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services-admin/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services-admin/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    // Route::resource('services-admin', ServiceController::class);


    // Orders Management
    Route::get('orders-admin', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders-admin/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders-admin/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::patch('orders-admin/{order}/update-payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment-status');
    Route::post('orders-admin/bulk-update', [AdminOrderController::class, 'bulkUpdate'])->name('admin.orders.bulk-update');
    Route::delete('orders-admin/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('orders-admin/export', [AdminOrderController::class, 'export'])->name('admin.orders.export');
});

Route::middleware(['member'])->group(function () {
    Route::get('/dashboard-member', [DashboardController::class, 'indexMember'])->name('dashboard.member');
    // Route::get('/services-member', [ServicesController::class, 'indexMember'])->name('services.member');
    // Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // Services Routes
    Route::get('/services-member', [ServiceController::class, 'indexMember'])->name('member.services.index');
    Route::get('/services-member/{service}', [ServiceController::class, 'show'])->name('member.services.show');

    // Orders Routes
    Route::get('/orders-member', [OrderController::class, 'index'])->name('member.orders.index');
    Route::get('/orders-member/create/{service}', [OrderController::class, 'create'])->name('member.orders.create');
    Route::post('/orders-member', [OrderController::class, 'store'])->name('member.orders.store');
    Route::get('/orders-member/status', [OrderController::class, 'status'])->name('member.orders.status');
    Route::get('/orders-member/{order}', [OrderController::class, 'show'])->name('member.orders.show');
    Route::patch('/orders-member/{order}/cancel', [OrderController::class, 'cancel'])->name('member.orders.cancel');
});


// API Routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->group(function () {

    // Customer API
    Route::prefix('customer')->name('api.customer.')->group(function () {
        Route::get('/orders/{order}/status', function (App\Models\Order $order) {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }
            return response()->json([
                'status' => $order->status,
                'status_label' => $order->status_label,
                'payment_status' => $order->payment_status,
                'payment_status_label' => $order->payment_status_label,
            ]);
        })->name('orders.status');
    });

    // Admin API
    Route::middleware(['admin'])->prefix('admin')->name('api.admin.')->group(function () {
        Route::get('/orders/stats', [AdminOrderController::class, 'getStats'])->name('orders.stats');
    });
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
