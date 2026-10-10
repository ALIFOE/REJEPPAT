<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ferme;
use App\Models\Temoignage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TemoignageController extends Controller
{
    public function index(Request $request): View
    {
        $temoignages = Temoignage::with('ferme')
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->orderBy('ordre')->orderBy('id')
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.temoignages.index', [
            'temoignages' => $temoignages,
            'compteurs' => Temoignage::selectRaw('type, COUNT(*) as total')->groupBy('type')->pluck('total', 'type'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.temoignages.form', ['temoignage' => new Temoignage([
            'type' => array_key_exists($request->query('type'), Temoignage::TYPES) ? $request->query('type') : 'temoignage',
            'ordre' => Temoignage::max('ordre') + 1,
            'publie' => true,
        ])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->enregistrer($request, new Temoignage);

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage ajouté.');
    }

    public function edit(Temoignage $temoignage): View
    {
        return view('admin.temoignages.form', compact('temoignage'));
    }

    public function update(Request $request, Temoignage $temoignage): RedirectResponse
    {
        $this->enregistrer($request, $temoignage);

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage mis à jour.');
    }

    public function destroy(Temoignage $temoignage): RedirectResponse
    {
        $this->supprimerPhoto($temoignage->photo);
        $temoignage->delete();

        return redirect()->route('admin.temoignages.index')->with('succes', 'Témoignage supprimé.');
    }

    private function enregistrer(Request $request, Temoignage $temoignage): void
    {
        $donnees = $request->validate([
            'type' => ['required', Rule::in(array_keys(Temoignage::TYPES))],
            'nom' => ['required', 'string', 'max:160'],
            'role' => ['nullable', 'string', 'max:160'],
            'titre' => ['nullable', 'required_if:type,success_story', 'string', 'max:200'],
            'texte' => ['required', 'string', 'max:10000'],
            'ferme_id' => ['nullable', 'required_if:type,apprenant', 'integer', Rule::exists(Ferme::class, 'id')],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'photo' => self::REGLE_IMAGE,
        ] + $this->reglesTraductions(), self::MESSAGES + [
            'titre.required_if' => 'Donnez un titre à la success story.',
            'ferme_id.required_if' => 'Choisissez la ferme école de l’apprenant.',
        ]);

        $temoignage->fill([
            'type' => $donnees['type'],
            'nom' => $donnees['nom'],
            'role' => $donnees['role'] ?? null,
            'titre' => $donnees['titre'] ?? null,
            'texte' => trim($donnees['texte']),
            'ferme_id' => $donnees['type'] === 'apprenant' ? $donnees['ferme_id'] : null,
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'photo' => $this->photoDemandee($request, $temoignage, 'temoignages'),
            'accueil' => $request->boolean('accueil'),
            'publie' => $request->boolean('publie'),
            'traductions' => $this->traductions($request, Temoignage::class),
        ])->save();
    }
}
