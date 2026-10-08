<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Accès aux contenus du site, stockés pour l'instant dans config/
 * (actualites.php, projets.php, fermes.php, boutique.php).
 */
class Contenu
{
    public const CATEGORIES_ACTUALITES = [
        'actualites' => 'Actualités',
        'evenements' => 'Événements',
    ];

    public static function actualites(): Collection
    {
        return collect(config('actualites'))->sortByDesc('date')->values();
    }

    public static function actualite(string $slug): array
    {
        return self::trouver(self::actualites(), $slug);
    }

    public static function projets(): Collection
    {
        return collect(config('projets.liste'));
    }

    public static function projet(string $slug): array
    {
        return self::trouver(self::projets(), $slug);
    }

    public static function fermes(): Collection
    {
        return collect(config('fermes.liste'));
    }

    public static function ferme(string $slug): array
    {
        return self::trouver(self::fermes(), $slug);
    }

    public static function produits(): Collection
    {
        return collect(config('boutique.produits'));
    }

    public static function produit(string $slug): array
    {
        return self::trouver(self::produits(), $slug);
    }

    /** Ex. « 4 Oct - 2026 », le format des dates du template. */
    public static function date(string $date, string $format = 'j M - Y'): string
    {
        return Carbon::parse($date)->locale('fr')->translatedFormat($format);
    }

    public static function prix(int $montant): string
    {
        return number_format($montant, 0, ',', ' ') . ' CFA';
    }

    /** Durée de lecture estimée (200 mots par minute). */
    public static function lecture(array $paragraphes): int
    {
        return max(1, (int) round(str_word_count(implode(' ', $paragraphes)) / 200));
    }

    public static function extrait(array $paragraphes, int $limite = 110): string
    {
        return \Illuminate\Support\Str::limit($paragraphes[0] ?? '', $limite);
    }

    private static function trouver(Collection $liste, string $slug): array
    {
        return $liste->firstWhere('slug', $slug) ?? throw new NotFoundHttpException();
    }
}
