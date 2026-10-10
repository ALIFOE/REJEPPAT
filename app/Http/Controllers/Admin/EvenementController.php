<?php

namespace App\Http\Controllers\Admin;

use App\Models\Evenement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EvenementController extends Controller
{
    public function index(Request $request): View
    {
        $evenements = Evenement::query()
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->query('periode') === 'passes', fn ($q) => $q->passe(), fn ($q) => $q->orderByDesc('debut'))
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.evenements.index', [
            'evenements' => $evenements,
            'aVenir' => Evenement::publie()->aVenir()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.evenements.form', ['evenement' => new Evenement([
            'type' => 'formation',
            'debut' => now()->addWeek()->setTime(9, 0),
            'description' => [],
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Evenement);

        return redirect()->route('admin.evenements.index')->with('succes', 'Événement ajouté à l’agenda.');
    }

    public function edit(Evenement $evenement): View
    {
        return view('admin.evenements.form', compact('evenement'));
    }

    public function update(Request $request, Evenement $evenement): RedirectResponse
    {
        $this->enregistrer($request, $evenement);

        return redirect()->route('admin.evenements.index')->with('succes', 'Événement mis à jour.');
    }

    public function destroy(Evenement $evenement): RedirectResponse
    {
        $this->supprimerPhoto($evenement->photo);
        $evenement->delete();

        return redirect()->route('admin.evenements.index')->with('succes', 'Événement supprimé.');
    }

    private function enregistrer(Request $request, Evenement $evenement): void
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(array_keys(Evenement::TYPES))],
            'debut' => ['required', 'date'],
            'fin' => ['nullable', 'date', 'after_or_equal:debut'],
            'lieu' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'lien_inscription' => ['nullable', 'url:http,https', 'max:255'],
            'photo' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES);

        $evenement->fill([
            'titre' => $donnees['titre'],
            'slug' => $this->slug(Evenement::class, $donnees['slug'] ?? null, $donnees['titre'], $evenement->exists ? $evenement : null),
            'type' => $donnees['type'],
            'debut' => $donnees['debut'],
            'fin' => $donnees['fin'] ?? null,
            'lieu' => $donnees['lieu'],
            'description' => $this->lignes($donnees['description']),
            'lien_inscription' => $donnees['lien_inscription'] ?? null,
            'photo' => $this->photoDemandee($request, $evenement, 'agenda'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Evenement::class),
        ])->save();
    }
}
