<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormBuilderController extends Controller
{
    public function edit(?Event $event = null)
    {
        $event ??= Event::query()->latest('id')->first();

        return view('admin.form-builder', [
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'questions' => $event?->pertanyaans()->orderBy('urutan')->get() ?? collect(),
        ]);
    }

    public function save(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'fields' => ['required', 'array', 'max:50'],
            'fields.*.id' => ['nullable', 'integer', 'exists:pertanyaan_event,id'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.tipe_input' => ['required', 'in:heading,subheading,text,email,textarea,number,select,radio,checkbox,file'],
            'fields.*.pilihan_jawaban' => ['nullable', 'array'],
            'fields.*.konfigurasi' => ['nullable', 'array'],
            'fields.*.wajib' => ['boolean'],
        ]);

        DB::transaction(function () use ($event, $data) {
            $keepIds = [];
            foreach ($data['fields'] as $position => $field) {
                $question = ! empty($field['id'])
                    ? $event->pertanyaans()->whereKey($field['id'])->first()
                    : null;

                $attributes = [
                    'label' => $field['label'],
                    'tipe_input' => $field['tipe_input'],
                    'pilihan_jawaban' => $field['pilihan_jawaban'] ?? null,
                    'konfigurasi' => $field['konfigurasi'] ?? null,
                    'wajib' => $field['wajib'] ?? false,
                    'urutan' => $position,
                ];

                $question ??= new Pertanyaan(['event_id' => $event->id]);
                $question->fill($attributes);
                $question->event_id = $event->id;
                $question->save();
                $keepIds[] = $question->id;
            }

            $event->pertanyaans()->whereNotIn('id', $keepIds ?: [0])->delete();
        });

        return response()->json(['message' => 'Form berhasil disimpan.']);
    }
}