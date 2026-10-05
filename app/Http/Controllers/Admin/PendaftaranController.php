<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use App\Models\PembayaranEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
            'nama' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s\.\'\-,]+$/u'],
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
                    'google_id' => 'manual-' . Str::uuid(),
                    'nama' => $data['nama'],
                ],
            );

            if ($user->nama !== $data['nama']) {
                $user->update(['nama' => $data['nama']]);
            }

            $sudahDaftar = Peserta::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['menunggu_review', 'diterima'])
                ->exists();

            if ($sudahDaftar) {
                throw ValidationException::withMessages([
                    'email' => 'Email ini sudah terdaftar untuk event ini.',
                ]);
            }

            // Simpan peserta + nama_pendaftaran (untuk data lama)
            $pesertaData = [
                'user_id'          => $user->id,
                'nama_pendaftaran' => $data['nama'],
                'status'           => 'diterima',
            ];

            // Tambah updated_at kalau kolomnya ada
            if (Schema::hasColumn('peserta_event', 'updated_at')) {
                $pesertaData['updated_at'] = now();
            }

            $peserta = $event->pesertas()->create($pesertaData);

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
                    'peserta_id'    => $peserta->id,
                    'pertanyaan_id' => $question->id,
                    'jawaban'       => $answer,
                ]);
            }

            return $peserta;
        });

        return response()->json([
            'message'    => 'Data peserta berhasil disimpan.',
            'peserta_id' => $peserta->id,
        ], 201);
    }

    public function indexPendaftar(Event $event)
    {
        $pesertas = $event->pesertas()
            ->with(['user', 'pembayaran', 'jawabans.pertanyaan'])
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }

    public function downloadJawabanFile(JawabanPeserta $jawaban)
    {
        $filePath = $jawaban->jawaban;

        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
        }

        if ($filePath && str_contains($filePath, '?')) {
            $filePath = parse_url($filePath, PHP_URL_PATH);
        }

        if (! $filePath) {
            abort(404, 'File tidak ditemukan');
        }

        if (! Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di server');
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $peserta = $jawaban->peserta;
        $user = $peserta?->user;

        $namaPeserta   = $user?->nama ?? 'Peserta';
        $emailPeserta  = $user?->email ?? 'tanpa-email';
        $tanggalDaftar = $peserta?->created_at?->format('Y-m-d') ?? date('Y-m-d');

        $namaSanitized  = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $namaPeserta);
        $emailSanitized = preg_replace('/[^a-zA-Z0-9_\-.@]/', '_', $emailPeserta);

        $newFilename = "{$namaSanitized}_{$emailSanitized}_{$tanggalDaftar}.{$extension}";

        return Storage::disk('public')->download($filePath, $newFilename);
    }

    public function index(Request $request)
    {
        $eventId = $request->query('event_id');
        $event = $eventId
            ? Event::find($eventId)
            : Event::query()->latest('id')->first();

        $baseQuery = Peserta::query()
            ->when($event, fn($q) => $q->where('event_id', $event->id));

        $totalPendaftar = (clone $baseQuery)->count();
        $menungguReview = (clone $baseQuery)->where('status', 'menunggu_review')->count();
        $diterima       = (clone $baseQuery)->where('status', 'diterima')->count();
        $ditolak        = (clone $baseQuery)->where('status', 'ditolak')->count();

        // lastUpdate — cek kolom dulu
        $lastUpdate = Schema::hasColumn('peserta_event', 'updated_at')
            ? (clone $baseQuery)->max('updated_at')
            : null;

        $pesertas = (clone $baseQuery)
            ->with(['user', 'event', 'jawaban.pertanyaan', 'pembayaran'])
            ->orderBy('id', 'desc')
            ->get();

        $events = Event::query()->latest('id')->get(['id', 'nama']);

        return view('admin.dashboard', compact(
            'event',
            'events',
            'pesertas',
            'totalPendaftar',
            'menungguReview',
            'diterima',
            'ditolak',
            'lastUpdate'
        ));
    }

    public function show($id)
    {
        $peserta = Peserta::with(['user', 'event', 'jawaban.pertanyaan', 'pembayaran'])
            ->findOrFail($id);

        return view('admin.peserta.show', compact('peserta'));
    }

    public function updateStatus(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $validStatus = ['diterima', 'ditolak', 'menunggu_review'];

        if (! in_array($request->status, $validStatus, true)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        if ($request->status === 'ditolak') {
            $request->validate([
                'catatan_admin' => ['required', 'string', 'max:1000'],
            ], [
                'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        $peserta->status = $request->status;

        if ($request->filled('catatan_admin')) {
            $peserta->catatan_admin = $request->catatan_admin;
        }

        if ($request->status === 'diterima') {
            $peserta->catatan_admin = null;
        }

        $peserta->save();

        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        // Hapus relasi dulu
        \App\Models\JawabanPeserta::where('peserta_id', $peserta->id)->delete();
        \App\Models\PembayaranEvent::where('peserta_id', $peserta->id)->delete();
        $peserta->delete();

        // Redirect ke halaman list peserta (dashboard admin)
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data peserta, jawaban, dan pembayaran berhasil dihapus.');
    }
    public function file(JawabanPeserta $jawaban)
    {
        $raw = is_array($jawaban->jawaban)
            ? ($jawaban->jawaban[0] ?? '')
            : (string) $jawaban->jawaban;

        if ($raw === '') {
            abort(404, 'Kolom jawaban kosong (tidak ada path file).');
        }

        $clean = parse_url($raw, PHP_URL_PATH) ?? $raw;
        $clean = ltrim($clean, '/');
        $clean = preg_replace('#^(public/|private/|storage/)+#', '', $clean);
        $basename = basename($clean);

        $candidates = [
            storage_path('app/private/' . $clean),
            storage_path('app/public/' . $clean),
            storage_path('app/' . $clean),
            public_path('storage/' . $clean),
            public_path($clean),
        ];

        foreach ($candidates as $c) {
            if (is_file($c)) {
                return response()->file($c);
            }
        }

        foreach ([storage_path(), public_path()] as $root) {
            if (! is_dir($root)) {
                continue;
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $f) {
                if ($f->isFile() && $f->getFilename() === $basename) {
                    return response()->file($f->getPathname());
                }
            }
        }

        abort(404, 'File fisik tidak ditemukan. Path di DB: ' . $raw);
    }

    public function clearQuestions(Request $request, Event $event)
    {
        $event->pertanyaans()->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Semua pertanyaan berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Semua pertanyaan berhasil dihapus.');
    }
}
