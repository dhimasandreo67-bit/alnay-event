<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\WebAuthController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\PendaftaranController as PublicPendaftaranController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AktivasiController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\ProfileController;

// ==========================================
// 1. ROUTE PUBLIK
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/daftar/{event}', [PublicPendaftaranController::class, 'showPublicForm'])
    ->name('public.registration');
Route::post('/daftar/{event}', [PublicPendaftaranController::class, 'storePublic'])
    ->name('public.registration.store');

// ==========================================
// 2. ROUTE AUTH ADMIN (tanpa middleware)
// ==========================================
Route::get('/admin/login', [WebAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [WebAuthController::class, 'login'])->name('admin.login.store');

Route::get('/pwa/admin-sw.js', function () {
    return response()->file(public_path('pwa/admin-sw.js'), [
        'Content-Type' => 'application/javascript',
        'Service-Worker-Allowed' => '/admin/',
    ]);
});

// ==========================================
// 3. GROUP ADMIN (Dilindungi Middleware)
// ==========================================
Route::middleware('admin.session')->prefix('admin')->group(function () {

    // A. Dashboard (HANYA SATU - ke DashboardController)
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // B. Profile & Logout (WAJIB di atas route dinamis)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('admin.logout.web');

    // C. Event Management (Resource - HANYA 1x)
    Route::resource('events', EventController::class)->names('admin.events');

    // D. Event Actions
    Route::patch('/events/{event}/voting', [EventController::class, 'toggleVoting'])
        ->name('admin.events.toggle-voting');
    Route::patch('/events/{event}/tampilkan-hasil', [EventController::class, 'toggleTampilkanHasil'])
        ->name('admin.events.toggle-hasil');
    Route::patch('/events/{event}/toggle-status', [EventController::class, 'toggleStatus'])
        ->name('admin.events.toggle-status');
    Route::delete('/events/{event}/questions/clear', [PendaftaranController::class, 'clearQuestions'])
        ->name('admin.events.questions.clear');
    Route::get('/events/{event}/peserta/export', [PesertaController::class, 'export'])
        ->name('admin.peserta.export');
    Route::get('/api/event/{event}/pending-payments', [EventController::class, 'getPendingPayments'])
        ->name('admin.events.pending-payments');
    Route::post('/events/set-active', [EventController::class, 'setActive'])
        ->name('admin.events.set-active');

    // E. Form Builder
    Route::get('/form-builder/{event?}', [FormBuilderController::class, 'edit'])
        ->name('admin.form-builder');
    Route::post('/form-builder/{event}/save', [FormBuilderController::class, 'save'])
        ->name('admin.form-builder.save');

    // F. Registrasi & Peserta
    Route::get('/registrations/{event?}', [NavigationController::class, 'page'])
        ->defaults('page', 'registrations')
        ->name('admin.registrations');
    Route::post('/registrations/{event}/manual', [PendaftaranController::class, 'storeManual'])
        ->name('admin.registrations.manual.store');

    Route::get('/peserta/{id}', [PendaftaranController::class, 'show'])
        ->name('admin.peserta.show');
    Route::post('/peserta/{id}/status', [PendaftaranController::class, 'updateStatus'])
        ->name('admin.peserta.update-status');
    Route::delete('/peserta/{id}', [PendaftaranController::class, 'destroy'])
        ->name('admin.peserta.destroy');
    Route::get('/peserta/jawaban/{jawaban}/file', [PendaftaranController::class, 'downloadJawabanFile'])
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
    Route::delete('/voters/{id}', [VotingController::class, 'destroy'])
        ->name('admin.voters.destroy');

    Route::get('/voters/{event?}', [VoterController::class, 'index'])
        ->name('admin.voters');
    Route::post('/voters/manual-vote', [VotingController::class, 'manualVote'])
        ->name('admin.voters.manual-vote');
    Route::get('/leaderboard/{event?}', [VotingController::class, 'leaderboard'])
        ->name('admin.leaderboard');
});

// ==========================================
// 4. ROUTE API
// ==========================================
Route::middleware('api')->group(function () {
    require __DIR__.'/auth.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/user.php';
});