<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Présentation du REJEPPAT partagée au colloque du réseau FAR sur l'insertion des jeunes (juin 2014),
 * reprise de l'ancien site pour être conservée. Le PDF versionné (resources/documents) est copié
 * dans public/uploads/ressources puis publié dans « Ressources » (Rapports & publications).
 */
return new class extends Migration
{
    private const SOURCE = 'documents/presentation-rejeppat-colloque-far-2014.pdf';

    private const FICHIER = 'ressources/presentation-rejeppat-colloque-far-2014.pdf';

    public function up(): void
    {
        $cible = public_path('uploads/'.self::FICHIER);
        if (! File::exists($cible)) {
            File::ensureDirectoryExists(dirname($cible));
            File::copy(resource_path(self::SOURCE), $cible);
        }

        if (DB::table('ressources')->where('fichier', self::FICHIER)->exists()) {
            return;
        }

        DB::table('ressources')->insert([
            'titre' => 'Présentation du REJEPPAT – Colloque FAR sur l’insertion des jeunes (2014)',
            'categorie' => 'rapport',
            'description' => 'Objectifs, vision, mission, actions, membres et fonctionnement du réseau, présentés au colloque du réseau FAR sur l’insertion des jeunes en juin 2014.',
            'fichier' => self::FICHIER,
            'ordre' => 0,
            'publie' => true,
            'traductions' => json_encode(['en' => [
                'titre' => 'REJEPPAT presentation – FAR conference on youth integration (2014)',
                'description' => 'The network’s objectives, vision, mission, actions, members and organisation, presented at the FAR network conference on youth integration in June 2014.',
            ]], JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('ressources')->where('fichier', self::FICHIER)->delete();
        File::delete(public_path('uploads/'.self::FICHIER));
    }
};
