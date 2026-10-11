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
        collect($region->gouverneurs ?? [])->each(fn ($gouverneur) => $this->supprimerPhoto($gouverneur['photo'] ?? null));
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
            'activites' => ['nullable', 'string', 'max:10000'],
            'gouverneurs' => ['nullable', 'array', 'max:'.Region::GOUVERNEURS],
            'gouverneurs.*.nom' => ['nullable', 'string', 'max:160'],
            'gouverneurs.*.fonction' => ['nullable', 'string', 'max:160'],
            'gouverneurs.*.telephone' => ['nullable', 'string', 'max:40'],
            'photos_gouverneurs' => ['nullable', 'array', 'max:'.Region::GOUVERNEURS],
            'photos_gouverneurs.*' => self::REGLE_IMAGE,
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
            'gouverneurs' => $this->gouverneurs($request, $region),
            'activites' => $this->lignes($donnees['activites'] ?? ''),
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

    /**
     * Le ou les gouverneurs de la section (emplacements « gouverneurs[i] » et « photos_gouverneurs[i] ») :
     * un emplacement sans nom est retiré avec sa photo.
     *
     * @return array<int, array{nom: string, fonction: ?string, telephone: ?string, photo: ?string}>
     */
    private function gouverneurs(Request $request, Region $region): array
    {
        $actuels = array_values($region->gouverneurs ?? []);
        $retirer = array_map('strval', (array) $request->input('retirer_photos_gouverneurs', []));

        return collect(range(0, Region::GOUVERNEURS - 1))
            ->map(function (int $i) use ($request, $actuels, $retirer) {
                $saisie = (array) $request->input("gouverneurs.{$i}", []);
                $photo = $actuels[$i]['photo'] ?? null;

                if (blank($saisie['nom'] ?? null) || ($photo && in_array((string) $i, $retirer, true))) {
                    $this->supprimerPhoto($photo);
                    $photo = null;
                }

                if (blank($saisie['nom'] ?? null)) {
                    return null;
                }

                return [
                    'nom' => trim($saisie['nom']),
                    'fonction' => filled($saisie['fonction'] ?? null) ? trim($saisie['fonction']) : null,
                    'telephone' => filled($saisie['telephone'] ?? null) ? trim($saisie['telephone']) : null,
                    'photo' => $this->enregistrerPhoto($request->file("photos_gouverneurs.{$i}"), 'regions/gouverneurs', $photo),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
