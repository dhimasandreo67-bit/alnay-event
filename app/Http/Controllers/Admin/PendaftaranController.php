<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends Controller
{
    public function storeManual(Request $request, Event $event): JsonResponse
    {
        $questions = $event->pertanyaans()->orderBy('urutan', 'asc')->get();
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ];

        foreach ($questions as $question) {
            if (in_array($question->tipe_input, ['heading', 'subheading'], true)) {
                continue;
            }

            $field = "answers.{$question->id}";
            $fieldRules = [$question->wajib ? 'required' : 'nullable'];

            if ($question->tipe_input === 'file') {
                $fieldRules[] = 'file';
                $fieldRules[] = 'max:5120';
            } elseif ($question->tipe_input === 'number') {
                $fieldRules[] = 'regex:/^\d+$/';
            } elseif ($question->tipe_input === 'checkbox') {
                $fieldRules[] = 'array';
                $rules["{$field}.*"] = ['string', Rule::in($question->pilihan_jawaban ?? [])];
            } elseif (in_array($question->tipe_input, ['select', 'radio'], true)) {
                $fieldRules[] = Rule::in($question->pilihan_jawaban ?? []);
            } elseif ($question->tipe_input === 'email') {
                // BUG LAMA: cabang ini sebelumnya gak ada, jadi pertanyaan
                // bertipe email gak pernah divalidasi format-nya.
                $fieldRules[] = 'email';
            } else {
                $fieldRules[] = 'string';
                $fieldRules[] = 'max:5000';
            }

            $rules[$field] = $fieldRules;
        }

        $data = $request->validate($rules);

        $peserta = DB::transaction(function () use ($data, $event, $questions, $request): Peserta {
            $user = PenggunaEvent::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'google_id' => 'manual-'.Str::uuid(),
                    'nama' => $data['nama'],
                ],
            );

            if ($user->nama !== $data['nama']) {
                $user->update(['nama' => $data['nama']]);
            }

            // Cegah 1 orang ke-input 2x ke event yang sama.
            $sudahDaftar = Peserta::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($sudahDaftar) {
                throw ValidationException::withMessages([
                    'email' => 'Email ini sudah terdaftar untuk event ini.',
                ]);
            }

            $peserta = $event->pesertas()->create([
                'user_id' => $user->id,
                'status' => 'diterima',
            ]);

            foreach ($questions as $question) {
                if (in_array($question->tipe_input, ['heading', 'subheading'], true)) {
                    continue;
                }

                $answer = $request->file("answers.{$question->id}")?->store('peserta');
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
                ]);
            }

            return $peserta;
        });

        return response()->json([
            'message' => 'Data peserta berhasil disimpan.',
            'peserta_id' => $peserta->id,
        ], 201);
    }


    /**
     * GET /event/:id/pendaftar (admin)
     * Daftar semua pendaftar event.
     */
    public function indexPendaftar(Event $event)
    {
        $pesertas = $event->pesertas()
            ->with(['user', 'pembayaran', 'jawabans.pertanyaan'])
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }

    /**
     * GET /admin/peserta/jawaban/:id/file
     *
     * Serve file upload peserta HANYA untuk admin yang login (session).
     * File disimpan di disk 'local' (private, BUKAN 'public'), jadi
     * satu-satunya cara mengaksesnya memang lewat route ini — tidak ada
     * URL publik langsung ke file-nya. Route ini WAJIB didaftarkan di
     * dalam grup middleware 'admin.session'.
     */
    public function downloadJawabanFile(JawabanPeserta $jawaban): Response
    {
        abort_unless($jawaban->pertanyaan?->tipe_input === 'file', 404);
        abort_unless($jawaban->jawaban && Storage::disk('local')->exists($jawaban->jawaban), 404);

        return Storage::disk('local')->download($jawaban->jawaban);
    }

    public function index()
    {
        // 1. Ambil data event
        $event = Event::first(); 

        // 2. Ambil seluruh data peserta
        $pesertas = Peserta::orderBy('id', 'desc')->get();

        // 3. Hitung jumlah statistik peserta berdasarkan status
        $totalPendaftar = $pesertas->count();
        $menungguReview = $pesertas->where('status', 'menunggu_review')->count();
        $perluRevisi    = $pesertas->where('status', 'revisi')->count();
        $diterima       = $pesertas->where('status', 'diterima')->count();
        $ditolak        = $pesertas->where('status', 'ditolak')->count();

        // 4. Kirim semua variabel ke view dashboard
        return view('admin.dashboard', compact(
            'event', 
            'pesertas', 
            'totalPendaftar', 
            'menungguReview', 
            'perluRevisi', 
            'diterima', 
            'ditolak'
        ));
    }

    // Menampilkan Detail Peserta
    public function show($id)
    {
        $peserta = Peserta::findOrFail($id);
        return view('admin.peserta.show', compact('peserta'));
    }

    // Mengubah Status Peserta (Terima/Tolak/Revisi)
    public function updateStatus(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->status = $request->status; // 'diterima', 'ditolak', atau 'revisi'
        $peserta->save();

        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    // Menghapus Peserta
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);

        // 1. Hapus semua jawaban yang dimiliki oleh peserta ini
        JawabanPeserta::where('peserta_id', $peserta->id)->delete();

        // 2. Hapus data peserta
        $peserta->delete();

        return redirect()->back()->with('success', 'Data peserta dan jawabannya berhasil dihapus.');
    }
}