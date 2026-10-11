<?php

namespace App\Models;

use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('offres')]
#[Fillable(['titre', 'texte', 'icone', 'ordre', 'actif', 'traductions'])]
class Offre extends Model
{
    use EstTraduisible;

    public const ICONE_PAR_DEFAUT = 'fas fa-seedling';

    /** Icônes Font Awesome proposées dans l'administration */
    public const ICONES = [
        'fas fa-seedling' => 'Jeune pousse',
        'fas fa-cloud-sun-rain' => 'Climat',
        'fas fa-leaf' => 'Agroécologie',
        'fas fa-tractor' => 'Tracteur',
        'fas fa-tools' => 'Matériels et équipements',
        'fas fa-hand-holding-usd' => 'Financement',
        'fas fa-chalkboard-teacher' => 'Coaching / formation',
        'fas fa-mortar-pestle' => 'Transformation',
        'fas fa-briefcase' => 'Entreprise',
        'fas fa-handshake' => 'Partenariat',
        'fas fa-users' => 'Groupe / réseau',
        'fas fa-bullhorn' => 'Plaidoyer',
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'texte' => ['Texte', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopeActif(Builder $query): void
    {
        $query->where('actif', true)->orderBy('ordre')->orderBy('id');
    }
}
