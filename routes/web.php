<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\WebAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\NavigationController;

// Route bawaan Laravel (halaman welcome), biarkan pakai middleware 'web' default
Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [WebAuthController::class, 'showLogin'])
    ->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])
    ->name('admin.login.store');

Route::middleware('admin.session')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/form-builder/{event?}', [FormBuilderController::class, 'edit'])->name('admin.form-builder');
    Route::post('/form-builder/{event}/save', [FormBuilderController::class, 'save'])->name('admin.form-builder.save');
    Route::get('/events/create', [NavigationController::class, 'page'])->defaults('page', 'event-create')->name('admin.events.create');
    Route::get('/registrations/{event?}', [NavigationController::class, 'page'])->defaults('page', 'registrations')->name('admin.registrations');
    Route::get('/activation/{event?}', [NavigationController::class, 'page'])->defaults('page', 'activation')->name('admin.activation');
    Route::get('/voting/settings/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voting-settings')->name('admin.voting.settings');
    Route::get('/voting/settings-page/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voting-settings')->name('admin.voting-settings');
    Route::get('/voters/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voters')->name('admin.voters');
    Route::get('/leaderboard/{event?}', [NavigationController::class, 'page'])->defaults('page', 'leaderboard')->name('admin.leaderboard');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');
});

// Route API (auth, admin, pelanggan) -> dibungkus middleware 'api'
// supaya TIDAK kena session + CSRF protection dari middleware 'web'.
// Kalau tidak dibungkus begini, semua POST/PUT/DELETE dari mobile app,
// Postman, dan webhook Midtrans bakal ditolak error 419 CSRF mismatch.
Route::middleware('api')->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});