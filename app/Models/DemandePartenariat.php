<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/** Proposition de partenariat envoyée depuis la page « Partenaires ». */
#[Table('demandes_partenariat')]
#[Fillable(['organisation', 'nom', 'fonction', 'email', 'telephone', 'type', 'message', 'statut', 'note_admin'])]
class DemandePartenariat extends Model
{
    /** Formes de partenariat proposées dans le formulaire */
    public const TYPES = [
        'technique' => 'Partenariat technique (formation, expertise, équipement)',
        'financier' => 'Partenariat financier (projet, programme, subvention)',
        'institutionnel' => 'Partenariat institutionnel',
        'commercial' => 'Partenariat commercial (achat, distribution des produits)',
        'media' => 'Partenariat média',
        'autre' => 'Autre forme de partenariat',
    ];

    /** Statut => [libellé, couleur du badge dans l'administration] */
    public const STATUTS = DemandeService::STATUTS;

    public function typeLibelle(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
