<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormBuilderController extends Controller
{
    /**
     * Menampilkan halaman pendaftaran publik berdasarkan Event ID
     */
    public function show($eventId)
    {
        $event = Event::findOrFail($eventId);

        $questions = $event->pertanyaans()
            ->orderBy('urutan', 'asc')
            ->get();

        return view('daftar.show', compact('event', 'questions'));
    }

    /**
     * Menampilkan halaman Form Builder (Admin)
     */
    public function edit(?Event $event = null)
    {
        $event ??= Event::query()->latest('id')->first();

        return view('admin.form-builder', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'questions' => $event?->pertanyaans()->orderBy('urutan', 'asc')->get() ?? collect(),
        ]);
    }

    /**
     * Menyimpan atau memperbarui seluruh komponen Form Builder ke Database
     */
    public function save(Request $request, Event $event)
    {
        $rawFields = $request->input('fields') ?? $request->input('questions') ?? [];

        $request->validate([
            'fields' => ['nullable', 'array'],
            'questions' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($event, $rawFields) {
            $keepIds = [];

            foreach ($rawFields as $position => $field) {
                $id = $field['id'] ?? null;
                $label = $field['label'] ?? '';
                $tipeInput = strtolower($field['tipe_input'] ?? 'text');

                $tipeInputMap = [
                    'dropdown' => 'select',
                    'teks_panjang' => 'textarea',
                    'angka' => 'number',
                    'teks' => 'text',
                    'sub_judul' => 'subheading',
                    'judul' => 'heading',
                ];

                if (array_key_exists($tipeInput, $tipeInputMap)) {
                    $tipeInput = $tipeInputMap[$tipeInput];
                }

                // PENTING: JANGAN json_encode() di sini. Model Pertanyaan
                // sudah punya $casts = ['pilihan_jawaban' => 'array',
                // 'konfigurasi' => 'array'] - Eloquent otomatis encode saat
                // simpan & decode saat baca. Kalau di-encode manual di sini
                // JUGA, hasilnya double-encoded dan rusak saat dibaca balik.
                $pilihanJawaban = $field['pilihan_jawaban'] ?? [];
                $konfigurasi = $field['konfigurasi'] ?? [];

                $question = $id ? $event->pertanyaans()->whereKey($id)->first() : null;
                $question ??= new Pertanyaan(['event_id' => $event->id]);

                $question->fill([
                    'label' => $label,
                    'tipe_input' => $tipeInput,
                    'pilihan_jawaban' => $pilihanJawaban,
                    'konfigurasi' => $konfigurasi,
                    'wajib' => filter_var($field['wajib'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'urutan' => $position + 1,
                ]);

                $question->event_id = $event->id;
                $question->save();

                $keepIds[] = $question->id;
            }

            $oldQuestionIds = $event->pertanyaans()
                ->whereNotIn('id', $keepIds ?: [0])
                ->pluck('id')
                ->all();

            if (! empty($oldQuestionIds)) {
                JawabanPeserta::whereIn('pertanyaan_id', $oldQuestionIds)->delete();
                $event->pertanyaans()->whereIn('id', $oldQuestionIds)->delete();
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Formulir berhasil diperbarui dan disinkronkan.',
        ]);
    }
}