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
        $jumlahPeserta = $this->hitungPesertaLayak($event);

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
        $pesertas = Peserta::with(['user', 'pembayaran'])
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->where('tampil_di_publik', true)
            ->get()
            ->filter(fn (Peserta $p) => $p->layakTampilPublik($event))
            ->sortByDesc('total_vote')
            ->values();

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
            $pesertas = Peserta::with(['user', 'jawabans.pertanyaan', 'pembayaran'])
                ->where('event_id', $event->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true)
                ->get()
                ->filter(fn (Peserta $p) => $p->layakTampilPublik($event))
                ->values();

            // Kalau blackout aktif — acak urutan biar gak bocorin peringkat
            if ($this->isBlackoutAktif($event)) {
                return $pesertas->shuffle()->values();
            }

            // Normal — urut vote terbanyak
            return $pesertas->sortByDesc('total_vote')->values();
        }

    /**
     * Hitung jumlah peserta yang memenuhi syarat tampil publik,
     * dipakai di halaman "akan datang" (cuma butuh angka, bukan daftar kartu).
     */
    private function hitungPesertaLayak(Event $event): int
    {
        return Peserta::with('pembayaran')
            ->where('event_id', $event->id)
            ->where('status', 'diterima')
            ->where('tampil_di_publik', true)
            ->get()
            ->filter(fn (Peserta $p) => $p->layakTampilPublik($event))
            ->count();
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