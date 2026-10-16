<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JawabanPeserta;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesertaTampilController extends Controller
{
    /**
     * GET /admin/peserta-tampil/{event?}
     * Halaman kelola tampil/sembunyi peserta
     */
    public function index(?Event $event = null)
    {
        if (!$event) {
            $eventId = session('active_event_id');
            $event = $eventId ? Event::find($eventId) : Event::query()->latest('id')->first();
        } else {
            session(['active_event_id' => $event->id]);
        }

        $pesertas = collect();
        if ($event) {
            $pesertas = Peserta::where('event_id', $event->id)
                ->where('status', 'diterima')
                ->with(['user', 'pembayaran', 'pembayaranRegistrasi'])
                ->orderByDesc('tampil_di_publik')
                ->orderByDesc('id')
                ->get();
        }

        return view('admin.peserta-tampil.index', [
            'event'    => $event,
            'events'   => Event::query()->latest('id')->get(['id', 'nama']),
            'pesertas' => $pesertas,
        ]);
    }

    /**
     * GET /admin/peserta-tampil/{event}/tambah
     * Form tambah peserta manual — mode admin
     */
    public function create(Event $event)
    {
        // Cuma bisa kalau pendaftaran publik mati
        if ($event->pendaftaran_aktif ?? true) {
            return redirect()
                ->route('admin.peserta-tampil', $event->id)
                ->with('error', 'Event ini pakai pendaftaran publik. Peserta harus daftar sendiri.');
        }

        $questions = $event->pertanyaans()->orderBy('urutan')->get();

        return view('registration', [
            'event'         => $event,
            'questions'     => $questions,
            'isAdminMode'   => true,      // ← flag untuk view
        ]);
    }

    /**
     * POST /admin/peserta-tampil/{event}/tambah
     * Submit form tambah peserta manual
     */
    public function storeForm(Request $request, Event $event)
    {
        if ($event->pendaftaran_aktif ?? true) {
            return redirect()
                ->route('admin.peserta-tampil', $event->id)
                ->with('error', 'Event ini pakai pendaftaran publik.');
        }

        $questions = $event->pertanyaans()->get();
        $namaDariForm = null;

        foreach ($questions as $q) {
            $labelLower = strtolower(trim($q->label ?? ''));
            if (in_array($labelLower, ['nama', 'name', 'nama lengkap', 'nama pendaftaran'])) {
                $namaDariForm = $request->input("answers.{$q->id}");
                break;
            }
        }

        // Nama — dari form builder — atau — dari input manual
        $nama = $namaDariForm ?? $request->input('nama_pendaftaran');

        if (empty($nama)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Nama peserta wajib diisi.');
        }

        // Email — dari input manual — atau — auto-generate
        $email = $request->input('email_manual');

        if (empty($email)) {
            // Auto-generate dari nama + timestamp
            $slug = \Illuminate\Support\Str::slug($nama);
            $email = $slug . '-' . time() . '@voting.local';
        }

        try {
            $peserta = DB::transaction(function () use ($request, $event, $nama, $email, $questions) {
                // 1. Cari atau buat user
                $user = PenggunaEvent::query()->firstOrCreate(
                    ['email' => $email],
                    [
                        'google_id' => 'voting-' . Str::uuid(),
                        'nama'      => $nama,
                    ]
                );

                if ($user->nama !== $nama) {
                    $user->update(['nama' => $nama]);
                }

                // 2. Cek duplikat
                $sudahDaftar = Peserta::where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->whereNotIn('status', ['gugur', 'ditolak'])
                    ->exists();

                if ($sudahDaftar) {
                    throw new \Exception('Peserta ini sudah terdaftar di event ini.');
                }

                // 3. Buat peserta via service — langsung diterima + tampil
                $peserta = app(\App\Services\PesertaAlurService::class)->tambahManual($event, [
                    'user_id'          => $user->id,
                    'nama_pendaftaran' => $nama,
                ]);

                // 4. Simpan jawaban form
                foreach ($questions as $question) {
                    if (in_array($question->tipe_input, ['heading', 'subheading'], true)) {
                        continue;
                    }

                    $answer = $request->file("answers.{$question->id}")?->store('peserta', 'public');
                    $answer ??= $request->input("answers.{$question->id}");

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

            return redirect()
                ->route('admin.peserta-tampil', $event->id)
                ->with('success', 'Peserta "' . $peserta->namaTampil() . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * POST /admin/peserta-tampil/{peserta}/toggle
     */
    public function toggle(Peserta $peserta)
    {
        $peserta->tampil_di_publik = !$peserta->tampil_di_publik;
        $peserta->save();

        return response()->json([
            'success'        => true,
            'tampilDiPublik' => (bool) $peserta->tampil_di_publik,
            'message'        => $peserta->namaTampil() . ' ' . ($peserta->tampil_di_publik ? 'ditampilkan' : 'disembunyikan') . ' di publik.',
        ]);
    }

    /**
     * POST /admin/peserta-tampil/{event}/toggle-semua
     */
    public function toggleSemua(Request $request, Event $event)
    {
        $tampil = $request->boolean('tampil');

        Peserta::where('event_id', $event->id)
            ->where('status', 'diterima')
            ->update(['tampil_di_publik' => $tampil]);

        return redirect()
            ->route('admin.peserta-tampil', $event->id)
            ->with('success', $tampil
                ? 'Semua peserta berhasil ditampilkan di publik.'
                : 'Semua peserta berhasil disembunyikan dari publik.');
    }

    /**
     * POST /admin/peserta-tampil/{event}/store
     * ⚠️ Legacy — nggak dipakai lagi
     */
    public function store(Request $request, Event $event)
    {
        return response()->json(['message' => 'Gunakan form create + storeForm'], 410);
    }
}
