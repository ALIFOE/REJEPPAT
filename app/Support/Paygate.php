<?php

namespace App\Support;

use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Paiements Mixx by Yas et Moov Money Flooz avec PayGate Global (https://paygateglobal.com).
 *
 * 1. initier() : PayGate envoie une demande de paiement sur le téléphone du client,
 *    qui la valide avec son code secret Mobile Money.
 * 2. verifier() : le statut est toujours demandé directement à PayGate (jamais repris
 *    des données envoyées par le navigateur ou le rappel), puis la commande est marquée payée.
 *
 * En mode « simulation », aucun appel n'est fait : le client valide ou refuse le paiement
 * depuis la page de paiement, pour tester le parcours complet sans argent réel.
 */
class Paygate
{
    /** Codes de statut de PayGate => statut du paiement */
    private const STATUTS = [0 => 'paye', 2 => 'en_attente', 4 => 'expire', 6 => 'annule'];

    /** Codes de réponse de PayGate à la demande de paiement */
    private const ERREURS_INITIATION = [
        2 => 'Le service de paiement est mal configuré (clé API invalide).',
        4 => 'Le numéro de téléphone ou le montant n’est pas accepté par le service de paiement.',
        6 => 'Ce paiement a déjà été demandé.',
    ];

    public function estSimulation(): bool
    {
        return config('services.paygate.mode') === 'simulation';
    }

    /** Paiement en ligne utilisable : clé API en mode réel, ou simulation hors production */
    public function disponible(): bool
    {
        return $this->estSimulation() ? ! app()->isProduction() : filled(config('services.paygate.token'));
    }

    /** Crée une tentative de paiement pour la commande et l'envoie au téléphone du client. */
    public function nouveauPaiement(Commande $commande, string $operateur, string $telephone): Paiement
    {
        $paiement = $commande->paiements()->create([
            'operateur' => $operateur,
            'telephone' => self::numeroLocal($telephone),
            'montant' => $commande->total,
            'identifiant' => $commande->numero.'-'.Str::upper(Str::random(6)),
        ]);

        $commande->update(['mode_paiement' => $operateur]);
        $this->initier($paiement);

        return $paiement;
    }

    public function initier(Paiement $paiement): void
    {
        if ($this->estSimulation()) {
            $paiement->update(['tx_reference' => 'SIM-'.Str::upper(Str::random(10))]);

            return;
        }

        try {
            $reponse = Http::acceptJson()->asJson()->timeout(config('services.paygate.timeout'))
                ->post(config('services.paygate.url').'/api/v1/pay', [
                    'auth_token' => config('services.paygate.token'),
                    'phone_number' => $paiement->telephone,
                    'amount' => $paiement->montant,
                    'description' => 'Commande '.$paiement->commande->numero.' – '.config('rejeppat.name'),
                    'identifier' => $paiement->identifiant,
                    'network' => config("boutique.paiements.{$paiement->operateur}.reseau"),
                ]);
            $code = $reponse->json('status');
            $txReference = $reponse->json('tx_reference');
        } catch (Throwable $erreur) {
            report($erreur);
            $code = $txReference = null;
        }

        if (is_numeric($code) && (int) $code === 0) {
            $paiement->update(['tx_reference' => $txReference]);

            return;
        }

        Log::warning('PayGate : demande de paiement refusée', ['paiement' => $paiement->identifiant, 'code' => $code]);
        $paiement->update([
            'statut' => 'echoue',
            'message' => self::ERREURS_INITIATION[(int) $code] ?? 'Le service de paiement est momentanément indisponible. Réessayez dans quelques minutes.',
        ]);
    }

    /** Demande le statut à PayGate et met à jour le paiement et la commande. */
    public function verifier(Paiement $paiement): Paiement
    {
        if (! $paiement->estEnAttente()) {
            return $paiement;
        }

        $resultat = $this->estSimulation() ? $this->statutSimule($paiement) : $this->statutPaygate($paiement);
        $code = $resultat['status'] ?? null;
        $statut = is_numeric($code) ? (self::STATUTS[(int) $code] ?? null) : null;

        if (! $statut || $statut === 'en_attente') {
            return $paiement;
        }

        $paiement->update([
            'statut' => $statut,
            'reference_operateur' => $resultat['payment_reference'] ?? null,
            'paye_le' => $statut === 'paye' ? now() : null,
            'message' => $statut === 'paye' ? null : 'Le paiement n’a pas été validé sur le téléphone.',
        ]);

        if ($statut === 'paye') {
            $paiement->commande->update([
                'statut_paiement' => 'paye',
                'mode_paiement' => $paiement->operateur,
                'reference_paiement' => $paiement->reference_operateur ?: $paiement->tx_reference,
            ]);
        }

        return $paiement;
    }

    /** Simulation : le client valide (ou refuse) le paiement depuis la page de paiement. */
    public function simuler(Paiement $paiement, bool $accepte): void
    {
        Cache::put("paygate-simulation:{$paiement->identifiant}", $accepte ? 0 : 6, now()->addHour());
    }

    /** Numéro togolais sur 8 chiffres, format attendu par PayGate */
    public static function numeroLocal(string $telephone): string
    {
        $chiffres = preg_replace('/\D+/', '', $telephone);

        return strlen($chiffres) > 8 && str_starts_with($chiffres, '228') ? substr($chiffres, 3) : $chiffres;
    }

    private function statutPaygate(Paiement $paiement): array
    {
        try {
            $reponse = Http::acceptJson()->asJson()->timeout(config('services.paygate.timeout'))
                ->post(config('services.paygate.url').'/api/v2/status', [
                    'auth_token' => config('services.paygate.token'),
                    'identifier' => $paiement->identifiant,
                ]);

            return $reponse->successful() ? (array) $reponse->json() : [];
        } catch (Throwable $erreur) {
            report($erreur);

            return [];
        }
    }

    private function statutSimule(Paiement $paiement): array
    {
        return [
            'status' => Cache::get("paygate-simulation:{$paiement->identifiant}", 2),
            'payment_reference' => 'SIM'.substr($paiement->tx_reference ?? '', -6),
        ];
    }
}
