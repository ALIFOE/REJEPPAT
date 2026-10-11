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
 * gouverneurs, activités à venir, chiffres, contacts) depuis l'administration.
 */
#[Table('regions')]
#[Fillable(['slug', 'nom', 'chef_lieu', 'presentation', 'filieres', 'gouverneurs', 'activites', 'nb_cooperatives', 'nb_membres', 'responsable', 'telephone', 'email', 'photo', 'ordre', 'publie', 'traductions'])]
class Region extends Model
{
    use AUnVisuel, EstTraduisible;

    /** Nombre de gouverneurs présentés par section */
    public const GOUVERNEURS = 3;

    public const TRADUCTIONS = [
        'presentation' => ['Présentation', 'lignes'],
        'activites' => ['Activités et informations à venir', 'lignes'],
        'filieres' => ['Filières principales', 'lignes'],
    ];

    protected function casts(): array
    {
        return [
            'presentation' => 'array',
            'filieres' => 'array',
            'gouverneurs' => 'array',
            'activites' => 'array',
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

    /** Activités de l'agenda organisées dans la région. */
    public function evenements(): HasMany
    {
        return $this->hasMany(Evenement::class);
    }

    public function visuel(): string
    {
        return $this->premierVisuel(["assets/images/rejeppat/regions/{$this->slug}.jpg", 'assets/images/rejeppat/slides/slide-'.($this->ordre % 3 + 1).'.jpg']);
    }

    /**
     * Gouverneurs de la section, avec l'adresse de leur photo.
     *
     * @return array<int, array{nom: string, fonction: ?string, telephone: ?string, photo: ?string, photo_url: ?string, initiales: string}>
     */
    public function listeGouverneurs(): array
    {
        return collect($this->gouverneurs ?? [])
            ->filter(fn (array $gouverneur) => filled($gouverneur['nom'] ?? null))
            ->map(fn (array $gouverneur) => [
                'nom' => $gouverneur['nom'],
                'fonction' => $gouverneur['fonction'] ?? null,
                'telephone' => $gouverneur['telephone'] ?? null,
                'photo' => $gouverneur['photo'] ?? null,
                'photo_url' => filled($gouverneur['photo'] ?? null) ? asset(self::DOSSIER_UPLOADS.'/'.$gouverneur['photo']) : null,
                'initiales' => collect(explode(' ', $gouverneur['nom']))->filter()->take(2)->map(fn ($mot) => mb_strtoupper(mb_substr($mot, 0, 1)))->implode(''),
            ])
            ->values()
            ->all();
    }
}
