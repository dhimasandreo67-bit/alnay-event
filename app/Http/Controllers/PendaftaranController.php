<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    /**
     * POST /event/:id/daftar (pelanggan, login)
     * Daftar jadi peserta, body berisi jawaban sesuai pertanyaan form event.
     */
    public function store(Request $request, Event $event)
    {
        $questionIds = $event->pertanyaans()->pluck('id');
        $data = $request->validate([
            'jawaban' => ['required', 'array'],
            'jawaban.*.pertanyaan_id' => ['required', 'integer', 'in:'.$questionIds->implode(',')],
            'jawaban.*.jawaban' => ['nullable', 'string'],
        ]);

        $answeredIds = collect($data['jawaban'])->pluck('pertanyaan_id');
        $requiredIds = $event->pertanyaans()->where('wajib', true)->pluck('id');
        if ($requiredIds->diff($answeredIds)->isNotEmpty()) {
            abort(422, 'Semua pertanyaan wajib harus diisi.');
        }

        $answersByQuestion = collect($data['jawaban'])->keyBy('pertanyaan_id');
        foreach ($event->pertanyaans()->where('tipe_input', 'email')->get() as $question) {
            $answer = $answersByQuestion->get($question->id)['jawaban'] ?? null;
            if ($answer !== null && ! filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                abort(422, "Jawaban untuk {$question->label} harus berupa email yang valid.");
            }
        }

        $peserta = DB::transaction(function () use ($event, $request, $data) {
            $peserta = Peserta::create([
                'event_id' => $event->id,
                'user_id' => $request->user()->id,
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

    /**
     * GET /pendaftaran/saya (pelanggan)
     * Daftar pendaftaran milik user yang login beserta status dan status bayar.
     */
    public function milikSaya(Request $request)
    {
        $pesertas = Peserta::with(['event', 'pembayaran'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }
}