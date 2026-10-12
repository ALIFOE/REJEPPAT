<?php

namespace Database\Seeders;

use App\Models\Actualite;
use App\Models\Ferme;
use App\Models\Media;
use App\Models\Offre;
use App\Models\Partenaire;
use App\Models\Produit;
use App\Models\Projet;
use App\Models\Region;
use App\Models\Temoignage;
use App\Support\TexteRiche;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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

        $this->regions();

        if (Partenaire::doesntExist()) {
            foreach (config('rejeppat.partenaires') as $ordre => $partenaire) {
                Partenaire::create($partenaire + ['ordre' => $ordre]);
            }
        }

        // Vidéos d'origine présentes sur le serveur (fichiers non versionnés)
        foreach (config('rejeppat.videos') as $ordre => $video) {
            if (Storage::disk(Media::DISQUE_VIDEOS)->exists($video['fichier']) && Media::where('fichier', $video['fichier'])->doesntExist()) {
                Media::create($video + ['type' => 'video', 'date' => today(), 'accueil' => true, 'ordre' => $ordre, 'publie' => true]);
            }
        }

        foreach (config('rejeppat.photos') as $photo) {
            Media::firstOrCreate(['photo' => Media::DOSSIER_PHOTOS_ORIGINE.'/'.$photo['photo']], [
                'titre' => $photo['titre'],
                'type' => 'photo',
                'date' => $photo['date'],
                'description' => $photo['description'],
                'publie' => true,
                'traductions' => ['en' => array_filter($photo['en'])],
            ]);
        }

        if (Temoignage::doesntExist()) {
            foreach (config('rejeppat.temoignages') as $ordre => $temoignage) {
                Temoignage::create($temoignage + ['type' => 'temoignage', 'accueil' => true, 'ordre' => $ordre]);
            }
        }
    }

    /** Les 5 sections régionales, et le rattachement des fermes écoles d'après la préfecture de leur adresse. */
    private function regions(): void
    {
        foreach (config('rejeppat.regions') as $ordre => $donnees) {
            $region = Region::firstOrCreate(['slug' => $donnees['slug']], [
                'nom' => $donnees['nom'],
                'chef_lieu' => $donnees['chef_lieu'],
                'presentation' => $donnees['presentation'] ?? [],
                'filieres' => [],
                'ordre' => $ordre,
            ]);

            $motif = '/préfecture d(e |’|\')('.implode('|', array_map('preg_quote', $donnees['prefectures'])).')\b/u';

            Ferme::whereNull('region_id')->get()
                ->filter(fn (Ferme $ferme) => $donnees['prefectures'] && preg_match($motif, $ferme->carte['adresse'] ?? ''))
                ->each(fn (Ferme $ferme) => $ferme->update(['region_id' => $region->id]));
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
