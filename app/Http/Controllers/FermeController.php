<?php

namespace App\Http\Controllers;

use App\Support\Contenu;
use Illuminate\View\View;

class FermeController extends Controller
{
    public function index(): View
    {
        return view('fermes.index', [
            'fermes' => Contenu::fermes(),
            'actualites' => Contenu::actualites()->take(4),
        ]);
    }

    public function show(string $slug): View
    {
        $ferme = Contenu::ferme($slug)->load('region');

        return view('fermes.show', [
            'ferme' => $ferme,
            'temoignages' => $ferme->temoignages()->publie()->get(),
            'fermes' => Contenu::fermes(),
            'actualites' => Contenu::actualites()->take(4),
        ]);
    }
}
