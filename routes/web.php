<?php
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Admin\AuthController;

// Toko (storefront) — berdiri sendiri
Route::get('/', function () {
    return view('beranda');
})->name('beranda');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/auth/logout', [GoogleAuthController::class, 'logout']);
Route::get('/auth/me', [GoogleAuthController::class, 'me']);

Route::post('/auth/admin/login', [AuthController::class, 'login']);
Route::post('/auth/admin/logout', [AuthController::class, 'logout']);

// Event - baca boleh publik
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);

// Event - hanya admin yang login
Route::middleware('admin')->group(function () {
    Route::post('/event', [EventController::class, 'store']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);
    Route::patch('/event/{id}/voting', [EventController::class, 'toggleVoting']);
    Route::patch('/event/{id}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil']);
});

// Dashboard Event (voting) — berdiri sendiri juga, tetap terpisah dari toko
Route::get('/dashboard-user', function () {
    return view('dashboard-user');
})->name('dashboard-user');

Route::get('/koleksi', function () {
    return view('penjualan.katalog-produk');
})->name('katalog-produk');

  Route::get('/detail-produk-beli', function () {
      return view('penjualan.detail-produk-beli');
  })->name('detail-produk-beli');

Route::get('/detail-produk-sewa', function () {
    return view('detail-produk-sewa');
})->name('detail-produk-sewa');

Route::get('/detail-event', function () {
    return view('detail-event');
})->name('detail-event');

Route::get('/detail-event-selesai', function () {
    return view('detail-event-selesai');
})->name('detail-event-selesai');

Route::get('/profil-user', function () {
    return view('profil-user');
})->name('profil-user');

Route::get('/riwayat-vote', function () {
    return view('riwayat-vote');
})->name('riwayat-vote');

Route::get('/detail-event-akan-datang', function () {
    return view('detail-event-akan-datang');
})->name('detail-event-akan-datang');

Route::put('/profil-user', [ProfilController::class, 'update'])->name('profil-update');
Route::post('/profil-user/foto', [ProfilController::class, 'updateFoto'])->name('profil-foto');
Route::delete('/profil-user/foto', [ProfilController::class, 'hapusFoto'])->name('profil-foto-hapus');