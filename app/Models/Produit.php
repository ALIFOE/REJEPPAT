<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('produits')]
#[Fillable(['slug', 'nom', 'categorie', 'image', 'photo', 'photos', 'prix', 'prix_initial', 'stock', 'resume', 'description', 'points_forts', 'ordre', 'actif', 'traductions'])]
class Produit extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TRADUCTIONS = [
        'nom' => ['Nom du produit', 'texte'],
        'resume' => ['Résumé', 'textarea'],
        'description' => ['Description', 'lignes'],
        'points_forts' => ['Points forts', 'lignes'],
    ];

    /** Nombre de photos supplémentaires possibles, en plus de la photo principale */
    public const PHOTOS_SUPPLEMENTAIRES = 3;

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'prix' => 'integer',
            'prix_initial' => 'integer',
            'stock' => 'integer',
            'description' => 'array',
            'points_forts' => 'array',
            'actif' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopeActif(Builder $query): void
    {
        $query->where('actif', true)->orderBy('ordre')->orderBy('id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(CommandeLigne::class);
    }

    public function categorieNom(): string
    {
        return __(config('boutique.categories')[$this->categorie] ?? ucfirst($this->categorie));
    }

    public function enStock(int $quantite = 1): bool
    {
        return $this->stock === null || $this->stock >= $quantite;
    }

    public function reduction(): int
    {
        return $this->prix_initial > $this->prix ? (int) round(100 - $this->prix * 100 / $this->prix_initial) : 0;
    }

    /** '' : visuel de la carte produit ; variantes d'origine : -detail, -thumb, -mini */
    public function visuel(string $variante = ''): string
    {
        return $this->premierVisuel([
            $variante !== '' ? "assets/images/rejeppat/shop/{$this->slug}{$variante}.jpg" : null,
            $this->image ? "assets/images/rejeppat/shop/{$this->image}" : null,
        ]);
    }

    /**
     * Galerie de la fiche produit : la photo principale puis les photos supplémentaires.
     *
     * @return array<int, array{grande: string, mini: string}>
     */
    public function galerie(): array
    {
        $supplementaires = collect($this->photos ?? [])
            ->filter()
            ->map(fn (string $photo) => asset(self::DOSSIER_UPLOADS.'/'.$photo))
            ->map(fn (string $url) => ['grande' => $url, 'mini' => $url]);

        return collect([['grande' => $this->visuel('-detail'), 'mini' => $this->visuel('-thumb')]])
            ->merge($supplementaires)
            ->values()
            ->all();
    }
}
