<?php

namespace App\Http\Controllers;

use App\Models\Opportunite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpportuniteController extends Controller
{
    public function index(Request $request): View
    {
        $type = array_key_exists((string) $request->query('type'), Opportunite::TYPES) ? $request->query('type') : null;
        $archives = $request->boolean('archives');

        $opportunites = Opportunite::publie()
            ->when($type, fn ($q) => $q->where('type', $type))
            ->when(
                $archives,
                fn ($q) => $q->whereDate('date_limite', '<', today())->orderByDesc('date_limite'),
                fn ($q) => $q->ouverte()->orderByRaw('date_limite is null')->orderBy('date_limite')
            )
            ->latest()
            ->paginate(10)->withQueryString();

        return view('espace-jeunes.opportunites', [
            'opportunites' => $opportunites,
            'type' => $type,
            'archives' => $archives,
            'compteurs' => Opportunite::publie()->ouverte()->selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
        ]);
    }

    public function show(string $slug): View
    {
        return view('espace-jeunes.opportunite', [
            'opportunite' => Opportunite::publie()->where('slug', $slug)->firstOrFail(),
            'autres' => Opportunite::publie()->ouverte()->where('slug', '!=', $slug)->latest()->take(4)->get(),
        ]);
    }
}
