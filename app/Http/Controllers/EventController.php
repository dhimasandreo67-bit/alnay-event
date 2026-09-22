<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * GET /event (public)
     * List event, filter status (berlangsung/akan_datang/selesai) dihitung dari tanggal.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('status')) {
            $query->withStatus($request->string('status'));
        }

        return response()->json($query->latest('id')->paginate(15));
    }

    /**
     * GET /event/:id (public)
     * Detail event + syarat ketentuan.
     */
    public function show(Event $event)
    {
        return response()->json($event);
    }
}