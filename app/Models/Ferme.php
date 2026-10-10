<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('fermes')]
#[Fillable(['slug', 'nom', 'localisation', 'region_id', 'specialite', 'description', 'modules', 'productions', 'actions', 'responsable', 'telephone', 'email', 'carte', 'image', 'photo', 'galerie', 'accueil', 'ordre', 'publie', 'traductions'])]
class Ferme extends Model
{
    use AUnVisuel, EstTraduisible;

    /** Nombre maximum de photos dans la galerie de la fiche */
    public const PHOTOS_GALERIE = 6;

    public const TRADUCTIONS = [
        'specialite' => ['Spécialité', 'texte'],
        'description' => ['Présentation', 'lignes'],
        'modules' => ['Domaines de formation', 'lignes'],
        'productions' => ['Productions', 'lignes'],
        'actions' => ['Actions majeures', 'paires'],
    ];

    protected function casts(): array
    {
        return [
            'description' => 'array',
            'modules' => 'array',
            'productions' => 'array',
            'actions' => 'array',
            'carte' => 'array',
            'galerie' => 'array',
            'accueil' => 'boolean',
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

    /** Témoignages des apprenants formés dans la ferme école. */
    public function temoignages(): HasMany
    {
        return $this->hasMany(Temoignage::class);
    }

    /** Variantes d'origine : '', -top */
    public function visuel(string $variante = ''): string
    {
        return $this->premierVisuel([
            $this->image ? "assets/images/rejeppat/fermes/{$this->image}{$variante}.jpg" : null,
            $this->image ? "assets/images/rejeppat/fermes/{$this->image}.jpg" : null,
        ]);
    }

    /** @return array<int, string> adresses des photos de la galerie */
    public function galerieUrls(): array
    {
        return collect($this->galerie ?? [])->filter()->map(fn (string $photo) => asset(self::DOSSIER_UPLOADS.'/'.$photo))->values()->all();
    }
}
