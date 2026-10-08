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
        return view('fermes.show', [
            'ferme' => Contenu::ferme($slug),
            'fermes' => Contenu::fermes(),
            'actualites' => Contenu::actualites()->take(4),
        ]);
    }
}
