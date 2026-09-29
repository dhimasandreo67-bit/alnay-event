<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Jawabanpeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PendaftaranController extends Controller
{
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

        // HELPER PERBAIKAN: Ambil array pilihan & bersihkan dari spasi/newline
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
            if (!empty($cleanOptions)) {
                $rules["{$field}.*"] = ['string', Rule::in($cleanOptions)];
            }
        } elseif (in_array($type, ['select', 'dropdown', 'radio'], true)) {
            if (!empty($cleanOptions)) {
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

        if (!$emailQuestion || !$email) {
            throw ValidationException::withMessages([
                'answers' => 'Form harus memiliki pertanyaan email yang diisi.',
            ]);
        }

        if (!$nameQuestion || !$nama) {
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

            // Cegah 1 orang daftar 2x ke event yang sama (unique constraint di
            // DB akan menolak juga, tapi ini kasih pesan yang jelas ke user,
            // bukan error 500 mentah).
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

                Jawabanpeserta::create([
                    'peserta_id' => $peserta->id,
                    'pertanyaan_id' => $question->id,
                    'jawaban' => $answer,
                ]);
            }
        });

        return redirect()->route('public.registration', $event)
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    /**
     * POST /event/:id/daftar (pelanggan, login via Sanctum/Google)
     * Jalur API — dipakai kalau nanti ada mobile app / SPA terpisah.
     */
    public function store(Request $request)
{
    // 1. Ambil semua pertanyaan yang wajib diisi dari database
    $questions = Question::all();

    $rules = [];
    $customAttributes = [];

    foreach ($questions as $question) {
        if (in_array($question->tipe_input, ['heading', 'subheading'])) {
            continue;
        }

        $fieldKey = "answers.{$question->id}";
        $ruleSet = [];

        // Keharusan pengisian
        if ($question->wajib) {
            $ruleSet[] = 'required';
        } else {
            $ruleSet[] = 'nullable';
        }

        // Tipe data khusus
        switch (strtolower($question->tipe_input)) {
            case 'email':
                $ruleSet[] = 'email';
                break;
            case 'number':
            case 'angka':
                $ruleSet[] = 'numeric';
                break;
            case 'checkbox':
                $ruleSet[] = 'array';
                break;
            case 'file':
                $ruleSet[] = 'file';
                $ruleSet[] = 'max:2048'; // Maksimal 2MB
                break;
            default:
                $ruleSet[] = 'string';
                break;
        }

        $rules[$fieldKey] = $ruleSet;
        // Penamaan pesan error agar pengguna membaca label pertanyaan (bukan "answers.12")
        $customAttributes[$fieldKey] = strtolower($question->label);
    }

    $validatedData = $request->validate($rules, [], $customAttributes);

    // Lanjutkan proses penyimpanan data...
}

    /**
     * GET /pendaftaran/saya (pelanggan)
     */
    public function milikSaya(Request $request)
    {
        $pesertas = Peserta::with(['event', 'pembayaran'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }

    public function index()
    {
        // 1. Ambil data event
        $event = Event::first(); 

        // 2. Ambil seluruh data peserta KECUALI 2 data terawal (ID 1 & 2)
        $pesertas = Peserta::whereNotIn('id', [1, 2])
            ->orderBy('id', 'desc')
            ->get();

        // 3. Hitung jumlah statistik peserta berdasarkan status (otomatis menyesuaikan data yang sudah difilter)
        $totalPendaftar = $pesertas->count();
        $menungguReview = $pesertas->where('status', 'menunggu_review')->count();
        // $perluRevisi    = $pesertas->where('status', 'revisi')->count();
        $diterima       = $pesertas->where('status', 'diterima')->count();
        $ditolak        = $pesertas->where('status', 'ditolak')->count();

        // 4. Kirim variabel ke view dashboard
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
        $statusBaru = $request->status; // 'diterima', 'ditolak', atau 'revisi'

        // Simpan catatan admin jika ada
        if ($request->filled('catatan_admin')) {
            $peserta->catatan_admin = $request->catatan_admin;
        }

        $peserta->status = $statusBaru;
        $peserta->save();

        if ($statusBaru === 'diterima') {
            $this->buatPembayaranOtomatis($peserta);
        }

        return redirect()->back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    // Menghapus Peserta
    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return redirect()->back()->with('success', 'Data peserta berhasil dihapus.');
    }

    public function saveBuilder(Request $request, Event $event)
    {
        $questionsData = $request->input('questions', []);

        DB::transaction(function () use ($event, $questionsData) {
            $event->pertanyaans()->delete();

            foreach ($questionsData as $index => $item) {
                // Pastikan pilihan_jawaban berupa array murni
                $options = $item['pilihan_jawaban'] ?? $item['options'] ?? null;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }

                $konfigurasi = $item['konfigurasi'] ?? null;
                if (is_string($konfigurasi)) {
                    $konfigurasi = json_decode($konfigurasi, true);
                }

                $event->pertanyaans()->create([
                    'label' => $item['label'],
                    'tipe_input' => strtolower($item['tipe_input']),
                    'wajib' => filter_var($item['wajib'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    // Jangan gunakan json_encode di sini! Cukup pass array-nya langsung:
                    'pilihan_jawaban' => $options,
                    'konfigurasi' => $konfigurasi,
                    'urutan' => $index + 1,
                ]);
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Formulir berhasil diperbarui']);
    }

    private function buatPembayaranOtomatis(Peserta $peserta): void
{
    // Cek apakah sudah ada pembayaran (hindari duplikasi)
    if ($peserta->pembayaran) {
        return;
    }

    $event = $peserta->event;
    $tarifDasar = (int) ($event->biaya_pendaftaran ?? 0);

    // Generate kode unik 3 digit dari ID peserta
    $kodeUnik = str_pad($peserta->id, 3, '0', STR_PAD_LEFT);

    // Nominal unik = biaya dasar + kode unik
    $nominalUnik = $tarifDasar + (int) $kodeUnik;

    // Buat order ID yang unik
    $orderId = 'PPA-' . date('Ymd') . '-' . str_pad($peserta->id, 4, '0', STR_PAD_LEFT);

    \App\Models\PembayaranEvent::create([
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
}