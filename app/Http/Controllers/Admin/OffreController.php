<?php

namespace App\Http\Controllers\Admin;

use App\Models\DemandeService;
use App\Models\Offre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OffreController extends Controller
{
    public function index(): View
    {
        $offres = Offre::orderBy('ordre')->orderBy('id')->get();
        $demandesParService = DemandeService::selectRaw('service, COUNT(*) as total')->groupBy('service')->pluck('total', 'service');

        return view('admin.offres.index', compact('offres', 'demandesParService'));
    }

    public function create(): View
    {
        return view('admin.offres.form', ['offre' => new Offre(['ordre' => Offre::max('ordre') + 1, 'actif' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Offre::create($this->donnees($request));

        return redirect()->route('admin.offres.index')->with('succes', 'Offre ajoutée.');
    }

    public function edit(Offre $offre): View
    {
        return view('admin.offres.form', compact('offre'));
    }

    public function update(Request $request, Offre $offre): RedirectResponse
    {
        $offre->update($this->donnees($request));

        return redirect()->route('admin.offres.index')->with('succes', 'Offre mise à jour.');
    }

    public function destroy(Offre $offre): RedirectResponse
    {
        $offre->delete();

        return redirect()->route('admin.offres.index')->with('succes', 'Offre supprimée.');
    }

    private function donnees(Request $request): array
    {
        $donnees = $request->validate([
            'titre' => ['required', 'string', 'max:160'],
            'texte' => ['required', 'string', 'max:1000'],
            'ordre' => ['nullable', 'integer', 'min:0'],
        ], self::MESSAGES);

        return [
            'titre' => $donnees['titre'],
            'texte' => $donnees['texte'],
            'ordre' => (int) ($donnees['ordre'] ?? 0),
            'actif' => $request->boolean('actif'),
        ];
    }
}
