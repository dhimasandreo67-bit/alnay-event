<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Staff / Admin (API only)
|--------------------------------------------------------------------------
|
| Route /auth/google dan /auth/google/callback SUDAH ADA di routes/web.php
| Jangan didaftarkan ulang di sini — biar gak kena middleware 'api'
| (middleware 'api' gak punya session, bikin Socialite InvalidStateException).
|
*/

Route::prefix('auth/admin')->group(function () {

    // Public — login admin via API
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('admin.api.login');

    // Butuh login sebagai admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->middleware(['auth:sanctum', 'role:admin'])
        ->name('admin.logout');
});