<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PertanyaanController extends Controller
{
    /**
     * GET /event/:id/pertanyaan (public)
     * Ambil daftar pertanyaan form pendaftaran event ini, urut sesuai urutan.
     */
    public function index(Event $event)
    {
        return response()->json(
            $event->pertanyaans()->orderBy('urutan')->get()
        );
    }
}