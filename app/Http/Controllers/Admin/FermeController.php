<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ferme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FermeController extends Controller
{
    public function index(Request $request): View
    {
        $fermes = Ferme::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('nom', 'like', "%{$recherche}%"))
            ->orderBy('ordre')->orderBy('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.fermes.index', compact('fermes'));
    }

    public function create(): View
    {
        return view('admin.fermes.form', ['ferme' => new Ferme([
            'modules' => [],
            'ordre' => Ferme::max('ordre') + 1,
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Ferme());

        return redirect()->route('admin.fermes.index')->with('succes', 'Ferme école ajoutée.');
    }

    public function edit(Ferme $ferme): View
    {
        return view('admin.fermes.form', compact('ferme'));
    }

    public function update(Request $request, Ferme $ferme): RedirectResponse
    {
        $this->enregistrer($request, $ferme);

        return redirect()->route('admin.fermes.index')->with('succes', 'Ferme école mise à jour.');
    }

    public function destroy(Ferme $ferme): RedirectResponse
    {
        $this->supprimerPhoto($ferme->photo);
        $ferme->delete();

        return redirect()->route('admin.fermes.index')->with('succes', 'Ferme école supprimée.');
    }

    private function enregistrer(Request $request, Ferme $ferme): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160'],
            'localisation' => ['nullable', 'string', 'max:255'],
            'specialite' => ['required', 'string', 'max:160'],
            'modules' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:5.5,11.5', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-0.5,2', 'required_with:latitude'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ], self::MESSAGES + [
            'between' => 'Les coordonnées doivent se trouver au Togo.',
            'numeric' => 'Indiquez un nombre (ex. 8.50413).',
            'required_with' => 'Indiquez la latitude et la longitude.',
        ]);

        $carte = filled($donnees['latitude'] ?? null) ? [
            'lat' => (float) $donnees['latitude'],
            'lng' => (float) $donnees['longitude'],
            'adresse' => $donnees['adresse'] ?: ($donnees['localisation'] ?? $donnees['nom']),
        ] : null;

        $ferme->fill([
            'nom' => $donnees['nom'],
            'slug' => $this->slug(Ferme::class, $donnees['slug'] ?? null, $donnees['nom'], $ferme->exists ? $ferme : null),
            'localisation' => $donnees['localisation'] ?? null,
            'specialite' => $donnees['specialite'],
            'modules' => $this->lignes($donnees['modules']),
            'carte' => $carte,
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $ferme, 'fermes'),
            'accueil' => $request->boolean('accueil'),
            'publie' => $request->boolean('publie'),
        ])->save();
    }
}
