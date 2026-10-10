<?php

namespace App\Models;

use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Offre d'emploi, appel à projets, financement, bourse ou formation relayée par le réseau. */
#[Table('opportunites')]
#[Fillable(['slug', 'titre', 'type', 'organisme', 'lieu', 'date_limite', 'description', 'lien', 'fichier', 'publie', 'traductions'])]
class Opportunite extends Model
{
    use EstTraduisible;

    public const TYPES = [
        'emploi' => 'Emplois & stages',
        'appel_projets' => 'Appels à projets',
        'financement' => 'Financements',
        'bourse' => 'Bourses',
        'formation' => 'Formations',
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'description' => ['Description', 'lignes'],
    ];

    protected function casts(): array
    {
        return [
            'date_limite' => 'date',
            'description' => 'array',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    /** Opportunités dont la date limite n'est pas passée (ou sans date limite). */
    public function scopeOuverte(Builder $query): void
    {
        $query->where(fn ($q) => $q->whereNull('date_limite')->orWhereDate('date_limite', '>=', today()));
    }

    public function estOuverte(): bool
    {
        return $this->date_limite === null || ! $this->date_limite->isPast() || $this->date_limite->isToday();
    }

    public function typeLibelle(): string
    {
        return __(self::TYPES[$this->type] ?? $this->type);
    }

    public function fichierUrl(): ?string
    {
        return $this->fichier ? asset('uploads/'.$this->fichier) : null;
    }
}
