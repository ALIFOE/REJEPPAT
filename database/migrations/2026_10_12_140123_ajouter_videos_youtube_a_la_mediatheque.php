<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Vidéos de la chaîne YouTube du REJEPPAT ajoutées à la médiathèque (modifiables ensuite depuis l'administration).
 */
return new class extends Migration
{
    private const VIDEOS = [
        ['titre' => 'Rejoignez-nous au REJEPPAT', 'url' => 'https://youtu.be/driT7pxTW6I', 'en' => 'Join REJEPPAT'],
        ['titre' => 'Promotion de l’agroécologie par le REJEPPAT', 'url' => 'https://youtu.be/8l80pedj4xM', 'en' => 'REJEPPAT promoting agroecology'],
        ['titre' => 'Nouvelle vague de recrutement du REJEPPAT', 'url' => 'https://youtu.be/59IB32DK7VA', 'en' => 'REJEPPAT’s new recruitment drive'],
        ['titre' => 'Mécanisme d’accompagnement du REJEPPAT', 'url' => 'https://youtu.be/Is_h3PXIdDI', 'en' => 'How REJEPPAT supports its members'],
    ];

    public function up(): void
    {
        foreach (self::VIDEOS as $video) {
            if (DB::table('medias')->where('url', $video['url'])->exists()) {
                continue;
            }

            DB::table('medias')->insert([
                'titre' => $video['titre'],
                'type' => 'video',
                'date' => today(),
                'url' => $video['url'],
                'publie' => true,
                'traductions' => json_encode(['en' => ['titre' => $video['en']]]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('medias')->whereIn('url', array_column(self::VIDEOS, 'url'))->delete();
    }
};
