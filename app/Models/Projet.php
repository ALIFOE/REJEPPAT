<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('projets')]
#[Fillable(['slug', 'titre', 'titre_court', 'icone', 'categories', 'date', 'resume', 'description', 'actions', 'resultats', 'points', 'image', 'photo', 'ordre', 'publie'])]
class Projet extends Model
{
    use AUnVisuel;

    /** Icônes du template proposées dans l'administration */
    public const ICONES = [
        'icon-farm-house-1' => 'Ferme',
        'icon-farmer' => 'Agriculteur',
        'icon-seedling' => 'Jeune pousse',
        'icon-eco-friendly' => 'Écologie',
        'icon-handshake' => 'Partenariat',
        'icon-smart-farming' => 'Agriculture intelligente',
        'icon-tree' => 'Arbre',
        'icon-tractor' => 'Tracteur',
        'icon-harvest' => 'Récolte',
        'icon-cow' => 'Élevage',
        'icon-wheat' => 'Céréales',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'categories' => 'array',
            'description' => 'array',
            'actions' => 'array',
            'resultats' => 'array',
            'points' => 'array',
            'publie' => 'boolean',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    /** Variantes d'origine : '', -detail, -side, -large, -small */
    public function visuel(string $variante = ''): string
    {
        return $this->premierVisuel([
            $this->image ? "assets/images/rejeppat/projets/{$this->image}{$variante}.jpg" : null,
            $this->image ? "assets/images/rejeppat/projets/{$this->image}.jpg" : null,
        ]);
    }
}
