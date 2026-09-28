<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\WebAuthController;
use App\Http\Controllers\PendaftaranController as PublicPendaftaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AktivasiController;
use App\Http\Controllers\Admin\PesertaController;

// Route Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// ===== Route Pendaftaran Publik =====
// HANYA 1 pasang route ini. Jangan tambah lagi route GET/POST ke
// '/daftar/{event}' di tempat lain, karena Laravel akan diam-diam
// menimpa registrasi sebelumnya kalau method+URI-nya sama persis.
Route::get('/daftar/{event}', [PublicPendaftaranController::class, 'showPublicForm'])
    ->name('public.registration');
Route::post('/daftar/{event}', [PublicPendaftaranController::class, 'storePublic'])
    ->name('public.registration.store');

// Route Auth Admin
Route::get('/admin/login', [WebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.store');

// Group Admin
Route::middleware('admin.session')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Event Management (admin.events.index, admin.events.create, admin.events.edit, dll.)
    Route::resource('events', EventController::class)->names('admin.events');

    // Toggle voting & tampilkan hasil (dipanggil via AJAX dari halaman voting settings)
    Route::patch('/events/{event}/voting', [EventController::class, 'toggleVoting'])
        ->name('admin.events.toggle-voting');
    Route::patch('/events/{event}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil'])
        ->name('admin.events.toggle-hasil');
    Route::delete('/admin/events/{event}/questions/clear', [PendaftaranController::class, 'clearQuestions'])
    ->name('admin.events.questions.clear');

    // Export peserta ke CSV
    Route::get('/admin/events/{event}/peserta/export', [PesertaController::class, 'export'])
    ->name('admin.peserta.export');

    // Form Builder
    Route::get('/form-builder/{event?}', [FormBuilderController::class, 'edit'])->name('admin.form-builder');
    Route::post('/form-builder/{event}/save', [FormBuilderController::class, 'save'])->name('admin.form-builder.save');

    // Registrasi
    Route::get('/registrations/{event?}', [NavigationController::class, 'page'])->defaults('page', 'registrations')->name('admin.registrations');
    Route::post('/registrations/{event}/manual', [PendaftaranController::class, 'storeManual'])->name('admin.registrations.manual.store');

    // Navigasi Fitur Lainnya
    Route::get('/activation/{event?}', [AktivasiController::class, 'index'])->name('admin.activation');
    Route::post('/activation/{peserta}/verifikasi', [AktivasiController::class, 'verifikasi'])->name('admin.activation.verifikasi');


    Route::get('/voting/settings/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voting-settings')->name('admin.voting.settings');
    Route::get('/voting/settings-page/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voting-settings')->name('admin.voting-settings');
    Route::get('/voters/{event?}', [NavigationController::class, 'page'])->defaults('page', 'voters')->name('admin.voters');
    Route::get('/leaderboard/{event?}', [NavigationController::class, 'page'])->defaults('page', 'leaderboard')->name('admin.leaderboard');

    Route::get('/peserta/jawaban/{jawaban}/file', [PendaftaranController::class, 'downloadJawabanFile'])->name('admin.jawaban.file');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');

    // Route dashboard/verifikasi utama
    Route::get('/', [PendaftaranController::class, 'index'])->name('admin.dashboard');

    // Route untuk melihat detail peserta
    Route::get('/peserta/{id}', [PendaftaranController::class, 'show'])->name('admin.peserta.show');

    // Route untuk update status (Revisi, Tolak, Terima)
    Route::post('/peserta/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('admin.peserta.update-status');

    // Route untuk menghapus peserta
    Route::delete('/peserta/{id}', [PendaftaranController::class, 'destroy'])->name('admin.peserta.destroy');
});

// Route API (auth, admin, pelanggan) -> dibungkus middleware 'api'
Route::middleware('api')->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});