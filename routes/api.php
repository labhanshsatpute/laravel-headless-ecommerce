<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(CategoryController::class)->group(function() {
    Route::get('category', 'getCategories');
});


Route::controller(ProductController::class)->group(function() {
    Route::get('product', 'getProducts');
});

Route::prefix('user')->group(function () {
    
    Route::controller(AuthController::class)->group(function() {
        Route::post('login', 'handleLogin');
        Route::post('register', 'handleRegister');
        Route::post('forgot-password', 'handleForgotPassword');
        Route::post('reset-password', 'handleResetPassword');
    });

});

