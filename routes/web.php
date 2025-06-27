<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexAdmin'])->name('dashboard.admin');
    Route::get('/services-admin', [ServiceController::class, 'index'])->name('services.admin');
    Route::get('/services-admin/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services-admin', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services-admin/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services-admin/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services-admin/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // Orders Management
    Route::get('orders-admin', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders-admin/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders-admin/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::patch('orders-admin/{order}/update-payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment-status');
    Route::post('orders-admin/bulk-update', [AdminOrderController::class, 'bulkUpdate'])->name('admin.orders.bulk-update');
    Route::delete('orders-admin/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('orders-admin/export', [AdminOrderController::class, 'export'])->name('admin.orders.export');

    // New route for export data
    Route::get('api/admin/orders/export-data', [AdminOrderController::class, 'getExportData'])->name('admin.orders.export-data');

    // Reports Management
    Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/pdf', [ReportController::class, 'exportPDF'])->name('pdf');
        Route::get('/export', [ReportController::class, 'exportReport'])->name('export');
        Route::get('/analytics', [ReportController::class, 'analytics'])->name('analytics');
        Route::get('/revenue', [ReportController::class, 'revenueReport'])->name('revenue');
        Route::get('/customers', [ReportController::class, 'customerReport'])->name('customers');
        Route::get('/services', [ReportController::class, 'serviceReport'])->name('services');
    });

    // Admin User Management
    Route::prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::get('/export', [AdminUserController::class, 'export'])->name('export');
    });

    // Admin Review Management
    Route::prefix('admin/reviews')->name('admin.reviews.')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [AdminReviewController::class, 'show'])->name('show');
        Route::delete('/{review}', [AdminReviewController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [AdminReviewController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/export', [AdminReviewController::class, 'export'])->name('export');
        Route::get('/chart-data', [AdminReviewController::class, 'getChartData'])->name('chart-data');
    });
});

Route::middleware(['member'])->group(function () {
    Route::get('/dashboard-member', [DashboardController::class, 'indexMember'])->name('dashboard.member');

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

    // Member History & Reviews
    Route::prefix('member/history')->name('member.history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::post('/{order}/review', [HistoryController::class, 'storeReview'])->name('store-review');
        Route::put('/{order}/review', [HistoryController::class, 'updateReview'])->name('update-review');
    });

    // Payment Routes - pastikan middleware member ada
    Route::middleware(['member'])->prefix('payment')->name('member.payment.')->group(function () {
        Route::post('/create/{order}', [PaymentController::class, 'createPayment'])->name('create');
        Route::get('/status/{order}', [PaymentController::class, 'checkStatus'])->name('status');
        Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('unfinish');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
    });
});

// Midtrans Webhook (no auth required)
Route::post('/payment/notification', [PaymentController::class, 'notification'])->name('payment.notification');

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
        Route::get('/reviews/chart-data', [AdminReviewController::class, 'getChartData'])->name('reviews.chart-data');
    });
});

Route::get(
    'notifications/get',
    [NotificationsController::class, 'getNotificationsData']
)->name('notifications.get');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
