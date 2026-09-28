<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use App\Models\Jawaban;
use App\Models\Pertanyaan;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class PesertaController extends Controller
{

    public const STATUS_OPTIONS = ['menunggu_review', 'revisi', 'diterima', 'ditolak'];
    /**
     * GET /peserta/:id (admin, owner)
     * Detail peserta dan semua jawaban form.
     */
    public function show(Peserta $peserta): View
    {
        $peserta->load([
            'event',
            'user',
            'jawabans.pertanyaan' => fn ($query) => $query->withTrashed(),
            'pembayaran',
        ]);
 
        return view('admin.peserta.show', compact('peserta'));
    }

    /**
     * PUT /peserta/:id/status (admin/owner)
     * Terima/tolak pendaftaran.
     */
    public function updateStatus(Request $request, Peserta $peserta): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ]);
 
        $peserta->update($data);
 
        $label = match ($data['status']) {
            'diterima' => 'diterima',
            'ditolak' => 'ditolak',
            // 'revisi' => 'diminta revisi',
            default => 'diperbarui',
        };
 
        return redirect()
            ->route('admin.peserta.show', $peserta)
            ->with('success', "Pendaftaran berhasil {$label}.");
    }

    /**
     * PUT /peserta/:id/hasil (admin/owner)
     * Hasil akhir atau skor akhir peserta.
     */
    public function updateHasil(Request $request, Peserta $peserta)
    {
        $this->authorizeAdminOrOwner($request, $peserta);

        $data = $request->validate([
            'hasil_akhir' => ['nullable', 'string', 'max:255'],
            'skor_akhir' => ['nullable', 'numeric'],
        ]);

        $peserta->update($data);

        return response()->json($peserta);
    }

    /**
     * GET /peserta/:id/voter (admin/owner)
     * Riwayat pembelian vote dengan identitas asli (user_id) dan status
     * bayar, untuk keperluan audit.
     */
    public function voterList(Request $request, Peserta $peserta)
    {
        $this->authorizeAdminOrOwner($request, $peserta);

        $votings = $peserta->votingMasuk()
            ->with('voter:id,nama,email')
            ->latest('id')
            ->get();

        return response()->json($votings);
    }

    private function authorizeAdminOrOwner(Request $request, Peserta $peserta): void
    {
        $user = $request->user();
        $isAdmin = $user instanceof AkunStaf;
        $isOwner = $user instanceof PenggunaEvent && $peserta->isOwnedBy($user);

        if (! $isAdmin && ! $isOwner) {
            abort(403, 'Tidak punya akses.');
        }
    }

    public function destroy(Peserta $peserta): RedirectResponse
    {
        $event = $peserta->event;
 
        // Jangan hapus kalau sudah ada riwayat voting (uang sungguhan sudah
        // masuk) - itu butuh proses lain (refund dsb), bukan hapus biasa.
        if ($peserta->votingMasuk()->exists()) {
            return redirect()
                ->route('admin.peserta.show', $peserta)
                ->with('error', 'Peserta ini sudah punya riwayat voting, tidak bisa dihapus langsung.');
        }
 
        // Hapus data anak dulu, karena foreign key di migration pakai
        // restrictOnDelete (bukan cascade), jadi Peserta::delete() langsung
        // akan gagal kalau masih ada jawaban/pembayaran yang nempel.
        $peserta->jawabans()->delete();
        $peserta->pembayaran()->delete();
        $peserta->delete();
 
        return redirect()
            ->route('admin.registrations', $event)
            ->with('success', 'Data peserta berhasil dihapus.');
    }

    public function export(Event $event): StreamedResponse
    {
        // 1. Ambil semua pertanyaan unik milik event ini untuk dijadikan header kolom dinamis
        $pertanyaans = Pertanyaan::where('event_id', $event->id)
            ->whereNotIn('tipe_input', ['heading', 'subheading']) // Abaikan elemen non-input
            ->orderBy('urutan', 'asc') // atau orderBy('id') jika tidak ada kolom urutan
            ->get();

        // 2. Ambil data peserta beserta relasi user dan jawabannya
        $pesertas = Peserta::with(['user', 'jawabans'])
            ->where('event_id', $event->id)
            ->get();

        $fileName = 'data-peserta-' . \Str::slug($event->nama ?? 'event') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($pesertas, $pertanyaans) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM UTF-8 agar karakter spesial & format sel terbaca rapi di Excel
            fputs($file, "\xEF\xBB\xBF");

            // 3. Menyusun Header CSV secara Dinamis
            $csvHeader = ['No', 'Nama', 'Email', 'Status Pendaftaran'];
            foreach ($pertanyaans as $pertanyaan) {
                $csvHeader[] = $pertanyaan->label ?? ('Pertanyaan ' . $pertanyaan->id);
            }
            fputcsv($file, $csvHeader);

            // 4. Menyusun Baris Data Peserta
            foreach ($pesertas as $index => $peserta) {
                // Ubah list jawaban peserta menjadi Key-Value (pertanyaan_id => nilai_jawaban)
                $jawabanMap = $peserta->jawabans->keyBy('pertanyaan_id');

                $row = [
                    $index + 1,
                    $peserta->user?->nama ?? '-',
                    $peserta->user?->email ?? '-',
                    ucwords(str_replace('_', ' ', $peserta->status ?? '-'))
                ];

                // Cocokkan jawaban peserta dengan setiap kolom pertanyaan
                foreach ($pertanyaans as $pertanyaan) {
                    $jawabanObj = $jawabanMap->get($pertanyaan->id);
                    $val = $jawabanObj?->jawaban;

                    if (is_array($val)) {
                        // Jika tipe input checkbox/multiple pilihan (berbentuk array)
                        $row[] = implode(', ', $val);
                    } elseif ($pertanyaan->tipe_input === 'file' || $pertanyaan->tipe_input === 'upload gambar') {
                        // Jika file/gambar, sertakan URL atau path filenya
                        $row[] = $val ? url('storage/' . $val) : '-';
                    } else {
                        $row[] = $val ?? '-';
                    }
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}