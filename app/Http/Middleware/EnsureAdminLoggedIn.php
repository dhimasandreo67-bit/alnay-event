<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Session::has('admin_id')) {
            return response()->json([
                'message' => 'Anda harus login sebagai admin',
            ], 401);
        }

        return $next($request);
    }
}