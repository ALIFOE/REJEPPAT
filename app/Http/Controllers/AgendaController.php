<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Agenda : ateliers, formations, panels, foires… */
class AgendaController extends Controller
{
    public function index(Request $request): View
    {
        $type = array_key_exists((string) $request->query('type'), Evenement::TYPES) ? $request->query('type') : null;

        return view('agenda.index', [
            'aVenir' => Evenement::publie()->aVenir()->when($type, fn ($q) => $q->where('type', $type))->get(),
            'passes' => Evenement::publie()->passe()->when($type, fn ($q) => $q->where('type', $type))->take(6)->get(),
            'type' => $type,
        ]);
    }

    public function show(string $slug): View
    {
        return view('agenda.show', [
            'evenement' => Evenement::publie()->where('slug', $slug)->firstOrFail(),
            'prochains' => Evenement::publie()->aVenir()->where('slug', '!=', $slug)->take(4)->get(),
        ]);
    }
}
