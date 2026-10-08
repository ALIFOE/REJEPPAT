<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('offres')]
#[Fillable(['titre', 'texte', 'ordre', 'actif'])]
class Offre extends Model
{
    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function scopeActif(Builder $query): void
    {
        $query->where('actif', true)->orderBy('ordre')->orderBy('id');
    }
}
