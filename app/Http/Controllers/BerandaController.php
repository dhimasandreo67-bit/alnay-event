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
                
                // ✅ DIPERBAIKI: Hapus ->where('tampil_di_publik', true)
                $ev->stat_jumlah_peserta = Peserta::where('event_id', $ev->id)
                    ->where('status', 'diterima')
                    ->where('tampil_di_publik', true)
                    ->count();
                    
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
            // ✅ DIPERBAIKI: Hapus ->where('tampil_di_publik', true)
            $peringkatTerkini = Peserta::where('event_id', $eventBerlangsung->id)
                ->where('status', 'diterima')
                ->where('tampil_di_publik', true)
                ->orderByDesc('total_vote')
                ->take(3)
                ->with('user')
                ->get();

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
            $peringkatAkhir = Peserta::where('event_id', $eventBaruSelesai->id)
                ->where('status', 'diterima')
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