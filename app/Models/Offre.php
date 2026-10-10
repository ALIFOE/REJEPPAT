<?php

namespace App\Models;

use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('offres')]
#[Fillable(['titre', 'texte', 'ordre', 'actif', 'traductions'])]
class Offre extends Model
{
    use EstTraduisible;

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'texte' => ['Texte', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopeActif(Builder $query): void
    {
        $query->where('actif', true)->orderBy('ordre')->orderBy('id');
    }
}
