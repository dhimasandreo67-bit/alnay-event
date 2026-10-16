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
    /**
     * POST /admin/registrations/{event}/manual
     * Input manual peserta - status langsung 'diterima'
     */
    public function storeManual(Request $request, Event $event): JsonResponse
    {
        $questions = $event->pertanyaans()->orderBy('urutan', 'asc')->get();

        $rules = [
            'nama'  => ['required', 'string', 'max:100', 'regex:/^[A-Za-z\s\.\'\-,]+$/u'],
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
            // 1. Cari atau buat user
            $user = PenggunaEvent::query()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'google_id' => 'manual-admin-' . Str::uuid(),
                    'nama'      => $data['nama'],
                ]
            );

            if ($user->nama !== $data['nama']) {
                $user->update(['nama' => $data['nama']]);
            }

            // 2. Cek duplikasi
            $sudahDaftar = Peserta::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->exists();

            if ($sudahDaftar) {
                throw ValidationException::withMessages([
                    'email' => 'Email ini sudah terdaftar untuk event ini.',
                ]);
            }

            // 3. ✅ BUAT PESERTA: Status LANGSUNG 'diterima'
            // 3. ✅ BUAT PESERTA via Service — biar konsisten dengan saklar
            $peserta = app(\App\Services\PesertaAlurService::class)->tambahManual($event, [
                'user_id'          => $user->id,
                'nama_pendaftaran' => $data['nama'],
                'jawabans'         => [], // nanti diisi jawaban di bawah
            ]);

            // 4. ✅ KONDISIONAL — beda per alur
            // Kalau pendaftaran publik MATI (Voting Saja) → bypass → langsung Lunas
            // Kalau pendaftaran publik HIDUP → track → peserta baru "Belum Bayar"
            $bypassPembayaran = !($event->pendaftaran_aktif ?? true);

            if ($bypassPembayaran && $event->biaya_pendaftaran > 0) {
                $jumlah = (int) $event->biaya_pendaftaran;
                \App\Models\PembayaranEvent::create([
                    'peserta_id'        => $peserta->id,
                    'jumlah'            => $jumlah,
                    'tarif_dasar'       => $jumlah,
                    'metode'            => 'manual_admin',
                    'midtrans_order_id' => 'ADMIN-MANUAL-' . date('YmdHis') . '-' . $peserta->id,
                    'status_midtrans'   => 'settlement',
                    'tanggal_bayar'     => now(),
                ]);
            }
            // Kalau pendaftaran publik HIDUP — nggak buat record — admin track manual

            // 5. Simpan jawaban Form Builder
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
            'message'    => 'Data peserta voting berhasil disimpan dan langsung diterima.',
            'peserta_id' => $peserta->id,
        ], 201);
    }

    /**
     * GET /admin/peserta/{id}
     * Detail peserta
     */
    public function show(Request $request, $id)
    {
        $peserta = Peserta::with([
            'user:id,nama,foto_url,foto_custom',
            'event:id,nama,harga_per_vote,tanggal_mulai,tanggal_selesai,voting_aktif,voting_mulai,voting_selesai,auto_freeze_voting,tampilkan_hasil_voting,format_hasil_voting,status',
            'jawabans.pertanyaan:id,label,tipe_input,tag_tampilan',
        ])
            ->select('id', 'event_id', 'user_id', 'galeri_foto', 'tampil_di_publik')
            ->findOrFail($id);

        // Cek akses publik: kalau tampil_di_publik = false & bukan admin → 404
        if (!$peserta->tampil_di_publik && !auth('admin')->check()) {
            abort(404);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($peserta);
        }

        return view('voting.index', [
            'pesertaId' => $id,
            'peserta' => $peserta,
        ]);
    }

    /**
     * GET /admin/peserta/{id}/edit
     * Redirect ke halaman aktivasi karena view edit terpisah tidak tersedia
     */
    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        
        // Redirect ke halaman aktivasi event terkait
        return redirect()->route('admin.activation', [
            'event' => $peserta->event_id,
            'mode' => 'pendaftaran'
        ])->with('info', 'Halaman edit form khusus tidak tersedia. Silakan kelola status & data peserta melalui halaman Aktivasi.');
    }

    /**
     * PUT /admin/peserta/{id}
     * Simpan perubahan data peserta + jawaban formulir
     */
    public function update(Request $request, $id)
    {
        $peserta = Peserta::with(['user', 'jawabans.pertanyaan'])->findOrFail($id);

        $request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('pengguna_event', 'email')->ignore($peserta->user_id),
            ],
        ]);

        DB::transaction(function () use ($request, $peserta) {
            // 1. Update akun user
            if ($peserta->user) {
                $peserta->user->update([
                    'nama'  => $request->nama,
                    'email' => $request->email,
                ]);
            }

            // 2. Update data peserta (status tetap 'diterima')
            $peserta->nama_pendaftaran = $request->nama;
            $peserta->catatan_admin    = $request->input('catatan_admin');
            $peserta->save();

            // 3. Update setiap jawaban formulir
            foreach ($peserta->jawabans as $jawaban) {
                $key  = "answers.{$jawaban->id}";
                $tipe = strtolower($jawaban->pertanyaan?->tipe_input ?? 'text');

                // FILE / GAMBAR
                if (in_array($tipe, ['file', 'upload_gambar', 'upload gambar', 'image', 'gambar'], true)) {
                    if ($request->hasFile($key)) {
                        $request->validate([$key => ['file', 'max:5120']]);

                        $old = is_array($jawaban->jawaban)
                            ? ($jawaban->jawaban[0] ?? null)
                            : $jawaban->jawaban;

                        if ($old && is_string($old) && Storage::disk('public')->exists($old)) {
                            Storage::disk('public')->delete($old);
                        }

                        $jawaban->update([
                            'jawaban' => $request->file($key)->store('peserta', 'public'),
                        ]);
                    }
                    continue;
                }

                // CHECKBOX
                if ($tipe === 'checkbox') {
                    $val = $request->input($key, []);
                    $jawaban->update([
                        'jawaban' => is_array($val) ? json_encode($val, JSON_THROW_ON_ERROR) : $val,
                    ]);
                    continue;
                }

                // TEXT / TEXTAREA / SELECT / RADIO
                if ($request->has($key)) {
                    $jawaban->update(['jawaban' => $request->input($key)]);
                }
            }
        });

        return redirect()
            ->route('admin.peserta.show', $peserta->id)
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * DELETE /admin/peserta/{id}
     * Hapus peserta
     */
    public function destroy(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        DB::transaction(function () use ($peserta) {
            // Hapus semua relasi — biar FK nggak nge-block
            \App\Models\JawabanPeserta::where('peserta_id', $peserta->id)->delete();
            \App\Models\PembayaranEvent::where('peserta_id', $peserta->id)->delete();
            \App\Models\PembayaranRegistrasi::where('peserta_id', $peserta->id)->delete();
            \App\Models\Voting::where('peserta_id', $peserta->id)->delete();

            // Baru hapus peserta
            $peserta->delete();
        });

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data peserta, jawaban, pembayaran, dan voting berhasil dihapus.');
    }

    /**
     * POST /admin/peserta/{id}/hasil-audisi
     * Update hasil audisi + catatan admin (opsional)
     */
    public function updateHasilAudisi(Request $request, $id)
    {
        $request->validate([
            'hasil_audisi'  => 'required|in:lolos,tidak_lolos',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $peserta = Peserta::findOrFail($id);
        $service = app(\App\Services\PesertaAlurService::class);

        // 1. Update status via service — biar tampil_di_publik di-handle
        if ($request->hasil_audisi === 'lolos') {
            $service->terima($peserta);
        } else {
            $service->tolak($peserta, $request->catatan_admin);
        }

        // 2. Update field audisi
        $updateData = [
            'hasil_audisi' => $request->hasil_audisi,
        ];
        if ($request->hasil_audisi === 'lolos') {
            $updateData['sudah_audisi']   = true;
            $updateData['tanggal_audisi'] = now();
        }

        if ($request->has('catatan_admin')) {
            $peserta->catatan_admin = $request->catatan_admin;
        }

        $peserta->save();

        // Return JSON untuk AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status audisi berhasil diperbarui.',
                'data'    => [
                    'id'            => $peserta->id,
                    'hasil_audisi'  => $peserta->hasil_audisi,
                    'catatan_admin' => $peserta->catatan_admin,
                    'nama'          => $peserta->namaTampil(),
                ],
            ]);
        }

        return redirect()
            ->route('admin.dashboard', ['event' => $peserta->event_id])
            ->with('success', 'Status audisi ' . $peserta->namaTampil() . ' berhasil diperbarui.');
    }

    /**
     * GET /admin/peserta/jawaban/{jawaban}/file
     * Download file jawaban
     */
    public function downloadJawabanFile(JawabanPeserta $jawaban)
    {
        $filePath = $jawaban->jawaban;

        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
        }

        if ($filePath && str_contains($filePath, '?')) {
            $filePath = parse_url($filePath, PHP_URL_PATH);
        }

        if (!$filePath) {
            abort(404, 'File tidak ditemukan');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di server');
        }

        $extension     = pathinfo($filePath, PATHINFO_EXTENSION);
        $peserta       = $jawaban->peserta;
        $user          = $peserta?->user;
        $namaPeserta   = $user?->nama ?? 'Peserta';
        $emailPeserta  = $user?->email ?? 'tanpa-email';
        $tanggalDaftar = $peserta?->created_at?->format('Y-m-d') ?? date('Y-m-d');

        $namaSanitized  = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', $namaPeserta);
        $emailSanitized = preg_replace('/[^a-zA-Z0-9_\-.@]/', '_', $emailPeserta);
        $newFilename    = "{$namaSanitized}_{$emailSanitized}_{$tanggalDaftar}.{$extension}";

        return Storage::disk('public')->download($filePath, $newFilename);
    }

    /**
     * DELETE /admin/events/{event}/questions/clear
     * Hapus semua pertanyaan event
     */
    public function clearQuestions(Request $request, Event $event)
    {
        $event->pertanyaans()->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Semua pertanyaan berhasil dihapus.']);
        }

        return redirect()->back()->with('success', 'Semua pertanyaan berhasil dihapus.');
    }

    /**
     * GET /admin/events/{event}/peserta/export
     * Export data peserta ke CSV
     */
    public function export(Event $event)
    {
        $pesertas = Peserta::with(['user', 'jawabans.pertanyaan'])
            ->where('event_id', $event->id)
            ->latest('id')
            ->get();

        $filename = 'Data_Peserta_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $event->nama) . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($pesertas) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, [
                'No',
                'Nama Peserta',
                'Email',
                'No. WhatsApp',
                'Status',
                'Status Pembayaran',
                'Tanggal Daftar',
            ]);

            // Data per baris
            foreach ($pesertas as $index => $peserta) {
                $nama  = $peserta->nama_pendaftaran ?? $peserta->user?->nama ?? 'Tanpa Nama';
                $email = $peserta->user?->email ?? 'Tanpa Email';

                // Cari WhatsApp
                $whatsapp = '-';
                foreach ($peserta->jawabans as $jawaban) {
                    $label = strtolower($jawaban->pertanyaan?->label ?? '');
                    if (str_contains($label, 'whatsapp') || str_contains($label, 'wa') || str_contains($label, 'no. hp') || str_contains($label, 'telepon')) {
                        $whatsapp = is_array($jawaban->jawaban) ? implode(', ', $jawaban->jawaban) : ($jawaban->jawaban ?? '-');
                        break;
                    }
                }

                $status      = 'Diterima'; // ✅ Hanya ada 1 status
                $pembayaran  = $peserta->pembayaran->sortByDesc('id')->first();
                $statusBayar = $pembayaran?->status_midtrans ?? 'Belum Bayar';
                $tanggal     = $peserta->created_at?->format('d M Y H:i') ?? '-';

                fputcsv($file, [
                    $index + 1,
                    $nama,
                    $email,
                    $whatsapp,
                    $status,
                    ucfirst($statusBayar),
                    $tanggal,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * GET /admin/events/{event}/peserta/template-csv
     * Download template CSV untuk import
     */
    public function downloadCsvTemplate(Event $event)
    {
        $questions = $event->pertanyaans()
            ->whereNotIn('tipe_input', ['heading', 'subheading'])
            ->orderBy('urutan')
            ->get();

        $headers = ['nama', 'email'];

        foreach ($questions as $q) {
            $label = trim($q->label);
            if (!in_array(strtolower($label), ['nama', 'nama lengkap', 'email', 'e-mail', 'alamat email'], true)) {
                $headers[] = $label;
            }
        }

        $filename = 'template_import_peserta_' . Str::slug($event->nama) . '.csv';

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $headers);

            $sampleRow = [];
            foreach ($headers as $h) {
                $sampleRow[] = 'Contoh ' . ucfirst($h);
            }
            fputcsv($file, $sampleRow);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    /**
     * POST /admin/events/{event}/peserta/import
     * Import CSV peserta
     */
    public function importCsv(Request $request, Event $event)
    {
        $file = $request->file('file_csv');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return redirect()->back()->with('error', 'Gagal membaca file CSV.');
        }

        // Deteksi delimiter
        $firstLine = fgets($handle);
        rewind($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

        $headers = fgetcsv($handle, 1000, $delimiter);

        if (!$headers) {
            fclose($handle);
            return redirect()->back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Bersihkan BOM
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $headers    = array_map(fn($h) => strtolower(trim($h)), $headers);

        // Petakan pertanyaan
        $questions = $event->pertanyaans()
            ->whereNotIn('tipe_input', ['heading', 'subheading'])
            ->get()
            ->keyBy(fn($q) => strtolower(trim($q->label)));

        $importedCount = 0;

        DB::transaction(function () use ($handle, $headers, $questions, $event, &$importedCount, $delimiter) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (count($row) !== count($headers)) {
                    continue;
                }

                $dataRow   = array_combine($headers, $row);
                $rowValues = array_values($dataRow);

                $nama  = null;
                $email = null;

                foreach ($dataRow as $key => $val) {
                    if (in_array($key, ['nama', 'nama lengkap', 'nama peserta'])) {
                        $nama = trim($val);
                    }
                    if (in_array($key, ['email', 'e-mail', 'alamat email'])) {
                        $email = trim($val);
                    }
                }

                if (empty($nama) && isset($rowValues[0])) {
                    $nama = trim($rowValues[0]);
                }
                if (empty($email) && isset($rowValues[1])) {
                    $email = trim($rowValues[1]);
                }

                if (empty($nama) || str_contains(strtolower($nama), 'contoh')) {
                    $nama = 'Peserta Import';
                }
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                // Cek user existing
                $existingUser = PenggunaEvent::where('email', $email)->first();
                if ($existingUser) {
                    $sudahDaftar = Peserta::where('event_id', $event->id)
                        ->where('user_id', $existingUser->id)
                        ->exists();
                    if ($sudahDaftar) {
                        continue;
                    }
                }

                // 1. Buat / Ambil Akun
                $user = PenggunaEvent::firstOrCreate(
                    ['email' => $email],
                    [
                        'google_id' => 'import-' . Str::uuid(),
                        'nama'      => $nama,
                    ]
                );

                if ($user->nama !== $nama && $nama !== 'Peserta Import') {
                    $user->update(['nama' => $nama]);
                }

                $pesertaData = [
                    'user_id'          => $user->id,
                    'nama_pendaftaran' => $nama,
                    'status'           => 'diterima',
                    'tampil_di_publik' => true,         
                ];

                if (Schema::hasColumn('peserta_event', 'updated_at')) {
                    $pesertaData['updated_at'] = now();
                }

                $peserta = $event->pesertas()->create($pesertaData);
                $importedCount++;

                // 3. Buat pembayaran otomatis jika ada biaya
                if ($event->biaya_pendaftaran > 0) {
                    $jumlah = (int) $event->biaya_pendaftaran;

                    PembayaranEvent::create([
                        'peserta_id'        => $peserta->id,
                        'jumlah'            => $jumlah,
                        'tarif_dasar'       => $jumlah,
                        'metode'            => 'transfer_bank',
                        'midtrans_order_id' => 'PPA-' . date('Ymd') . '-' . str_pad($peserta->id, 4, '0', STR_PAD_LEFT),
                        'status_midtrans'   => 'pending',
                        'tanggal_bayar'     => null,
                    ]);
                }

                // 4. Simpan Jawaban
                foreach ($dataRow as $columnName => $value) {
                    if (in_array($columnName, ['nama', 'nama lengkap', 'nama peserta', 'email', 'e-mail', 'alamat email'])) {
                        continue;
                    }

                    if (isset($questions[$columnName]) && !empty(trim($value))) {
                        $question = $questions[$columnName];
                        JawabanPeserta::create([
                            'peserta_id'    => $peserta->id,
                            'pertanyaan_id' => $question->id,
                            'jawaban'       => trim($value),
                        ]);
                    }
                }
            }
        });

        fclose($handle);

        return redirect()->back()->with('success', "Berhasil mengimpor {$importedCount} data peserta baru!");
    }

    /**
     * POST /admin/kandidat/{event}
     * Simpan kandidat voting langsung
     */
    public function storeKandidat(Request $request, Event $event)
    {
        $request->validate([
            'nama'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $peserta = DB::transaction(function () use ($request, $event) {
            // 1. Cari atau buat user
            $user = \App\Models\PenggunaEvent::query()->firstOrCreate(
                ['email' => $request->email],
                [
                    'google_id' => 'kandidat-voting-' . \Illuminate\Support\Str::uuid(),
                    'nama'      => $request->nama,
                ]
            );

            if ($user->nama !== $request->nama) {
                $user->update(['nama' => $request->nama]);
            }

            // 2. Cek duplikasi kandidat voting
            $sudahDaftar = Peserta::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->where('kandidat_voting', true)
                ->exists();

            if ($sudahDaftar) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'Email ini sudah terdaftar sebagai kandidat voting.',
                ]);
            }

            $pesertaData = [
            'user_id'          => $user->id,
            'nama_pendaftaran' => $request->nama,
            'status'           => 'diterima',
            'tampil_di_publik' => true,     
            'kandidat_voting'  => true,
        ];

            if (\Illuminate\Support\Facades\Schema::hasColumn('peserta_event', 'updated_at')) {
                $pesertaData['updated_at'] = now();
            }

            $peserta = $event->pesertas()->create($pesertaData);

            // 4. Buat pembayaran dengan status PENDING agar admin bisa menginput manual nanti
            $jumlah = (int) $event->biaya_pendaftaran;
            if ($jumlah > 0) {
                \App\Models\PembayaranEvent::create([
                    'peserta_id'        => $peserta->id,
                    'jumlah'            => 0, // Mulai dari 0, admin yang isi nominal di modal
                    'tarif_dasar'       => $jumlah,
                    'metode'            => null,
                    'midtrans_order_id' => 'KANDIDAT-' . date('YmdHis') . '-' . $peserta->id,
                    'status_midtrans'   => 'settlement',
                    'tanggal_bayar'     => now(),
                ]);
            }

            return $peserta;
        });

        return redirect()->back()->with('success', 'Kandidat voting "' . $request->nama . '" berhasil ditambahkan.');
    }

    /**
     * POST /admin/kandidat/pembayaran
     * Simpan pembayaran manual untuk kandidat voting
     */
        /**
     * POST /admin/kandidat/pembayaran
     * Simpan pembayaran manual untuk kandidat voting
     */
    public function storePembayaranManual(Request $request)
    {
        $request->validate([
            'peserta_id'    => 'required|exists:peserta_event,id',
            'total_biaya'   => 'required|numeric|min:0',
            'jumlah_bayar'  => 'required|numeric|min:1',
            'metode'        => 'required|in:transfer_bank,tunai,qris',
            'tanggal_bayar' => 'required|date',
        ]);

        $peserta = Peserta::findOrFail($request->peserta_id);
        $totalBiaya = (int) $request->total_biaya;
        $jumlahBayar = (int) $request->jumlah_bayar;

        // ✅ Normalisasi waktu bayar: pastikan ada jam (bukan 00:00 dari input date saja)
        $rawTanggal = $request->input('tanggal_bayar');
        $tanggalBayar = \Carbon\Carbon::parse($rawTanggal, 'Asia/Jakarta');
        $hanyaTanggal = !preg_match('/[T\s]\d{1,2}:\d{2}/', (string) $rawTanggal);
        if ($hanyaTanggal || $tanggalBayar->format('H:i:s') === '00:00:00') {
            $tanggalBayar = $tanggalBayar->setTimeFrom(\Carbon\Carbon::now('Asia/Jakarta'));
        }

        DB::transaction(function () use ($request, $peserta, $totalBiaya, $jumlahBayar, $tanggalBayar) {
            $pembayaran = PembayaranEvent::where('peserta_id', $peserta->id)
                ->orderBy('id', 'desc')
                ->first();

            $sudahDibayar = $pembayaran?->jumlah ?? 0;
            $totalSekarang = $sudahDibayar + $jumlahBayar;

            $isLunas = $totalSekarang >= $totalBiaya;
            $status = $isLunas ? 'settlement' : 'pending';

            if ($pembayaran) {
                $pembayaran->update([
                    'jumlah'          => $totalSekarang,
                    'tarif_dasar'     => $totalBiaya,
                    'metode'          => $request->metode,
                    'status_midtrans' => $status,
                    'tanggal_bayar'   => $tanggalBayar,
                ]);
            } else {
                PembayaranEvent::create([
                    'peserta_id'        => $peserta->id,
                    'jumlah'            => $totalSekarang,
                    'tarif_dasar'       => $totalBiaya,
                    'metode'            => $request->metode,
                    'midtrans_order_id' => 'MANUAL-' . date('YmdHis') . '-' . $peserta->id,
                    'status_midtrans'   => $status,
                    'tanggal_bayar'     => $tanggalBayar,
                ]);
            }
        });

        if ($request->expectsJson() || $request->ajax()) {
            $peserta->load('pembayaran');
            $pembayaranTerbaru = $peserta->pembayaran->sortByDesc('id')->first();
            $sudahDibayarBaru  = (int) ($pembayaranTerbaru?->jumlah ?? 0);
            $isLunasBaru       = $sudahDibayarBaru >= $totalBiaya && $pembayaranTerbaru?->status_midtrans === 'settlement';

            return response()->json([
                'success'       => true,
                'message'       => 'Pembayaran kandidat berhasil disimpan!',
                'peserta_id'    => $peserta->id,
                'sudah_dibayar' => $sudahDibayarBaru,
                'total_biaya'   => $totalBiaya,
                'sisa'          => max(0, $totalBiaya - $sudahDibayarBaru),
                'is_lunas'      => $isLunasBaru,
                'riwayat'       => $peserta->pembayaran->values(),
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran kandidat berhasil disimpan!');
    }
}
