<?php

namespace App\Http\Controllers\Admin;

use App\Models\Partenaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PartenaireController extends Controller
{
    public function index(Request $request): View
    {
        $partenaires = Partenaire::query()
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->orderBy('ordre')->orderBy('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.partenaires.index', [
            'partenaires' => $partenaires,
            'compteurs' => Partenaire::selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
        ]);
    }

    public function create(): View
    {
        return view('admin.partenaires.form', ['partenaire' => new Partenaire([
            'type' => 'technique',
            'ordre' => Partenaire::max('ordre') + 1,
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Partenaire);

        return redirect()->route('admin.partenaires.index')->with('succes', 'Partenaire ajouté.');
    }

    public function edit(Partenaire $partenaire): View
    {
        return view('admin.partenaires.form', compact('partenaire'));
    }

    public function update(Request $request, Partenaire $partenaire): RedirectResponse
    {
        $this->enregistrer($request, $partenaire);

        return redirect()->route('admin.partenaires.index')->with('succes', 'Partenaire mis à jour.');
    }

    public function destroy(Partenaire $partenaire): RedirectResponse
    {
        $this->supprimerPhoto($partenaire->photo);
        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')->with('succes', 'Partenaire supprimé.');
    }

    private function enregistrer(Request $request, Partenaire $partenaire): void
    {
        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:160'],
            'type' => ['required', Rule::in(array_keys(Partenaire::TYPES))],
            'description' => ['nullable', 'string', 'max:2000'],
            'site' => ['nullable', 'url:http,https', 'max:255'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES);

        $partenaire->fill([
            'nom' => $donnees['nom'],
            'type' => $donnees['type'],
            'description' => $donnees['description'] ?? null,
            'site' => $donnees['site'] ?? null,
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $partenaire, 'partenaires'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Partenaire::class),
        ])->save();
    }
}
