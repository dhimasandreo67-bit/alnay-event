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
                ->with(['user', 'pembayaran', 'pembayaranRegistrasi', 'jawabans.pertanyaan']);

            // Mode Registrasi — cuma tampil yang sudah audisi
            if ($mode === 'registrasi') {
                $query->where('sudah_audisi', true);
            }

            $finalis = $query->latest('id')->get()
                ->map(fn (Peserta $peserta) => $this->transform($peserta));
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
        $peserta->save();

        $status = $peserta->sudah_audisi ? 'ditandai sudah audisi' : 'dibatalkan audisinya';

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