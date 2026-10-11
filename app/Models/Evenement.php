<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Rendez-vous de l'agenda : atelier, formation, panel, foire… */
#[Table('evenements')]
#[Fillable(['slug', 'titre', 'type', 'region_id', 'debut', 'fin', 'lieu', 'description', 'lien_inscription', 'photo', 'publie', 'traductions'])]
class Evenement extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TYPES = [
        'atelier' => 'Atelier',
        'formation' => 'Formation',
        'panel' => 'Panel',
        'foire' => 'Foire',
        'reunion' => 'Rencontre',
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'description' => ['Description', 'lignes'],
    ];

    protected function casts(): array
    {
        return [
            'debut' => 'datetime',
            'fin' => 'datetime',
            'description' => 'array',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    /** Événements en cours ou à venir. */
    public function scopeAVenir(Builder $query): void
    {
        $query->where(fn ($q) => $q->where('debut', '>=', today())->orWhere('fin', '>=', now()))->orderBy('debut');
    }

    public function scopePasse(Builder $query): void
    {
        $query->where('debut', '<', today())->where(fn ($q) => $q->whereNull('fin')->orWhere('fin', '<', now()))->orderByDesc('debut');
    }

    /** Section régionale qui organise l'activité (facultatif). */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function typeLibelle(): string
    {
        return __(self::TYPES[$this->type] ?? $this->type);
    }

    public function visuel(): string
    {
        return $this->premierVisuel(['assets/images/rejeppat/breadcrumb/actualites.jpg']);
    }
}
