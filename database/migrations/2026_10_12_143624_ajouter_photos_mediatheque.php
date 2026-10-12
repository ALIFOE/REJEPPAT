<?php

use App\Models\Media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Photos transmises par le réseau (octobre 2026) ajoutées à la médiathèque : atelier des maîtres
 * formateurs, ferme école Capable-Plus, marchés des produits locaux de Sokodé… (config rejeppat.photos).
 * Les fichiers sont versionnés dans public/assets/images/rejeppat/mediatheque.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Base vide : le seeder importe les contenus d'origine
        if (DB::table('fermes')->doesntExist()) {
            return;
        }

        foreach (config('rejeppat.photos') as $photo) {
            $chemin = Media::DOSSIER_PHOTOS_ORIGINE.'/'.$photo['photo'];

            if (DB::table('medias')->where('photo', $chemin)->exists()) {
                continue;
            }

            DB::table('medias')->insert([
                'titre' => $photo['titre'],
                'type' => 'photo',
                'date' => $photo['date'],
                'description' => $photo['description'],
                'photo' => $chemin,
                'publie' => true,
                'traductions' => json_encode(['en' => array_filter($photo['en'])]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('medias')->where('photo', 'like', Media::DOSSIER_PHOTOS_ORIGINE.'/%')->delete();
    }
};
