<?php
namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Event;

trait HasActiveEvent
{
    protected function getActiveEvent(?int $eventId = null): ?Event
    {
        // Prioritas: parameter > session > event terbaru
        if ($eventId) {
            session(['active_event_id' => $eventId]);
            return Event::find($eventId);
        }

        $activeId = session('active_event_id');
        $event = $activeId ? Event::find($activeId) : null;
        $event ??= Event::query()->latest('id')->first();

        if (!$event) {
            session()->forget('active_event_id');
        }

        return $event;
    }
}