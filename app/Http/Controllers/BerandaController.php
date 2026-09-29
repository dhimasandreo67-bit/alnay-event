<?php

namespace App\Http\Controllers;

use App\Models\Event;

class BerandaController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->where('tampil_di_beranda', true)
            ->latest('id')
            ->get();

        return view('beranda', [
            'events' => $events,
        ]);
    }
}