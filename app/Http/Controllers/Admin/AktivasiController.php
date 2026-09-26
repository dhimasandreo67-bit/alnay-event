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
        $event ??= Event::query()->latest('id')->first();

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
    public function verifikasi(Peserta $peserta): RedirectResponse
    {
        $pembayaran = $peserta->pembayaran;

        if (! $pembayaran) {
            return back()->with('error', 'Peserta ini belum punya transaksi pembayaran.');
        }

        // TODO: panggil Midtrans Status API di sini, contoh:
        // \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        // $status = \Midtrans\Transaction::status($pembayaran->midtrans_order_id);
        // $pembayaran->update([
        //     'status_midtrans' => $status->transaction_status,
        //     'midtrans_transaction_id' => $status->transaction_id,
        //     'tanggal_bayar' => $status->transaction_status === 'settlement' ? now() : $pembayaran->tanggal_bayar,
        // ]);

        return redirect()
            ->route('admin.activation', $peserta->event_id)
            ->with('success', "Status pembayaran {$peserta->user->nama} berhasil disinkronkan.");
    }

    private function transform(Peserta $peserta): array
    {
        $kategoriJawaban = $peserta->jawabans->first(
            fn ($jawaban) => $jawaban->pertanyaan && str_contains(strtolower($jawaban->pertanyaan->label), 'kategori')
        );

        $lunas = $peserta->pembayaran?->status_midtrans === 'settlement';

        return [
            'id' => $peserta->id,
            'no_registrasi' => 'PPA-'.now()->year.'-'.str_pad($peserta->id, 3, '0', STR_PAD_LEFT),
            'nama' => $peserta->user->nama ?? '-',
            'email' => $peserta->user->email ?? '-',
            'kategori' => $kategoriJawaban?->jawaban ?? '-',
            'pembayaran' => $peserta->pembayaran,
            'statusAkun' => $lunas ? 'aktif' : 'terkunci',
            'peserta' => $peserta,
        ];
    }
}