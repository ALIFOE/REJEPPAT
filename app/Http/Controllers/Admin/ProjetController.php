<?php

namespace App\Http\Controllers\Admin;

use App\Models\Projet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjetController extends Controller
{
    public function index(Request $request): View
    {
        $projets = Projet::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('titre', 'like', "%{$recherche}%"))
            ->orderBy('ordre')->orderBy('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.projets.index', compact('projets'));
    }

    public function create(): View
    {
        return view('admin.projets.form', ['projet' => new Projet([
            'date' => now(),
            'categories' => [],
            'icone' => 'icon-farm-house-1',
            'ordre' => Projet::max('ordre') + 1,
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Projet());

        return redirect()->route('admin.projets.index')->with('succes', 'Projet ajouté.');
    }

    public function edit(Projet $projet): View
    {
        return view('admin.projets.form', compact('projet'));
    }

    public function update(Request $request, Projet $projet): RedirectResponse
    {
        $this->enregistrer($request, $projet);

        return redirect()->route('admin.projets.index')->with('succes', 'Projet mis à jour.');
    }

    public function destroy(Projet $projet): RedirectResponse
    {
        $this->supprimerPhoto($projet->photo);
        $projet->delete();

        return redirect()->route('admin.projets.index')->with('succes', 'Projet supprimé.');
    }

    private function enregistrer(Request $request, Projet $projet): void
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'titre_court' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:255'],
            'icone' => ['required', Rule::in(array_keys(Projet::ICONES))],
            'categories' => ['required', 'array'],
            'categories.*' => [Rule::in(array_keys(config('projets.categories')))],
            'date' => ['required', 'date'],
            'resume' => ['required', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'actions' => ['nullable', 'string'],
            'resultats' => ['nullable', 'string'],
            'points' => ['nullable', 'string'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ], self::MESSAGES + ['categories.required' => 'Choisissez au moins une catégorie.']);

        $projet->fill([
            'titre' => $donnees['titre'],
            'titre_court' => $donnees['titre_court'],
            'slug' => $this->slug(Projet::class, $donnees['slug'] ?? null, $donnees['titre_court'], $projet->exists ? $projet : null),
            'icone' => $donnees['icone'],
            'categories' => array_values($donnees['categories']),
            'date' => $donnees['date'],
            'resume' => $donnees['resume'],
            'description' => $this->lignes($donnees['description']),
            'actions' => $this->paires($donnees['actions'] ?? ''),
            'resultats' => $this->lignes($donnees['resultats'] ?? ''),
            'points' => $this->lignes($donnees['points'] ?? ''),
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $projet, 'projets'),
            'publie' => $request->boolean('publie'),
        ])->save();
    }
}
