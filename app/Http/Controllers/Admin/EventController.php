<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PembayaranEvent;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * GET /admin/events
     */
    public function index(Request $request)
    {
        // 1. Ambil event dengan paginate — biar view punya hasPages()
        $eventList = Event::query()->latest('id')->paginate(15);

        // 2. Tentukan event aktif — 3 prioritas
        $activeEvent = null;
        $adminId = $request->session()->get('admin_id');

        // Prioritas 1: URL ?event=X
        if ($request->has('event')) {
            $activeEvent = Event::find($request->event);
            if ($activeEvent) {
                session(['active_event_id' => $activeEvent->id]);
                if ($adminId) {
                    AkunStaf::where('id', $adminId)
                        ->update(['last_active_event_id' => $activeEvent->id]);
                }
            }
        }

        // Prioritas 2: Session
        if (!$activeEvent) {
            $activeEventId = session('active_event_id');
            $activeEvent = $activeEventId ? Event::find($activeEventId) : null;
        }

        // Prioritas 3: Database
        if (!$activeEvent && $adminId) {
            $admin = AkunStaf::find($adminId);
            if ($admin && $admin->last_active_event_id) {
                $activeEvent = Event::find($admin->last_active_event_id);
                if ($activeEvent) {
                    session(['active_event_id' => $activeEvent->id]);
                }
            }
        }

        // Fallback: event terbaru
        $activeEvent ??= $eventList->first();

        return view('admin.events.index', [
            'eventList'   => $eventList,      // dropdown + tabel
            'events'      => $eventList,      // alias
            'activeEvent' => $activeEvent,
            'event'       => $activeEvent,
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
                // 3 kolom ini NOT NULL tanpa default di database
        $validated['biaya_pendaftaran'] ??= 0;
        $validated['harga_per_vote'] ??= 0;
        $validated['mode_penilaian'] ??= 'voting';

        // Default pendaftaran dibuka untuk event baru
        $validated['pendaftaran_dibuka'] = 1;

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

        // ✅ PERBAIKAN: Pertahankan nilai lama jika checkbox tidak dikirim
        // Checkbox HTML hanya mengirim value saat DICENTANG.
        // Jika tidak dicentang, field tidak ada di request → $request->has() = false
        if ($request->has('pendaftaran_dibuka')) {
            $validated['pendaftaran_dibuka'] = $request->boolean('pendaftaran_dibuka');
        } else {
            // Pertahankan nilai sebelumnya agar tidak berubah jadi false
            $validated['pendaftaran_dibuka'] = $event->pendaftaran_dibuka;
        }

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
    // DELETE /admin/events/{id}
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        DB::transaction(function () use ($event) {
            $pesertaIds = Peserta::where('event_id', $event->id)->pluck('id');

            // 1. Hapus jawaban peserta
            if ($pesertaIds->isNotEmpty()) {
                JawabanPeserta::whereIn('peserta_id', $pesertaIds)->delete();
                PembayaranEvent::whereIn('peserta_id', $pesertaIds)->delete();
            }

            // 2. Hapus voting
            Voting::where('event_id', $event->id)->delete();

            // 3. Hapus peserta
            Peserta::where('event_id', $event->id)->delete();

            // 4. Hapus pertanyaan
            Pertanyaan::where('event_id', $event->id)->delete();

            // 5. Hapus banner
            if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }

            // 6. Hapus event
            $event->delete();
        });

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event beserta data terkait berhasil dihapus.');
    }

    // PATCH /admin/events/{event}/voting
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

    // PATCH /admin/events/{event}/toggle-pendaftaran
    public function togglePendaftaran(Event $event): JsonResponse
    {
        // Balikkan status pendaftaran_dibuka (true -> false / false -> true)
        $event->pendaftaran_dibuka = ! (bool) $event->pendaftaran_dibuka;
        $event->save();

        return response()->json([
            'success' => true,
            'pendaftaran_dibuka' => $event->pendaftaran_dibuka,
            'message' => $event->pendaftaran_dibuka 
                ? 'Pendaftaran berhasil diaktifkan.' 
                : 'Pendaftaran berhasil dinonaktifkan.',
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

    public function setActive(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:event,id',
        ]);

        \App\Helpers\EventHelper::setActiveEvent($request, $request->event_id);

        return redirect()->back()->with('success', 'Event aktif berhasil diubah.');
    }
}
