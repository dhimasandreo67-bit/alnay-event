<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PertanyaanEventController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\AuthController;
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

// Auth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/auth/logout', [GoogleAuthController::class, 'logout']);
Route::get('/auth/me', [GoogleAuthController::class, 'me']);

Route::post('/auth/admin/login', [AuthController::class, 'login']);
Route::post('/auth/admin/logout', [AuthController::class, 'logout']);

// Event & Peserta - Boleh diakses Publik (Tanpa Login)
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'index']);
Route::get('/event/{id}/peserta', [PendaftaranController::class, 'daftarPeserta']);

// Halaman Detail Peserta Publik (Blade/View)
Route::get('/peserta/{id}', [PendaftaranController::class, 'show']);

// API JSON Detail Peserta Khusus Checkout (Dipisah rutenya agar tidak bentrok dengan PendaftaranController)
Route::get('/api/peserta/{peserta}', [VotingController::class, 'showPeserta']);

// Voting & Hasil Voting - Publik
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);
Route::get('/event/{event}/peserta/{peserta}/dukungan-terkini', [VotingController::class, 'dukunganTerkini']);
Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook']);
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook']);

// Event - Hanya Admin
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

// Pelanggan - Hanya yang Login Google
Route::middleware('pelanggan')->group(function () {
    Route::post('/event/{id}/daftar', [PendaftaranController::class, 'store']);
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);
    Route::get('/voting/{orderId}/status', [VotingController::class, 'cekStatus']);
    
    // API Proses Transaksi Midtrans
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
    
    // HALAMAN FORM CHECKOUT VOTE
    Route::get('/peserta/{id}/checkout', function ($id) {
        return view('vote-checkout', [
            'pesertaId' => $id,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransIsProduction' => config('services.midtrans.is_production'),
        ]);
    });

    // Halaman Instruksi Pembayaran
    // Halaman Instruksi Pembayaran (Custom Invoice Page)
    Route::get('/voting/{orderId}/instruksi', [VotingController::class, 'instruksiPembayaran']);
    
});