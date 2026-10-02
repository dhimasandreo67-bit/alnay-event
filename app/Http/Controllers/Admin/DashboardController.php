<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index($eventId = null)
    {
        // 1. Tentukan Event mana yang diambil:
        // Prioritas 1: Dari Parameter URL (misal: /admin/7)
        // Prioritas 2: Dari Session aktif
        // Prioritas 3: Fallback ke event terbaru
        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                session(['active_event_id' => $event->id]);
            }
        } else {
            $activeEventId = session('active_event_id');
            $event = $activeEventId ? Event::find($activeEventId) : null;
        }

        // Fallback jika belum ada event pilihan
        $event ??= Event::query()->latest('id')->first();

        // 2. Hitung statistik HANYA untuk event ini
        $totalPendaftar = $event ? Peserta::where('event_id', $event->id)->count() : 0;
        $menungguReview = $event
            ? Peserta::where('event_id', $event->id)->where('status', 'menunggu_review')->count()
            : 0;
        $diterima       = $event ? Peserta::where('event_id', $event->id)->where('status', 'diterima')->count() : 0;
        $ditolak        = $event ? Peserta::where('event_id', $event->id)->where('status', 'ditolak')->count() : 0;
        $perluRevisi    = $event ? Peserta::where('event_id', $event->id)->where('status', 'revisi')->count() : 0;

        // Total suara untuk event ini
        $totalSuara = $event
            ? Voting::where('event_id', $event->id)->where('status_midtrans', 'settlement')->sum('jumlah_vote')
            : 0;

        // 3. Ambil daftar peserta HANYA milik event ini
        $pesertas = $event
            ? Peserta::where('event_id', $event->id)
                ->with(['user', 'event', 'jawabans.pertanyaan'])
                ->latest('id')
                ->get()
            : collect();

        // Timestamp update terakhir — pakai Schema::hasColumn (biar aman)
        $lastUpdate = \Illuminate\Support\Facades\Schema::hasColumn('peserta_event', 'updated_at')
            ? ($event ? Peserta::where('event_id', $event->id)->max('updated_at') : null)
            : null;

        // Daftar event untuk dropdown
        $events = Event::query()->latest('id')->get(['id', 'nama']);

        // 4. Kirim data ke view
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