<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        $akun = Auth::guard('admin')->user();

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

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}