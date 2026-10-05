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
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (public)
|--------------------------------------------------------------------------
*/

Route::post('/peserta/midtrans/webhook', function (\Illuminate\Http\Request $request) {
    $orderId = (string) $request->input('order_id', '');

    \Log::info('Midtrans webhook universal (legacy URL)', ['order_id' => $orderId]);

    if (str_starts_with($orderId, 'VOTING-')) {
        return app(\App\Http\Controllers\VotingController::class)->webhook($request);
    }

    if (str_starts_with($orderId, 'PENDAFTARAN-')) {
        return app(\App\Http\Controllers\PembayaranController::class)->webhook($request);
    }

    return response()->json(['status_code' => '200', 'status_message' => 'OK']);
});

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