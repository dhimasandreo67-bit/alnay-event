<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login'); // Sesuaikan dengan nama view login kamu
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

        // 🔑 RESTORE EVENT AKTIF TERAKHIR DARI DATABASE SAAT LOGIN
        if ($admin->last_active_event_id) {
            $event = Event::find($admin->last_active_event_id);
            if ($event) {
                $request->session()->put('active_event_id', $event->id);
            }
        }

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        // Hapus session, TAPI JANGAN hapus data di database (akun_staf.last_active_event_id tetap aman)
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email', 'active_event_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}