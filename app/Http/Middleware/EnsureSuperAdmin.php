<?php

namespace App\Http\Middleware;

use App\Models\AkunStaf;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Pasang middleware ini di route-route "Kelola Admin" (alias: 'super_admin').
     * Dipasang SETELAH 'admin.session', karena bergantung pada admin_id di session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = AkunStaf::find($request->session()->get('admin_id'));

        if (! $admin || ! $admin->isSuperAdmin()) {
            abort(403, 'Hanya Super Admin yang boleh membuka halaman ini.');
        }

        return $next($request);
    }
}
