<?php

namespace App\Http\Controllers\Admin;

use App\Models\DemandePartenariat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DemandePartenariatController extends Controller
{
    public function index(Request $request): View
    {
        $demandes = DemandePartenariat::query()
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('organisation', 'like', "%{$recherche}%")
                ->orWhere('nom', 'like', "%{$recherche}%")
                ->orWhere('email', 'like', "%{$recherche}%")))
            ->when($request->query('statut'), fn ($q, $statut) => $q->where('statut', $statut))
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        return view('admin.partenariats.index', [
            'demandes' => $demandes,
            'compteurs' => DemandePartenariat::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut'),
        ]);
    }

    public function show(DemandePartenariat $partenariat): View
    {
        return view('admin.partenariats.show', ['demande' => $partenariat]);
    }

    public function update(Request $request, DemandePartenariat $partenariat): RedirectResponse
    {
        $partenariat->update($request->validate([
            'statut' => ['required', Rule::in(array_keys(DemandePartenariat::STATUTS))],
            'note_admin' => ['nullable', 'string', 'max:2000'],
        ], self::MESSAGES));

        return back()->with('succes', 'Demande de partenariat mise à jour.');
    }

    public function destroy(DemandePartenariat $partenariat): RedirectResponse
    {
        $partenariat->delete();

        return redirect()->route('admin.partenariats.index')->with('succes', 'Demande de partenariat supprimée.');
    }
}
