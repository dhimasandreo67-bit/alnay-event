<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request, $eventId = null)
    {
        $event = null;
        $adminId = $request->session()->get('admin_id');

        // 1. Prioritas URL — /admin/7
        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                session(['active_event_id' => $event->id]);
                if ($adminId) {
                    AkunStaf::where('id', $adminId)->update(['last_active_event_id' => $event->id]);
                }
            }
        }

        // 2. Prioritas session
        if (!$event) {
            $activeEventId = session('active_event_id');
            if ($activeEventId) {
                $event = Event::find($activeEventId);
            }
        }

        // 3. Prioritas DB — last_active_event_id
        if (!$event && $adminId) {
            $admin = AkunStaf::find($adminId);
            if ($admin && $admin->last_active_event_id) {
                $event = Event::find($admin->last_active_event_id);
                if ($event) {
                    session(['active_event_id' => $event->id]);
                }
            }
        }

        // 4. Fallback — event terbaru
        $event ??= Event::query()->latest('id')->first();

        // Statistik
        $totalPendaftar = $event ? Peserta::where('event_id', $event->id)->count() : 0;
        $menungguReview = $event
            ? Peserta::where('event_id', $event->id)->where('status', 'menunggu_review')->count()
            : 0;
        $diterima       = $event ? Peserta::where('event_id', $event->id)->where('status', 'diterima')->count() : 0;
        $ditolak        = $event ? Peserta::where('event_id', $event->id)->where('status', 'ditolak')->count() : 0;
        $perluRevisi    = $event ? Peserta::where('event_id', $event->id)->where('status', 'revisi')->count() : 0;

        // Total suara
        $totalSuara = $event
            ? Voting::where('event_id', $event->id)->where('status_midtrans', 'settlement')->sum('jumlah_vote')
            : 0;

        // Daftar peserta
        $pesertas = $event
            ? Peserta::where('event_id', $event->id)
                ->with(['user', 'event', 'jawabans.pertanyaan'])
                ->latest('id')
                ->get()
            : collect();

        // Timestamp update terakhir
        $lastUpdate = Schema::hasColumn('peserta_event', 'updated_at')
            ? ($event ? Peserta::where('event_id', $event->id)->max('updated_at') : null)
            : null;

        // Daftar event untuk dropdown
        $events = Event::query()->latest('id')->get(['id', 'nama']);

        return view('admin.dashboard', [
            'event'          => $event,
            'events'         => $events,
            'pesertas'       => $pesertas,
            'totalPendaftar' => $totalPendaftar,
            'menungguReview' => $menungguReview,
            'diterima'       => $diterima,
            'ditolak'        => $ditolak,
            'perluRevisi'    => $perluRevisi,
            'totalSuara'     => $totalSuara,
            'lastUpdate'     => $lastUpdate,
        ]);
    }
}