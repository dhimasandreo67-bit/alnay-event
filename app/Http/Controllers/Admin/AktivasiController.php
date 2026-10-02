<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AktivasiController extends Controller
{
    /**
     * GET /admin/activation/:event?
     */

    public function index(?Event $event = null): View
    {
        // Jika tidak ada event di URL, ambil dari session, atau fallback ke terbaru
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        } else {
            // Jika ada di URL, simpan ke session agar konsisten
            session(['active_event_id' => $event->id]);
        }

        $finalis = collect();
        if ($event) {
            $finalis = Peserta::where('event_id', $event->id)
                ->where('status', 'diterima')
                ->with(['user', 'pembayaran', 'jawabans.pertanyaan'])
                ->latest('id')
                ->get()
                ->map(fn (Peserta $peserta) => $this->transform($peserta));
        }

        $totalFinalis = $finalis->count();
        $lunas = $finalis->where('statusAkun', 'aktif')->count();
        $menungguVerifikasi = $totalFinalis - $lunas;

        return view('admin.aktivasi.index', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'finalis' => $finalis,
            'totalFinalis' => $totalFinalis,
            'lunas' => $lunas,
            'menungguVerifikasi' => $menungguVerifikasi,
        ]);
    }

    /**
     * POST /admin/activation/:peserta/verifikasi
     *
     * Cek ulang status pembayaran ke Midtrans (untuk kasus webhook telat/
     * gagal), lalu simpan hasilnya. Kalau statusnya 'settlement', akun
     * otomatis kelihatan "Aktif & Lunas" (itu cuma tampilan turunan dari
     * status_midtrans, bukan kolom baru).
     */
    public function verifikasi(Peserta $peserta)
    {
        $peserta->load(['user', 'pembayaran', 'event']);
        $pembayaran = $peserta->pembayaran;

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Peserta belum memiliki transaksi pembayaran.');
        }

        // Jika status sudah settlement (atau logika cek Midtrans Anda menandakan lunas)
        if ($pembayaran->status_midtrans === 'settlement') {

            // 1. Aktifkan akun peserta (sesuaikan field di tabel Anda)
            $peserta->update(['status_akun' => 'aktif']);

            // 2. ✅ BARU: masukkan data ke tabel voting
            $this->sinkronkanKeVoting($peserta, $pembayaran);

            return redirect()->back()->with('success', 'Pembayaran lunas. Akun aktif & data tersinkron ke voting.');
        }

        return redirect()->back()->with('error', 'Status pembayaran belum settlement.');
    }

    private function transform(Peserta $peserta): array
    {
        $kategoriJawaban = $peserta->jawabans->first(
            fn ($jawaban) => $jawaban->pertanyaan && str_contains(strtolower($jawaban->pertanyaan->label), 'kategori')
        );

        $pembayaran = $peserta->pembayaran;
        $lunas = $pembayaran?->status_midtrans === 'settlement';

        return [
            'id' => $peserta->id,
            'no_registrasi' => 'PPA-' . now()->year . '-' . str_pad($peserta->id, 3, '0', STR_PAD_LEFT),
            'nama' => $peserta->user->nama ?? '-',
            'email' => $peserta->user->email ?? '-',
            'kategori' => $kategoriJawaban?->jawaban ?? '-',
            'pembayaran' => $pembayaran,
            'nominal_unik' => $pembayaran ? 'Rp ' . number_format($pembayaran->jumlah, 0, ',', '.') : null,
            'kode_unik' => $pembayaran?->kode_unik,
            'tarif_dasar' => $pembayaran?->tarif_dasar,
            'statusAkun' => $lunas ? 'aktif' : 'terkunci',
            'peserta' => $peserta,
        ];
    }

    private function sinkronkanKeVoting(Peserta $peserta, $pembayaran): void
    {
        \App\Models\Voting::firstOrCreate(
            // Kunci unik: order id pembayaran (cegah duplikat)
            ['midtrans_order_id' => $pembayaran->midtrans_order_id],
            // Data yang diisi saat record belum ada
            [
                'event_id'        => $peserta->event_id,
                'peserta_id'      => $peserta->id,
                'user_id'         => $peserta->user_id,
                'nama_tampilan'   => $peserta->user?->nama ?? 'Peserta',
                'jumlah_vote'     => 0,
                'harga_per_vote'  => $peserta->event?->harga_per_vote ?? 0,
                'total_bayar'     => $pembayaran->jumlah,
                'status_midtrans' => 'settlement',
                'tanggal_vote'    => $pembayaran->tanggal_bayar ?? now(),
            ]
        );
    }
}