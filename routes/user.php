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

// Rekap hasil voting publik
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilEvent']);

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (public)
|--------------------------------------------------------------------------
*/

Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook'])
    ->name('midtrans.webhook.pendaftaran');

Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook'])
    ->name('midtrans.webhook.voting');

/*
|--------------------------------------------------------------------------
| PELANGGAN — Route ini SUDAH ADA di routes/web.php
|--------------------------------------------------------------------------
|
| JANGAN didaftarkan ulang di sini, biar gak kena middleware 'api' + 'auth:sanctum'.
| Route yang benar ada di routes/web.php dalam group middleware('pelanggan'):
|   - /pendaftaran/saya
|   - /peserta/{peserta}/bayar
|   - /peserta/{peserta}/vote
|   - /voting/{orderId}/status
|
*/