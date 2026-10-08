<?php

namespace App\Http\Controllers\Admin;

use App\Models\Actualite;
use App\Support\TexteRiche;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ActualiteController extends Controller
{
    public function index(Request $request): View
    {
        $actualites = Actualite::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('title', 'like', "%{$recherche}%"))
            ->when($request->query('categorie'), fn ($q, $categorie) => $q->whereJsonContains('categories', $categorie))
            ->orderByDesc('date')->orderByDesc('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        $toutes = Actualite::orderByDesc('date')->get(['title', 'date', 'categories', 'vues']);
        $mois = collect(range(11, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $graphiques = [
            'lus' => $toutes->sortByDesc('vues')->take(8)->values(),
            'mois' => $mois->map(fn ($m) => ucfirst($m->locale('fr')->translatedFormat('M y'))),
            'publications' => collect(Actualite::CATEGORIES)->map(fn ($nom, $cle) => [
                'label' => $nom,
                'data' => $mois->map(fn ($m) => $toutes->filter(fn ($a) => $a->date->isSameMonth($m) && in_array($cle, $a->categories))->count()),
            ])->values(),
        ];

        return view('admin.actualites.index', compact('actualites', 'graphiques'));
    }

    public function create(): View
    {
        return view('admin.actualites.form', ['actualite' => new Actualite(['date' => now(), 'categories' => ['actualites'], 'publie' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $actualite = new Actualite();
        $this->enregistrer($request, $actualite);

        return redirect()->route('admin.actualites.index')->with('succes', 'Article publié.');
    }

    public function edit(Actualite $actualite): View
    {
        return view('admin.actualites.form', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite): RedirectResponse
    {
        $this->enregistrer($request, $actualite);

        return redirect()->route('admin.actualites.index')->with('succes', 'Article mis à jour.');
    }

    public function destroy(Actualite $actualite): RedirectResponse
    {
        $this->supprimerPhoto($actualite->photo);
        foreach ($actualite->gallery ?? [] as $photo) {
            $this->supprimerPhoto($photo);
        }
        $actualite->delete();

        return redirect()->route('admin.actualites.index')->with('succes', 'Article supprimé.');
    }

    /** Image insérée dans le texte depuis l'éditeur (réponse attendue par TinyMCE : { location }). */
    public function image(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096']], self::MESSAGES);

        $chemin = $request->file('file')->store('actualites/contenu', 'uploads');

        return response()->json(['location' => parse_url(asset(Actualite::DOSSIER_UPLOADS . '/' . $chemin), PHP_URL_PATH)]);
    }

    private function enregistrer(Request $request, Actualite $actualite): void
    {
        $donnees = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'categories' => ['required', 'array'],
            'categories.*' => [Rule::in(array_keys(Actualite::CATEGORIES))],
            'corps' => ['required', 'string', 'max:500000'],
            'tags' => ['nullable', 'string', 'max:500'],
            'photo' => self::REGLE_IMAGE,
            'galerie' => ['nullable', 'array', 'max:12'],
            'galerie.*' => self::REGLE_IMAGE,
            'retirer_galerie' => ['nullable', 'array'],
        ], self::MESSAGES + ['categories.required' => 'Choisissez au moins une catégorie.']);

        $corps = TexteRiche::nettoyer($donnees['corps']);
        $paragraphes = TexteRiche::paragraphes($corps);
        if ($paragraphes === []) {
            throw ValidationException::withMessages(['corps' => 'Rédigez le texte de l’article.']);
        }

        // Galerie : retrait des photos cochées, ajout des nouvelles
        $retirees = $request->input('retirer_galerie', []);
        $galerie = collect($actualite->gallery ?? [])
            ->reject(function ($photo) use ($retirees) {
                if (in_array($photo, $retirees, true)) {
                    $this->supprimerPhoto($photo);

                    return true;
                }

                return false;
            })
            ->merge(collect($request->file('galerie', []))->map(fn ($fichier) => $fichier->store('actualites/galerie', 'uploads')))
            ->values()
            ->all();

        $actualite->fill([
            'title' => $donnees['title'],
            'slug' => $this->slug(Actualite::class, $donnees['slug'] ?? null, $donnees['title'], $actualite->exists ? $actualite : null),
            'date' => $donnees['date'],
            'categories' => array_values($donnees['categories']),
            'corps' => $corps,
            'content' => $paragraphes,
            'tags' => $this->liste($donnees['tags'] ?? ''),
            'gallery' => $galerie,
            'photo' => $this->photoDemandee($request, $actualite, 'actualites'),
            'publie' => $request->boolean('publie'),
        ])->save();
    }
}
