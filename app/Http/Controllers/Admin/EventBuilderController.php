<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller ini khusus buat panel admin session-based (WebAuthController,
 * EnsureAdminSession). Sengaja dipisah dari App\Http\Controllers\Admin\
 * EventController yang sudah ada, karena controller itu dipakai untuk
 * jalur API/Sanctum (POST /event, PUT /event/:id, dst) yang punya
 * method store()/update() dengan tujuan berbeda.
 */
class EventBuilderController extends Controller
{
    /**
     * GET /admin/events/create
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * POST /admin/events
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'banner' => ['nullable', 'image', 'max:5120'],
            'tanggal_mulai' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'waktu_selesai' => ['nullable', 'date_format:H:i'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'batas_pendaftaran_tanggal' => ['nullable', 'date', 'before_or_equal:tanggal_mulai'],
            'batas_pendaftaran_waktu' => ['nullable', 'date_format:H:i'],
            'kuota_peserta' => ['nullable', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('banner')) {
            // Banner ditampilkan ke publik (halaman pratinjau event), jadi
            // disk 'public' + storage:link, BEDA dari file jawaban peserta
            // yang sengaja disimpan private di disk 'local'.
            $data['banner'] = $request->file('banner')->store('event-banner', 'public');
        }

        // biaya_pendaftaran, harga_per_vote, mode_penilaian belum dikumpulkan
        // di step ini (ada di step "Pengaturan Event & Voting"), jadi diisi
        // default dulu supaya tidak melanggar constraint NOT NULL di DB.
        $data['biaya_pendaftaran'] = 0;
        $data['harga_per_vote'] = 0;
        $data['mode_penilaian'] = 'voting';

        $event = Event::create($data);

        return redirect()
            ->route('admin.form-builder', $event)
            ->with('success', 'Event berhasil dibuat. Lanjut atur form pendaftarannya.');
    }
}