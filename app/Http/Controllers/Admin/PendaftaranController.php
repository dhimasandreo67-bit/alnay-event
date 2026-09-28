<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use App\Models\Pembayaranevent;
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

                $answer = $request->file("answers.{$question->id}")?->store('peserta' , 'public');
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
    public function downloadJawabanFile(JawabanPeserta $jawaban)
    {
        // 1. Ambil path file dari jawaban
        $filePath = $jawaban->jawaban;

        // Jika jawaban berupa array, ambil elemen pertama
        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
        }

        // Bersihkan query string jika ada (misal ?v=123)
        if ($filePath && str_contains($filePath, '?')) {
            $filePath = parse_url($filePath, PHP_URL_PATH);
        }

        // Validasi path tidak kosong
        if (!$filePath) {
            abort(404, 'File tidak ditemukan');
        }

        // Cek keberadaan file di disk 'public'
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di server');
        }

        // 2. Ambil ekstensi file asli (misal: pdf, jpg, png)
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        // 3. Ambil data peserta untuk kustomisasi nama file
        $peserta = $jawaban->peserta;
        $user = $peserta?->user;

        $namaPeserta   = $user?->nama ?? 'Peserta';
        $emailPeserta  = $user?->email ?? 'tanpa-email';
        $tanggalDaftar = $peserta?->created_at?->format('Y-m-d') ?? date('Y-m-d');

        // 4. Sanitasi nama file (ganti spasi & karakter aneh dengan underscore)
        // Agar aman dan tidak error saat didownload di berbagai OS
        $namaSanitized  = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $namaPeserta);
        $emailSanitized = preg_replace('/[^a-zA-Z0-9_\-.@]/', '_', $emailPeserta);

        // 5. Format nama file baru: Nama_Email_Tanggal.ext
        $newFilename = "{$namaSanitized}_{$emailSanitized}_{$tanggalDaftar}.{$extension}";

        // 6. Return file sebagai response download dengan nama baru
        return Storage::disk('public')->download($filePath, $newFilename);
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
        // $perluRevisi    = $pesertas->where('status', 'revisi')->count();
        $diterima       = $pesertas->where('status', 'diterima')->count();
        $ditolak        = $pesertas->where('status', 'ditolak')->count();

        // 4. Kirim semua variabel ke view dashboard
        return view('admin.dashboard', compact(
            'event', 
            'pesertas', 
            'totalPendaftar', 
            'menungguReview', 
            // 'perluRevisi', 
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

    private function buatPembayaranOtomatis(Peserta $peserta): void
    {
        // Cek apakah sudah ada pembayaran (hindari duplikasi)
        if ($peserta->pembayaran) {
            return;
        }

        $event = $peserta->event;
        $tarifDasar = (int) ($event->tarif_pendaftaran ?? 0);

        // Generate kode unik 3 digit dari ID peserta
        // ID 19 → "019", ID 127 → "127", ID 5 → "005"
        $kodeUnik = str_pad($peserta->id, 3, '0', STR_PAD_LEFT);

        // Nominal unik = tarif dasar + kode unik
        // Contoh: 90000 + 19 = 90019
        $nominalUnik = $tarifDasar + (int) $kodeUnik;

        // Buat order ID yang unik
        $orderId = 'PPA-' . date('Ymd') . '-' . str_pad($peserta->id, 4, '0', STR_PAD_LEFT);

        PembayaranEvent::create([
            'peserta_id'        => $peserta->id,
            'jumlah'            => $nominalUnik,
            'tarif_dasar'       => $tarifDasar,
            'kode_unik'         => $kodeUnik,
            'midtrans_order_id' => $orderId,
            'status_midtrans'   => 'pending',
            'metode'            => 'transfer_bank',
            'tanggal_bayar'     => null,
        ]);
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

    public function file(Jawaban $jawaban)
    {
        $raw = is_array($jawaban->jawaban)
            ? ($jawaban->jawaban[0] ?? '')
            : (string) $jawaban->jawaban;

        if ($raw === '') {
            abort(404, 'Kolom jawaban kosong (tidak ada path file).');
        }

        // Normalisasi path dari DB
        $clean = parse_url($raw, PHP_URL_PATH) ?? $raw;
        $clean = ltrim($clean, '/');
        $clean = preg_replace('#^(public/|private/|storage/)+#', '', $clean);
        $basename = basename($clean);

        // --- Tahap 1: lokasi-lokasi kandidat ---
        $candidates = [
            storage_path('app/private/' . $clean),  // disk local Laravel 11+
            storage_path('app/public/' . $clean),   // disk public
            storage_path('app/' . $clean),          // disk local Laravel <= 10
            public_path('storage/' . $clean),       // hasil storage:link
            public_path($clean),                    // kalau sempat masuk public/
        ];

        foreach ($candidates as $c) {
            if (is_file($c)) {
                return response()->file($c); // Content-Type otomatis → gambar tampil
            }
        }

        // --- Tahap 2: pencarian rekursif berdasarkan nama file ---
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

        // --- Tidak ketemu: laporkan semua lokasi yang sudah dicari ---
        abort(404, 'File fisik tidak ditemukan. Path di DB: ' . $raw .
            ' | Sudah dicari di: ' . implode(', ', $candidates) .
            ' + pencarian rekursif di storage/ dan public/');
    }

        // Hapus semua pertanyaan milik event (dipanggil dari halaman form builder)
    public function clearQuestions(Request $request, Event $event)
    {
        $event->pertanyaans()->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Semua pertanyaan berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Semua pertanyaan berhasil dihapus.');
    }
}