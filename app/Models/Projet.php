<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('projets')]
#[Fillable(['slug', 'titre', 'titre_court', 'icone', 'categories', 'date', 'statut', 'resume', 'description', 'actions', 'resultats', 'points', 'image', 'photo', 'ordre', 'publie', 'traductions'])]
class Projet extends Model
{
    use AUnVisuel, EstTraduisible;

    /** État d'avancement => [libellé, couleur du badge dans l'administration] */
    public const STATUTS = [
        'en_cours' => ['En cours', 'vert'],
        'a_venir' => ['À venir', 'jaune'],
        'termine' => ['Terminé', 'gris'],
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'titre_court' => ['Titre court', 'texte'],
        'resume' => ['Résumé', 'textarea'],
        'description' => ['Description', 'lignes'],
        'actions' => ['Actions', 'paires'],
        'resultats' => ['Résultats obtenus', 'lignes'],
        'points' => ['Points clés', 'lignes'],
    ];

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
            'traductions' => 'array',
        ];
    }

    public function statutLibelle(): string
    {
        return __(self::STATUTS[$this->statut][0] ?? $this->statut);
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
