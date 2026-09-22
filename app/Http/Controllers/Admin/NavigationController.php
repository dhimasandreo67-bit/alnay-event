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
        ]);
    }
}