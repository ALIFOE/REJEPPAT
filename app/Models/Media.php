<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Élément de la médiathèque :
 * - photo : « photo » envoyée depuis l'administration ;
 * - vidéo : lien YouTube (« url »), affiché dans un lecteur intégré ;
 * - podcast : fichier audio envoyé (« fichier ») ou lien d'écoute (« url »).
 */
#[Table('medias')]
#[Fillable(['titre', 'type', 'date', 'description', 'photo', 'url', 'fichier', 'publie', 'traductions'])]
class Media extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TYPES = [
        'photo' => 'Photos',
        'video' => 'Vidéos',
        'podcast' => 'Podcasts',
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'description' => ['Description', 'textarea'],
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderByDesc('date')->orderByDesc('id');
    }

    /** Identifiant d'une vidéo YouTube (liens youtube.com/watch?v=…, youtu.be/…, /embed/…, /shorts/…). */
    public function youtubeId(): ?string
    {
        if (! $this->url) {
            return null;
        }

        preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $this->url, $correspondance);

        return $correspondance[1] ?? null;
    }

    public function visuel(): string
    {
        if ($this->photoUrl()) {
            return $this->photoUrl();
        }

        if ($id = $this->youtubeId()) {
            return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
        }

        return $this->premierVisuel(['assets/images/rejeppat/breadcrumb/actualites.jpg']);
    }

    public function fichierUrl(): ?string
    {
        return $this->fichier ? asset('uploads/'.$this->fichier) : null;
    }
}
