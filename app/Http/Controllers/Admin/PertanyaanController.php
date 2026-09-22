<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    /**
     * POST /event/:id/pertanyaan (admin)
     * Tambah satu pertanyaan baru ke form.
     */
    public function store(Request $request, Event $event)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'tipe_input' => ['required', 'in:heading,subheading,text,email,textarea,number,select,radio,checkbox,file'],
            'pilihan_jawaban' => ['nullable', 'array'],
            'konfigurasi' => ['nullable', 'array'],
            'wajib' => ['boolean'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['event_id'] = $event->id;
        $data['urutan'] ??= $event->pertanyaans()->max('urutan') + 1;

        $pertanyaan = Pertanyaan::create($data);

        return response()->json($pertanyaan, 201);
    }

    /**
     * PUT /pertanyaan/:id (admin)
     * Ubah pertanyaan (teks, wajib/tidak, urutan).
     */
    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        $data = $request->validate([
            'label' => ['sometimes', 'string', 'max:255'],
            'tipe_input' => ['sometimes', 'in:heading,subheading,text,email,textarea,number,select,radio,checkbox,file'],
            'pilihan_jawaban' => ['nullable', 'array'],
            'konfigurasi' => ['nullable', 'array'],
            'wajib' => ['sometimes', 'boolean'],
            'urutan' => ['sometimes', 'integer', 'min:0'],
        ]);

        $pertanyaan->update($data);

        return response()->json($pertanyaan);
    }

    /**
     * DELETE /pertanyaan/:id (admin)
     * Hapus pertanyaan dari form.
     */
    public function destroy(Pertanyaan $pertanyaan)
    {
        // TODO: pertimbangkan larangan hapus kalau sudah ada jawaban_peserta terkait
        $pertanyaan->delete();

        return response()->json(['message' => 'Pertanyaan dihapus']);
    }
}