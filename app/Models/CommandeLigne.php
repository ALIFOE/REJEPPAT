<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('commande_lignes')]
#[Fillable(['commande_id', 'produit_id', 'nom_produit', 'prix_unitaire', 'quantite', 'total'])]
class CommandeLigne extends Model
{
    protected function casts(): array
    {
        return [
            'prix_unitaire' => 'integer',
            'quantite' => 'integer',
            'total' => 'integer',
        ];
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }
}
