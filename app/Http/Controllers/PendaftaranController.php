<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends Controller
{
    // =====================================================================
    // DARI HAN: form pendaftaran publik (Blade) di /daftar/{event}
    // =====================================================================

    public function showPublicForm(Event $event)
    {
        return view('registration', [
            'event' => $event,
            'questions' => $event->pertanyaans()->orderBy('urutan')->get(),
        ]);
    }

    public function storePublic(Request $request, Event $event)
    {
        $questions = $event->pertanyaans()->get();
        $rules = [
            'answers' => ['required', 'array'],
        ];

        foreach ($questions as $question) {
            $type = strtolower($question->tipe_input);

            if (in_array($type, ['heading', 'subheading'], true)) {
                continue;
            }

            $field = "answers.{$question->id}";
            $fieldRules = [$question->wajib ? 'required' : 'nullable'];

            $rawOptions = $question->pilihan_jawaban ?? [];
            if (is_string($rawOptions)) {
                $rawOptions = json_decode($rawOptions, true) ?? [];
            }

            $cleanOptions = array_map(function ($opt) {
                return trim(str_replace(["\r", "\n"], '', (string) $opt));
            }, (array) $rawOptions);

            if ($type === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'max:5120';
            } elseif (in_array($type, ['number', 'angka'], true)) {
                $fieldRules[] = 'regex:/^\d+$/';
            } elseif ($type === 'checkbox') {
                $fieldRules[] = 'array';
                if (! empty($cleanOptions)) {
                    $rules["{$field}.*"] = ['string', Rule::in($cleanOptions)];
                }
            } elseif (in_array($type, ['select', 'dropdown', 'radio'], true)) {
                if (! empty($cleanOptions)) {
                    $fieldRules[] = Rule::in($cleanOptions);
                }
            } elseif ($type === 'email') {
                $fieldRules[] = 'email';
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:5000';
            }

            $rules[$field] = $fieldRules;
        }

        $data = $request->validate($rules);
        $answers = $data['answers'];

        $emailQuestion = $questions->first(
            fn ($question): bool => strtolower($question->tipe_input) === 'email'
        );
        $nameQuestion = $questions->first(
            fn ($question): bool => in_array(strtolower($question->tipe_input), ['text', 'teks'], true)
                && str_contains(strtolower($question->label), 'nama')
        ) ?? $questions->first(
            fn ($question): bool => in_array(strtolower($question->tipe_input), ['text', 'teks'], true)
        );
        $email = $emailQuestion ? ($answers[$emailQuestion->id] ?? null) : null;
        $nama = $nameQuestion ? ($answers[$nameQuestion->id] ?? null) : null;

        if (! $emailQuestion || ! $email) {
            throw ValidationException::withMessages([
                'answers' => 'Form harus memiliki pertanyaan email yang diisi.',
            ]);
        }

        if (! $nameQuestion || ! $nama) {
            throw ValidationException::withMessages([
                'answers' => 'Form harus memiliki pertanyaan nama yang diisi.',
            ]);
        }

        DB::transaction(function () use ($data, $event, $questions, $request, $nama, $email): void {
            $user = PenggunaEvent::query()->firstOrCreate(
                ['email' => $email],
                [
                    'google_id' => 'web-'.Str::uuid(),
                    'nama' => $nama,
                ],
            );

            if ($user->nama !== $nama) {
                $user->update(['nama' => $nama]);
            }

            $sudahDaftar = Peserta::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($sudahDaftar) {
                throw ValidationException::withMessages([
                    'email' => 'Email ini sudah terdaftar untuk event ini.',
                ]);
            }

            $peserta = Peserta::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'menunggu_review',
            ]);

            foreach ($questions as $question) {
                if (in_array($question->tipe_input, ['heading', 'subheading'], true)) {
                    continue;
                }

                $answer = $request->file("answers.{$question->id}")?->store('peserta', 'public');
                $answer ??= $data['answers'][$question->id] ?? null;

                if ($answer === null || $answer === '') {
                    continue;
                }

                if (is_array($answer)) {
                    $answer = json_encode($answer, JSON_THROW_ON_ERROR);
                }

                JawabanPeserta::create([
                    'peserta_id' => $peserta->id,
                    'pertanyaan_id' => $question->id,
                    'jawaban' => $answer,
                ]);
            }
        });

        return redirect()->route('public.registration', $event)
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    // =====================================================================
    // DARI MAIN: API JSON
    // =====================================================================

    public function store(Request $request, $id)
    {
        $questionIds = Pertanyaan::where('event_id', $id)->pluck('id')->toArray();

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

    public function milikSaya(Request $request)
    {
        $pesertas = Peserta::with(['event', 'pembayaran'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }

    public function daftarPeserta($id)
    {
        $pesertas = Peserta::with('user:id,nama,foto_url')
            ->where('event_id', $id)
            ->where('status', 'diterima')
            ->get(['id', 'event_id', 'user_id']);

        return response()->json($pesertas);
    }

    public function show(Request $request, $id)
    {
        $peserta = Peserta::with([
            'user:id,nama,foto_url',
            'event:id,nama,harga_per_vote,tanggal_mulai,tanggal_selesai,voting_aktif',
            'jawabans.pertanyaan:id,label,tag_tampilan',
        ])
            ->select('id', 'event_id', 'user_id', 'galeri_foto')
            ->findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($peserta);
        }

        return view('voting.index', [
            'pesertaId' => $id,
            'peserta' => $peserta,
        ]);
    }
}
