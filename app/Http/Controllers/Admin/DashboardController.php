<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
     public function index(Request $request, $eventId = null)
    {
        $event = null;
        $adminId = $request->session()->get('admin_id');

        // 1. Prioritas URL
        if ($eventId) {
            $event = Event::find($eventId);
            if ($event) {
                session(['active_event_id' => $event->id]);
                if ($adminId) {
                    AkunStaf::where('id', $adminId)->update(['last_active_event_id' => $event->id]);
                }
            }
        }

        // 2. Prioritas session
        if (!$event) {
            $activeEventId = session('active_event_id');
            $event = $activeEventId ? Event::find($activeEventId) : null;
        }

        // 3. Prioritas DB
        if (!$event && $adminId) {
            $admin = AkunStaf::find($adminId);
            if ($admin && $admin->last_active_event_id) {
                $event = Event::find($admin->last_active_event_id);
                if ($event) {
                    session(['active_event_id' => $event->id]);
                }
            }
        }

        // 4. Fallback
        $event ??= Event::query()->latest('id')->first();

        // ============================================
        // STATISTIK (HANYA PESERTA BIASA: kandidat_voting = 0)
        // ============================================
        $statistik = $event
            ? Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', false)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
            : collect();

        $totalPendaftar = $statistik->sum();
        $diterima       = $statistik->get('diterima', 0);

        // ============================================
        // STATISTIK AUDISI
        // ============================================
        $audisiQuery = $event
        ? Peserta::where('event_id', $event->id)
            ->where('kandidat_voting', false)
            ->whereIn('status', ['menunggu_audisi', 'diterima', 'ditolak'])
            ->whereHas('pembayaran', fn ($q) => $q->where('status_midtrans', 'settlement'))
        : null;

        $totalAudisi = $audisiQuery ? (clone $audisiQuery)->count() : 0;

        $audisiStatistik = $audisiQuery
            ? (clone $audisiQuery)
                ->selectRaw('hasil_audisi, COUNT(*) as total')
                ->groupBy('hasil_audisi')
                ->pluck('total', 'hasil_audisi')
            : collect();

        $lolosAudisi      = $audisiStatistik->get('lolos', 0);
        $tidakLolosAudisi = $audisiStatistik->get('tidak_lolos', 0);
        $belumDinilai     = $totalAudisi - $lolosAudisi - $tidakLolosAudisi;

        // ============================================
        // STATISTIK KANDIDAT VOTING (KHUSUS)
        // ============================================
        $kandidatQuery = $event
            ? Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', true)
            : null;

        $totalKandidatVoting = $kandidatQuery ? (clone $kandidatQuery)->count() : 0;

        // ============================================
        // ✅ STATISTIK REGISTRASI (peserta pendaftaran yang SUDAH DITERIMA,
        // tahap bayar biaya registrasi — bukan kandidat voting)
        // ============================================
        $registrasiQuery = $event
            ? Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', false)
                ->where('status', 'diterima')
                ->where('hasil_audisi', 'lolos')
            : null;

        $totalRegistrasi = $registrasiQuery ? (clone $registrasiQuery)->count() : 0;

        $registrasiStatistik = $registrasiQuery
            ? (clone $registrasiQuery)
                ->selectRaw('status_registrasi, COUNT(*) as total')
                ->groupBy('status_registrasi')
                ->pluck('total', 'status_registrasi')
            : collect();

        $registrasiLunas = $registrasiStatistik->get('lunas', 0);
        $registrasiDp    = $registrasiStatistik->get('dp', 0);
        $registrasiBelum = $totalRegistrasi - $registrasiLunas - $registrasiDp;

        // ============================================
        // FILTER & SEARCH LOGIC
        // ============================================
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $search  = $request->input('search');

        // ============================================
        // 1. QUERY PENDAFTARAN / REGULER
        // ============================================
        $pesertaQuery = $event
            ? Peserta::where('event_id', $event->id)
                ->where('kandidat_voting', '!=', 1)
                ->with(['user', 'event', 'jawabans.pertanyaan', 'pembayaran'])
            : Peserta::query();

        $pendaftaranFilter = $request->input('pendaftaran_filter');
        if ($pendaftaranFilter === 'settlement') {
            $pesertaQuery->whereHas('pembayaran', function($q) {
                $q->where('status_midtrans', 'settlement');
            });
        } elseif ($pendaftaranFilter === 'pending') {
            $pesertaQuery->whereHas('pembayaran', function($q) {
                $q->where('status_midtrans', 'pending');
            });
        } elseif ($pendaftaranFilter === 'belum_bayar') {
            $pesertaQuery->whereDoesntHave('pembayaran', function($q) {
                $q->whereIn('status_midtrans', ['settlement', 'pending']);
            });
        }

        // ============================================
        // 2. QUERY AUDISI
        // ============================================
        $pesertaAudisiQuery = $audisiQuery
            ? (clone $audisiQuery)
                ->with([
                    'user',
                    'jawabans.pertanyaan',
                    'pembayaran' => fn ($q) => $q->where('status_midtrans', 'settlement')->latest('id'),
                ])
                ->latest('id')
            : null;

        $audisiFilter = $request->input('audisi_filter');
        if ($pesertaAudisiQuery) {
            if ($audisiFilter && in_array($audisiFilter, ['lolos', 'tidak_lolos'])) {
                $pesertaAudisiQuery->where('hasil_audisi', $audisiFilter);
            } elseif ($audisiFilter === 'belum_dinilai') {
                $pesertaAudisiQuery->whereNull('hasil_audisi');
            }
        }

        // ============================================
        // 3. QUERY KANDIDAT VOTING (DENGAN FILTER PEMBAYARAN AMAN)
        // ============================================
        $kandidatVotingQuery = $kandidatQuery
            ? (clone $kandidatQuery)
                ->with(['user', 'pembayaran'])
                ->latest('id')
            : null;

        // ============================================
        // ✅ 4. QUERY REGISTRASI (peserta diterima, belum/sedang/lunas bayar registrasi)
        // ============================================
        $pesertaRegistrasiQuery = $registrasiQuery
            ? (clone $registrasiQuery)
                ->with(['user', 'event', 'pembayaranRegistrasi'])
                ->latest('id')
            : null;

        $registrasiFilter = $request->input('registrasi_filter');
        if ($pesertaRegistrasiQuery && in_array($registrasiFilter, ['lunas', 'dp', 'belum'], true)) {
            $pesertaRegistrasiQuery->where('status_registrasi', $registrasiFilter);
        }

        $kandidatFilter = $request->input('kandidat_filter');
        if ($kandidatVotingQuery && $kandidatFilter) {
            $totalBiayaEvent = $event->biaya_pendaftaran ?? 0;
            if ($kandidatFilter === 'lunas') {
                $kandidatVotingQuery->whereHas('pembayaran', function($q) use ($totalBiayaEvent) {
                    $q->where('status_midtrans', 'settlement')
                      ->where('jumlah', '>=', $totalBiayaEvent);
                });
            } elseif ($kandidatFilter === 'dp') {
                $kandidatVotingQuery->whereHas('pembayaran', function($q) use ($totalBiayaEvent) {
                    $q->where('jumlah', '>', 0)
                      ->where('jumlah', '<', $totalBiayaEvent);
                });
            } elseif ($kandidatFilter === 'belum_bayar') {
                $kandidatVotingQuery->where(function($q) use ($totalBiayaEvent) {
                    $q->whereDoesntHave('pembayaran')
                      ->orWhereHas('pembayaran', function($subQ) {
                          $subQ->where('jumlah', '<=', 0);
                      });
                });
            }
        }

        // ============================================
        // TERAPKAN GLOBAL SEARCH KE SEMUA QUERY
        // ============================================
        if ($search) {
            $searchClosure = function ($q) use ($search) {
                $q->where('nama_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('nama', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            };

            $pesertaQuery->where($searchClosure);
            if ($pesertaAudisiQuery) $pesertaAudisiQuery->where($searchClosure);
            if ($kandidatVotingQuery) $kandidatVotingQuery->where($searchClosure);
            if ($pesertaRegistrasiQuery) $pesertaRegistrasiQuery->where($searchClosure);
        }

        // ============================================
        // EKSEKUSI PAGINATION (HANYA SEKALI DI SINI)
        // ============================================
        $pesertas = $pesertaQuery->latest('id')->paginate($perPage)->appends($request->query());

        $pesertaAudisi = $pesertaAudisiQuery
            ? $pesertaAudisiQuery->paginate($perPage, ['*'], 'audisi_page')->appends($request->query())
            : collect();

        $kandidatVoting = $kandidatVotingQuery
            ? $kandidatVotingQuery->paginate($perPage, ['*'], 'kandidat_page')->appends($request->query())
            : collect();

        $pesertaRegistrasi = $pesertaRegistrasiQuery
            ? $pesertaRegistrasiQuery->paginate($perPage, ['*'], 'registrasi_page')->appends($request->query())
            : collect();

        // ============================================
        // DATA LAINNYA
        // ============================================
        $totalSuara = $event
            ? Voting::where('event_id', $event->id)->where('status_midtrans', 'settlement')->sum('jumlah_vote')
            : 0;

        $lastUpdate = Schema::hasColumn('peserta_event', 'updated_at')
            ? ($event ? Peserta::where('event_id', $event->id)->max('updated_at') : null)
            : null;

        $events = Event::query()->latest('id')->get(['id', 'nama']);

        // ✅ Ambil pertanyaan untuk form builder di modal
        $pertanyaans = $event
            ? $event->pertanyaans()
                ->whereNotIn('tipe_input', ['heading', 'subheading'])
                ->orderBy('urutan')
                ->get()
            : collect();

        return view('admin.dashboard', [
            'event'              => $event,
            'events'             => $events,
            'pesertas'           => $pesertas,
            'totalPendaftar'     => $totalPendaftar,
            'diterima'           => $diterima,
            'totalSuara'         => $totalSuara,
            'lastUpdate'         => $lastUpdate,
            'pesertaAudisi'      => $pesertaAudisi,
            'totalAudisi'        => $totalAudisi,
            'lolosAudisi'        => $lolosAudisi,
            'tidakLolosAudisi'   => $tidakLolosAudisi,
            'belumDinilai'       => $belumDinilai,
            'kandidatVoting'     => $kandidatVoting,
            'totalKandidatVoting'=> $totalKandidatVoting,
            'pertanyaans'        => $pertanyaans,
            // ✅ Data tab Registrasi
            'pesertaRegistrasi'  => $pesertaRegistrasi,
            'totalRegistrasi'    => $totalRegistrasi,
            'registrasiLunas'    => $registrasiLunas,
            'registrasiDp'       => $registrasiDp,
            'registrasiBelum'    => $registrasiBelum,
        ]);
    }
}