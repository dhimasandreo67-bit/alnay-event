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

        $peserta = Peserta::with('event')
            ->where('user_id', $user->id)
            ->where('status', 'diterima')
            ->latest('id')
            ->first();

        return view('profil-user', [
            'user' => $user,
            'totalVote' => $totalVote,
            'totalEvent' => $totalEvent,
            'peserta' => $peserta,
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
}
