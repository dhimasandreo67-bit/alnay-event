<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\AkunStaf;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, $eventId = null)
    {
        $event = null;
        $adminId = $request->session()->get('admin_id');

        // 1. Cek dari Parameter URL (misal: /admin/7)
        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                session(['active_event_id' => $event->id]);
                if ($adminId) {
                    AkunStaf::where('id', $adminId)->update(['last_active_event_id' => $event->id]);
                }
            }
        }

        // 2. Jika belum ada, Cek dari Session
        if (!$event) {
            $activeEventId = session('active_event_id');
            if ($activeEventId) {
                $event = Event::find($activeEventId);
            }
        }

        // 3. 🔑 Jika masih belum ada, Cek dari Database (Last Active Event)
        if (!$event && $adminId) {
            $admin = AkunStaf::find($adminId);
            if ($admin && $admin->last_active_event_id) {
                $event = Event::find($admin->last_active_event_id);
                if ($event) {
                    // Restore ke session agar halaman lain juga tau
                    session(['active_event_id' => $event->id]);
                }
            }
        }

        // 4. Fallback: Ambil event terbaru jika semuanya gagal
        $event ??= Event::query()->latest('id')->first();

        // 5. Hitung statistik HANYA untuk event ini
        $totalPendaftar = $event ? Peserta::where('event_id', $event->id)->count() : 0;
        $menungguReview = $event ? Peserta::where('event_id', $event->id)->where('status', 'menunggu_review')->count() : 0;
        $diterima       = $event ? Peserta::where('event_id', $event->id)->where('status', 'diterima')->count() : 0;
        $ditolak        = $event ? Peserta::where('event_id', $event->id)->where('status', 'ditolak')->count() : 0;

        // 6. Ambil daftar peserta
        $pesertas = $event
            ? Peserta::where('event_id', $event->id)->with(['user', 'jawabans.pertanyaan'])->latest('id')->get()
            : collect();

        $lastUpdate = $pesertas->first()?->updated_at ?? $event?->updated_at;

        return view('admin.dashboard', [
            'event'          => $event,
            'pesertas'       => $pesertas,
            'totalPendaftar' => $totalPendaftar,
            'menungguReview' => $menungguReview,
            'diterima'       => $diterima,
            'ditolak'        => $ditolak,
            'lastUpdate'     => $lastUpdate,
        ]);
    }
}