<?php

namespace App\Http\Middleware;

use App\Models\AkunStaf;
use App\Models\Event;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminEventAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $admin = AkunStaf::find($request->session()->get('admin_id'));

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        $eventParam = $request->route('event') ?? $request->route('eventId');

        if ($eventParam) {
            $eventId = $eventParam instanceof Event ? $eventParam->id : (int) $eventParam;

            if (! $admin->hasEventAccess($eventId)) {
                // REDIRECT KE PROFIL JIKA TIDAK PUNYA AKSES EVENT
                return redirect()->route('admin.profile')
                    ->with('error', 'Kamu tidak punya akses ke event tersebut.');
            }
        }

        return $next($request);
    }
}