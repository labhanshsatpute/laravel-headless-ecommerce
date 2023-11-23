<?php

use App\Http\Controllers\Admin\AccessController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['guest:admin'])->group(function () {

    Route::get('login', [AuthController::class, 'viewLogin'])
        ->name('admin.view.login');
    Route::post('login', [AuthController::class, 'handleLogin'])
        ->name('admin.handle.login');

    Route::get('/forgot-password', [AuthController::class, 'viewForgotPassword'])
        ->name('admin.view.forgot.password');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])
        ->name('admin.handle.forgot.password');

    Route::get('/reset-password/{token}', [AuthController::class, 'viewResetPassword'])
        ->name('admin.view.reset.password');
    Route::post('/reset-password/{token}', [AuthController::class, 'handleResetPassword'])
        ->name('admin.handle.reset.password');
});

/*
|--------------------------------------------------------------------------
| Authorized Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->group(function () {

    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('admin.view.login');
    })->name('admin.handle.logout');

    Route::get('dashboard', [DashboardController::class, 'viewDashboard'])
        ->name('admin.view.dashboard');

    Route::prefix('access')->controller(AccessController::class)->group(function () {
        Route::get('/', 'viewAccessList')->name('admin.view.access.list');
        Route::get('/create', 'viewAccessCreate')->name('admin.view.access.create');
        Route::get('/update/{id}', 'viewAccessUpdate')->name('admin.view.access.update');
        Route::post('/create', 'handleAccessCreate')->name('admin.handle.access.create');
        Route::post('/update/{id}', 'handleAccessUpdate')->name('admin.handle.access.update');
        Route::put('/status', 'handleToggleAccessStatus')->name('admin.handle.access.status');
        Route::get('/delete/{id}', 'handleAccessDelete')->name('admin.handle.access.delete');
    });

    Route::prefix('coupon')->controller(CouponController::class)->group(function () {
        Route::get('/', 'viewCouponList')->name('admin.view.coupon.list');
        Route::get('/create', 'viewCouponCreate')->name('admin.view.coupon.create');
        Route::get('/update/{id}', 'viewCouponUpdate')->name('admin.view.coupon.update');
        Route::post('/create', 'handleCouponCreate')->name('admin.handle.coupon.create');
        Route::post('/update/{id}', 'handleCouponUpdate')->name('admin.handle.coupon.update');
        Route::put('/status', 'handleToggleCouponStatus')->name('admin.handle.coupon.status');
        Route::get('/delete/{id}', 'handleCouponDelete')->name('admin.handle.coupon.delete');
    });

});

