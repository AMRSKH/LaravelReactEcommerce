<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => response()->json(['status' => 'API is working!']));

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('/products', ProductController::class)->only(['index', 'show']);
Route::apiResource('/categories', CategoryController::class)->only(['index', 'show']);


/*
|--------------------------------------------------------------------------
| Protected Routes (auth:sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Get current user
    Route::get('/user', fn(Request $request) => $request->user());

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Orders for user
    Route::apiResource('/orders', OrderController::class);

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (prefix: /admin, middleware: admin)
    |--------------------------------------------------------------------------
    */
    Route::prefix('/admin')->middleware('admin')->group(function () {
        Route::apiResource('/products', AdminController::class)->except(['create', 'edit']);
        Route::get('/orders', [AdminController::class, 'getOrders']);
        Route::put('/orders/{order}/status', [AdminController::class, 'updateOrderStatus']);
        Route::get('/users', [AdminController::class, 'getUsers']);
    });
});
