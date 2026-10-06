<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'biaya_pendaftaran' => 'required|numeric|min:0.01',
            'harga_per_vote' => 'required|numeric|min:0.01',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'mode_penilaian' => 'required|string',
        ]);

        $data['voting_aktif'] = false;
        $data['tampilkan_hasil_voting'] = false;

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
            'biaya_pendaftaran' => 'sometimes|required|numeric|min:0.01',
            'harga_per_vote' => 'sometimes|required|numeric|min:0.01',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after_or_equal:tanggal_mulai',
            'mode_penilaian' => 'sometimes|required|string',
        ]);

        DB::table('event')->where('id', $id)->update($data);

        return response()->json(DB::table('event')->find($id));
    }

    // DELETE /event/{id}
    public function destroy($id)
    {
        $event = DB::table('event')->find($id);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        $punyaPeserta = DB::table('peserta_event')->where('event_id', $id)->exists();
        if ($punyaPeserta) {
            return response()->json([
                'message' => 'Event tidak bisa dihapus karena sudah ada peserta',
            ], 409);
        }

        DB::table('event')->where('id', $id)->delete();

        return response()->json(['message' => 'Event dihapus']);
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
    // Fungsi khusus untuk menampilkan halaman tampilan web Blade
    public function detailWeb($id)
    {
        // 1. Ambil data event dari database
        $event = DB::table('event')->find($id);

        if (! $event) {
            abort(404, 'Event tidak ditemukan');
        }

        // 2. Ambil data peserta/kandidat
        $kandidat = DB::table('peserta_event')
            ->where('event_id', $id)
            ->get();

        // 3. Tampilkan ke file detail-event.blade.php
        return view('detail-event', compact('event', 'kandidat'));
    }
}
