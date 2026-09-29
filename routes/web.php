<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PertanyaanEventController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AktivasiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\WebAuthController;
use Illuminate\Support\Facades\Route;

// Route Halaman Depan
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

// API JSON Detail Peserta Khusus Checkout
Route::get('/api/peserta/{peserta}', [VotingController::class, 'showPeserta']);

// Voting & Hasil Voting - Publik
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);
Route::get('/event/{event}/peserta/{peserta}/dukungan-terkini', [VotingController::class, 'dukunganTerkini']);
Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook']);
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook']);

// Event - Hanya Admin (API JSON, guard session akun_staf)
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

    // Halaman Form Checkout Vote
    Route::get('/peserta/{id}/checkout', function ($id) {
        return view('vote-checkout', [
            'pesertaId' => $id,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransIsProduction' => config('services.midtrans.is_production'),
        ]);
    });

    // Halaman Instruksi Pembayaran (Custom Invoice Page)
    Route::get('/voting/{orderId}/instruksi', [VotingController::class, 'instruksiPembayaran']);
});

// ===== Route Pendaftaran Publik (dari han) =====
// HANYA 1 pasang route ini. Jangan tambah route GET/POST ke '/daftar/{event}'
// di tempat lain, karena Laravel akan menimpa registrasi sebelumnya.
Route::get('/daftar/{event}', [PendaftaranController::class, 'showPublicForm'])
    ->name('public.registration');
Route::post('/daftar/{event}', [PendaftaranController::class, 'storePublic'])
    ->name('public.registration.store');

// ===== Route Auth Admin Blade (dari han) =====
Route::get('/admin/login', [WebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.store');

// ===== Group Admin Blade (dari han) =====
Route::middleware('admin.session')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Event Management
    Route::resource('events', AdminEventController::class)->names('admin.events');

    // Toggle voting & tampilkan hasil
    Route::patch('/events/{event}/voting', [AdminEventController::class, 'toggleVoting'])
        ->name('admin.events.toggle-voting');
    Route::patch('/events/{event}/tampilkan-hasil', [AdminEventController::class, 'toggleTampilkanHasil'])
        ->name('admin.events.toggle-hasil');
    Route::delete('/admin/events/{event}/questions/clear', [AdminPendaftaranController::class, 'clearQuestions'])
        ->name('admin.events.questions.clear');

    // Export peserta ke CSV
    Route::get('/admin/events/{event}/peserta/export', [PesertaController::class, 'export'])
        ->name('admin.peserta.export');

    Route::get('/api/event/{event}/pending-payments', [EventController::class, 'getPendingPayments'])
        ->name('admin.events.pending-payments');

    // Form Builder
    Route::get('/form-builder/{event?}', [FormBuilderController::class, 'edit'])->name('admin.form-builder');
    Route::post('/form-builder/{event}/save', [FormBuilderController::class, 'save'])->name('admin.form-builder.save');

    // Registrasi
    Route::get('/registrations/{event?}', [NavigationController::class, 'page'])->defaults('page', 'registrations')->name('admin.registrations');
    Route::post('/registrations/{event}/manual', [AdminPendaftaranController::class, 'storeManual'])->name('admin.registrations.manual.store');

    // Aktivasi
    Route::get('/activation/{event?}', [AktivasiController::class, 'index'])->name('admin.activation');
    Route::post('/activation/{peserta}/verifikasi', [AktivasiController::class, 'verifikasi'])->name('admin.activation.verifikasi');

    // Voting settings (dari han)
    Route::get('/voting/settings/{event?}', [VotingController::class, 'edit'])->name('admin.voting.settings');
    Route::post('/voting/settings/{event}', [VotingController::class, 'update'])->name('admin.voting.settings.update');

    // Voter (dari han)
    Route::get('/voters/{event?}', [VotingController::class, 'voters'])->name('admin.voters');

    // Leaderboard (dari han)
    Route::get('/leaderboard/{event?}', [VotingController::class, 'leaderboard'])->name('admin.leaderboard');

    Route::get('/peserta/jawaban/{jawaban}/file', [AdminPendaftaranController::class, 'downloadJawabanFile'])->name('admin.jawaban.file');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');

    // Dashboard/verifikasi utama (menimpa route '/' di atas, sama seperti di branch han)
    Route::get('/', [AdminPendaftaranController::class, 'index'])->name('admin.dashboard');

    // Detail, status, hapus peserta
    Route::get('/peserta/{id}', [AdminPendaftaranController::class, 'show'])->name('admin.peserta.show');
    Route::post('/peserta/{id}/status', [AdminPendaftaranController::class, 'updateStatus'])->name('admin.peserta.update-status');
    Route::delete('/peserta/{id}', [AdminPendaftaranController::class, 'destroy'])->name('admin.peserta.destroy');
});

// Route API han (auth, admin, user) -> dibungkus middleware 'api'
Route::middleware('api')->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});

// ===== Kembalikan route main yang tertimpa route han (URI persis sama) =====
// Grup 'api' han di atas mendaftarkan URI yang sama, dan yang terakhir menang.
// Blok ini HARUS tetap di paling bawah file, setelah grup 'api'.

// Auth pelanggan (Google, session): han memakai Auth\GoogleAuthController + sanctum
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/me', [GoogleAuthController::class, 'me'])->name('auth.me');

// Logout admin API: han menambah auth:sanctum + role:admin, main memakai guard session 'admin'
Route::post('/auth/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Hasil voting publik (han memakai VotingController@hasilEvent)
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);

// Admin (guard session akun_staf)
Route::middleware('admin')->group(function () {
    Route::post('/event', [EventController::class, 'store']);
    Route::get('/peserta/{peserta}/voter', [VotingController::class, 'voters']);
});

// Pelanggan (middleware main, bukan auth:sanctum)
Route::middleware('pelanggan')->group(function () {
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
});