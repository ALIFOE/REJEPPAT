<?php

namespace App\Models;

use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/** Document téléchargeable (guide, manuel, support de formation, rapport) stocké dans public/uploads/ressources. */
#[Table('ressources')]
#[Fillable(['titre', 'categorie', 'description', 'fichier', 'ordre', 'publie', 'traductions'])]
class Ressource extends Model
{
    use EstTraduisible;

    public const CATEGORIES = [
        'guide' => 'Guides pratiques',
        'manuel' => 'Manuels techniques',
        'formation' => 'Supports de formation',
        'rapport' => 'Rapports & publications',
    ];

    /** Présentation du réseau au colloque FAR (2014), reprise de l'ancien site et mise en avant sur « Axes stratégiques » */
    public const PRESENTATION_REJEPPAT = 'ressources/presentation-rejeppat-colloque-far-2014.pdf';

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'description' => ['Description', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'telechargements' => 'integer',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderBy('ordre')->orderByDesc('id');
    }

    public function categorieLibelle(): string
    {
        return __(self::CATEGORIES[$this->categorie] ?? $this->categorie);
    }

    /** Extension en majuscules (PDF, DOCX…), affichée à côté du lien de téléchargement. */
    public function format(): string
    {
        return strtoupper(pathinfo($this->fichier, PATHINFO_EXTENSION));
    }

    /** Taille lisible du fichier (ex. « 1,2 Mo »), ou null si le fichier est introuvable. */
    public function taille(): ?string
    {
        $disque = Storage::disk('uploads');

        if (! $disque->exists($this->fichier)) {
            return null;
        }

        $octets = $disque->size($this->fichier);

        return $octets >= 1048576
            ? number_format($octets / 1048576, 1, ',', ' ').' Mo'
            : max(1, (int) round($octets / 1024)).' Ko';
    }
}
