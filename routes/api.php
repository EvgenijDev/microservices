<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;



Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::prefix('v1')->group(function () {
    // Route::apiResource('products', ProductController::class);
    Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
});

Route::get('v1/test', function () {
    return 'Test route works!';
});
