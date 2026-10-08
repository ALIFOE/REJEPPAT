<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Table('commandes')]
#[Fillable(['numero', 'nom', 'telephone', 'email', 'ville', 'adresse', 'mode_livraison', 'frais_livraison', 'sous_total', 'total', 'mode_paiement', 'reference_paiement', 'statut', 'statut_paiement', 'note', 'note_admin'])]
class Commande extends Model
{
    /** Statut => [libellé, couleur du badge dans l'administration] */
    public const STATUTS = [
        'en_attente' => ['En attente', 'jaune'],
        'confirmee' => ['Confirmée', 'bleu'],
        'en_livraison' => ['En livraison', 'violet'],
        'livree' => ['Livrée', 'vert'],
        'annulee' => ['Annulée', 'rouge'],
    ];

    public const STATUTS_PAIEMENT = [
        'en_attente' => ['À encaisser', 'jaune'],
        'paye' => ['Payée', 'vert'],
        'rembourse' => ['Remboursée', 'gris'],
    ];

    protected function casts(): array
    {
        return [
            'frais_livraison' => 'integer',
            'sous_total' => 'integer',
            'total' => 'integer',
        ];
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(CommandeLigne::class);
    }

    public static function nouveauNumero(): string
    {
        do {
            $numero = 'RJ-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (static::where('numero', $numero)->exists());

        return $numero;
    }

    public function statutLibelle(): string
    {
        return self::STATUTS[$this->statut][0] ?? $this->statut;
    }

    public function statutPaiementLibelle(): string
    {
        return self::STATUTS_PAIEMENT[$this->statut_paiement][0] ?? $this->statut_paiement;
    }

    public function modePaiementLibelle(): string
    {
        return config("boutique.paiements.{$this->mode_paiement}.label", $this->mode_paiement);
    }

    public function modeLivraisonLibelle(): string
    {
        return config("boutique.livraisons.{$this->mode_livraison}.label", $this->mode_livraison);
    }

    /** Chiffre d'affaires : commandes non annulées */
    public function scopeValide($query): void
    {
        $query->where('statut', '!=', 'annulee');
    }
}
