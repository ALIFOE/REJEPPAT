<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('demandes_services')]
#[Fillable(['nom', 'telephone', 'email', 'organisation', 'service', 'region', 'objet', 'besoin', 'statut', 'note_admin'])]
class DemandeService extends Model
{
    /** Statut => [libellé, couleur du badge dans l'administration] */
    public const STATUTS = [
        'nouvelle' => ['Nouvelle', 'jaune'],
        'en_cours' => ['En cours', 'bleu'],
        'traitee' => ['Traitée', 'vert'],
        'rejetee' => ['Rejetée', 'rouge'],
    ];

    public function statutLibelle(): string
    {
        return self::STATUTS[$this->statut][0] ?? $this->statut;
    }

    /** Message pré-rempli pour répondre au demandeur sur WhatsApp */
    public function lienWhatsapp(): string
    {
        $numero = preg_replace('/\D+/', '', $this->telephone);
        if (strlen($numero) === 8) {
            $numero = '228' . $numero;
        }

        return 'https://wa.me/' . $numero . '?text=' . rawurlencode("Bonjour {$this->nom}, nous revenons vers vous au sujet de votre demande « {$this->objet} » adressée au REJEPPAT.");
    }
}
