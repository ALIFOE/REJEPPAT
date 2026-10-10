<?php

namespace App\Http\Controllers\Admin;

use App\Models\Membre;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MembreController extends Controller
{
    public function index(Request $request): View
    {
        $membres = Membre::with('region')
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('nom', 'like', "%{$recherche}%")
                ->orWhere('organisation', 'like', "%{$recherche}%")))
            ->when($request->query('etat') === 'attente', fn ($q) => $q->where('publie', false))
            ->when($request->query('region'), fn ($q, $region) => $q->where('region_id', $region))
            ->orderBy('publie')->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.membres.index', [
            'membres' => $membres,
            'enAttente' => Membre::where('publie', false)->count(),
            'regions' => Region::orderBy('ordre')->pluck('nom', 'id'),
        ]);
    }

    public function create(): View
    {
        return view('admin.membres.form', ['membre' => new Membre(['presentation' => [], 'filiere' => 'maraichage', 'publie' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Membre);

        return redirect()->route('admin.membres.index')->with('succes', 'Membre ajouté à l’annuaire.');
    }

    public function edit(Membre $membre): View
    {
        return view('admin.membres.form', compact('membre'));
    }

    public function update(Request $request, Membre $membre): RedirectResponse
    {
        $this->enregistrer($request, $membre);

        return redirect()->route('admin.membres.index')->with('succes', 'Profil mis à jour.');
    }

    public function destroy(Membre $membre): RedirectResponse
    {
        $this->supprimerPhoto($membre->photo);
        $membre->delete();

        return redirect()->route('admin.membres.index')->with('succes', 'Profil supprimé de l’annuaire.');
    }

    private function enregistrer(Request $request, Membre $membre): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160'],
            'organisation' => ['nullable', 'string', 'max:160'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
            'localite' => ['nullable', 'string', 'max:120'],
            'filiere' => ['required', Rule::in(array_keys(Membre::FILIERES))],
            'presentation' => ['required', 'string', 'max:5000'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'photo' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES);

        $membre->fill([
            'nom' => $donnees['nom'],
            'slug' => $this->slug(Membre::class, $donnees['slug'] ?? null, $donnees['nom'], $membre->exists ? $membre : null),
            'organisation' => $donnees['organisation'] ?? null,
            'region_id' => $donnees['region_id'] ?? null,
            'localite' => $donnees['localite'] ?? null,
            'filiere' => $donnees['filiere'],
            'presentation' => $this->lignes($donnees['presentation']),
            'telephone' => $donnees['telephone'] ?? null,
            'email' => $donnees['email'] ?? null,
            'photo' => $this->photoDemandee($request, $membre, 'membres'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Membre::class),
        ])->save();
    }
}
