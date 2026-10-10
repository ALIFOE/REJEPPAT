<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Tentative de paiement Mobile Money d'une commande (PayGate Global). */
#[Table('paiements')]
#[Fillable(['commande_id', 'operateur', 'telephone', 'montant', 'identifiant', 'tx_reference', 'reference_operateur', 'statut', 'message', 'paye_le'])]
class Paiement extends Model
{
    /** Statut => [libellé, couleur du badge dans l'administration] */
    public const STATUTS = [
        'en_attente' => ['En attente de validation', 'jaune'],
        'paye' => ['Payé', 'vert'],
        'expire' => ['Expiré', 'gris'],
        'annule' => ['Annulé', 'rouge'],
        'echoue' => ['Échoué', 'rouge'],
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'integer',
            'paye_le' => 'datetime',
        ];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function estEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function estPaye(): bool
    {
        return $this->statut === 'paye';
    }

    public function statutLibelle(): string
    {
        return self::STATUTS[$this->statut][0] ?? $this->statut;
    }

    public function operateurLibelle(): string
    {
        return config("boutique.paiements.{$this->operateur}.label", $this->operateur);
    }
}
