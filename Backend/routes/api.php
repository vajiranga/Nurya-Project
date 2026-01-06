<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WhatsAppOrderController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\AdminDeviceMiddleware;

// Public Routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/order/whatsapp', [WhatsAppOrderController::class, 'submit']);

// Voucher Routes
Route::get('/vouchers/public', [VoucherController::class, 'publicIndex']); // For frontend list
Route::post('/vouchers/verify', [VoucherController::class, 'verify']); // For checkout

// Temporary Public Admin Routes (For Dev) - Admin Access controls these in UI
Route::get('/vouchers', [VoucherController::class, 'index']); // For Admin List
Route::post('/vouchers', [VoucherController::class, 'store']);
Route::put('/vouchers/{id}', [VoucherController::class, 'update']);
Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']);

Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);

// Admin Routes (Auth protected if using auth)
Route::middleware(['auth:sanctum', AdminDeviceMiddleware::class])->prefix('admin')->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
});
