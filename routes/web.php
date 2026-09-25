<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PertanyaanEventController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/beranda', function () {
    return view('beranda');
});

Route::get('/peserta/{id}/vote', function ($id) {
    return view('voting.index', [
        'pesertaId' => $id,
        'midtransClientKey' => config('services.midtrans.client_key'),
        'midtransIsProduction' => config('services.midtrans.is_production'),
    ]);
});

use App\Http\Controllers\Admin\AuthController;

Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/auth/logout', [GoogleAuthController::class, 'logout']);
Route::get('/auth/me', [GoogleAuthController::class, 'me']);

Route::post('/auth/admin/login', [AuthController::class, 'login']);
Route::post('/auth/admin/logout', [AuthController::class, 'logout']);

// Event - baca boleh publik
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'index']);
Route::get('/event/{id}/peserta', [PendaftaranController::class, 'daftarPeserta']);
Route::get('/peserta/{id}', [PendaftaranController::class, 'show']);

// Event - hanya admin yang login
Route::middleware('admin')->group(function () {
    Route::post('/event', [EventController::class, 'store']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);
    Route::patch('/event/{id}/voting', [EventController::class, 'toggleVoting']);
    Route::patch('/event/{id}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil']);

    Route::post('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'store']);
    Route::put('/pertanyaan/{id}', [PertanyaanEventController::class, 'update']);
    Route::delete('/pertanyaan/{id}', [PertanyaanEventController::class, 'destroy']);

    Route::get('/peserta/{peserta}/voter', [VotingController::class, 'voters']);
});

// Pelanggan - hanya yang login Google
Route::middleware('pelanggan')->group(function () {
    Route::post('/event/{id}/daftar', [PendaftaranController::class, 'store']);
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
});

// Webhook - publik
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook']);

// Voting - publik
// Voting - publik
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);
Route::get('/event/{event}/peserta/{peserta}/dukungan-terkini', [VotingController::class, 'dukunganTerkini']);
Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook']);