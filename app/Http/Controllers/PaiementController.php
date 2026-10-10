<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiement;
use App\Support\Paygate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Paiement en ligne d'une commande : Mixx by Yas ou Moov Money Flooz (PayGate Global). */
class PaiementController extends Controller
{
    public function __construct(private Paygate $paygate) {}

    /** Page d'attente : le client valide le paiement sur son téléphone. */
    public function show(Request $request, string $numero): View|RedirectResponse
    {
        $commande = $this->commande($request, $numero);
        $paiement = $commande->dernierPaiement ? $this->paygate->verifier($commande->dernierPaiement) : null;

        if (! $paiement || $commande->fresh()->estPayee()) {
            return redirect()->route('commande.confirmation', $commande->numero);
        }

        return view('boutique.paiement', [
            'commande' => $commande,
            'paiement' => $paiement,
            'operateurs' => $this->operateurs(),
            'simulation' => $this->paygate->estSimulation(),
        ]);
    }

    /** Statut du paiement, interrogé régulièrement par la page d'attente. */
    public function statut(Request $request, string $numero): JsonResponse
    {
        $commande = $this->commande($request, $numero);
        $paiement = $this->paygate->verifier($commande->dernierPaiement()->firstOrFail());

        return response()->json([
            'statut' => $paiement->statut,
            'libelle' => $paiement->statutLibelle(),
            'message' => $paiement->message,
            'redirection' => $paiement->estPaye() ? route('commande.confirmation', $commande->numero) : null,
        ]);
    }

    /** Nouvelle tentative (autre numéro ou autre opérateur) après un échec. */
    public function relancer(Request $request, string $numero): RedirectResponse
    {
        $commande = $this->commande($request, $numero);
        abort_if($commande->estPayee() || $commande->statut === 'annulee', 404);

        $donnees = $request->validate([
            'operateur' => ['required', Rule::in(array_keys($this->operateurs()))],
            'telephone_paiement' => ['required', 'string', 'regex:/^(\+?228)?\s*\d{2}(\s*\d{2}){3}$/'],
        ], [
            'required' => __('Ce champ est obligatoire.'),
            'in' => __('Veuillez choisir un opérateur.'),
            'regex' => __('Indiquez un numéro togolais à 8 chiffres (ex. 90 12 34 56).'),
        ]);

        $this->paygate->nouveauPaiement($commande, $donnees['operateur'], $donnees['telephone_paiement']);

        return redirect()->route('paiement.show', $commande->numero);
    }

    /**
     * Rappel de PayGate après un paiement (URL à déclarer dans le compte marchand).
     * Le contenu du rappel n'est pas cru : le statut est redemandé à PayGate.
     */
    public function rappel(Request $request): JsonResponse
    {
        $paiement = Paiement::where('identifiant', (string) $request->input('identifier'))
            ->orWhere(fn ($q) => $q->whereNotNull('tx_reference')->where('tx_reference', (string) $request->input('tx_reference')))
            ->first();

        if ($paiement) {
            $this->paygate->verifier($paiement);
        }

        return response()->json(['recu' => true]);
    }

    /** Mode simulation uniquement : valider ou refuser le paiement comme sur le téléphone. */
    public function simuler(Request $request, string $numero): RedirectResponse
    {
        abort_unless($this->paygate->estSimulation() && ! app()->isProduction(), 404);

        $commande = $this->commande($request, $numero);
        $this->paygate->simuler($commande->dernierPaiement()->firstOrFail(), $request->boolean('accepte'));

        return redirect()->route('paiement.show', $commande->numero);
    }

    private function commande(Request $request, string $numero): Commande
    {
        $commande = Commande::where('numero', $numero)->firstOrFail();
        abort_unless($commande->estVisiblePar($request) && $commande->paiementEnLigne(), 404);

        return $commande;
    }

    /** @return array<string, array> modes de paiement Mobile Money en ligne */
    private function operateurs(): array
    {
        return collect(config('boutique.paiements'))->filter(fn ($mode) => filled($mode['reseau'] ?? null))->all();
    }
}
