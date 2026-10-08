<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(Request $request): View
    {
        $commandes = Commande::query()
            ->withCount('lignes')
            ->when($request->query('q'), fn ($q, $recherche) => $q->where(fn ($q) => $q
                ->where('numero', 'like', "%{$recherche}%")
                ->orWhere('nom', 'like', "%{$recherche}%")
                ->orWhere('telephone', 'like', "%{$recherche}%")))
            ->when($request->query('statut'), fn ($q, $statut) => $q->where('statut', $statut))
            ->when($request->query('paiement'), fn ($q, $paiement) => $q->where('statut_paiement', $paiement))
            ->latest()
            ->paginate(self::PAR_PAGE)->withQueryString();

        $compteurs = Commande::selectRaw('statut, COUNT(*) as total')->groupBy('statut')->pluck('total', 'statut');

        // Ventes des 30 derniers jours (hors annulations)
        $jours = collect(range(29, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $recentes = Commande::valide()->where('created_at', '>=', $jours->first())->get(['total', 'created_at']);
        $ventes = [
            'labels' => $jours->map(fn ($j) => $j->format('d/m')),
            'montants' => $jours->map(fn ($j) => $recentes->filter(fn ($c) => $c->created_at->isSameDay($j))->sum('total')),
        ];

        return view('admin.commandes.index', compact('commandes', 'compteurs', 'ventes'));
    }

    public function show(Commande $commande): View
    {
        $commande->load('lignes.produit');

        return view('admin.commandes.show', compact('commande'));
    }

    public function update(Request $request, Commande $commande): RedirectResponse
    {
        $donnees = $request->validate([
            'statut' => ['required', Rule::in(array_keys(Commande::STATUTS))],
            'statut_paiement' => ['required', Rule::in(array_keys(Commande::STATUTS_PAIEMENT))],
            'note_admin' => ['nullable', 'string', 'max:2000'],
        ], self::MESSAGES);

        DB::transaction(function () use ($commande, $donnees) {
            $annulee = $donnees['statut'] === 'annulee';
            $etaitAnnulee = $commande->statut === 'annulee';

            // Une commande annulée remet ses produits en stock ; une commande réactivée les retire à nouveau.
            if ($annulee !== $etaitAnnulee) {
                foreach ($commande->lignes as $ligne) {
                    Produit::whereKey($ligne->produit_id)->whereNotNull('stock')
                        ->{$annulee ? 'increment' : 'decrement'}('stock', $ligne->quantite);
                }
            }

            $commande->update($donnees);
        });

        return back()->with('succes', 'Commande mise à jour.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $commande->delete();

        return redirect()->route('admin.commandes.index')->with('succes', "Commande {$commande->numero} supprimée.");
    }
}
