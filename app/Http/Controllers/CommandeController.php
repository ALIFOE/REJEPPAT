<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Support\Contenu;
use App\Support\Panier;
use App\Support\Paygate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function create(Paygate $paygate): View|RedirectResponse
    {
        $lignes = Panier::lignes();

        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index');
        }

        return view('boutique.commande', [
            'lignes' => $lignes,
            'sousTotal' => Panier::sousTotal($lignes),
            'paiements' => $this->modesDePaiement($paygate),
        ]);
    }

    public function store(Request $request, Paygate $paygate): RedirectResponse
    {
        $paiements = $this->modesDePaiement($paygate);

        $donnees = $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'telephone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'mode_livraison' => ['required', Rule::in(array_keys(config('boutique.livraisons')))],
            'ville' => ['nullable', 'required_unless:mode_livraison,retrait', 'string', 'max:120'],
            'adresse' => ['nullable', 'required_unless:mode_livraison,retrait', 'string', 'max:255'],
            'mode_paiement' => ['required', Rule::in(array_keys($paiements))],
            'note' => ['nullable', 'string', 'max:2000'],
        ], $this->messages([
            'required' => 'Ce champ est obligatoire.',
            'required_unless' => 'Ce champ est obligatoire pour une livraison.',
            'email' => 'L’adresse e-mail n’est pas valide.',
            'in' => 'Veuillez choisir une option.',
            'max' => 'Ce champ est trop long.',
        ]));

        // Mobile Money : numéro qui recevra la demande de paiement
        $enLigne = filled($paiements[$donnees['mode_paiement']]['reseau'] ?? null);
        if ($enLigne) {
            $request->validate([
                'telephone_paiement' => ['required', 'string', 'regex:/^(\+?228)?\s*\d{2}(\s*\d{2}){3}$/'],
            ], $this->messages([
                'required' => 'Indiquez le numéro Mobile Money qui va payer.',
                'regex' => 'Indiquez un numéro togolais à 8 chiffres (ex. 90 12 34 56).',
            ]));
        }

        $lignes = Panier::lignes();
        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index');
        }

        // Client connecté : la commande apparaît dans son espace « Mon compte »
        $donnees['user_id'] = $request->user()?->id;

        $commande = DB::transaction(function () use ($donnees, $lignes) {
            // Vérification du stock au moment de la commande
            foreach ($lignes as $ligne) {
                $produit = Produit::lockForUpdate()->find($ligne['produit']->id);
                if (! $produit?->enStock($ligne['quantite'])) {
                    throw ValidationException::withMessages([
                        'panier' => __('Stock insuffisant pour « :produit ». Veuillez modifier votre panier.', ['produit' => $ligne['produit']->tr('nom')]),
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
        session()->push(Commande::SESSION_CLIENT, $commande->numero);

        rescue(fn () => Mail::raw($this->resumeCommande($commande), fn ($message) => $message
            ->to(config('rejeppat.emails.0'))
            ->subject("[Boutique REJEPPAT] Nouvelle commande {$commande->numero}")));

        if ($enLigne) {
            $paygate->nouveauPaiement($commande, $donnees['mode_paiement'], $request->input('telephone_paiement'));

            return redirect()->route('paiement.show', $commande->numero);
        }

        return redirect()->route('commande.confirmation', $commande->numero);
    }

    public function confirmation(Request $request, string $numero): View
    {
        $commande = Commande::with(['lignes', 'dernierPaiement'])->where('numero', $numero)->firstOrFail();
        abort_unless($commande->estVisiblePar($request), 404);

        return view('boutique.confirmation', compact('commande'));
    }

    /** Facture de la commande : réservée au navigateur ou au compte client qui l'a passée. */
    public function facture(Request $request, string $numero): View
    {
        $commande = Commande::with('lignes')->where('numero', $numero)->firstOrFail();
        abort_unless($commande->estVisiblePar($request), 404);

        return view('boutique.facture', compact('commande'));
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

    /** Modes de paiement proposés : le Mobile Money en ligne seulement si PayGate est configuré. */
    private function modesDePaiement(Paygate $paygate): array
    {
        return collect(config('boutique.paiements'))
            ->filter(fn ($mode) => blank($mode['reseau'] ?? null) || $paygate->disponible())
            ->all();
    }

    private function resumeCommande(Commande $commande): string
    {
        $lignes = $commande->lignes->map(fn ($l) => "- {$l->nom_produit} x {$l->quantite} = ".Contenu::prix($l->total))->implode("\n");

        return "Nouvelle commande sur la boutique du REJEPPAT\n\n"
            ."Numéro : {$commande->numero}\n"
            ."Client : {$commande->nom} ({$commande->telephone})\n"
            .'E-mail : '.($commande->email ?: '-')."\n"
            .'Livraison : '.$commande->modeLivraisonLibelle().' '.trim("{$commande->ville} {$commande->adresse}")."\n"
            .'Paiement : '.$commande->modePaiementLibelle().($commande->reference_paiement ? " (réf. {$commande->reference_paiement})" : '')."\n\n"
            .$lignes."\n\n"
            .'Total : '.Contenu::prix($commande->total)."\n\n"
            .'Gérer la commande : '.route('admin.commandes.show', $commande);
    }
}
