<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input wajib ada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari akun staf berdasarkan email
        $akun = AkunStaf::where('email', $request->email)->first();

        // 3. Cek akun ada dan password cocok
        if (! $akun || ! Hash::check($request->password, $akun->password_hash)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        // 4. Login resmi lewat guard 'admin' (supaya Auth::guard('admin')->check() bisa mendeteksi)
        Auth::guard('admin')->login($akun);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login berhasil',
            'admin' => [
                'id' => $akun->id,
                'nama' => $akun->nama,
                'email' => $akun->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah keluar dari akun admin.');
    }
}