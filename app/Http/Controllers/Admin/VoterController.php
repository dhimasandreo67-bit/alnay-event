<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Voting;
use Illuminate\View\View;

class VoterController extends Controller
{
    /**
     * GET /admin/voters/:event?
     */
    public function index(?Event $event = null): View
    {
        $event ??= Event::query()->latest('id')->first();

        $votings = collect();
        $totalSuara = 0;
        $voterUnik = 0;
        $terpopuler = null;
        $suaraTerakhir = null;

        if ($event) {
            $base = Voting::where('event_id', $event->id)->where('status_midtrans', 'settlement');

            $votings = (clone $base)
                ->with(['peserta.user', 'voter'])
                ->latest('id')
                ->take(50)
                ->get();

            $totalSuara = (clone $base)->sum('jumlah_vote');
            $voterUnik = (clone $base)->distinct('user_id')->count('user_id');

            $terpopuler = (clone $base)
                ->selectRaw('peserta_id, SUM(jumlah_vote) as total')
                ->groupBy('peserta_id')
                ->orderByDesc('total')
                ->with('peserta.user')
                ->first();

            $suaraTerakhir = (clone $base)->latest('id')->first();
        }

        return view('admin.voters.index', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'pesertaList' => $event?->pesertas()->where('status', 'diterima')->with('user')->get() ?? collect(),
            'votings' => $votings,
            'totalSuara' => $totalSuara,
            'voterUnik' => $voterUnik,
            'terpopuler' => $terpopuler,
            'suaraTerakhir' => $suaraTerakhir,
        ]);
    }
}