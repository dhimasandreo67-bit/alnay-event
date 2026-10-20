<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WebAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // Jika sudah login, jangan lempar ke dashboard (bisa 403). Arahkan ke profil.
        if ($request->session()->has('admin_id')) {
            return redirect()->route('admin.profile');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $admin = AkunStaf::where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password_hash)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // Regenerasi session untuk keamanan
        $request->session()->regenerate();

        // Simpan data admin ke session
        $request->session()->put('admin_id', $admin->id);
        $request->session()->put('admin_name', $admin->nama);
        $request->session()->put('admin_email', $admin->email);

        // =====================================================================
        // 🔑 PERBAIKAN 1: SET EVENT AKTIF DENGAN VALIDASI HAK AKSES
        // =====================================================================
        $activeEvent = null;
        
        // Cek apakah event terakhir dia masih valid dan dia masih punya akses
        if ($admin->last_active_event_id && $admin->hasEventAccess($admin->last_active_event_id)) {
            $activeEvent = Event::find($admin->last_active_event_id);
        } 
        // Jika tidak, ambil event PERTAMA yang dia diizinkan
        elseif (!$admin->isSuperAdmin()) {
            $activeEvent = $admin->allowedEvents()->first();
        }
        // Jika Super Admin dan tidak ada last event, ambil event pertama di DB
        else {
            $activeEvent = Event::first();
        }

        if ($activeEvent) {
            $request->session()->put('active_event_id', $activeEvent->id);
            $admin->update(['last_active_event_id' => $activeEvent->id]); // Sinkronkan DB
        }

        // =====================================================================
        // 🔑 PERBAIKAN 2: REDIRECT KE HALAMAN YANG DIIZINKAN (BUKAN ASAL DASHBOARD)
        // =====================================================================
        if ($admin->isSuperAdmin()) {
            return redirect()->route('admin.dashboard', $activeEvent ? $activeEvent->id : null);
        }

        // Jika Admin Biasa, cari halaman pertama yang dia punya akses
        $allowedPages = $admin->allowed_pages ?? [];
        
        if (empty($allowedPages)) {
            // Jika tidak punya akses halaman sama sekali, lempar ke profil
            return redirect()->route('admin.profile');
        }

        $firstPage = collect($allowedPages)->first();

        // Arahkan ke rute yang sesuai dengan halaman pertama
        switch ($firstPage) {
            case 'voters':
                return redirect()->route('admin.voters', $activeEvent ? $activeEvent->id : null);
            case 'leaderboard':
                return redirect()->route('admin.leaderboard', $activeEvent ? $activeEvent->id : null);
            case 'activation':
                return redirect()->route('admin.activation', $activeEvent ? $activeEvent->id : null);
            case 'form-builder':
                return redirect()->route('admin.form-builder', $activeEvent ? $activeEvent->id : null);
            case 'voting-settings':
                return redirect()->route('admin.voting.settings', $activeEvent ? $activeEvent->id : null);
            case 'peserta-tampil':
                return redirect()->route('admin.peserta-tampil', $activeEvent ? $activeEvent->id : null);
            case 'events-index':
                return redirect()->route('admin.events.index');
            case 'dashboard':
                return redirect()->route('admin.dashboard', $activeEvent ? $activeEvent->id : null);
            default:
                return redirect()->route('admin.profile');
        }
    }

    public function logout(Request $request)
    {
        // Hapus session
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email', 'active_event_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}