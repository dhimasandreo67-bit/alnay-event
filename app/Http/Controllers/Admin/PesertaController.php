<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunStaf;
use App\Models\PenggunaEvent;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * GET /peserta/:id (admin, owner)
     * Detail peserta dan semua jawaban form.
     */
    public function show(Request $request, Peserta $peserta)
    {
        $this->authorizeAdminOrOwner($request, $peserta);

        return response()->json(
            $peserta->load(['event', 'user', 'jawabans.pertanyaan', 'pembayaran'])
        );
    }

    /**
     * PUT /peserta/:id/status (admin/owner)
     * Terima/tolak pendaftaran.
     */
    public function updateStatus(Request $request, Peserta $peserta)
    {
        $this->authorizeAdminOrOwner($request, $peserta);

        $data = $request->validate([
            'status' => ['required', 'in:menunggu_pembayaran,diterima,ditolak'],
            'catatan_admin' => ['nullable', 'string'],
        ]);

        // TODO: kalau mau lebih ketat, batasi supaya cuma admin yang boleh
        // set 'diterima'/'ditolak', owner cuma boleh baca.

        $peserta->update($data);

        return response()->json($peserta);
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
}