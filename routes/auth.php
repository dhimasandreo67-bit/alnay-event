<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Pelanggan (Google OAuth)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    // Public
    Route::get('/google', [GoogleAuthController::class, 'redirect'])
        ->name('auth.google');

    Route::get('/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('auth.google.callback');

    // Butuh login (pelanggan)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [GoogleAuthController::class, 'logout'])
            ->name('auth.logout');

        Route::get('/me', [GoogleAuthController::class, 'me'])
            ->name('auth.me');
    });
});

/*
|--------------------------------------------------------------------------
| Auth Staff / Admin
|--------------------------------------------------------------------------
*/
Route::prefix('auth/admin')->group(function () {

    // Public (justru ini endpoint buat login-nya)
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('admin.api.login');

    // Butuh login sebagai admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware(['auth:sanctum', 'role:admin'])
        ->name('admin.logout');
});