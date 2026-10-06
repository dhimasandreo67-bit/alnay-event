<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;

class DetailEventController extends Controller
{
    public function berlangsung(Event $event)
    {
        $pesertas = $this->getPesertas($event);
        $totalSuara = $this->getTotalSuara($event);
        $jumlahPeserta = $pesertas->count();

        $sisaWaktu = $event->tanggal_selesai
            ? (int) now()->diffInDays($event->tanggal_selesai)
            : 0;

        return view('detail-event', [
            'event'         => $event,
            'pesertas'      => $pesertas,
            'totalSuara'    => $totalSuara,
            'jumlahPeserta' => $jumlahPeserta,
            'sisaWaktu'     => $sisaWaktu,
            'blackoutAktif' => $this->isBlackoutAktif($event),
        ]);
    }

    public function akanDatang(Event $event)
    {
        $jumlahPeserta = Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->count();

        $sisaHariPendaftaran = $event->batas_pendaftaran_tanggal
            ? (int) now()->diffInDays($event->batas_pendaftaran_tanggal, false)
            : null;

        return view('detail-event-akan-datang', [
            'event'               => $event,
            'jumlahPeserta'       => $jumlahPeserta,
            'sisaHariPendaftaran' => $sisaHariPendaftaran,
            'blackoutAktif'       => $this->isBlackoutAktif($event),
        ]);
    }

    public function selesai(Event $event)
    {
        $pesertas = Peserta::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderByDesc('total_vote')
            ->get();

        $totalSuara = $pesertas->sum('total_vote');
        $pemenang = $pesertas->take(3);
        $peringkatLain = $pesertas->slice(3, 7);

        return view('detail-event-selesai', [
            'event'         => $event,
            'pesertas'      => $pesertas,
            'pemenang'      => $pemenang,
            'peringkatLain' => $peringkatLain,
            'totalSuara'    => $totalSuara,
            'blackoutAktif' => $this->isBlackoutAktif($event),
        ]);
    }

    private function getPesertas(Event $event)
    {
        return Peserta::with(['user', 'jawaban.pertanyaan'])
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderByDesc('total_vote')
            ->get();
    }

    private function getTotalSuara(Event $event)
    {
        return Voting::where('event_id', $event->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');
    }

    /**
     * Helper — cek blackout H-2 aktif.
     */
    private function isBlackoutAktif(Event $event): bool
    {
        if (! $event->blackout_h2_voting || ! $event->voting_selesai) {
            return false;
        }

        $selisihJam = now()->diffInHours($event->voting_selesai, false);

        return $selisihJam <= 48 && $selisihJam > 0;
    }
}