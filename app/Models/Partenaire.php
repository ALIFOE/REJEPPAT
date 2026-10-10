<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Partenaire institutionnel, technique ou financier. « photo » : logo envoyé depuis l'administration ;
 * « image » : logo d'origine du site (public/assets/images/rejeppat/partenaires).
 */
#[Table('partenaires')]
#[Fillable(['nom', 'type', 'description', 'site', 'image', 'photo', 'ordre', 'publie', 'traductions'])]
class Partenaire extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TYPES = [
        'institutionnel' => 'Partenaires institutionnels',
        'technique' => 'Partenaires techniques',
        'financier' => 'Partenaires financiers',
    ];

    public const TRADUCTIONS = [
        'description' => ['Description', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderBy('ordre')->orderBy('id');
    }

    public function typeLibelle(): string
    {
        return __(self::TYPES[$this->type] ?? $this->type);
    }

    public function visuel(): string
    {
        return $this->premierVisuel([$this->image ? "assets/images/rejeppat/partenaires/{$this->image}" : null]);
    }
}
