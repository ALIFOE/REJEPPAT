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
        $this->enregistrer($request, new Ferme);

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
        collect($ferme->galerie ?? [])->each(fn ($photo) => $this->supprimerPhoto($photo));
        $ferme->delete();

        return redirect()->route('admin.fermes.index')->with('succes', 'Ferme école supprimée.');
    }

    private function enregistrer(Request $request, Ferme $ferme): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160'],
            'localisation' => ['nullable', 'string', 'max:255'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'specialite' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:10000'],
            'modules' => ['required', 'string'],
            'productions' => ['nullable', 'string', 'max:5000'],
            'actions' => ['nullable', 'string', 'max:10000'],
            'responsable' => ['nullable', 'string', 'max:160'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'latitude' => ['nullable', 'numeric', 'between:5.5,11.5', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-0.5,2', 'required_with:latitude'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
            'photos' => ['nullable', 'array', 'max:'.Ferme::PHOTOS_GALERIE],
            'photos.*' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES + [
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
            'region_id' => $donnees['region_id'] ?? null,
            'specialite' => $donnees['specialite'],
            'description' => $this->lignes($donnees['description'] ?? ''),
            'modules' => $this->lignes($donnees['modules']),
            'productions' => $this->lignes($donnees['productions'] ?? ''),
            'actions' => $this->paires($donnees['actions'] ?? ''),
            'responsable' => $donnees['responsable'] ?? null,
            'telephone' => $donnees['telephone'] ?? null,
            'email' => $donnees['email'] ?? null,
            'carte' => $carte,
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $ferme, 'fermes'),
            'galerie' => $this->galerieDemandee($request, $ferme->galerie, Ferme::PHOTOS_GALERIE, 'fermes/galerie'),
            'accueil' => $request->boolean('accueil'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Ferme::class),
        ])->save();
    }
}
