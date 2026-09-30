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
        // 1. Jika pilihan_jawaban dikirim dalam bentuk JSON String dari frontend, dekode dulu
        if ($request->has('pilihan_jawaban') && is_string($request->pilihan_jawaban)) {
            $decoded = json_decode($request->pilihan_jawaban, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['pilihan_jawaban' => $decoded]);
            }
        }

        if (in_array(strtolower($pertanyaan->tipe_input), ['dropdown', 'select', 'radio'])) {
            $rawOptions = $pertanyaan->pilihan_jawaban ?? $pertanyaan->options ?? [];

            if (is_string($rawOptions)) {
                $rawOptions = json_decode($rawOptions, true) ?? [];
            }

            // Bersihkan spasi, \r, \n dari setiap opsi
            $cleanOptions = array_map(function ($opt) {
                return trim(str_replace(["\r", "\n"], '', (string) $opt));
            }, (array) $rawOptions);

            if (!empty($cleanOptions)) {
                $fieldRules[] = Rule::in($cleanOptions);
            }
        }

        // 2. Validasi input
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            // PERBAIKAN: Tambahkan 'dropdown' ke dalam daftar validasi tipe_input
            'tipe_input' => ['required', 'in:heading,subheading,text,email,textarea,number,select,dropdown,radio,checkbox,file'],
            'pilihan_jawaban' => ['nullable', 'array'],
            'konfigurasi' => ['nullable', 'array'],
            'wajib' => ['nullable', 'boolean'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        // 3. Pastikan nilai 'wajib' bertipe boolean (default false jika tidak dicentang)
        $data['wajib'] = $request->boolean('wajib');

        // 4. Set event_id & urutan
        $data['event_id'] = $event->id;
        $data['urutan'] ??= ($event->pertanyaans()->max('urutan') ?? 0) + 1;

        // 5. Simpan ke database
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