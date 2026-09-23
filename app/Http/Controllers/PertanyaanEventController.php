<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PertanyaanEventController extends Controller
{
    // GET /event/{id}/pertanyaan
    public function index($eventId)
    {
        $event = DB::table('event')->find($eventId);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        $pertanyaan = DB::table('pertanyaan_event')
            ->where('event_id', $eventId)
            ->orderBy('urutan')
            ->get();

        return response()->json($pertanyaan);
    }

    // POST /event/{id}/pertanyaan
    public function store(Request $request, $eventId)
    {
        $event = DB::table('event')->find($eventId);
        if (! $event) {
            return response()->json(['message' => 'Event tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'label' => 'required|string|max:255',
            'tipe_input' => 'required|string|in:teks_pendek,paragraf,pilihan_tunggal,pilihan_ganda,upload_file',
            'pilihan_jawaban' => 'nullable|string',
            'wajib' => 'boolean',
            'urutan' => 'nullable|integer|min:0',
        ]);

        $data['event_id'] = $eventId;
        $data['wajib'] = $data['wajib'] ?? false;

        if (! isset($data['urutan'])) {
            $urutanTerakhir = DB::table('pertanyaan_event')
                ->where('event_id', $eventId)
                ->max('urutan');
            $data['urutan'] = ($urutanTerakhir ?? 0) + 1;
        }

        $id = DB::table('pertanyaan_event')->insertGetId($data);

        return response()->json(DB::table('pertanyaan_event')->find($id), 201);
    }

    // PUT /pertanyaan/{id}
    public function update(Request $request, $id)
    {
        $pertanyaan = DB::table('pertanyaan_event')->find($id);
        if (! $pertanyaan) {
            return response()->json(['message' => 'Pertanyaan tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'label' => 'sometimes|required|string|max:255',
            'tipe_input' => 'sometimes|required|string|in:teks_pendek,paragraf,pilihan_tunggal,pilihan_ganda,upload_file',
            'pilihan_jawaban' => 'nullable|string',
            'wajib' => 'sometimes|boolean',
            'urutan' => 'sometimes|integer|min:0',
        ]);

        DB::table('pertanyaan_event')->where('id', $id)->update($data);

        return response()->json(DB::table('pertanyaan_event')->find($id));
    }

    // DELETE /pertanyaan/{id}
    public function destroy($id)
    {
        $pertanyaan = DB::table('pertanyaan_event')->find($id);
        if (! $pertanyaan) {
            return response()->json(['message' => 'Pertanyaan tidak ditemukan'], 404);
        }

        $sudahDijawab = DB::table('jawaban_peserta')->where('pertanyaan_id', $id)->exists();
        if ($sudahDijawab) {
            return response()->json([
                'message' => 'Pertanyaan tidak bisa dihapus karena sudah ada jawaban peserta',
            ], 409);
        }

        DB::table('pertanyaan_event')->where('id', $id)->delete();

        return response()->json(['message' => 'Pertanyaan dihapus']);
    }
}
