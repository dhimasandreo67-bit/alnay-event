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
        // Ambil event aktif dari session atau parameter
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        }

        if (!$event) {
            return view('admin.voters.index', [
                'event' => null,
                'votings' => collect(),
                'totalSuara' => 0,
                'voterUnik' => 0,
                'terpopuler' => null,
                'suaraTerakhir' => null,
                'pesertaList' => collect(),
            ]);
        }

        // ✅ HANYA ambil voting yang status_midtrans = 'settlement'
        $votings = \App\Models\Voting::where('event_id', $event->id)
        ->where('status_midtrans', 'settlement')
        ->with(['peserta.user'])
        ->latest('tanggal_vote')
        ->get();

        // Hitung statistik
        $totalSuara = $votings->sum('jumlah_vote');
        $voterUnik = $votings->unique('user_id')->count();
        
        $terpopuler = $votings->groupBy('peserta_id')
            ->map(fn($group) => [
                'peserta' => $group->first()->peserta,
                'total' => $group->sum('jumlah_vote'),
            ])
            ->sortByDesc('total')
            ->first();

        $suaraTerakhir = $votings->first();

        $pesertaList = \App\Models\Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->whereHas('pembayaran', function ($query) {
                $query->where('status_midtrans', 'settlement');
            })
            ->with('user')
            ->get();

        return view('admin.voters.index', [
            'event' => $event,
            'votings' => $votings,
            'totalSuara' => $totalSuara,
            'voterUnik' => $voterUnik,
            'terpopuler' => $terpopuler,
            'suaraTerakhir' => $suaraTerakhir,
            'pesertaList' => $pesertaList,
        ]);
    }
}