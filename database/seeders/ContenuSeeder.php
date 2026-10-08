<?php

namespace Database\Seeders;

use App\Models\Actualite;
use App\Models\Ferme;
use App\Models\Offre;
use App\Models\Produit;
use App\Models\Projet;
use App\Support\TexteRiche;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

/**
 * Importe les contenus d'origine du site (config/*.php) dans la base.
 * Les contenus déjà présents (même slug) ne sont pas écrasés.
 */
class ContenuSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('actualites') as $actualite) {
            $this->importer(Actualite::class, $actualite + [
                'corps' => TexteRiche::depuisParagraphes($actualite['content']),
                'publie' => true,
            ]);
        }

        foreach (config('projets.liste') as $ordre => $projet) {
            $this->importer(Projet::class, $projet + ['image' => $projet['slug'], 'ordre' => $ordre]);
        }

        $accueil = ['ma-joie', 'cadete', 'pain-de-vie', 'pirenadou'];
        foreach (config('fermes.liste') as $ordre => $ferme) {
            $this->importer(Ferme::class, $ferme + [
                'image' => $ferme['slug'],
                'accueil' => in_array($ferme['slug'], $accueil),
                'ordre' => $ordre,
            ]);
        }

        if (Offre::doesntExist()) {
            foreach (config('rejeppat.offres') as $ordre => $offre) {
                Offre::create($offre + ['ordre' => $ordre]);
            }
        }

        foreach (config('boutique.produits') as $ordre => $produit) {
            $this->importer(Produit::class, $produit + ['ordre' => $ordre]);
        }
    }

    /** @param  class-string<Model>  $modele */
    private function importer(string $modele, array $donnees): void
    {
        // Le seeder désactive la protection « fillable » : on ne garde que les colonnes connues.
        $donnees = Arr::only($donnees, (new $modele)->getFillable());

        $modele::firstOrCreate(['slug' => $donnees['slug']], $donnees);
    }
}
