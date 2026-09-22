<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

class PendaftaranController extends Controller
{
    /**
     * GET /event/:id/pendaftar (admin)
     * Daftar semua pendaftar event.
     */
    public function indexPendaftar(Event $event)
    {
        $pesertas = $event->pesertas()
            ->with(['user', 'pembayaran', 'jawabans.pertanyaan'])
            ->latest('id')
            ->get();

        return response()->json($pesertas);
    }
}