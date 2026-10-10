<?php

namespace App\Http\Controllers\Admin;

use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        $regions = Region::withCount(['fermes', 'membresAnnuaire'])->orderBy('ordre')->orderBy('id')->get();

        return view('admin.regions.index', compact('regions'));
    }

    public function create(): View
    {
        return view('admin.regions.form', ['region' => new Region([
            'presentation' => [],
            'filieres' => [],
            'ordre' => Region::max('ordre') + 1,
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Region);

        return redirect()->route('admin.regions.index')->with('succes', 'Section régionale ajoutée.');
    }

    public function edit(Region $region): View
    {
        return view('admin.regions.form', compact('region'));
    }

    public function update(Request $request, Region $region): RedirectResponse
    {
        $this->enregistrer($request, $region);

        return redirect()->route('admin.regions.index')->with('succes', 'Section régionale mise à jour.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        // Les fermes et les membres de la région restent, sans région
        $this->supprimerPhoto($region->photo);
        $region->delete();

        return redirect()->route('admin.regions.index')->with('succes', 'Section régionale supprimée.');
    }

    private function enregistrer(Request $request, Region $region): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120'],
            'chef_lieu' => ['nullable', 'string', 'max:120'],
            'presentation' => ['nullable', 'string', 'max:10000'],
            'filieres' => ['nullable', 'string', 'max:2000'],
            'nb_cooperatives' => ['nullable', 'integer', 'min:0'],
            'nb_membres' => ['nullable', 'integer', 'min:0'],
            'responsable' => ['nullable', 'string', 'max:160'],
            'telephone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES);

        $region->fill([
            'nom' => $donnees['nom'],
            'slug' => $this->slug(Region::class, $donnees['slug'] ?? null, $donnees['nom'], $region->exists ? $region : null),
            'chef_lieu' => $donnees['chef_lieu'] ?? null,
            'presentation' => $this->lignes($donnees['presentation'] ?? ''),
            'filieres' => $this->lignes($donnees['filieres'] ?? ''),
            'nb_cooperatives' => $donnees['nb_cooperatives'] ?? null,
            'nb_membres' => $donnees['nb_membres'] ?? null,
            'responsable' => $donnees['responsable'] ?? null,
            'telephone' => $donnees['telephone'] ?? null,
            'email' => $donnees['email'] ?? null,
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $region, 'regions'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Region::class),
        ])->save();
    }
}
