<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

#[Table('commandes')]
#[Fillable(['user_id', 'numero', 'nom', 'telephone', 'email', 'ville', 'adresse', 'mode_livraison', 'frais_livraison', 'sous_total', 'total', 'mode_paiement', 'reference_paiement', 'statut', 'statut_paiement', 'note', 'note_admin'])]
class Commande extends Model
{
    /** Clé de session : numéros des commandes passées depuis ce navigateur */
    public const SESSION_CLIENT = 'commandes_passees';

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

    /** Compte client ayant passé la commande (aucun pour une commande sans compte). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Tentatives de paiement Mobile Money en ligne */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function dernierPaiement(): HasOne
    {
        return $this->hasOne(Paiement::class)->latestOfMany();
    }

    /** Mode de paiement réglé en ligne (Mixx by Yas, Moov Money Flooz) */
    public function paiementEnLigne(): bool
    {
        return filled(config("boutique.paiements.{$this->mode_paiement}.reseau"));
    }

    public function estPayee(): bool
    {
        return $this->statut_paiement === 'paye';
    }

    /**
     * Pages de confirmation et de paiement : réservées au navigateur qui a passé la commande
     * ou au compte client qui l'a passée.
     */
    public function estVisiblePar(Request $request): bool
    {
        return in_array($this->numero, $request->session()->get(self::SESSION_CLIENT, []), true)
            || ($this->user_id && $this->user_id === $request->user()?->id);
    }

    public static function nouveauNumero(): string
    {
        do {
            $numero = 'RJ-'.now()->format('ymd').'-'.Str::upper(Str::random(4));
        } while (static::where('numero', $numero)->exists());

        return $numero;
    }

    public function statutLibelle(): string
    {
        return __(self::STATUTS[$this->statut][0] ?? $this->statut);
    }

    public function statutPaiementLibelle(): string
    {
        return __(self::STATUTS_PAIEMENT[$this->statut_paiement][0] ?? $this->statut_paiement);
    }

    public function modePaiementLibelle(): string
    {
        return __(config("boutique.paiements.{$this->mode_paiement}.label", $this->mode_paiement));
    }

    public function modeLivraisonLibelle(): string
    {
        return __(config("boutique.livraisons.{$this->mode_livraison}.label", $this->mode_livraison));
    }

    /** Chiffre d'affaires : commandes non annulées */
    public function scopeValide($query): void
    {
        $query->where('statut', '!=', 'annulee');
    }
}
