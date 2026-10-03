<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;

class DetailEventController extends Controller
{
    /**
     * GET /detail-event/{event}
     * Halaman detail event BERLANGSUNG.
     */
    public function berlangsung(Event $event)
    {
        // Data peserta yang diterima + sudah bayar
        $pesertas = $this->getPesertas($event);

        // Hitung total suara event
        $totalSuara = $this->getTotalSuara($event);

        // Jumlah peserta
        $jumlahPeserta = $pesertas->count();

        // Sisa waktu (hari)
        $sisaWaktu = $event->tanggal_selesai
            ? (int) now()->diffInDays($event->tanggal_selesai)
            : 0;

        return view('detail-event', [
            'event'         => $event,
            'pesertas'      => $pesertas,
            'totalSuara'    => $totalSuara,
            'jumlahPeserta' => $jumlahPeserta,
            'sisaWaktu'     => $sisaWaktu,
        ]);
    }

    /**
     * GET /detail-event-akan-datang/{event}
     * Halaman detail event AKAN DATANG.
     */
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
        ]);
    }

    /**
     * GET /detail-event-selesai/{event}
     * Halaman detail event SELESAI.
     */
    public function selesai(Event $event)
    {
        // Peserta — diurutkan by total_vote (pemenang)
        $pesertas = Peserta::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderByDesc('total_vote')
            ->get();

        $totalSuara = $pesertas->sum('total_vote');

        // Pemenang — top 3
        $pemenang = $pesertas->take(3);

        // Peringkat lainnya — 4-10
        $peringkatLain = $pesertas->slice(3, 7);

        return view('detail-event-selesai', [
            'event'         => $event,
            'pesertas'      => $pesertas,
            'pemenang'      => $pemenang,
            'peringkatLain' => $peringkatLain,
            'totalSuara'    => $totalSuara,
        ]);
    }

    /**
     * Helper — ambil peserta event.
     */
    private function getPesertas(Event $event)
    {
        return Peserta::with(['user', 'jawaban.pertanyaan'])
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->orderByDesc('total_vote')
            ->get();
    }

    /**
     * Helper — total suara event.
     */
    private function getTotalSuara(Event $event)
    {
        return Voting::where('event_id', $event->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');
    }
}