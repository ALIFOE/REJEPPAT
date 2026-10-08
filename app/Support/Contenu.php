<?php

namespace App\Support;

use App\Models\Actualite;
use App\Models\Ferme;
use App\Models\Offre;
use App\Models\Produit;
use App\Models\Projet;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Accès aux contenus publiés du site, gérés depuis l'administration.
 * Les fichiers config/actualites.php, projets.php, fermes.php et boutique.php
 * servent de données initiales (DatabaseSeeder).
 * Les listes sont chargées une seule fois par requête (menu, pied de page…).
 */
class Contenu
{
    public const CATEGORIES_ACTUALITES = Actualite::CATEGORIES;

    public static function actualites(): Collection
    {
        return once(fn () => Actualite::publie()->orderByDesc('date')->orderByDesc('id')->get());
    }

    public static function actualite(string $slug): Actualite
    {
        return Actualite::publie()->where('slug', $slug)->firstOrFail();
    }

    public static function projets(): Collection
    {
        return once(fn () => Projet::publie()->orderBy('ordre')->orderBy('id')->get());
    }

    public static function projet(string $slug): Projet
    {
        return Projet::publie()->where('slug', $slug)->firstOrFail();
    }

    public static function fermes(): Collection
    {
        return once(fn () => Ferme::publie()->orderBy('ordre')->orderBy('id')->get());
    }

    public static function ferme(string $slug): Ferme
    {
        return Ferme::publie()->where('slug', $slug)->firstOrFail();
    }

    public static function produits(): Collection
    {
        return Produit::actif()->get();
    }

    public static function produit(string $slug): Produit
    {
        return Produit::actif()->where('slug', $slug)->firstOrFail();
    }

    public static function offres(): Collection
    {
        return once(fn () => Offre::actif()->get());
    }

    /** Services proposés dans le formulaire de demande : les offres actives, plus « Autre besoin ». */
    public static function servicesDemande(): array
    {
        return self::offres()->pluck('titre')->push('Autre besoin')->unique()->values()->all();
    }

    /** Ex. « 4 Oct - 2026 », le format des dates du template. */
    public static function date(string|DateTimeInterface $date, string $format = 'j M - Y'): string
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
        return Str::limit($paragraphes[0] ?? '', $limite);
    }
}
