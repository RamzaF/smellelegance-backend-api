<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'SmellElegance API',
        'version' => '1.0.0',
        'laravel' => app()->version(),
        'message' => 'API is running. Please use /api/v1 endpoints.',
    ]);
});

// Fallback para requests de autenticación web
Route::get('/login', function () {
    return response()->json([
        'message' => 'This is an API-only application. Please use /api/v1/auth/login endpoint.',
    ], 401);
})->name('login');
