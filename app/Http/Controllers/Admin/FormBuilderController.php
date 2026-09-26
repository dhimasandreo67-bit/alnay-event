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
        
        // Ambil pertanyaan yang sudah diurutkan
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
        // Terima data 'fields' atau 'questions' agar aman dari kedua format JS
        $rawFields = $request->input('fields') ?? $request->input('questions') ?? [];

        // Validasi struktur data
        $validated = $request->validate([
            'fields' => ['nullable', 'array'],
            'questions' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($event, $rawFields) {
            $keepIds = [];

            foreach ($rawFields as $position => $field) {
                // Normalisasi nama key dan tipe input
                $id = $field['id'] ?? null;
                $label = $field['label'] ?? '';
                $tipeInput = strtolower($field['tipe_input'] ?? 'text');

                // Pemetaan Alias Tipe Input
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

                // Format Pilihan Jawaban & Konfigurasi (array/json)
                $pilihanJawaban = $field['pilihan_jawaban'] ?? null;
                if (is_array($pilihanJawaban)) {
                    $pilihanJawaban = json_encode($pilihanJawaban);
                }

                $konfigurasi = $field['konfigurasi'] ?? null;
                if (is_array($konfigurasi)) {
                    $konfigurasi = json_encode($konfigurasi);
                }

                // Cari atau buat instance pertanyaan
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

            // Hapus pertanyaan lama yang tidak ada dalam daftar kiriman baru
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
            'message' => 'Formulir berhasil diperbarui dan disinkronkan.'
        ]);
    }

    /**
     * Toggle status aktif/non-aktif form pendaftaran
     */
    public function toggleFormStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->is_form_active = !$event->is_form_active;
        $event->save();

        return back()->with('status', 'Status form berhasil diperbarui!');
    }

    public function update(Request $request, $eventId)
    {
        $submittedQuestions = $request->input('questions', []);
        $keepIds = collect($submittedQuestions)->pluck('id')->filter()->toArray();

        $oldQuestionIds = Pertanyaan::where('event_id', $eventId)
            ->whereNotIn('id', $keepIds)
            ->pluck('id')
            ->all();

        if (! empty($oldQuestionIds)) {
            JawabanPeserta::whereIn('pertanyaan_id', $oldQuestionIds)->delete();
            Pertanyaan::whereIn('id', $oldQuestionIds)->delete();
        }

        foreach ($submittedQuestions as $index => $qData) {
            Pertanyaan::updateOrCreate(
                [
                    'id' => $qData['id'] ?? null,
                    'event_id' => $eventId,
                ],
                [
                    'label' => $qData['label'] ?? '',
                    'tipe_input' => $qData['tipe_input'] ?? 'text',
                    'pilihan_jawaban' => isset($qData['options']) ? json_encode($qData['options']) : null,
                    'urutan' => $index + 1,
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Form berhasil disimpan']);
    }
}