<?php

namespace App\Http\Middleware;

use App\Models\AkunStaf;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Cek role user yang login.
     *
     * PENTING: karena admin & pelanggan disimpan di TABEL BERBEDA
     * (AkunStaf vs PenggunaEvent), role di sini ditentukan dari JENIS MODEL
     * yang berhasil di-resolve oleh token Sanctum, bukan dari kolom 'role'.
     *
     * Contoh pakai di route: ->middleware(['auth:sanctum', 'role:admin'])
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Belum login.');
        }

        $actualRole = $user instanceof AkunStaf ? 'admin' : 'pelanggan';

        if ($actualRole !== $role) {
            abort(403, 'Tidak punya akses.');
        }

        return $next($request);
    }
}