<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaTampilController extends Controller
{
    /**
     * GET /admin/peserta-tampil/{event?}
     *
     * Siapa yang muncul di halaman ini (2 aturan — lihat Peserta::layakTampilPublik()):
     * 1. Peserta PENDAFTARAN (mandiri atau manual, kandidat_voting = false):
     *    harus SUDAH LUNAS biaya pendaftaran (pembayaran settlement), dan
     *    KALAU event ini butuh_audisi = true, tambahan wajib SUDAH LOLOS
     *    AUDISI (hasil_audisi = 'lolos'). Kalau event tidak butuh audisi,
     *    syarat audisi ini dilewati.
     * 2. Peserta KANDIDAT VOTING (kandidat_voting = true): langsung ikut
     *    tanpa syarat audisi maupun pembayaran — status pembayarannya
     *    ditampilkan apa adanya, otomatis "Belum Bayar" kalau belum ada
     *    transaksi sama sekali.
     *
     * Pembayaran REGISTRASI (terpisah dari biaya pendaftaran) TIDAK ikut
     * disyaratkan di sini — boleh dilunasi kapan saja sebelum event berakhir.
     */
    public function index(?Event $event = null): View
    {
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        } else {
            session(['active_event_id' => $event->id]);
        }

        $pesertas = collect();
        if ($event) {
            // 1. Peserta pendaftaran: lunas pembayaran + (kalau butuh_audisi) lolos audisi
            $pendaftaran = Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', false)
                ->where('status', 'diterima')
                ->with(['user', 'pembayaran'])
                ->get()
                ->filter(fn (Peserta $p) => $p->layakTampilPublik($event));

            // 2. Kandidat voting: langsung ikut apapun status bayarnya
            $kandidat = Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', true)
                ->where('status', 'diterima')
                ->with(['user', 'pembayaran'])
                ->get();

            $pesertas = $pendaftaran->concat($kandidat)
                ->map(fn (Peserta $p) => $this->attachStatusBayar($p, $event))
                ->sortByDesc('id')
                ->values();
        }

        return view('admin.peserta-tampil.index', [
            'event'    => $event,
            'events'   => Event::query()->latest('id')->get(['id', 'nama']),
            'pesertas' => $pesertas,
        ]);
    }

    /**
     * Hitung & tempelkan status pembayaran (lunas/dp/belum) ke peserta.
     * Dipakai sama untuk peserta pendaftaran maupun kandidat voting — karena
     * keduanya memang memakai biaya_pendaftaran sebagai basis tarif (lihat
     * PendaftaranController@storeManual dan @storeKandidat).
     */
    private function attachStatusBayar(Peserta $peserta, Event $event): Peserta
    {
        $totalBiaya = (int) ($event->biaya_pendaftaran ?? 0);
        $sudahDibayar = $peserta->pembayaran
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah');

        if ($totalBiaya <= 0 || $sudahDibayar >= $totalBiaya) {
            // Event gratis (tidak ada biaya), atau sudah dibayar penuh → lunas.
            $statusBayar = 'lunas';
        } elseif ($sudahDibayar > 0) {
            $statusBayar = 'dp';
        } else {
            // ✅ Baru ditambahkan & belum ada transaksi sama sekali → "Belum Bayar"
            $statusBayar = 'belum';
        }

        $peserta->setAttribute('status_bayar_tampil', $statusBayar);
        $peserta->setAttribute('sudah_dibayar_tampil', $sudahDibayar);
        $peserta->setAttribute('total_biaya_tampil', $totalBiaya);

        return $peserta;
    }

    /**
     * POST /admin/peserta-tampil/{peserta}/toggle
     */
    public function toggle(Request $request, Peserta $peserta): JsonResponse
    {
        $peserta->tampil_di_publik = ! $peserta->tampil_di_publik;
        $peserta->save();

        return response()->json([
            'success'        => true,
            'tampilDiPublik' => (bool) $peserta->tampil_di_publik,
            'message'        => $peserta->namaTampil() . ' ' . ($peserta->tampil_di_publik ? 'ditampilkan' : 'disembunyikan') . ' di publik.',
        ]);
    }

    /**
     * POST /admin/peserta-tampil/{event}/toggle-semua
     */
    public function toggleSemua(Request $request, Event $event): RedirectResponse
    {
        $show = $request->boolean('tampil');

        $idsLayak = Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->with('pembayaran')
            ->get()
            ->filter(fn (Peserta $p) => $p->layakTampilPublik($event))
            ->pluck('id');

        Peserta::whereIn('id', $idsLayak)
            ->update(['tampil_di_publik' => $show]);

            return redirect()
            ->to('/admin/peserta-tampil/' . $event->id)
            ->with('success', $show
                ? 'Semua peserta berhasil ditampilkan di halaman publik.'
                : 'Semua peserta berhasil disembunyikan dari halaman publik.');
    }

    /**
     * POST /admin/peserta-tampil/{event}/store
     * ⚠️ Legacy — nggak dipakai lagi
     */
    public function store(Request $request, Event $event)
    {
        return response()->json(['message' => 'Gunakan form create + storeForm'], 410);
    }

    // app/Http/Controllers/Admin/PesertaTampilController.php

public function updateFoto(Request $request, $peserta)
{
    $request->validate([
        'foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ], [
        'foto.required' => 'File gambar wajib dipilih.',
        'foto.image'    => 'File harus berupa gambar.',
        'foto.mimes'    => 'Format yang diizinkan: JPG, PNG, WEBP.',
        'foto.max'      => 'Ukuran maksimal 2 MB.',
    ]);

    $peserta = \App\Models\Peserta::with('user')->findOrFail($peserta);

    if (!$peserta->user) {
        return response()->json([
            'success' => false,
            'message' => 'Akun user peserta tidak ditemukan.',
        ], 422);
    }

    if (!empty($peserta->user->foto_custom)
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($peserta->user->foto_custom)
    ) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($peserta->user->foto_custom);
    }

    $path = $request->file('foto')->store('peserta/foto', 'public');

    $peserta->user->update([
        'foto_custom' => $path,
    ]);

    return response()->json([
        'success'  => true,
        'message'  => 'Gambar berhasil disimpan.',
        'foto_url' => asset('storage/' . $path),
    ]);
}
}