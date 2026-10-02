<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
        /**
     * GET /admin/events
     * Tampilkan daftar event dengan fitur "Event Aktif" yang tersimpan di session
     */
    public function index(Request $request)
    {
        // 1. Jika ada parameter ?event=X di URL, simpan ID-nya ke session
        if ($request->has('event')) {
            session(['active_event_id' => $request->event]);
        }

        // 2. Ambil event aktif: prioritaskan dari session, fallback ke event terbaru
        $activeEventId = session('active_event_id');
        $activeEvent = $activeEventId ? Event::find($activeEventId) : null;
        $activeEvent ??= Event::query()->latest('id')->first();

        $events = Event::orderBy('id', 'desc')->paginate(10);
        $eventList = Event::orderBy('id', 'desc')->get(['id', 'nama']);

        // 3. Kirim ke view (variabel 'event' ini yang akan dibaca oleh Sidebar)
        return view('admin.events.index', [
            'events'      => $events,
            'activeEvent' => $activeEvent,
            'eventList'   => $eventList,
            'event'       => $activeEvent, 
        ]);
    }

    // 2. Form Edit Event
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * POST /admin/events (admin, web session)
     * Buat event baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'                      => 'required|string|max:255',
            'deskripsi'                 => 'nullable|string',
            'biaya_pendaftaran'         => 'nullable|numeric|min:0',
            'harga_per_vote'            => 'nullable|numeric|min:0',
            'tanggal_mulai'             => 'required|date',
            'waktu_mulai'               => 'nullable',
            'tanggal_selesai'           => 'nullable|date',
            'waktu_selesai'             => 'nullable',
            'pembukaan_pendaftaran_tanggal'     => 'nullable|date',
            'pembukaan_pendaftaran_waktu'       => 'nullable',
            'lokasi'                    => 'required|string',
            'batas_pendaftaran_tanggal' => 'nullable|date',
            'batas_pendaftaran_waktu'   => 'nullable',
            'kuota_peserta'             => 'nullable|integer',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'status'                    => 'required|in:akan_datang,sedang_berlangsung,telah_berakhir',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        // 3 kolom ini NOT NULL tanpa default di database, dan form "Buat
        // Event Baru" belum mengumpulkannya (baru diisi di step "Pengaturan
        // Event & Voting"). Tanpa baris ini, Event::create() di bawah bakal
        // gagal dengan integrity constraint violation.
        $validated['biaya_pendaftaran'] ??= 0;
        $validated['harga_per_vote'] ??= 0;
        $validated['mode_penilaian'] ??= 'voting';

        $event = Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event "'.$event->nama.'" berhasil dipublikasikan!');
    }

    /**
     * PUT /admin/events/:id (admin)
     * Update detail event.
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'nama'                      => 'required|string|max:255',
            'deskripsi'                 => 'nullable|string',
            'syarat_ketentuan'          => 'nullable|string',
            'banner'                    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'biaya_pendaftaran'         => 'nullable|numeric|min:0',
            'harga_per_vote'            => 'nullable|numeric|min:0',
            'tanggal_mulai'             => 'required|date',
            'waktu_mulai'               => 'nullable',
            'tanggal_selesai'           => 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_selesai'             => 'nullable',
            'pembukaan_pendaftaran_tanggal'     => 'nullable|date',
            'pembukaan_pendaftaran_waktu'       => 'nullable',
            'lokasi'                    => 'nullable|string|max:255',
            'batas_pendaftaran_tanggal' => 'nullable|date',
            'batas_pendaftaran_waktu'   => 'nullable',
            'kuota_peserta'             => 'nullable|integer|min:1',
            'status'                    => 'required|in:akan_datang,sedang_berlangsung,telah_berakhir',
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner && Storage::disk('public')->exists($event->banner)) {
                Storage::disk('public')->delete($event->banner);
            }
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil diperbarui!');
    }

    /**
     * DELETE /admin/events/:id (admin)
     * Hapus event.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->banner && Storage::disk('public')->exists($event->banner)) {
            Storage::disk('public')->delete($event->banner);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
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

    /**
 * PATCH /admin/events/{event}/update-pending-payments
 * Update semua tagihan pending ke harga baru
 */
    public function updatePendingPayments(Request $request, Event $event)
    {
        $hargaBaru = (int) $request->input('biaya_pendaftaran', $event->biaya_pendaftaran);
        
        // Update semua tagihan pending
        $updated = \App\Models\PembayaranEvent::whereHas('peserta', function ($q) use ($event) {
                $q->where('event_id', $event->id);
            })
            ->where('status_midtrans', '!=', 'settlement')
            ->update([
                'tarif_dasar' => $hargaBaru,
                'jumlah' => \DB::raw("{$hargaBaru} + CAST(kode_unik AS UNSIGNED)"),
            ]);

        return redirect()->back()->with('success', 
            "{$updated} tagihan pending berhasil diupdate ke harga baru Rp " . number_format($hargaBaru, 0, ',', '.')
        );
    }

    public function toggleStatus(Request $request, Event $event)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        // Mengubah nilai is_active secara langsung
        $event->is_active = $request->boolean('is_active');
        
        // Simpan perubahan ke MySQL
        $event->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$event->is_active,
            'message' => $event->is_active ? 'Pendaftaran berhasil dibuka.' : 'Pendaftaran berhasil ditutup.'
        ]);
    }
}