<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PertanyaanEventController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\Admin\AktivasiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\WebAuthController;
use App\Http\Controllers\ProfilUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. ROUTE PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('welcome'));

Route::get('/login', fn() => view('auth.login'))->name('login');

Route::get('/beranda', [BerandaController::class, 'index'])
    ->name('dashboard-user');

/*
|--------------------------------------------------------------------------
| DETAIL EVENT
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\DetailEventController;

Route::get('/detail-event/{event}', [DetailEventController::class, 'berlangsung'])
    ->name('detail-event.berlangsung');

Route::get('/detail-event-akan-datang/{event}', [DetailEventController::class, 'akanDatang'])
    ->name('detail-event.akan-datang');

Route::get('/detail-event-selesai/{event}', [DetailEventController::class, 'selesai'])
    ->name('detail-event.selesai');

/*
|--------------------------------------------------------------------------
| 2. AUTH — GOOGLE OAUTH (Pelanggan)
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/me', [GoogleAuthController::class, 'me'])->name('auth.me');

/*
|--------------------------------------------------------------------------
| 3. AUTH — ADMIN API (JSON)
|--------------------------------------------------------------------------
*/

Route::post('/auth/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
Route::post('/auth/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| 4. EVENT & PESERTA (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);
Route::get('/event/{id}/pertanyaan', [PertanyaanEventController::class, 'index']);
Route::get('/event/{id}/peserta', [PendaftaranController::class, 'daftarPeserta']);
Route::get('/peserta/{id}', [PendaftaranController::class, 'show']);
Route::get('/api/peserta/{peserta}', [VotingController::class, 'showPeserta']);
Route::get('/event/{event}/hasil-voting', [VotingController::class, 'hasilVoting']);
Route::get('/event/{event}/kandidat', [PesertaController::class, 'kandidat'])->name('event.peserta');
Route::get('/event/{event}/peserta/{peserta}/dukungan-terkini', [VotingController::class, 'dukunganTerkini']);

/*
|--------------------------------------------------------------------------
| 5. WEBHOOK MIDTRANS (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::post('/voting/midtrans/webhook', [VotingController::class, 'webhook']);
Route::post('/peserta/midtrans/webhook', [PembayaranController::class, 'webhook']);

/*
|--------------------------------------------------------------------------
| 6. PENDAFTARAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/daftar/{event}', [PendaftaranController::class, 'showPublicForm'])
    ->name('public.registration');
Route::post('/daftar/{event}', [PendaftaranController::class, 'storePublic'])
    ->name('public.registration.store');

/*
|--------------------------------------------------------------------------
| 7. PELANGGAN (wajib login Google)
|--------------------------------------------------------------------------
*/

Route::middleware('pelanggan')->group(function () {

    // Profil user
    Route::get('/profil-user', [ProfilUserController::class, 'index'])->name('profil-user');
    Route::put('/profil-user', [ProfilUserController::class, 'update'])->name('profil-user.update');
    Route::post('/profil-user/foto', [ProfilUserController::class, 'uploadFoto'])->name('profil-user.foto');
    Route::delete('/profil-user/foto', [ProfilUserController::class, 'hapusFoto'])->name('profil-user.foto.hapus');

    // Logout khusus pelanggan
    Route::post('/profil-user/logout', function () {
        \Auth::guard('pelanggan')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('dashboard-user');
    })->name('profil-user.logout');

    // Pendaftaran
    Route::get('/pendaftaran/saya', [PendaftaranController::class, 'milikSaya']);

    // Pembayaran pendaftaran
    Route::post('/peserta/{peserta}/bayar', [PembayaranController::class, 'bayar']);

    // Halaman pembayaran pendaftaran
    Route::get('/peserta/{peserta}/pembayaran', [PembayaranController::class, 'show'])
        ->name('peserta.pembayaran');

    // Request charge ke Midtrans
    Route::post('/peserta/{peserta}/charge', [PembayaranController::class, 'charge'])
        ->name('peserta.pembayaran.charge');

    // Halaman instruksi (QR/VA)
    Route::get('/peserta/{peserta}/pembayaran/instruksi', [PembayaranController::class, 'instruksi'])
        ->name('peserta.pembayaran.instruksi');

    // Cek status (polling)
    Route::get('/peserta/{peserta}/pembayaran/status', [PembayaranController::class, 'statusPembayaran'])
        ->name('peserta.pembayaran.status');

    // Voting
    Route::post('/peserta/{peserta}/vote', [VotingController::class, 'vote']);
    Route::get('/voting/{orderId}/status', [VotingController::class, 'cekStatus']);
    Route::get('/voting/{orderId}/instruksi', [VotingController::class, 'instruksiPembayaran']);

    // Checkout vote
        // Checkout vote
    Route::get('/peserta/{id}/checkout', function ($id) {
        return view('vote-checkout', [
            'pesertaId' => $id,
            'midtransClientKey' => config('services.midtrans.client_key'),
            'midtransIsProduction' => config('services.midtrans.is_production'),
        ]);
    })->name('vote.checkout');
});

/*
|--------------------------------------------------------------------------
| 8. ADMIN API (guard 'admin')
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
| 9. ADMIN — BLADE (Web Session)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [WebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.store');

Route::middleware('admin.session')->prefix('admin')->group(function () {

    // A. Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // B. Profile & Logout (di atas route dinamis)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');

    // C. Event Management
    Route::resource('events', AdminEventController::class)->names('admin.events');

    // D. Event Actions
    Route::patch('/events/{event}/voting', [AdminEventController::class, 'toggleVoting'])
        ->name('admin.events.toggle-voting');
    Route::patch('/events/{event}/tampilkan-hasil', [AdminEventController::class, 'toggleTampilkanHasil'])
        ->name('admin.events.toggle-hasil');
    Route::patch('/events/{event}/toggle-status', [AdminEventController::class, 'toggleStatus'])
        ->name('admin.events.toggle-status');
    Route::delete('/events/{event}/questions/clear', [AdminPendaftaranController::class, 'clearQuestions'])
        ->name('admin.events.questions.clear');
    Route::get('/events/{event}/peserta/export', [PesertaController::class, 'export'])
        ->name('admin.peserta.export');
    Route::get('/api/event/{event}/pending-payments', [EventController::class, 'getPendingPayments'])
        ->name('admin.events.pending-payments');

    // E. Form Builder
    Route::get('/form-builder/{event?}', [FormBuilderController::class, 'edit'])
        ->name('admin.form-builder');
    Route::post('/form-builder/{event}/save', [FormBuilderController::class, 'save'])
        ->name('admin.form-builder.save');

    // F. Registrasi & Peserta
    Route::get('/registrations/{event?}', [NavigationController::class, 'page'])
        ->defaults('page', 'registrations')
        ->name('admin.registrations');
    Route::post('/registrations/{event}/manual', [AdminPendaftaranController::class, 'storeManual'])
        ->name('admin.registrations.manual.store');

    Route::get('/preview/{event}', [PendaftaranController::class, 'previewForm'])
        ->name('admin.preview-form');

    Route::get('/peserta/{id}', [AdminPendaftaranController::class, 'show'])
        ->name('admin.peserta.show');
    Route::post('/peserta/{id}/status', [AdminPendaftaranController::class, 'updateStatus'])
        ->name('admin.peserta.update-status');
    Route::delete('/peserta/{id}', [AdminPendaftaranController::class, 'destroy'])
        ->name('admin.peserta.destroy');
    Route::get('/peserta/jawaban/{jawaban}/file', [AdminPendaftaranController::class, 'downloadJawabanFile'])
        ->name('admin.jawaban.file');

    // G. Aktivasi & Voting
    Route::get('/activation/{event?}', [AktivasiController::class, 'index'])
        ->name('admin.activation');
    Route::post('/activation/{peserta}/verifikasi', [AktivasiController::class, 'verifikasi'])
        ->name('admin.activation.verifikasi');

    Route::get('/voting/settings/{event?}', [VotingController::class, 'edit'])
        ->name('admin.voting.settings');
    Route::post('/voting/settings/{event}', [VotingController::class, 'update'])
        ->name('admin.voting.settings.update');

    Route::get('/voters/{event?}', [VotingController::class, 'voters'])
        ->name('admin.voters');
    Route::get('/leaderboard/{event?}', [VotingController::class, 'leaderboard'])
        ->name('admin.leaderboard');
});

/*
|--------------------------------------------------------------------------
| 10. SERVICE WORKER PWA
|--------------------------------------------------------------------------
*/

Route::get('/pwa/admin-sw.js', function () {
    return response()->file(public_path('pwa/admin-sw.js'), [
        'Content-Type' => 'application/javascript',
        'Service-Worker-Allowed' => '/admin/',
    ]);
});

/*
|--------------------------------------------------------------------------
| 11. ROUTE API (admin, user)
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});

/*
|--------------------------------------------------------------------------
| 12. DEBUG (hapus kalau production)
|--------------------------------------------------------------------------
*/

Route::get('/test-session', function () {
    session(['test_key' => 'test_value_' . time()]);
    return [
        'session_id' => session()->getId(),
        'driver'     => config('session.driver'),
        'test_key'   => session('test_key'),
        'user_id'    => optional(auth('pelanggan')->user())->id,
        'user_nama'  => optional(auth('pelanggan')->user())->nama,
    ];
});