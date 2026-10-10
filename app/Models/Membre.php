<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Profil de l'annuaire des membres. Un profil proposé depuis le site reste masqué
 * jusqu'à sa validation dans l'administration.
 */
#[Table('membres')]
#[Fillable(['slug', 'nom', 'organisation', 'region_id', 'localite', 'filiere', 'presentation', 'telephone', 'email', 'photo', 'publie', 'traductions'])]
class Membre extends Model
{
    use AUnVisuel, EstTraduisible;

    public const FILIERES = [
        'maraichage' => 'Maraîchage',
        'cereales' => 'Céréales & tubercules',
        'elevage' => 'Élevage',
        'apiculture' => 'Apiculture',
        'transformation' => 'Transformation agroalimentaire',
        'arboriculture' => 'Arboriculture & fruits',
        'pisciculture' => 'Pisciculture',
        'services' => 'Services agricoles',
        'autre' => 'Autre filière',
    ];

    public const TRADUCTIONS = [
        'presentation' => ['Présentation', 'lignes'],
    ];

    protected function casts(): array
    {
        return [
            'presentation' => 'array',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function filiereLibelle(): string
    {
        return __(self::FILIERES[$this->filiere] ?? $this->filiere);
    }

    public function visuel(): string
    {
        return $this->premierVisuel([]);
    }

    public function initiales(): string
    {
        return collect(explode(' ', $this->nom))->filter()->take(2)->map(fn ($mot) => mb_strtoupper(mb_substr($mot, 0, 1)))->implode('');
    }
}
