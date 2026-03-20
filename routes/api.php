<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SearchController;



Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::get('/profile', [AuthController::class, 'me'])->middleware('auth:sanctum');
});


Route::prefix('v1')->group(function () {
    Route::post('products/import', [ProductController::class, 'import'])->middleware('auth:sanctum');
    Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');
    Route::get('products/import/{import_id}/errors', [ProductController::class, 'getImportErrors'])->middleware('auth:sanctum');
});

Route::get('v1/test', function () {
    return 'Test route works!';
});

Route::get('v1/search', [SearchController::class, 'search']);
