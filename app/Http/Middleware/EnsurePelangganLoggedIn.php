<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePelangganLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('pelanggan')->check()) {

            // Kalau request AJAX/JSON → balikin 401 JSON + redirect_url
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message'      => 'Sesi kamu habis. Silakan login ulang.',
                    'redirect_url' => route('auth.google', [
                        'redirect_to' => url()->current(),
                    ]),
                ], 401);
            }

            // Kalau request biasa (buka halaman) → redirect ke Google OAuth
            return redirect()->route('auth.google', [
                'redirect_to' => url()->current(),
            ]);
        }

        $request->setUserResolver(fn () => Auth::guard('pelanggan')->user());

        return $next($request);
    }
}