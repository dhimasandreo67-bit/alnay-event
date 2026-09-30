<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;

class BerandaController extends Controller
{
    public function index()
    {
        // Event berlangsung (maks 1)
        $eventBerlangsung = Event::where('tampil_di_beranda', true)
            ->where('status', 'sedang_berlangsung')
            ->latest('id')
            ->first();

        // Event akan datang — MAX 3
        $eventAkanDatangList = Event::where('tampil_di_beranda', true)
            ->where('status', 'akan_datang')
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        // Event akan datang #1 — HANYA jadi "utama" kalau gak ada event berlangsung
        $eventAkanDatangUtama = $eventBerlangsung ? null : $eventAkanDatangList->first();

        // Event akan datang lainnya — card kecil
        // Kalau ada berlangsung → SEMUA event akan datang masuk sini
        // Kalau gak ada berlangsung → #2 & #3 saja
        $eventAkanDatangLainnya = $eventBerlangsung
            ? $eventAkanDatangList
            : $eventAkanDatangList->slice(1);

        // Statistik — dari event berlangsung
        $peringkatTerkini = collect();
        $totalSuara = 0;
        $jumlahPeserta = 0;
        $sisaWaktu = null;

        if ($eventBerlangsung) {
            $peringkatTerkini = Peserta::where('event_id', $eventBerlangsung->id)
                ->where('status', 'diterima')
                ->orderByDesc('total_vote')
                ->take(3)
                ->with('user')
                ->get();

            $totalSuara = Voting::where('event_id', $eventBerlangsung->id)
                ->where('status_midtrans', 'settlement')
                ->sum('jumlah_vote');

            $jumlahPeserta = Peserta::where('event_id', $eventBerlangsung->id)
                ->where('status', 'diterima')
                ->count();

            if ($eventBerlangsung->tanggal_selesai) {
                $sisaWaktu = (int) now()->diffInDays($eventBerlangsung->tanggal_selesai);
            }
        }

        // Event sebelumnya
        $eventSebelumnya = Event::where('tampil_di_beranda', true)
            ->where('status', 'telah_berakhir')
            ->latest('tanggal_selesai')
            ->take(6)
            ->get();

        return view('beranda', compact(
            'eventBerlangsung',
            'eventAkanDatangUtama',
            'eventAkanDatangLainnya',
            'eventSebelumnya',
            'peringkatTerkini',
            'totalSuara',
            'jumlahPeserta',
            'sisaWaktu'
        ));
    }
}