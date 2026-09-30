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
use App\Http\Controllers\ProfilUserController;

/*
|--------------------------------------------------------------------------
| HALAMAN DEPAN (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Beranda = dashboard user (dari wijdan)
Route::get('/beranda', [\App\Http\Controllers\BerandaController::class, 'index'])
    ->name('dashboard-user');

// Placeholder route
// Profil User (wajib login)
Route::middleware('pelanggan')->group(function () {
    Route::get('/profil-user', [ProfilUserController::class, 'index'])
        ->name('profil-user');
    Route::put('/profil-user', [ProfilUserController::class, 'update'])
        ->name('profil-user.update');
    Route::post('/profil-user/foto', [ProfilUserController::class, 'uploadFoto'])
        ->name('profil-user.foto');
    Route::delete('/profil-user/foto', [ProfilUserController::class, 'hapusFoto'])
        ->name('profil-user.foto.hapus');
});

Route::get('/detail-event', function () {
    return '<div style="padding:40px;font-family:sans-serif"><h1>Detail Event</h1><p>Halaman ini sedang dikembangkan.</p><a href="/beranda">← Kembali ke Beranda</a></div>';
})->name('detail-event');

Route::get('/detail-event-selesai', function () {
    return '<div style="padding:40px;font-family:sans-serif"><h1>Detail Event Selesai</h1><p>Halaman ini sedang dikembangkan.</p><a href="/beranda">← Kembali ke Beranda</a></div>';
})->name('detail-event-selesai');

/*
|--------------------------------------------------------------------------
| AUTH — Google OAuth (Pelanggan)
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/me', [GoogleAuthController::class, 'me'])->name('auth.me');

Route::middleware('pelanggan')->group(function () {
    Route::get('/profil-user', [ProfilUserController::class, 'index'])
        ->name('profil-user');
    Route::put('/profil-user', [ProfilUserController::class, 'update'])
        ->name('profil-user.update');
    Route::post('/profil-user/foto', [ProfilUserController::class, 'uploadFoto'])
        ->name('profil-user.foto');
    Route::delete('/profil-user/foto', [ProfilUserController::class, 'hapusFoto'])
        ->name('profil-user.foto.hapus');

    // ← TAMBAH INI
    Route::post('/profil-user/logout', function () {
        \Auth::guard('pelanggan')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('dashboard-user');
    })->name('profil-user.logout');
});

/*
|--------------------------------------------------------------------------
| AUTH — Admin (API JSON)
|--------------------------------------------------------------------------
*/

Route::post('/auth/admin/login', [AuthController::class, 'login']);
Route::post('/auth/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| EVENT & PESERTA (PUBLIC — tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'index']);
Route::get('/event/{id}/peserta', [PendaftaranController::class, 'daftarPeserta']);

// Detail peserta (Blade)
Route::get('/peserta/{id}', [PendaftaranController::class, 'show']);

// API JSON detail peserta (checkout)
Route::get('/api/peserta/{peserta}', [VotingController::class, 'showPeserta']);

// Hasil voting & dukungan terkini
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);
Route::get('/event/{event}/peserta/{peserta}/dukungan-terkini', [VotingController::class, 'dukunganTerkini']);

/*
|--------------------------------------------------------------------------
| WEBHOOK MIDTRANS (PUBLIC — signature diverifikasi di controller)
|--------------------------------------------------------------------------
*/

Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook']);
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook']);

/*
|--------------------------------------------------------------------------
| PENDAFTARAN PUBLIK (form pendaftaran user)
|--------------------------------------------------------------------------
*/

Route::get('/daftar/{event}', [PendaftaranController::class, 'showPublicForm'])
    ->name('public.registration');
Route::post('/daftar/{event}', [PendaftaranController::class, 'storePublic'])
    ->name('public.registration.store');

/*
|--------------------------------------------------------------------------
| PELANGGAN (wajib login Google)
|--------------------------------------------------------------------------
*/

Route::middleware('pelanggan')->group(function () {
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);

    // Voting
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
    Route::get('/voting/{orderId}/status', [VotingController::class, 'cekStatus']);
    Route::get('/voting/{orderId}/instruksi', [VotingController::class, 'instruksiPembayaran']);

    // Checkout vote
    Route::get('/peserta/{id}/checkout', function ($id) {
        return view('vote-checkout', [
            'pesertaId' => $id,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransIsProduction' => config('services.midtrans.is_production'),
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN API (JSON — guard 'admin')
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {
    Route::post('/event', [EventController::class, 'store']);
    Route::put('/event/{id}', [EventController::class, 'update']);
    Route::delete('/event/{id}', [EventController::class, 'destroy']);
    Route::patch('/event/{id}/voting', [EventController::class, 'toggleVoting']);
    Route::patch('/event/{id}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil']);

    Route::post('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'store']);
    Route::put('/pertanyaan/{id}', [PertanyaanEventController::class, 'update']);
    Route::delete('/pertanyaan/{id}', [PertanyaanEventController::class, 'destroy']);

    Route::get('/peserta/{peserta}/voter', [VotingController::class, 'votersByPeserta']);
});

/*
|--------------------------------------------------------------------------
| ADMIN — Blade (Web Session)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [WebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.store');

Route::middleware('admin.session')->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Event Management
    Route::resource('events', AdminEventController::class)->names('admin.events');

    Route::patch('/events/{event}/voting', [AdminEventController::class, 'toggleVoting'])
        ->name('admin.events.toggle-voting');
    Route::patch('/events/{event}/tampilkan-hasil', [AdminEventController::class, 'toggleTampilkanHasil'])
        ->name('admin.events.toggle-hasil');
    Route::delete('/admin/events/{event}/questions/clear', [AdminPendaftaranController::class, 'clearQuestions'])
        ->name('admin.events.questions.clear');

    // Export peserta
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

    // Voting settings
    Route::get('/voting/settings/{event?}', [VotingController::class, 'edit'])->name('admin.voting.settings');
    Route::post('/voting/settings/{event}', [VotingController::class, 'update'])->name('admin.voting.settings.update');

    // Voter
    Route::get('/voters/{event?}', [VotingController::class, 'voters'])->name('admin.voters');

    // Leaderboard
    Route::get('/leaderboard/{event?}', [VotingController::class, 'leaderboard'])->name('admin.leaderboard');

    // File jawaban
    Route::get('/peserta/jawaban/{jawaban}/file', [AdminPendaftaranController::class, 'downloadJawabanFile'])->name('admin.jawaban.file');

    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');

    // Profile admin
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('admin.profile');
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Detail, status, hapus peserta
    Route::get('/peserta/{id}', [AdminPendaftaranController::class, 'show'])->name('admin.peserta.show');
    Route::post('/peserta/{id}/status', [AdminPendaftaranController::class, 'updateStatus'])->name('admin.peserta.update-status');
    Route::delete('/peserta/{id}', [AdminPendaftaranController::class, 'destroy'])->name('admin.peserta.destroy');
});

/*
|--------------------------------------------------------------------------
| ROUTE API HAN (auth, admin, user)
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});