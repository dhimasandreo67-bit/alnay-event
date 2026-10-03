<?php

namespace App\Helpers;

use App\Models\AkunStaf;
use App\Models\Event;
use Illuminate\Http\Request;

class EventHelper
{
    /**
     * Set event aktif: simpan ke session DAN database akun staf
     */
    public static function setActiveEvent(Request $request, $eventId): ?Event
    {
        $event = Event::find($eventId);
        
        if (!$event) {
            return null;
        }

        // Simpan ke session (untuk akses cepat)
        session(['active_event_id' => $event->id]);

        // Simpan ke database akun staf (untuk persistensi setelah logout)
        $adminId = $request->session()->get('admin_id');
        if ($adminId) {
            AkunStaf::where('id', $adminId)->update(['last_active_event_id' => $event->id]);
        }

        return $event;
    }

    /**
     * Ambil event aktif: session → database → fallback terbaru
     */
    public static function getActiveEvent(Request $request): ?Event
    {
        // 1. Coba dari session
        $eventId = session('active_event_id');
        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) return $event;
        }

        // 2. Coba dari database akun staf
        $adminId = $request->session()->get('admin_id');
        if ($adminId) {
            $admin = AkunStaf::find($adminId);
            if ($admin && $admin->last_active_event_id) {
                $event = Event::find($admin->last_active_event_id);
                if ($event) {
                    // Restore ke session
                    session(['active_event_id' => $event->id]);
                    return $event;
                }
            }
        }

        // 3. Fallback ke event terbaru
        return Event::query()->latest('id')->first();
    }
}