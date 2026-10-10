<?php

namespace App\Http\Controllers\Admin;

use App\Models\Opportunite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OpportuniteController extends Controller
{
    public function index(Request $request): View
    {
        $opportunites = Opportunite::query()
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->query('q'), fn ($q, $recherche) => $q->where('titre', 'like', "%{$recherche}%"))
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.opportunites.index', [
            'opportunites' => $opportunites,
            'ouvertes' => Opportunite::publie()->ouverte()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.opportunites.form', ['opportunite' => new Opportunite(['type' => 'appel_projets', 'description' => [], 'publie' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Opportunite);

        return redirect()->route('admin.opportunites.index')->with('succes', 'Opportunité publiée.');
    }

    public function edit(Opportunite $opportunite): View
    {
        return view('admin.opportunites.form', compact('opportunite'));
    }

    public function update(Request $request, Opportunite $opportunite): RedirectResponse
    {
        $this->enregistrer($request, $opportunite);

        return redirect()->route('admin.opportunites.index')->with('succes', 'Opportunité mise à jour.');
    }

    public function destroy(Opportunite $opportunite): RedirectResponse
    {
        $this->supprimerPhoto($opportunite->fichier);
        $opportunite->delete();

        return redirect()->route('admin.opportunites.index')->with('succes', 'Opportunité supprimée.');
    }

    private function enregistrer(Request $request, Opportunite $opportunite): void
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Opportunite::TYPES))],
            'organisme' => ['nullable', 'string', 'max:160'],
            'lieu' => ['nullable', 'string', 'max:160'],
            'date_limite' => ['nullable', 'date'],
            'description' => ['required', 'string', 'max:20000'],
            'lien' => ['nullable', 'url:http,https', 'max:255'],
            'fichier' => ['nullable', ...self::REGLE_DOCUMENT],
        ] + $this->reglesTraductions(), self::MESSAGES + ['fichier.mimes' => 'Formats acceptés : PDF, Word, Excel, PowerPoint, ODT ou ZIP.']);

        $fichier = $request->boolean('retirer_fichier') ? null : $opportunite->fichier;
        if ($request->boolean('retirer_fichier')) {
            $this->supprimerPhoto($opportunite->fichier);
        }

        $opportunite->fill([
            'titre' => $donnees['titre'],
            'slug' => $this->slug(Opportunite::class, $donnees['slug'] ?? null, $donnees['titre'], $opportunite->exists ? $opportunite : null),
            'type' => $donnees['type'],
            'organisme' => $donnees['organisme'] ?? null,
            'lieu' => $donnees['lieu'] ?? null,
            'date_limite' => $donnees['date_limite'] ?? null,
            'description' => $this->lignes($donnees['description']),
            'lien' => $donnees['lien'] ?? null,
            'fichier' => $this->enregistrerFichier($request->file('fichier'), 'opportunites', $fichier),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Opportunite::class),
        ])->save();
    }
}
