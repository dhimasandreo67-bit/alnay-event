<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PesertaController extends Controller
{
    /**
     * GET /event/:id/peserta (public)
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
}