<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** Abonné à la newsletter. Le jeton sert au lien de désabonnement présent dans chaque lettre. */
#[Table('abonnes')]
#[Fillable(['email', 'locale', 'jeton', 'desabonne_le'])]
class Abonne extends Model
{
    protected function casts(): array
    {
        return [
            'desabonne_le' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn (Abonne $abonne) => $abonne->jeton ??= Str::random(48));
    }

    public function scopeActif(Builder $query): void
    {
        $query->whereNull('desabonne_le');
    }

    public function lienDesabonnement(): string
    {
        return route('newsletter.desabonnement', $this->jeton);
    }
}
