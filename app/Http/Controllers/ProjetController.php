<?php

namespace App\Http\Controllers;

use App\Support\Contenu;
use Illuminate\View\View;

class ProjetController extends Controller
{
    public function index(): View
    {
        return view('projets.index', [
            'projets' => Contenu::projets(),
            'categories' => config('projets.categories'),
        ]);
    }

    public function show(string $slug): View
    {
        return view('projets.show', [
            'projet' => Contenu::projet($slug),
            'categories' => config('projets.categories'),
        ]);
    }
}
