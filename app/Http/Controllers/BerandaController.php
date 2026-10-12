<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        // ============================================================
        // 1. EVENT BERLANGSUNG (prioritas utama)
        // ============================================================
        $eventBerlangsung = Event::where('tampil_di_beranda', true)
            ->where('status', 'sedang_berlangsung')
            ->latest('id')
            ->first();

        // ============================================================
        // 2. EVENT BARU SELESAI (≤ 3 hari) — buat tampil peringkat akhir
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
        // 4. STATISTIK EVENT BERLANGSUNG
        // ============================================================
        $peringkatTerkini = collect();
        $totalSuara = 0;
        $jumlahPeserta = 0;
        $sisaWaktu = null;

        if ($eventBerlangsung) {
            // ✅ FILTER tampil_di_publik
            $peringkatTerkini = Peserta::where('event_id', $eventBerlangsung->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true)
                ->orderByDesc('total_vote')
                ->take(3)
                ->with('user')
                ->get();

            $totalSuara = Voting::where('event_id', $eventBerlangsung->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah_vote');

            // ✅ FILTER tampil_di_publik
            $jumlahPeserta = Peserta::where('event_id', $eventBerlangsung->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true)
                ->count();

            if ($eventBerlangsung->tanggal_selesai) {
                $sisaWaktu = (int) now()->diffInDays($eventBerlangsung->tanggal_selesai);
            }
        }

        // ============================================================
        // 5. PERINGKAT AKHIR — dari event baru selesai (≤ 3 hari)
        // ============================================================
        $peringkatAkhir = collect();
        $totalSuaraAkhir = 0;
        $eventPeringkatAkhir = null;

        if (! $eventBerlangsung && $eventBaruSelesai) {
            $eventPeringkatAkhir = $eventBaruSelesai;

            // ✅ FILTER tampil_di_publik
            $peringkatAkhir = Peserta::where('event_id', $eventBaruSelesai->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true)
                ->orderByDesc('total_vote')
                ->take(3)
                ->with('user')
                ->get();

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
        // 7. BLACKOUT H-2 — cek mode rahasia
        // ============================================================
        $blackoutAktif = false;
        $blackoutEvent = null;

                // Blackout — manual dari checkbox
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