<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * POST /event (admin)
     * Buat event baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'banner' => ['nullable', 'string'],
            'syarat_ketentuan' => ['nullable', 'string'],
            'biaya_pendaftaran' => ['required', 'numeric', 'min:0'],
            'harga_per_vote' => ['required', 'numeric', 'min:0'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'mode_penilaian' => ['required', 'in:voting,juri'],
        ]);

        // TODO: simpan banner upload kalau berupa file, bukan string path

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    /**
     * PUT /event/:id (admin)
     * Update detail event.
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'nama' => ['sometimes', 'string', 'max:255'],
            'banner' => ['nullable', 'string'],
            'syarat_ketentuan' => ['nullable', 'string'],
            'biaya_pendaftaran' => ['sometimes', 'numeric', 'min:0'],
            'harga_per_vote' => ['sometimes', 'numeric', 'min:0'],
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_selesai' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'mode_penilaian' => ['sometimes', 'in:voting,juri'],
        ]);

        $event->update($data);

        return response()->json($event);
    }

    /**
     * DELETE /event/:id (admin)
     * Hapus event.
     */
    public function destroy(Event $event)
    {
        // TODO: pertimbangkan larangan hapus event yang sudah punya peserta
        $event->delete();

        return response()->json(['message' => 'Event dihapus']);
    }

    /**
     * PATCH /event/:id/voting (admin)
     * Aktifkan/nonaktifkan sistem voting.
     */
    public function toggleVoting(Event $event)
    {
        $event->update(['voting_aktif' => ! $event->voting_aktif]);

        return response()->json($event);
    }

    /**
     * PATCH /event/:id/tampilkan-hasil (admin)
     * Toggle tampil/sembunyikan leaderboard persentase suara ke publik.
     */
    public function toggleTampilkanHasil(Event $event)
    {
        $event->update(['tampilkan_hasil_voting' => ! $event->tampilkan_hasil_voting]);

        return response()->json($event);
    }
}