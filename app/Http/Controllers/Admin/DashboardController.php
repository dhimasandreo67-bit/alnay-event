<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $event = Event::query()->latest('id')->first();
        $pesertaQuery = Peserta::query();

        // Cek kolom updated_at — kalau gak ada, pakai null
        $lastUpdate = Schema::hasColumn('peserta_event', 'updated_at')
            ? (clone $pesertaQuery)->max('updated_at')
            : null;

        return view('admin.dashboard', [
            'event'           => $event,
            'totalPendaftar'  => (clone $pesertaQuery)->count(),
            'menungguReview'  => (clone $pesertaQuery)->where('status', 'menunggu_review')->count(),
            'diterima'        => (clone $pesertaQuery)->where('status', 'diterima')->count(),
            'perluRevisi'     => (clone $pesertaQuery)->where('status', 'revisi')->count(),
            'ditolak'         => (clone $pesertaQuery)->where('status', 'ditolak')->count(),
            'totalSuara'      => Voting::where('status_midtrans', 'settlement')->sum('jumlah_vote'),
            'lastUpdate'      => $lastUpdate,
            'pesertas'        => Peserta::with(['user', 'event', 'jawabans.pertanyaan'])
                ->latest('id')
                ->take(8)
                ->get(),
        ]);
    }
}