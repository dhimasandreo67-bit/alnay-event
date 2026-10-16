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
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        } else {
            session(['active_event_id' => $event->id]);
        }

        $finalis = collect();
            if ($event) {
                $mode = request()->query('mode', 'pendaftaran');

                $query = Peserta::where('event_id', $event->id)
                    ->where('status', 'diterima')
                    ->where('kandidat_voting', false)
                    ->with(['user', 'pembayaran', 'pembayaranRegistrasi', 'jawabans.pertanyaan']);

            // Mode Registrasi — cuma tampil yang sudah lolos audisi (kalau event butuh audisi)
            if ($mode === 'registrasi' && $event->butuh_audisi) {
                $query->where('hasil_audisi', 'lolos');
            }

        $totalFinalis = $finalis->count();
        $lunas = $finalis->where('statusAkun', 'aktif')->count();
        $menungguVerifikasi = $totalFinalis - $lunas;

        // ===== DATA UNTUK MODE REGISTRASI =====
        $sudahAudisi = $finalis->where('sudahAudisi', true)->count();
        $belumAudisi = $totalFinalis - $sudahAudisi;
        $registrasiLunas = $finalis->where('statusRegistrasi', 'lunas')->count();
        $registrasiDp = $finalis->where('statusRegistrasi', 'dp')->count();

        // ===== MODE — dari URL ?mode=xxx =====
        $mode = request()->query('mode', 'pendaftaran');

        return view('admin.aktivasi.index', [
            'event'                 => $event,
            'events'                => Event::query()->latest('id')->get(['id', 'nama']),
            'finalis'               => $finalis,
            'totalFinalis'          => $totalFinalis,
            'lunas'                 => $lunas,
            'menungguVerifikasi'    => $menungguVerifikasi,
            'sudahAudisi'           => $sudahAudisi,
            'belumAudisi'           => $belumAudisi,
            'registrasiLunas'       => $registrasiLunas,
            'registrasiDp'          => $registrasiDp,
            'mode'                  => $mode,
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

        return redirect()
            ->route('admin.activation', $peserta->event_id)
            ->with('success', "Status pembayaran " . ($peserta->nama_pendaftaran ?: $peserta->user->nama) . " berhasil disinkronkan.");
    }

    /**
     * POST /admin/activation/:peserta/toggle-audisi
     * Admin centang/hapus centang "sudah audisi"
     */
    public function toggleAudisi(Peserta $peserta): RedirectResponse
    {
        $peserta->sudah_audisi = ! $peserta->sudah_audisi;
        $peserta->tanggal_audisi = $peserta->sudah_audisi ? now() : null;

        // ✅ Set hasil_audisi juga — biar sinkron dengan /profil-user
        if ($peserta->sudah_audisi) {
            $peserta->hasil_audisi = 'lolos';
        } else {
            $peserta->hasil_audisi = null;
        }

        $peserta->save();

        // ✅ TRIGGER ALUR — sync status & tampil_di_publik
        $service = app(\App\Services\PesertaAlurService::class);
        
        if ($peserta->sudah_audisi) {
            // Lolos audisi → status 'diterima' + tampil_di_publik true
            $service->terima($peserta);
        } else {
            // Batalkan audisi → balikin ke 'menunggu_audisi' + sembunyikan
            $peserta->update([
                'status' => 'menunggu_audisi',
                'tampil_di_publik' => false,
            ]);
        }

        $status = $peserta->sudah_audisi ? 'ditandai lolos audisi' : 'dibatalkan audisinya';

        return redirect()
            ->route('admin.activation', ['event' => $peserta->event_id, 'mode' => 'registrasi'])
            ->with('success', "Peserta berhasil {$status}.");
    }

    /**
     * Transform data peserta untuk view
     */
       private function transform(Peserta $peserta): array
    {
        $pembayaran = $this->pembayaranTerbaik($peserta);

        // Ambil semua pembayaran registrasi peserta
        $semuaPembayaranRegistrasi = $peserta->pembayaranRegistrasi->sortBy('id');

        // Cari per tipe
        $dp1 = $semuaPembayaranRegistrasi->where('tipe', 'dp1')->first();
        $dp2 = $semuaPembayaranRegistrasi->where('tipe', 'dp2')->first();
        $lunas = $semuaPembayaranRegistrasi->where('tipe', 'lunas')->first();

        // Pembayaran registrasi terbaru
        $pembayaranRegistrasi = $semuaPembayaranRegistrasi->sortByDesc('id')->first();

        // Hitung sisa pembayaran DP
        $sisaPembayaranDp = null;
        if ($peserta->event
            && $peserta->event->harga_registrasi
            && $dp1
            && $dp1->status_midtrans === 'settlement') {
            $sisaPembayaranDp = max(0, $peserta->event->harga_registrasi - $dp1->jumlah);
        }

        $nominalUnik = null;
        $kodeUnik = null;
        if ($pembayaran && $pembayaran->kode_unik) {
            $kodeUnik = $pembayaran->kode_unik;
            $nominalUnik = 'Rp ' . number_format($pembayaran->jumlah ?? 0, 0, ',', '.');
        }

        $statusAkun = 'menunggu';
        if ($pembayaran && $pembayaran->status_midtrans === 'settlement') {
            $statusAkun = 'aktif';
        }

        return [
            'id'                 => $peserta->id,
            'no_registrasi'      => 'PPA-' . date('Y') . '-' . str_pad($peserta->id, 3, '0', STR_PAD_LEFT),
            'nama'               => $peserta->namaTampil(),
            'email'              => $peserta->emailTampil(),
            'statusAkun'         => $statusAkun,
            'nominal_unik'       => $nominalUnik,
            'kode_unik'          => $kodeUnik,
            'pembayaran'         => $pembayaran,
            'sudahAudisi'        => (bool) $peserta->sudah_audisi,
            'tanggalAudisi'      => $peserta->tanggal_audisi,
            'statusRegistrasi'   => $peserta->status_registrasi ?? 'belum',
            'pembayaranRegistrasi' => $pembayaranRegistrasi,
            'dp1'                => $dp1,               
            'dp2'                => $dp2,               
            'lunas'              => $lunas,             
            'sisaPembayaranDp'   => $sisaPembayaranDp,
            'tampilDiPublik'     => (bool) ($peserta->tampil_di_publik ?? true),
        ];
    }

    /**
     * POST /admin/registrasi/pembayaran
     * Input pembayaran REGISTRASI manual (offline/tunai/transfer) oleh admin,
     * dipanggil dari tab "Registrasi" di Dashboard.
     *
     * NOTE: sesuaikan nama kolom di bawah (jenis, metode, dsb.) kalau nama
     * kolom aslimu di tabel pembayaran berbeda.
     */
    public function simpanPembayaranRegistrasi(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'peserta_id'    => ['required', 'exists:peserta_event,id'],
            'jumlah_bayar'  => ['required', 'integer', 'min:1'],
            'metode'        => ['required', 'string'],
            'tanggal_bayar' => ['required', 'date'],
        ]);

        $peserta = Peserta::with(['pembayaranRegistrasi', 'event'])->findOrFail($data['peserta_id']);

        $biayaRegistrasi = (int) ($peserta->event->harga_registrasi ?? 0);

        $sudahDibayar = $peserta->pembayaranRegistrasi
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah');

        $jumlahBayar  = (int) $data['jumlah_bayar'];
        $totalSetelah = $sudahDibayar + $jumlahBayar;

        // Tentukan tipe cicilan: dp1 (pembayaran pertama/sebagian),
        // dp2 (pelunasan setelah dp1), atau lunas (langsung penuh sekali bayar).
        if ($totalSetelah >= $biayaRegistrasi && $biayaRegistrasi > 0) {
            $tipe = $sudahDibayar > 0 ? 'dp2' : 'lunas';
        } else {
            $tipe = $sudahDibayar > 0 ? 'dp2' : 'dp1';
        }

        $peserta->pembayaranRegistrasi()->create([
            'jenis'             => 'registrasi',
            'tipe'              => $tipe,
            'jumlah'            => $jumlahBayar,
            'metode'            => $data['metode'],
            'tanggal_bayar'     => $data['tanggal_bayar'],
            'status_midtrans'   => 'settlement',
            'midtrans_order_id' => 'MANUAL-' . strtoupper(uniqid()),
        ]);

        // ✅ Sinkronkan status_registrasi di tabel peserta — inilah field yang
        // dibaca halaman Aktivasi (mode registrasi) untuk badge status.
        $peserta->status_registrasi = ($totalSetelah >= $biayaRegistrasi && $biayaRegistrasi > 0)
            ? 'lunas'
            : 'dp';
        $peserta->save();

        return redirect()
            ->back()
            ->with('success', 'Pembayaran registrasi ' . $peserta->namaTampil() . ' berhasil disimpan.');
    }

    /**
     * Ambil pembayaran terbaik (pendaftaran)
     */
    private function pembayaranTerbaik(Peserta $peserta)
    {
        $pembayaran = $peserta->pembayaran ?? collect();

        return $pembayaran->where('status_midtrans', 'settlement')->sortByDesc('id')->first()
            ?? $pembayaran->sortByDesc('id')->first();
    }
            /**
     * POST /admin/activation/{peserta}/toggle-tampil
     * Toggle visibility 1 peserta — return JSON (AJAX)
     */
    public function toggleTampil(Request $request, Peserta $peserta): \Illuminate\Http\JsonResponse
    {
        $peserta->tampil_di_publik = ! $peserta->tampil_di_publik;
        $peserta->save();

        return response()->json([
            'success'         => true,
            'tampilDiPublik'  => (bool) $peserta->tampil_di_publik,
            'message'         => $peserta->namaTampil() . ' ' . ($peserta->tampil_di_publik ? 'ditampilkan' : 'disembunyikan') . ' di halaman publik.',
        ]);
    }

        public function toggleTampilSemua(Request $request, Event $event): RedirectResponse
    {
        $show = $request->boolean('tampil');
        $mode = $request->input('mode', 'registrasi');

        Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->update(['tampil_di_publik' => $show]);

        return redirect()
            ->route('admin.activation', ['event' => $event->id, 'mode' => $mode])
            ->with(
                'success',
                $show
                    ? 'Semua peserta berhasil ditampilkan di halaman publik.'
                    : 'Semua peserta berhasil disembunyikan dari halaman publik.'
            );
    }
}