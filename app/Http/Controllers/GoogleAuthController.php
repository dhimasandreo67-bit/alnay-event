<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PenggunaEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // GET /auth/google
    public function redirect(Request $request)
    {
        // Simpan URL asal biar bisa balik setelah login
        if ($request->has('redirect_to')) {
            session(['redirect_after_login' => $request->get('redirect_to')]);
        }

        // Pakai stateless() biar gak butuh state di session
        // (workaround: cookie cross-site dari accounts.google.com sering diblokir)
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    // GET /auth/google/callback
    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $pengguna = PenggunaEvent::where('google_id', $googleUser->getId())->first();

        if ($pengguna) {
            $pengguna->update([
                'foto_url' => $googleUser->getAvatar(),
                'login_terakhir' => now(),
                // 'nama' sengaja tidak di-update — biar user bisa edit sendiri
            ]);
        } else {
            $pengguna = PenggunaEvent::create([
                'google_id' => $googleUser->getId(),
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'foto_url' => $googleUser->getAvatar(),
                'tanggal_daftar' => now(),
                'login_terakhir' => now(),
            ]);
        }

        Auth::guard('pelanggan')->login($pengguna);

        // Redirect ke URL asal (kalau ada), atau ke /beranda
        $redirectTo = session()->pull('redirect_after_login', '/beranda');

        return redirect($redirectTo);
    }

    // POST /auth/logout
    public function logout()
    {
        Auth::guard('pelanggan')->logout();

        return response()->json(['message' => 'Logout berhasil']);
    }

    // GET /auth/me
    public function me()
    {
        $pengguna = Auth::guard('pelanggan')->user();

        if (! $pengguna) {
            return response()->json(['message' => 'Belum login'], 401);
        }

        return response()->json($pengguna);
    }
}