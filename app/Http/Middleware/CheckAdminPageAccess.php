<?php

namespace App\Http\Middleware;

use App\Models\AkunStaf;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPageAccess
{
    public function handle(Request $request, Closure $next, string $page): Response
    {
        $admin = AkunStaf::find($request->session()->get('admin_id'));

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        // Cek apakah dia punya akses ke halaman ini
        if (! $admin->hasPageAccess($page)) {
            if ($request->routeIs('admin.profile')) {
                return $next($request); 
            }
            
            // TAMBAHKAN VARIABEL $page DI PESAN ERROR AGAR KITA TAHU SLUG APA YANG GAGAL
            return redirect()->route('admin.profile')
                ->with('error', "Kamu tidak punya akses ke halaman: " . $page);
            
        }

        return $next($request);
    }
}