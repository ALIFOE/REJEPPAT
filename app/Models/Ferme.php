<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('fermes')]
#[Fillable(['slug', 'nom', 'localisation', 'specialite', 'modules', 'carte', 'image', 'photo', 'accueil', 'ordre', 'publie'])]
class Ferme extends Model
{
    use AUnVisuel;

    protected function casts(): array
    {
        return [
            'modules' => 'array',
            'carte' => 'array',
            'accueil' => 'boolean',
            'publie' => 'boolean',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    /** Variantes d'origine : '', -top */
    public function visuel(string $variante = ''): string
    {
        return $this->premierVisuel([
            $this->image ? "assets/images/rejeppat/fermes/{$this->image}{$variante}.jpg" : null,
            $this->image ? "assets/images/rejeppat/fermes/{$this->image}.jpg" : null,
        ]);
    }
}
