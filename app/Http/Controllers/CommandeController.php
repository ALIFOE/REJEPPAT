<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Support\Contenu;
use App\Support\Panier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CommandeController extends Controller
{
    /** Numéros des commandes passées pendant la session : seules celles-ci ont une page de confirmation. */
    private const SESSION_COMMANDES = 'commandes_passees';

    public function create(): View|RedirectResponse
    {
        $lignes = Panier::lignes();

        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index');
        }

        return view('boutique.commande', [
            'lignes' => $lignes,
            'sousTotal' => Panier::sousTotal($lignes),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $paiements = config('boutique.paiements');

        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'telephone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'mode_livraison' => ['required', Rule::in(array_keys(config('boutique.livraisons')))],
            'ville' => ['nullable', 'required_unless:mode_livraison,retrait', 'string', 'max:120'],
            'adresse' => ['nullable', 'required_unless:mode_livraison,retrait', 'string', 'max:255'],
            'mode_paiement' => ['required', Rule::in(array_keys($paiements))],
            'reference_paiement' => ['nullable', 'string', 'max:80'],
            'note' => ['nullable', 'string', 'max:2000'],
        ], [
            'required' => 'Ce champ est obligatoire.',
            'required_unless' => 'Ce champ est obligatoire pour une livraison.',
            'email' => 'L’adresse e-mail n’est pas valide.',
            'in' => 'Veuillez choisir une option.',
            'max' => 'Ce champ est trop long.',
        ]);

        if (($paiements[$donnees['mode_paiement']]['reference'] ?? false) && blank($donnees['reference_paiement'] ?? null)) {
            throw ValidationException::withMessages(['reference_paiement' => 'Indiquez la référence de la transaction Mobile Money.']);
        }

        $lignes = Panier::lignes();
        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index');
        }

        $commande = DB::transaction(function () use ($donnees, $lignes) {
            // Vérification du stock au moment de la commande
            foreach ($lignes as $ligne) {
                $produit = Produit::lockForUpdate()->find($ligne['produit']->id);
                if (! $produit?->enStock($ligne['quantite'])) {
                    throw ValidationException::withMessages([
                        'panier' => "Stock insuffisant pour « {$ligne['produit']->nom} ». Veuillez modifier votre panier.",
                    ]);
                }
            }

            $sousTotal = Panier::sousTotal($lignes);
            $frais = config("boutique.livraisons.{$donnees['mode_livraison']}.frais", 0);

            $commande = Commande::create($donnees + [
                'numero' => Commande::nouveauNumero(),
                'sous_total' => $sousTotal,
                'frais_livraison' => $frais,
                'total' => $sousTotal + $frais,
            ]);

            foreach ($lignes as $ligne) {
                $commande->lignes()->create([
                    'produit_id' => $ligne['produit']->id,
                    'nom_produit' => $ligne['produit']->nom,
                    'prix_unitaire' => $ligne['produit']->prix,
                    'quantite' => $ligne['quantite'],
                    'total' => $ligne['total'],
                ]);

                if ($ligne['produit']->stock !== null) {
                    Produit::whereKey($ligne['produit']->id)->decrement('stock', $ligne['quantite']);
                }
            }

            return $commande;
        });

        Panier::vider();
        session()->push(self::SESSION_COMMANDES, $commande->numero);

        rescue(fn () => Mail::raw($this->resumeCommande($commande), fn ($message) => $message
            ->to(config('rejeppat.emails.0'))
            ->subject("[Boutique REJEPPAT] Nouvelle commande {$commande->numero}")));

        return redirect()->route('commande.confirmation', $commande->numero);
    }

    public function confirmation(string $numero): View
    {
        abort_unless(in_array($numero, session(self::SESSION_COMMANDES, []), true), 404);

        return view('boutique.confirmation', [
            'commande' => Commande::with('lignes')->where('numero', $numero)->firstOrFail(),
        ]);
    }

    /** Suivi d'une commande avec son numéro et le téléphone utilisé. */
    public function suivi(Request $request): View
    {
        $commande = null;

        if ($request->filled(['numero', 'telephone'])) {
            $telephone = preg_replace('/\D+/', '', $request->input('telephone'));
            $commande = Commande::with('lignes')
                ->where('numero', strtoupper(trim($request->input('numero'))))
                ->get()
                ->first(fn (Commande $c) => str_ends_with(preg_replace('/\D+/', '', $c->telephone), substr($telephone, -8)));
        }

        return view('boutique.suivi', [
            'commande' => $commande,
            'recherche' => $request->filled(['numero', 'telephone']),
        ]);
    }

    private function resumeCommande(Commande $commande): string
    {
        $lignes = $commande->lignes->map(fn ($l) => "- {$l->nom_produit} x {$l->quantite} = " . Contenu::prix($l->total))->implode("\n");

        return "Nouvelle commande sur la boutique du REJEPPAT\n\n"
            . "Numéro : {$commande->numero}\n"
            . "Client : {$commande->nom} ({$commande->telephone})\n"
            . 'E-mail : ' . ($commande->email ?: '-') . "\n"
            . 'Livraison : ' . $commande->modeLivraisonLibelle() . ' ' . trim("{$commande->ville} {$commande->adresse}") . "\n"
            . 'Paiement : ' . $commande->modePaiementLibelle() . ($commande->reference_paiement ? " (réf. {$commande->reference_paiement})" : '') . "\n\n"
            . $lignes . "\n\n"
            . 'Total : ' . Contenu::prix($commande->total) . "\n\n"
            . 'Gérer la commande : ' . route('admin.commandes.show', $commande);
    }
}
