<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
        $akun = DB::table('akun_staf')
            ->where('email', $request->email)
            ->first();

        // 3. Cek akun ada dan password cocok
        if (! $akun || ! Hash::check($request->password, $akun->password_hash)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        // 4. Simpan sesi login
        Session::put('admin_id', $akun->id);
        Session::put('admin_nama', $akun->nama);

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
        Session::forget('admin_id');
        Session::forget('admin_nama');

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}