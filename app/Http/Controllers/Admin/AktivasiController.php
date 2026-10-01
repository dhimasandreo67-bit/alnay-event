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
     */
    public function verifikasi(Peserta $peserta): RedirectResponse
    {
        $pembayaran = $this->pembayaranTerbaik($peserta);

        if (! $pembayaran) {
            return back()->with('error', 'Peserta ini belum punya transaksi pembayaran.');
        }

        // TODO: panggil Midtrans Status API di sini kalau perlu
        // \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        // $status = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
        // $pembayaran->update([...]);

        return redirect()
            ->route('admin.activation', $peserta->event_id)
            ->with('success', "Status pembayaran " . ($peserta->nama_pendaftaran ?: $peserta->user->nama) . " berhasil disinkronkan.");
    }

    /**
     * Ambil pembayaran terbaik:
     * 1. Kalau ada yang 'settlement' → pakai itu (yang terbaru)
     * 2. Kalau gak ada → pakai yang terbaru (pending, dll)
     */
    private function pembayaranTerbaik(Peserta $peserta)
    {
        $semua = $peserta->pembayaran instanceof \Illuminate\Support\Collection
            ? $peserta->pembayaran
            : collect([$peserta->pembayaran])->filter();

        if ($semua->isEmpty()) {
            return null;
        }

        return $semua->where('status_midtrans', 'settlement')->sortByDesc('id')->first()
            ?? $semua->sortByDesc('id')->first();
    }

    private function transform(Peserta $peserta): array
    {
        $kategoriJawaban = $peserta->jawabans->first(
            fn ($jawaban) => $jawaban->pertanyaan
                && str_contains(strtolower($jawaban->pertanyaan->label), 'kategori')
        );

        $pembayaran = $this->pembayaranTerbaik($peserta);
        $lunas = $pembayaran?->status_midtrans === 'settlement';

        return [
            'id' => $peserta->id,
            'no_registrasi' => 'PPA-' . now()->year . '-' . str_pad($peserta->id, 3, '0', STR_PAD_LEFT),
            'nama' => $peserta->nama_pendaftaran ?: ($peserta->user->nama_custom ?: $peserta->user->nama ?? '-'),
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
}