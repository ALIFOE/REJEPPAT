<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('produits')]
#[Fillable(['slug', 'nom', 'categorie', 'image', 'photo', 'prix', 'prix_initial', 'stock', 'resume', 'description', 'points_forts', 'ordre', 'actif'])]
class Produit extends Model
{
    use AUnVisuel;

    protected function casts(): array
    {
        return [
            'prix' => 'integer',
            'prix_initial' => 'integer',
            'stock' => 'integer',
            'description' => 'array',
            'points_forts' => 'array',
            'actif' => 'boolean',
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
        return config('boutique.categories')[$this->categorie] ?? ucfirst($this->categorie);
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
}
