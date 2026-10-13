<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Voting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilUserController extends Controller
{
    public function index()
    {
        $user = Auth::guard('pelanggan')->user();

        $totalVote = Voting::where('user_id', $user->id)
            ->where('status_midtrans', 'settlement')
            ->sum('jumlah_vote');

        $totalEvent = Peserta::where('user_id', $user->id)
            ->distinct('event_id')
            ->count('event_id');

        $pendaftarans = Peserta::with(['event', 'pembayaran', 'jawaban.pertanyaan', 'user'])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return view('profil-user', [
            'user'         => $user,
            'totalVote'    => $totalVote,
            'totalEvent'   => $totalEvent,
            'pendaftarans' => $pendaftarans,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('pelanggan')->user();

        $data = $request->validate([
            'nama_custom' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($data);

        return redirect()->route('profil-user')->with('status', 'Profil berhasil diperbarui.');
    }

    public function uploadFoto(Request $request)
    {
        $user = Auth::guard('pelanggan')->user();

        $request->validate([
            'foto' => ['required', 'image', 'max:2048'],
        ]);

        if ($user->foto_custom && Storage::disk('public')->exists($user->foto_custom)) {
            Storage::disk('public')->delete($user->foto_custom);
        }

        $path = $request->file('foto')->store('foto-user', 'public');
        $user->update(['foto_custom' => $path]);

        return redirect()->route('profil-user')->with('status', 'Foto berhasil diperbarui.');
    }

    public function hapusFoto()
    {
        $user = Auth::guard('pelanggan')->user();

        if ($user->foto_custom && Storage::disk('public')->exists($user->foto_custom)) {
            Storage::disk('public')->delete($user->foto_custom);
        }

        $user->update(['foto_custom' => null]);

        return redirect()->route('profil-user')->with('status', 'Foto berhasil dihapus.');
    }

    /**
     * GET /profil-user/pendaftaran/{peserta}
     * Lihat data pendaftaran milik user (READ-ONLY)
     */
    public function showPendaftaran(Request $request, $pesertaId)
    {
        $peserta = Peserta::with(['event', 'jawabans.pertanyaan'])->findOrFail($pesertaId);

        // 🔒 KEAMANAN: hanya pemilik data yang boleh melihat
        abort_unless($peserta->user_id === $request->user()->id, 403);

        // Urutkan jawaban sesuai urutan pertanyaan & buang heading/subheading
        $jawabans = $peserta->jawabans
            ->filter(fn ($j) => $j->pertanyaan && !in_array(strtolower($j->pertanyaan->tipe_input), ['heading', 'subheading'], true))
            ->sortBy(fn ($j) => $j->pertanyaan->urutan ?? 0)
            ->values();

        return view('pendaftaran-show', [
            'event'    => $peserta->event,
            'peserta'  => $peserta,
            'jawabans' => $jawabans,
        ]);
    }
}