<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\JawabanPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    // POST /event/{id}/daftar
    public function store(Request $request, $id)
    {
        // 1. Ambil ID pertanyaan dari DB berdasarkan event_id di URL
        $questionIds = Pertanyaan::where('event_id', $id)->pluck('id')->toArray();

        // 2. Validasi input
        $data = $request->validate([
            'jawaban' => ['required', 'array'],
            'jawaban.*.pertanyaan_id' => ['required', 'integer', Rule::in($questionIds)],
            'jawaban.*.jawaban' => ['nullable', 'string'],
        ]);

        $answeredIds = collect($data['jawaban'])->pluck('pertanyaan_id');
        $requiredIds = Pertanyaan::where('event_id', $id)->where('wajib', true)->pluck('id');
        
        if ($requiredIds->diff($answeredIds)->isNotEmpty()) {
            return response()->json(['message' => 'Semua pertanyaan wajib harus diisi.'], 422);
        }

        // 3. Simpan data peserta & jawaban
        $peserta = DB::transaction(function () use ($id, $request, $data) {
            $peserta = Peserta::create([
                'event_id' => $id,
                'user_id' => $request->user()?->id,
                'status' => 'menunggu_pembayaran',
            ]);

            foreach ($data['jawaban'] as $item) {
                JawabanPeserta::create([
                    'peserta_id' => $peserta->id,
                    'pertanyaan_id' => $item['pertanyaan_id'],
                    'jawaban' => $item['jawaban'] ?? null,
                ]);
            }

            return $peserta;
        });

        return response()->json($peserta->load('jawabans'), 201);
    }

    // GET /pendaftaran/saya
    public function milikSaya(Request $request)
    {
        $pesertas = Peserta::with(['event', 'pembayaran'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }
}