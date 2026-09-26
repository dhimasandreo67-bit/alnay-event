<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PertanyaanController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\PesertaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Khusus Admin (role:admin)
|--------------------------------------------------------------------------
| Semua route di sini WAJIB login sebagai admin.
| Middleware 'role' adalah middleware custom, contoh registrasinya
| ada di bagian bawah file ini (comment).
*/
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    // ===== Event =====
    Route::post('/event', [EventController::class, 'store']);
    Route::put('/event/{event}', [EventController::class, 'update']);
    Route::delete('/event/{event}', [EventController::class, 'destroy']);
    Route::patch('/event/{event}/voting', [EventController::class, 'toggleVoting']);
    Route::patch('/event/{event}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil']);

    // ===== Form Pendaftaran Dinamis (Pertanyaan) =====
    Route::post('/event/{event}/pertanyaan', [PertanyaanController::class, 'store']);
    Route::put('/pertanyaan/{pertanyaan}', [PertanyaanController::class, 'update']);
    Route::delete('/pertanyaan/{pertanyaan}', [PertanyaanController::class, 'destroy']);

    // ===== Pendaftaran Peserta (murni admin) =====
    Route::get('/event/{event}/pendaftar', [PendaftaranController::class, 'indexPendaftar']);
});

/*
|--------------------------------------------------------------------------
| Akses "admin ATAU owner" (pemilik data)
|--------------------------------------------------------------------------
| Middleware cuma memastikan user login. Pengecekan "apakah dia admin
| ATAU pemilik peserta ini" dilakukan di controller/Policy, karena
| middleware/role saja tidak cukup untuk cek kepemilikan data per-baris.
|
| Contoh di controller:
|   if ($request->user()->role !== 'admin'
|       && $request->user()->id !== $peserta->user_id) {
|       abort(403);
|   }
| Atau lebih rapi pakai Laravel Policy: $this->authorize('view', $peserta);
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/peserta/{peserta}', [PesertaController::class, 'show']);            // admin, owner
    Route::put('/peserta/{peserta}/status', [PesertaController::class, 'updateStatus']); // admin/owner
    Route::put('/peserta/{peserta}/hasil', [PesertaController::class, 'updateHasil']);   // admin/owner
    Route::get('/peserta/{peserta}/voter', [PesertaController::class, 'voterList']);     // admin/owner
});