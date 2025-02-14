<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Models\User;

// Rota para testar a autenticação
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout',  [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
});

// Rota para criar um pedido sem autenticação
Route::post('orders', [OrderController::class, 'store']);
Route::get('products', [ProductController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']);
Route::post('/customers/find', [CustomerController::class, 'findByEmail']);
