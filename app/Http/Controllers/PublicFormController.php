<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicFormController extends Controller
{
    public function show(Event $event)
    {
        // Karena sudah diset $casts = ['form_fields' => 'array'] di model, 
        // variabel $fields langsung berupa array.
        $fields = $event->form_fields ?? [];

        return view('public.form-builder-render', compact('event', 'fields'));
    }
}