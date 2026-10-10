<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/** Lettre d'information rédigée dans l'administration et envoyée aux abonnés. */
#[Table('lettres')]
#[Fillable(['sujet', 'contenu', 'envoyee_le', 'destinataires'])]
class Lettre extends Model
{
    protected function casts(): array
    {
        return [
            'envoyee_le' => 'datetime',
            'destinataires' => 'integer',
        ];
    }

    public function estEnvoyee(): bool
    {
        return $this->envoyee_le !== null;
    }
}
