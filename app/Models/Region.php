<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Section régionale du réseau. Chaque région complète sa page (présentation, filières,
 * chiffres, contacts) depuis l'administration.
 */
#[Table('regions')]
#[Fillable(['slug', 'nom', 'chef_lieu', 'presentation', 'filieres', 'nb_cooperatives', 'nb_membres', 'responsable', 'telephone', 'email', 'photo', 'ordre', 'publie', 'traductions'])]
class Region extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TRADUCTIONS = [
        'presentation' => ['Présentation', 'lignes'],
        'filieres' => ['Filières principales', 'lignes'],
    ];

    protected function casts(): array
    {
        return [
            'presentation' => 'array',
            'filieres' => 'array',
            'nb_cooperatives' => 'integer',
            'nb_membres' => 'integer',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderBy('ordre')->orderBy('id');
    }

    public function fermes(): HasMany
    {
        return $this->hasMany(Ferme::class);
    }

    /** Membres de la région inscrits dans l'annuaire. */
    public function membresAnnuaire(): HasMany
    {
        return $this->hasMany(Membre::class);
    }

    public function visuel(): string
    {
        return $this->premierVisuel(["assets/images/rejeppat/regions/{$this->slug}.jpg", 'assets/images/rejeppat/slides/slide-'.($this->ordre % 3 + 1).'.jpg']);
    }
}
