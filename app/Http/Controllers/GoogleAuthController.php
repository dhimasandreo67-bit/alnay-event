<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // GET /auth/google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // GET /auth/google/callback
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $pengguna = DB::table('pengguna_event')
            ->where('google_id', $googleUser->getId())
            ->first();

        if ($pengguna) {
            // Sudah pernah login, update data terbaru
            DB::table('pengguna_event')
                ->where('id', $pengguna->id)
                ->update([
                    'nama' => $googleUser->getName(),
                    'foto_url' => $googleUser->getAvatar(),
                    'login_terakhir' => now(),
                ]);
            $penggunaId = $pengguna->id;
        } else {
            // Login pertama kali, buat baris baru
            $penggunaId = DB::table('pengguna_event')->insertGetId([
                'google_id' => $googleUser->getId(),
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'foto_url' => $googleUser->getAvatar(),
                'tanggal_daftar' => now(),
                'login_terakhir' => now(),
            ]);
        }

        Session::put('pengguna_id', $penggunaId);

        $pengguna = DB::table('pengguna_event')->find($penggunaId);

        return response()->json([
            'message' => 'Login berhasil',
            'pengguna' => $pengguna,
        ]);
    }

    // POST /auth/logout
    public function logout()
    {
        Session::forget('pengguna_id');

        return response()->json(['message' => 'Logout berhasil']);
    }

    // GET /auth/me
    public function me()
    {
        if (! Session::has('pengguna_id')) {
            return response()->json(['message' => 'Belum login'], 401);
        }

        $pengguna = DB::table('pengguna_event')->find(Session::get('pengguna_id'));

        return response()->json($pengguna);
    }
}