<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Support\Panier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanierController extends Controller
{
    public function index(): View
    {
        $lignes = Panier::lignes();

        return view('boutique.panier', [
            'lignes' => $lignes,
            'sousTotal' => Panier::sousTotal($lignes),
        ]);
    }

    public function ajouter(Request $request, string $slug): RedirectResponse
    {
        $produit = Produit::actif()->where('slug', $slug)->firstOrFail();
        $quantite = max(1, min(Panier::QUANTITE_MAX, (int) $request->input('quantite', 1)));
        $dejaAuPanier = Panier::contenu()[$produit->id] ?? 0;

        if (! $produit->enStock($dejaAuPanier + $quantite)) {
            return back()->with('erreur_panier', "Stock insuffisant pour « {$produit->nom} » (reste : {$produit->stock}).");
        }

        Panier::ajouter($produit, $quantite);

        return redirect()->route('panier.index')
            ->with('succes', "« {$produit->nom} » a été ajouté à votre panier.");
    }

    public function modifier(Request $request): RedirectResponse
    {
        $request->validate([
            'quantites' => ['array'],
            'quantites.*' => ['integer', 'min:0', 'max:' . Panier::QUANTITE_MAX],
        ]);

        foreach ($request->input('quantites', []) as $produitId => $quantite) {
            Panier::modifier((int) $produitId, (int) $quantite);
        }

        return redirect()->route('panier.index')->with('succes', 'Votre panier a été mis à jour.');
    }

    public function retirer(int $produit): RedirectResponse
    {
        Panier::retirer($produit);

        return redirect()->route('panier.index')->with('succes', 'Le produit a été retiré du panier.');
    }
}
