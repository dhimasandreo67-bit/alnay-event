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
            return response()->json([
                'message' => 'Anda harus login sebagai pelanggan',
            ], 401);
        }

        $request->setUserResolver(fn () => Auth::guard('pelanggan')->user());

        return $next($request);
    }
}