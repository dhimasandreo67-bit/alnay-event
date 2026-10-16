<?php

namespace App\Http\Controllers;

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


class EventController extends Controller
{
    // GET /event
    public function index(Request $request)
    {
        $query = DB::table('event');

        if ($request->has('status')) {
            $today = now()->toDateString();
            match ($request->status) {
                'akan_datang' => $query->where('tanggal_mulai', '>', $today),
                'berlangsung' => $query->where('tanggal_mulai', '<=', $today)
                                        ->where('tanggal_selesai', '>=', $today),
                'selesai' => $query->where('tanggal_selesai', '<', $today),
                default => null,
            };
        }

        return response()->json($query->orderBy('tanggal_mulai', 'desc')->get());
    }

    // GET /event/{id}
    public function show($id)
    {
        $event = DB::table('event')->find($id);

        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        return response()->json($event);
    }

    // POST /event
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'banner' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'harga_per_vote' => 'required|numeric|min:0.01',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'mode_penilaian' => 'required|string',
        ]);

        // Flag default — jangan biarkan form override
        $data['voting_aktif'] = false;
        $data['tampilkan_hasil_voting'] = false;

        // Flag pendaftaran dibuka — bisa diisi dari form (default: true)
        $data['pendaftaran_dibuka'] = $request->boolean('pendaftaran_dibuka', true);

        $id = DB::table('event')->insertGetId($data);

        return response()->json(DB::table('event')->find($id), 201);
    }

    // PUT /event/{id}
    public function update(Request $request, $id)
    {
        $event = DB::table('event')->find($id);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'banner' => 'nullable|string',
            'syarat_ketentuan' => 'nullable|string',
            'biaya_pendaftaran' => 'sometimes|required|numeric|min:0',
            'harga_per_vote' => 'sometimes|required|numeric|min:0.01',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'mode_penilaian' => 'sometimes|required|string',
        ]);

        // Handle pendaftaran_dibuka — boolean dari form
        if ($request->has('pendaftaran_dibuka')) {
            $data['pendaftaran_dibuka'] = $request->boolean('pendaftaran_dibuka');
        }

        DB::table('event')->where('id', $id)->update($data);

        return response()->json(DB::table('event')->find($id));
    }

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

    // PATCH /event/{id}/voting
    public function toggleVoting($id)
    {
        $event = DB::table('event')->find($id);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        DB::table('event')->where('id', $id)->update([
            'voting_aktif' => ! $event->voting_aktif,
        ]);

        return response()->json(DB::table('event')->find($id));
    }

    // PATCH /event/{id}/tampilkan-hasil
    public function toggleTampilkanHasil($id)
    {
        $event = DB::table('event')->find($id);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        DB::table('event')->where('id', $id)->update([
            'tampilkan_hasil_voting' => ! $event->tampilkan_hasil_voting,
        ]);

        return response()->json(DB::table('event')->find($id));
    }
}