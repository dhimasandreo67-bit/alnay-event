<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Carbon\Carbon;

class BerandaController extends Controller
{
    /**
     * Peserta (pendaftaran + kandidat voting) yang berhak dihitung & tampil
     * di statistik/peringkat beranda untuk satu event.
     *
     * Aturannya disamakan persis dengan Admin\PesertaTampilController (lihat
     * Peserta::layakTampilPublik()):
     * 1. Peserta PENDAFTARAN (kandidat_voting = false): status diterima,
     *    sudah lunas biaya pendaftaran (settlement), dan — kalau event ini
     *    butuh_audisi — wajib sudah lolos audisi.
     * 2. Peserta KANDIDAT VOTING (kandidat_voting = true): cukup status
     *    diterima, tanpa syarat audisi maupun pembayaran sama sekali.
     *
     * $hanyaTampilPublik — kalau true, hanya ambil yang sudah di-toggle
     * "Tampil di Publik" oleh admin (dipakai untuk peringkat yang ditampilkan
     * ke pengunjung; tidak dipakai untuk statistik jumlah peserta).
     */
    private function pesertaLayakVoting(Event $event, bool $hanyaTampilPublik = false)
    {
        $pendaftaran = Peserta::where('event_id', $event->id)
            ->where('kandidat_voting', false)
            ->where('status', 'diterima')
            ->when($hanyaTampilPublik, fn ($q) => $q->where('tampil_di_publik', true))
            ->with(['user', 'pembayaran'])
            ->get()
            ->filter(fn (Peserta $p) => $p->layakTampilPublik($event));

        $kandidat = Peserta::where('event_id', $event->id)
            ->where('kandidat_voting', true)
            ->where('status', 'diterima')
            ->when($hanyaTampilPublik, fn ($q) => $q->where('tampil_di_publik', true))
            ->with(['user', 'pembayaran'])
            ->get();

        return $pendaftaran->concat($kandidat);
    }

    public function index()
    {
        // ============================================================
        // 1. EVENT BERLANGSUNG — semua (untuk carousel)
        // ============================================================

        $eventBerlangsungList = Event::where('tampil_di_beranda', true)
            ->where('status', 'sedang_berlangsung')
            ->latest('id')
            ->get()
            ->map(function ($ev) {

                // Stats per-event — untuk carousel
                $ev->stat_sisa_waktu = $ev->tanggal_selesai
                    ? max(0, (int) now()->diffInDays($ev->tanggal_selesai, false))
                    : 0;

                // Peserta pendaftaran dihitung kalau diterima + lunas + lolos audisi
                // (bila perlu); kandidat voting dihitung langsung selama diterima
                $ev->stat_jumlah_peserta = $this->pesertaLayakVoting($ev)->count();

                $ev->stat_total_suara = Voting::where('event_id', $ev->id)
                    ->where('status_midtrans', 'settlement')
                    ->sum('jumlah_vote');

                return $ev;
            });

        // Event pertama — untuk backward compat (stats, peringkat, dll)
        $eventBerlangsung = $eventBerlangsungList->first();

        // ============================================================
        // 2. EVENT BARU SELESAI (≤ 3 hari)
        // ============================================================
        $eventBaruSelesai = Event::where('tampil_di_beranda', true)
            ->where('status', 'telah_berakhir')
            ->where('tanggal_selesai', '>=', now()->subDays(3))
            ->latest('tanggal_selesai')
            ->first();

        // ============================================================
        // 3. EVENT AKAN DATANG — MAX 3
        // ============================================================
        $eventAkanDatangList = Event::where('tampil_di_beranda', true)
            ->where('status', 'akan_datang')
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        $eventAkanDatangUtama = $eventBerlangsung ? null : $eventAkanDatangList->first();
        $eventAkanDatangLainnya = $eventBerlangsung
            ? $eventAkanDatangList
            : $eventAkanDatangList->slice(1);

        // ============================================================
        // 4. STATISTIK EVENT BERLANGSUNG (yang pertama)
        // ============================================================
        $peringkatTerkini = collect();
        $totalSuara = 0;
        $jumlahPeserta = 0;
        $sisaWaktu = null;

        if ($eventBerlangsung) {
            // Pendaftaran: diterima + lunas + lolos audisi (bila perlu).
            // Kandidat voting: diterima saja, tanpa syarat audisi/bayar.
            // Keduanya tetap harus sudah di-toggle "Tampil di Publik".
            $peringkatTerkini = $this->pesertaLayakVoting($eventBerlangsung, true)
                ->sortByDesc('total_vote')
                ->take(3)
                ->values();

            $totalSuara = $eventBerlangsung->stat_total_suara ?? 0;
            $jumlahPeserta = $eventBerlangsung->stat_jumlah_peserta ?? 0;
            $sisaWaktu = $eventBerlangsung->stat_sisa_waktu ?? 0;
        }

        // ============================================================
        // 5. PERINGKAT AKHIR
        // ============================================================
        $peringkatAkhir = collect();
        $totalSuaraAkhir = 0;
        $eventPeringkatAkhir = null;

        if (! $eventBerlangsung && $eventBaruSelesai) {
            $eventPeringkatAkhir = $eventBaruSelesai;
            $peringkatAkhir = $this->pesertaLayakVoting($eventBaruSelesai)
                ->sortByDesc('total_vote')
                ->take(3)
                ->values();

            $totalSuaraAkhir = Voting::where('event_id', $eventBaruSelesai->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah_vote');
        }

        // ============================================================
        // 6. EVENT SEBELUMNYA (arsip)
        // ============================================================
        $eventSebelumnya = Event::where('tampil_di_beranda', true)
            ->where('status', 'telah_berakhir')
            ->latest('tanggal_selesai')
            ->take(6)
            ->get();

        // ============================================================
        // 7. BLACKOUT
        // ============================================================
        $blackoutAktif = false;
        $blackoutEvent = null;

        if ($eventBerlangsung && $eventBerlangsung->blackout_h2_voting) {
            $blackoutAktif = true;
            $blackoutEvent = $eventBerlangsung;
        }

        if (! $blackoutAktif && $eventPeringkatAkhir && $eventPeringkatAkhir->blackout_h2_voting) {
            $blackoutAktif = true;
            $blackoutEvent = $eventPeringkatAkhir;
        }

        return view('beranda', compact(
            'eventBerlangsung',
            'eventBerlangsungList',
            'eventBaruSelesai',
            'eventAkanDatangUtama',
            'eventAkanDatangLainnya',
            'eventSebelumnya',
            'peringkatTerkini',
            'totalSuara',
            'jumlahPeserta',
            'sisaWaktu',
            'peringkatAkhir',
            'totalSuaraAkhir',
            'eventPeringkatAkhir',
            'blackoutAktif',
            'blackoutEvent'
        ));
    }
}