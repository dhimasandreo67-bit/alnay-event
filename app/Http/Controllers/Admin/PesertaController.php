<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class PesertaController extends Controller
{
    /**
     * GET /event/:id/peserta (public, API JSON)
     * Daftar peserta yang sudah diterima.
     */
    public function indexDiterima(Event $event)
    {
        $pesertas = $event->pesertas()
            ->where('status', 'diterima')
            ->with('user:id,nama,foto_url')
            ->get();

        return response()->json($pesertas);
    }

    /**
     * GET /event/:id/kandidat (public, halaman web)
     * Daftar kandidat yang bisa divote untuk satu event.
     */
    public function kandidat(Event $event)
    {
        $pesertas = $event->pesertas()
            ->where('status', 'diterima')
            ->with('user:id,nama,foto_url')
            ->orderByDesc('total_vote')
            ->get();

        return view('event.peserta', [
            'event' => $event,
            'pesertas' => $pesertas,
        ]);
    }
}   