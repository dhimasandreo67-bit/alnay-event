<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;

class DashboardController extends Controller
{
    public function index()
    {
        $event = Event::query()->latest('id')->first();
        $pesertaQuery = Peserta::query();

        return view('admin.dashboard', [
            'event' => $event,
            'totalPendaftar' => (clone $pesertaQuery)->count(),
            'menungguReview' => (clone $pesertaQuery)->where('status', 'menunggu_pembayaran')->count(),
            'diterima' => (clone $pesertaQuery)->where('status', 'diterima')->count(),
            'perluRevisi' => (clone $pesertaQuery)->where('status', 'ditolak')->count(),
            'totalSuara' => Voting::where('status_midtrans', 'settlement')->sum('jumlah_vote'),
            'peserta' => Peserta::with(['user', 'event'])->latest('id')->take(8)->get(),
        ]);
    }
}