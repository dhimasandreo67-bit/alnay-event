<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC (tanpa login)
|--------------------------------------------------------------------------
*/

// Event
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{event}', [EventController::class, 'show']);

// Form pendaftaran dinamis (lihat pertanyaan)
Route::get('/event/{event}/pertanyaan', [PertanyaanController::class, 'index']);

// Daftar peserta yang sudah diterima
Route::get('/event/{event}/peserta', [PesertaController::class, 'indexDiterima']);

// Rekap hasil voting publik (pakai nama_tampilan, bukan nama asli)
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilEvent']);

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (public, tapi wajib verifikasi signature di controller!)
|--------------------------------------------------------------------------
| JANGAN kasih middleware 'auth' di sini karena yang manggil endpoint ini
| server Midtrans, bukan user login. Validasi keasliannya pakai
| signature_key sesuai dokumentasi Midtrans, dilakukan di dalam controller.
*/
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook'])
    ->name('midtrans.webhook.pendaftaran');

Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook'])
    ->name('midtrans.webhook.voting');

/*
|--------------------------------------------------------------------------
| PELANGGAN (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Pendaftaran peserta
    Route::post('/event/{event}/daftar', [PendaftaranController::class, 'store']);
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);

    // Pembayaran pendaftaran
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);

    // Voting
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
    Route::get('/voting/{transaksi}', [VotingController::class, 'status']);
});