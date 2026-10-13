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
use Illuminate\Database\Eloquent\Model;

class PesertaController extends Controller
{
    /**
     * GET /peserta/:id (admin)
     * Detail peserta dan semua jawaban form.
     */
    public function show(Peserta $peserta)
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
     * PUT /peserta/:id/hasil (admin)
     * Hasil akhir atau skor akhir peserta.
     */
    public function updateHasil(Request $request, Peserta $peserta)
    {
        $this->authorizeAdminOrOwner($request, $peserta);

        $data = $request->validate([
            'hasil_akhir' => ['nullable', 'string', 'max:255'],
            'skor_akhir'  => ['nullable', 'numeric'],
        ]);

        $peserta->update($data);

        return response()->json($peserta);
    }

    /**
     * GET /peserta/:id/voter (admin)
     * Riwayat pembelian vote.
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

    /**
     * DELETE /peserta/:id
     * Hapus peserta.
     */
    public function destroy(Peserta $peserta)
    {
        // Cek jika ada voting
        if ($peserta->votingMasuk()->exists()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Peserta ini sudah punya riwayat voting, tidak bisa dihapus langsung.');
        }

        // Hapus data relasi
        $peserta->jawabans()->delete();
        $peserta->pembayaran()->delete();

        // Hapus peserta
        $peserta->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Data peserta berhasil dihapus.');
    }

    /**
     * GET /events/{event}/peserta/export
     * Export data peserta ke CSV.
     */
    public function export(Event $event): StreamedResponse
    {
        // 1. Ambil pertanyaan untuk header dinamis
        $pertanyaans = Pertanyaan::where('event_id', $event->id)
            ->whereNotIn('tipe_input', ['heading', 'subheading'])
            ->orderBy('urutan', 'asc')
            ->get();

        // 2. Ambil data peserta
        $pesertas = Peserta::with(['user', 'jawabans'])
            ->where('event_id', $event->id)
            ->get();

        $fileName = 'data-peserta-' . \Str::slug($event->nama ?? 'event') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($pesertas, $pertanyaans) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8
            fputs($file, "\xEF\xBB\xBF");

            // Header CSV
            $csvHeader = ['No', 'Nama', 'Email', 'Status Pendaftaran'];
            foreach ($pertanyaans as $pertanyaan) {
                $csvHeader[] = $pertanyaan->label ?? ('Pertanyaan ' . $pertanyaan->id);
            }
            fputcsv($file, $csvHeader);

            // Data peserta
            foreach ($pesertas as $index => $peserta) {
                $jawabanMap = $peserta->jawabans->keyBy('pertanyaan_id');

                $row = [
                    $index + 1,
                    $peserta->user?->nama ?? '-',
                    $peserta->user?->email ?? '-',
                    'Diterima', // ✅ Hanya ada 1 status
                ];

                foreach ($pertanyaans as $pertanyaan) {
                    $jawabanObj = $jawabanMap->get($pertanyaan->id);
                    $val        = $jawabanObj?->jawaban;

                    if (is_array($val)) {
                        $row[] = implode(', ', $val);
                    } elseif ($pertanyaan->tipe_input === 'file' || $pertanyaan->tipe_input === 'upload gambar') {
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

    /**
     * Authorization helper
     */
    private function authorizeAdminOrOwner(Request $request, Peserta $peserta): void
    {
        $user    = $request->user();
        $isAdmin = $user instanceof AkunStaf;
        $isOwner = $user instanceof PenggunaEvent && $peserta->isOwnedBy($user);

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Tidak punya akses.');
        }
    }
}