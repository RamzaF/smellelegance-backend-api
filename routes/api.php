<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API v1 - Public routes
Route::prefix('v1')->group(function () {
    // Authentication (public)
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Catalog (public - read only)
    Route::get('/brands', [CatalogController::class, 'brands']);
    Route::get('/products', [CatalogController::class, 'products']);
    Route::get('/products/{id}', [CatalogController::class, 'show']);
});

// API v1 - Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Authentication (protected)
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Catalog Management (CRUD - Admin only)
    Route::post('/products', [CatalogController::class, 'store']);
    Route::put('/products/{id}', [CatalogController::class, 'update']);
    Route::delete('/products/{id}', [CatalogController::class, 'destroy']);
});