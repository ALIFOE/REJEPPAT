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
 * Témoignage de bénéficiaire, success story de jeune producteur ou témoignage
 * d'apprenant d'une ferme école (affiché sur la fiche de la ferme).
 */
#[Table('temoignages')]
#[Fillable(['type', 'nom', 'role', 'titre', 'texte', 'ferme_id', 'image', 'photo', 'accueil', 'ordre', 'publie', 'traductions'])]
class Temoignage extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TYPES = [
        'temoignage' => 'Témoignage',
        'success_story' => 'Success story',
        'apprenant' => 'Témoignage d’apprenant (ferme école)',
    ];

    public const TRADUCTIONS = [
        'role' => ['Rôle / activité', 'texte'],
        'titre' => ['Titre', 'texte'],
        'texte' => ['Témoignage', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'accueil' => 'boolean',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderBy('ordre')->orderBy('id');
    }

    public function ferme(): BelongsTo
    {
        return $this->belongsTo(Ferme::class);
    }

    public function visuel(): string
    {
        return $this->premierVisuel([$this->image ? "assets/images/rejeppat/temoignages/{$this->image}" : null]);
    }
}
