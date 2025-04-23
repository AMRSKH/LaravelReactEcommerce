<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AdminController;

/* Public Routes */

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

/* Authenticated Routes */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('orders', OrderController::class);

    /* Admin Routes */
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('products', [AdminController::class, 'index']);
        Route::post('products', [AdminController::class, 'store']);
        Route::put('products/{product}', [AdminController::class, 'update']);
        Route::delete('products/{product}', [AdminController::class, 'destroy']);

        Route::get('orders', [AdminController::class, 'getOrders']);
        Route::put('orders/{order}/status', [AdminController::class, 'updateOrderStatus']);

        Route::get('users', [AdminController::class, 'getUsers']);
    });
});
