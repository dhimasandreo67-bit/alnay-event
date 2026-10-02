<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class NavigationController extends Controller
{
    public function page(string $page, ?Event $event = null)
    {
        $pages = [
            'event-create' => ['Buat Event Baru', 'Atur identitas, jadwal, biaya, dan mode penilaian event.'],
            'registrations' => ['Verifikasi Pendaftaran Peserta', 'Tinjau data peserta dan putuskan status pendaftaran.'],
            'activation' => ['Aktivasi Akun Peserta', 'Kelola akun dan akses peserta yang sudah lolos verifikasi.'],
            'voting-settings' => ['Pengaturan Event & Voting', 'Atur voting, harga per suara, dan publikasi hasil.'],
            'voters' => ['Daftar Voter', 'Lihat riwayat voter dan transaksi suara yang tervalidasi.'],
            'leaderboard' => ['Leaderboard Suara Voting', 'Pantau perolehan suara peserta secara real time.'],
        ];

        abort_unless(isset($pages[$page]), 404);
        $event ??= Event::query()->latest('id')->first();

        return view('admin.navigation-page', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'page' => $page,
            'pageRoute' => match ($page) {
                'event-create' => 'admin.events.create',
                'registrations' => 'admin.registrations',
                'activation' => 'admin.activation',
                'voting-settings' => 'admin.voting.settings',
                'voters' => 'admin.voters',
                'leaderboard' => 'admin.leaderboard',
            },
            'pageTitle' => $pages[$page][0],
            'pageDescription' => $pages[$page][1],
            'questions' => $event?->pertanyaans()->get() ?? collect(),
        ]);
    }

    public function index(Request $request)
    {
        // ✅ Baca event aktif dari session (yang sudah dipilih di halaman Kelola Event)
        $eventId = session('active_event_id');
        $event = $eventId ? Event::find($eventId) : null;
        $event ??= Event::query()->latest('id')->first();

        // ✅ Hitung statistik HANYA untuk event aktif
        $totalPendaftar = 0;
        $menungguReview = 0;
        $diterima = 0;
        $ditolak = 0;
        $perluRevisi = 0;
        $pesertas = collect();

        if ($event) {
            $pesertas = Peserta::where('event_id', $event->id)
                ->with(['user', 'jawabans.pertanyaan'])
                ->latest('id')
                ->get();

            $totalPendaftar = $pesertas->count();
            $menungguReview = $pesertas->where('status', 'menunggu_review')->count();
            $diterima = $pesertas->where('status', 'diterima')->count();
            $ditolak = $pesertas->where('status', 'ditolak')->count();
            $perluRevisi = $pesertas->where('status', 'revisi')->count();
        }

        return view('admin.dashboard', [
            'event' => $event,
            'pesertas' => $pesertas,
            'totalPendaftar' => $totalPendaftar,
            'menungguReview' => $menungguReview,
            'diterima' => $diterima,
            'ditolak' => $ditolak,
            'perluRevisi' => $perluRevisi,
        ]);
    }
}
