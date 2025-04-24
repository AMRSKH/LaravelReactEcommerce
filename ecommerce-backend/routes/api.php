<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AdminController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Public Routes */

Route::get('/', function () {
    return response()->json(['status' => 'API is working!']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::middleware('auth:sanctum')->apiResource('products', ProductController::class)->except(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

/* Authenticated Routes */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('orders', OrderController::class);

    /* Admin Routes */
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::apiResource('products', AdminController::class)->except(['create', 'edit']);
        Route::get('orders', [AdminController::class, 'getOrders']);
        Route::put('orders/{order}/status', [AdminController::class, 'updateOrderStatus']);
        Route::get('users', [AdminController::class, 'getUsers']);
    });
});
