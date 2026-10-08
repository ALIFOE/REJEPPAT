<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Support\Contenu;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ActualiteController extends Controller
{
    private const PAR_PAGE = 9;

    public function index(Request $request): View
    {
        $categorie = $request->query('categorie');
        $recherche = trim((string) $request->query('q'));

        $actualites = Contenu::actualites()
            ->when(isset(Contenu::CATEGORIES_ACTUALITES[$categorie]), fn ($liste) => $liste->filter(fn ($a) => in_array($categorie, $a['categories'])))
            ->when($recherche !== '', fn ($liste) => $liste->filter(fn ($a) => Str::contains(
                Str::ascii($a['title'] . ' ' . implode(' ', $a['content'])),
                Str::ascii($recherche),
                ignoreCase: true
            )))
            ->values();

        $page = LengthAwarePaginator::resolveCurrentPage();
        $pagination = new LengthAwarePaginator(
            $actualites->forPage($page, self::PAR_PAGE)->values(),
            $actualites->count(),
            self::PAR_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('actualites.index', [
            'actualites' => $pagination,
            'categorie' => $categorie,
            'recherche' => $recherche,
        ]);
    }

    public function show(string $slug): View
    {
        $toutes = Contenu::actualites();
        $actualite = Contenu::actualite($slug);
        Actualite::withoutTimestamps(fn () => $actualite->increment('vues'));
        $position = $toutes->search(fn ($a) => $a['slug'] === $slug);

        return view('actualites.show', [
            'actualite' => $actualite,
            'precedente' => $toutes->get($position + 1),
            'suivante' => $position > 0 ? $toutes->get($position - 1) : null,
            'recentes' => $toutes->reject(fn ($a) => $a['slug'] === $slug)->take(4),
            'compteurs' => collect(Contenu::CATEGORIES_ACTUALITES)->map(fn ($nom, $cle) => $toutes->filter(fn ($a) => in_array($cle, $a['categories']))->count()),
            'tags' => $toutes->pluck('tags')->flatten()->unique()->values(),
        ]);
    }
}
