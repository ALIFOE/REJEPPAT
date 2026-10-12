<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

/**
 * Élément de la médiathèque :
 * - photo : « photo » envoyée depuis l'administration ;
 * - vidéo : lien YouTube (« url ») ou fichier vidéo envoyé (« fichier », stocké hors du dossier public
 *   dans storage/app/private/videos et diffusé par un lien temporaire, sans bouton de téléchargement) ;
 * - podcast : fichier audio envoyé (« fichier », public/uploads) ou lien d'écoute (« url »).
 */
#[Table('medias')]
#[Fillable(['titre', 'type', 'date', 'description', 'photo', 'url', 'fichier', 'accueil', 'ordre', 'publie', 'traductions'])]
class Media extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TYPES = [
        'photo' => 'Photos',
        'video' => 'Vidéos',
        'podcast' => 'Podcasts',
    ];

    /** Provenance du contenu d'une vidéo ou d'un podcast : fichier envoyé sur le site ou lien en ligne */
    public const SOURCES = [
        'video' => ['fichier' => 'Fichier vidéo envoyé sur le site', 'youtube' => 'Lien YouTube'],
        'podcast' => ['fichier' => 'Fichier audio envoyé sur le site', 'lien' => 'Lien d’écoute (SoundCloud, Spotify…)'],
    ];

    public const TRADUCTIONS = [
        'titre' => ['Titre', 'texte'],
        'description' => ['Description', 'textarea'],
    ];

    /** Disque et dossier des vidéos protégées */
    public const DISQUE_VIDEOS = 'local';

    public const DOSSIER_VIDEOS = 'videos';

    /** Durée de validité du lien de lecture d'une vidéo protégée */
    public const DUREE_LIEN_VIDEO = 180;

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'accueil' => 'boolean',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true)->orderByDesc('date')->orderByDesc('id');
    }

    /** Vidéos envoyées sur le site et mises en avant sur la page d'accueil. */
    public function scopeVideosAccueil(Builder $query): void
    {
        $query->where('publie', true)->where('type', 'video')->whereNotNull('fichier')->where('accueil', true)
            ->orderBy('ordre')->orderByDesc('date')->orderByDesc('id');
    }

    /** Identifiant d'une vidéo YouTube (liens youtube.com/watch?v=…, youtu.be/…, /embed/…, /shorts/…). */
    public function youtubeId(): ?string
    {
        return self::extraireYoutubeId($this->url);
    }

    public static function extraireYoutubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $correspondance);

        return $correspondance[1] ?? null;
    }

    /** Provenance actuelle d'une vidéo ou d'un podcast (voir SOURCES), null pour une photo. */
    public function source(): ?string
    {
        return match ($this->type) {
            'video' => $this->url && ! $this->fichier ? 'youtube' : 'fichier',
            'podcast' => $this->url && ! $this->fichier ? 'lien' : 'fichier',
            default => null,
        };
    }

    /** Vidéo envoyée sur le site (et non lien YouTube) : lue avec le lecteur du site. */
    public function estVideoProtegee(): bool
    {
        return $this->type === 'video' && filled($this->fichier);
    }

    /** Lien de lecture temporaire et signé de la vidéo protégée (inutilisable une fois expiré). */
    public function lienLecture(): ?string
    {
        return $this->estVideoProtegee()
            ? URL::temporarySignedRoute('video.lecture', now()->addMinutes(self::DUREE_LIEN_VIDEO), ['media' => $this->id])
            : null;
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

    /** Affiche personnalisée de la vidéo protégée, ou null pour afficher la première image de la vidéo. */
    public function affiche(): ?string
    {
        return $this->photoUrl();
    }

    /** Adresse publique d'un fichier audio (podcast). Les vidéos n'ont pas d'adresse publique. */
    public function fichierUrl(): ?string
    {
        return $this->fichier && $this->type === 'podcast' ? asset('uploads/'.$this->fichier) : null;
    }
}
