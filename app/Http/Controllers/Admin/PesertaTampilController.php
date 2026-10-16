<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaTampilController extends Controller
{
    /**
     * GET /admin/peserta-tampil/{event?}
     * Halaman kelola tampil/sembunyi peserta
     */
    public function index(?Event $event = null)
    {
        // Logika event aktif
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
            'event' => $event,
            'events' => Event::query()->latest('id')->get(['id', 'nama']),
            'pesertas' => $pesertas,
        ]);
    }

    /**
     * POST /admin/peserta-tampil/{peserta}/toggle
     * Toggle per peserta — AJAX
     */
    public function toggle(Peserta $peserta)
    {
        $peserta->tampil_di_publik = !$peserta->tampil_di_publik;
        $peserta->save();

        return response()->json([
            'success' => true,
            'tampilDiPublik' => (bool) $peserta->tampil_di_publik,
            'message' => $peserta->namaTampil() . ' ' . ($peserta->tampil_di_publik ? 'ditampilkan' : 'disembunyikan') . ' di publik.',
        ]);
    }

    /**
     * POST /admin/peserta-tampil/{event}/toggle-semua
     * Toggle massal
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
}