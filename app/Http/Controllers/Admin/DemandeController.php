<?php

namespace App\Http\Controllers\Admin;

use App\Models\DemandeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DemandeController extends Controller
{
    public function index(Request $request): View
    {
        $demandes = DemandeService::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('nom', 'like', "%{$recherche}%")
                ->orWhere('objet', 'like', "%{$recherche}%")
                ->orWhere('organisation', 'like', "%{$recherche}%")
                ->orWhere('telephone', 'like', "%{$recherche}%")))
            ->when($request->query('statut'), fn ($q, $statut) => $q->where('statut', $statut))
            ->when($request->query('service'), fn ($q, $service) => $q->where('service', $service))
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.demandes.index', [
            'demandes' => $demandes,
            'compteurs' => DemandeService::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut'),
            'services' => DemandeService::distinct()->orderBy('service')->pluck('service'),
            'parService' => DemandeService::selectRaw('service, COUNT(*) as total')->groupBy('service')->orderByDesc('total')->pluck('total', 'service'),
        ]);
    }

    public function show(DemandeService $demande): View
    {
        return view('admin.demandes.show', compact('demande'));
    }

    public function update(Request $request, DemandeService $demande): RedirectResponse
    {
        $demande->update($request->validate([
            'statut' => ['required', Rule::in(array_keys(DemandeService::STATUTS))],
            'note_admin' => ['nullable', 'string', 'max:2000'],
        ], self::MESSAGES));

        return back()->with('succes', 'Demande mise à jour.');
    }

    public function destroy(DemandeService $demande): RedirectResponse
    {
        $demande->delete();

        return redirect()->route('admin.demandes.index')->with('succes', 'Demande supprimée.');
    }
}
