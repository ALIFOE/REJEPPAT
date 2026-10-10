<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ressource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RessourceController extends Controller
{
    public function index(Request $request): View
    {
        $ressources = Ressource::query()
            ->when($request->query('categorie'), fn ($q, $categorie) => $q->where('categorie', $categorie))
            ->orderBy('ordre')->orderByDesc('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.ressources.index', [
            'ressources' => $ressources,
            'telechargements' => Ressource::sum('telechargements'),
        ]);
    }

    public function create(): View
    {
        return view('admin.ressources.form', ['ressource' => new Ressource(['categorie' => 'guide', 'ordre' => 0, 'publie' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Ressource);

        return redirect()->route('admin.ressources.index')->with('succes', 'Ressource ajoutée.');
    }

    public function edit(Ressource $ressource): View
    {
        return view('admin.ressources.form', compact('ressource'));
    }

    public function update(Request $request, Ressource $ressource): RedirectResponse
    {
        $this->enregistrer($request, $ressource);

        return redirect()->route('admin.ressources.index')->with('succes', 'Ressource mise à jour.');
    }

    public function destroy(Ressource $ressource): RedirectResponse
    {
        $this->supprimerPhoto($ressource->fichier);
        $ressource->delete();

        return redirect()->route('admin.ressources.index')->with('succes', 'Ressource supprimée.');
    }

    private function enregistrer(Request $request, Ressource $ressource): void
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'categorie' => ['required', Rule::in(array_keys(Ressource::CATEGORIES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'fichier' => [$ressource->exists ? 'nullable' : 'required', ...self::REGLE_DOCUMENT],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ] + $this->reglesTraductions(), self::MESSAGES + ['fichier.mimes' => 'Formats acceptés : PDF, Word, Excel, PowerPoint, ODT ou ZIP.']);

        $ressource->fill([
            'titre' => $donnees['titre'],
            'categorie' => $donnees['categorie'],
            'description' => $donnees['description'] ?? null,
            'fichier' => $this->enregistrerFichier($request->file('fichier'), 'ressources', $ressource->fichier),
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Ressource::class),
        ])->save();
    }
}
