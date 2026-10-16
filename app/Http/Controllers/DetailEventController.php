<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;

class DetailEventController extends Controller
{
    /**
     * GET /detail-event/{event} (Berlangsung)
     */
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

    /**
     * GET /detail-event/{event}/akan-datang
     */
    public function akanDatang(Event $event)
    {
        $jumlahPeserta = Peserta::where('event_id', $event->id)
        ->where('status', 'diterima')
        ->where('tampil_di_publik', true)
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

    /**
     * GET /detail-event/{event}/selesai
     */
    public function selesai(Event $event)
    {
        $pesertas = Peserta::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->where('tampil_di_publik', true)
            ->orderByDesc('total_vote')
            ->get();

        $totalSuara = $this->getTotalSuara($event);
        $jumlahPeserta = $pesertas->count(); // ✅ TAMBAHKAN INI agar tidak Undefined variable
        
        $pemenang = $pesertas->take(3);
        $peringkatLain = $pesertas->slice(3, 7);

        return view('detail-event-selesai', [
            'event'         => $event,
            'pesertas'      => $pesertas,
            'pemenang'      => $pemenang,
            'peringkatLain' => $peringkatLain,
            'totalSuara'    => $totalSuara,
            'jumlahPeserta' => $jumlahPeserta, // ✅ KIRIM KE VIEW
            'blackoutAktif' => $this->isBlackoutAktif($event),
        ]);
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    private function getPesertas(Event $event)
        {
            $query = Peserta::with(['user', 'jawabans.pertanyaan'])
                ->where('event_id', $event->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true);

            // Kalau blackout aktif — acak urutan biar gak bocorin peringkat
            if ($this->isBlackoutAktif($event)) {
                return $query->inRandomOrder()->get();
            }

            // Normal — urut vote terbanyak
            return $query->orderByDesc('total_vote')->get();
        }

    private function getTotalSuara(Event $event)
    {
        return Voting::where('event_id', $event->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');
    }

    private function isBlackoutAktif(Event $event): bool
    {
        return (bool) $event->blackout_h2_voting;
    }
}