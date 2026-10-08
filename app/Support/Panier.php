<?php

namespace App\Support;

use App\Models\Produit;
use Illuminate\Support\Collection;

/**
 * Panier de la boutique, conservé en session : [id du produit => quantité].
 */
class Panier
{
    private const CLE = 'panier';

    public const QUANTITE_MAX = 99;

    /** @return array<int, int> */
    public static function contenu(): array
    {
        return session(self::CLE, []);
    }

    public static function ajouter(Produit $produit, int $quantite = 1): void
    {
        $contenu = self::contenu();
        $contenu[$produit->id] = min(self::QUANTITE_MAX, ($contenu[$produit->id] ?? 0) + max(1, $quantite));
        session([self::CLE => $contenu]);
    }

    public static function modifier(int $produitId, int $quantite): void
    {
        $contenu = self::contenu();

        if ($quantite <= 0) {
            unset($contenu[$produitId]);
        } else {
            $contenu[$produitId] = min(self::QUANTITE_MAX, $quantite);
        }

        session([self::CLE => $contenu]);
    }

    public static function retirer(int $produitId): void
    {
        self::modifier($produitId, 0);
    }

    public static function vider(): void
    {
        session()->forget(self::CLE);
    }

    /** Nombre d'articles (somme des quantités), affiché dans l'en-tête. */
    public static function nombre(): int
    {
        return array_sum(self::contenu());
    }

    /**
     * Lignes du panier avec les produits encore en vente.
     *
     * @return Collection<int, array{produit: Produit, quantite: int, total: int}>
     */
    public static function lignes(): Collection
    {
        $contenu = self::contenu();

        if ($contenu === []) {
            return collect();
        }

        return Produit::actif()->whereIn('id', array_keys($contenu))->get()
            ->map(fn (Produit $produit) => [
                'produit' => $produit,
                'quantite' => $contenu[$produit->id],
                'total' => $produit->prix * $contenu[$produit->id],
            ])
            ->values();
    }

    public static function sousTotal(?Collection $lignes = null): int
    {
        return ($lignes ?? self::lignes())->sum('total');
    }
}
