<?php

namespace App\Models;

use App\Models\Concerns\AUnVisuel;
use App\Models\Concerns\EstTraduisible;
use App\Support\TexteRiche;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('actualites')]
#[Fillable(['slug', 'title', 'date', 'categories', 'image', 'photo', 'content', 'corps', 'tags', 'gallery', 'publie', 'traductions'])]
class Actualite extends Model
{
    use AUnVisuel, EstTraduisible;

    public const TRADUCTIONS = [
        'title' => ['Titre', 'texte'],
        'corps' => ['Texte de l’article', 'html'],
    ];

    public const CATEGORIES = [
        'actualites' => 'Actualités',
        'evenements' => 'Événements',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date:Y-m-d',
            'categories' => 'array',
            'content' => 'array',
            'tags' => 'array',
            'gallery' => 'array',
            'publie' => 'boolean',
            'traductions' => 'array',
        ];
    }

    public function scopePublie(Builder $query): void
    {
        $query->where('publie', true);
    }

    /** Variantes d'origine : '', -detail, -accueil, -accueil-grand, -thumb, -mini */
    public function visuel(string $variante = ''): string
    {
        return $this->premierVisuel([
            $this->image ? "assets/images/rejeppat/actualites/{$this->image}{$variante}.jpg" : null,
            $this->image ? "assets/images/rejeppat/actualites/{$this->image}-detail.jpg" : null,
        ]);
    }

    /** Texte mis en forme de l'article (déjà nettoyé à l'enregistrement). */
    public function corpsHtml(): string
    {
        return $this->tr('corps') ?: TexteRiche::depuisParagraphes($this->content ?? []);
    }

    /** Paragraphes de l'article dans la langue du visiteur (extraits, durée de lecture). */
    public function paragraphes(): array
    {
        $traduction = app()->getLocale() !== 'fr' ? $this->traduction('corps', app()->getLocale()) : null;

        return filled($traduction) ? TexteRiche::paragraphes($traduction) : ($this->content ?? []);
    }

    /** @return array<int, array{mini: string, full: string}> */
    public function galerie(): array
    {
        return collect($this->gallery ?? [])->map(function (string $photo) {
            // Photo envoyée depuis l'administration
            if (str_contains($photo, '/')) {
                $url = asset(self::DOSSIER_UPLOADS.'/'.$photo);

                return ['mini' => $url, 'full' => $url];
            }

            return [
                'mini' => asset("assets/images/rejeppat/actualites/galerie/{$photo}.jpg"),
                'full' => asset("assets/images/rejeppat/actualites/galerie/{$photo}-full.jpg"),
            ];
        })->all();
    }
}
