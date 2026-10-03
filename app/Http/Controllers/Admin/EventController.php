<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // GET /admin/events
        // GET /admin/events
        // GET /admin/events
    public function index(Request $request)
    {
                $eventList = Event::query()->latest('id')->paginate(15);

        // Event aktif — dari query param ?event= atau default terbaru
        $activeEventId = $request->query('event');
        $activeEvent = $activeEventId
            ? Event::find($activeEventId)
            : $eventList->first();

        return view('admin.events.index', [
            'eventList'   => $eventList,
            'events'      => $eventList,   // alias
            'activeEvent' => $activeEvent,
            'event'       => $activeEvent, // fallback untuk view lama
        ]);
    }

    // GET /admin/events/{id}/edit
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.events.edit', compact('event'));
    }

    // GET /admin/events/create
    public function create()
    {
        return view('admin.events.create');
    }

    // POST /admin/events
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                      => 'required|string|max:255',
            'deskripsi'                 => 'nullable|string',
            'syarat_ketentuan'          => 'nullable|string',
            'biaya_pendaftaran'         => 'nullable|numeric|min:0',
            'harga_per_vote'            => 'nullable|numeric|min:0',
            'tanggal_mulai'             => 'required|date',
            'waktu_mulai'               => 'nullable',
            'tanggal_selesai'           => 'nullable|date',
            'waktu_selesai'             => 'nullable',
            'lokasi'                    => 'required|string',
            'batas_pendaftaran_tanggal' => 'nullable|date',
            'batas_pendaftaran_waktu'   => 'nullable',
            'kuota_peserta'             => 'nullable|integer',
            'banner'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        // 3 kolom ini NOT NULL tanpa default di database
        $validated['biaya_pendaftaran'] ??= 0;
        $validated['harga_per_vote'] ??= 0;
        $validated['mode_penilaian'] ??= 'voting';

        $event = Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "' . $event->nama . '" berhasil dipublikasikan!');
    }

    // PUT /admin/events/{id}
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama'                      => 'required|string|max:255',
            'deskripsi'                 => 'nullable|string',
            'syarat_ketentuan'          => 'nullable|string',
            'banner'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status'                    => 'required|in:akan_datang,sedang_berlangsung,telah_berakhir',
            'biaya_pendaftaran'         => 'nullable|numeric|min:0',
            'harga_per_vote'            => 'nullable|numeric|min:0',
            'tanggal_mulai'             => 'required|date',
            'waktu_mulai'               => 'nullable',
            'tanggal_selesai'           => 'nullable|date',
            'waktu_selesai'             => 'nullable',
            'lokasi'                    => 'required|string',
            'kuota_peserta'             => 'nullable|integer',
            'pembukaan_pendaftaran_tanggal' => 'nullable|date',
            'pembukaan_pendaftaran_waktu'   => 'nullable',
            'batas_pendaftaran_tanggal' => 'nullable|date',
            'batas_pendaftaran_waktu'   => 'nullable',
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "' . $event->nama . '" berhasil diperbarui!');
    }

    // DELETE /admin/events/{id}
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->banner && Storage::disk('public')->exists($event->banner)) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    // PATCH /admin/events/{event}/voting
    public function toggleVoting(Event $event)
    {
        $event->update([
            'voting_aktif' => ! $event->voting_aktif,
        ]);

        return response()->json([
            'voting_aktif' => $event->voting_aktif,
        ]);
    }

    // PATCH /admin/events/{event}/tampilkan-hasil
    public function toggleTampilkanHasil(Event $event)
    {
        $event->update([
            'tampilkan_hasil_voting' => ! $event->tampilkan_hasil_voting,
        ]);

        return response()->json([
            'tampilkan_hasil_voting' => $event->tampilkan_hasil_voting,
        ]);
    }
}