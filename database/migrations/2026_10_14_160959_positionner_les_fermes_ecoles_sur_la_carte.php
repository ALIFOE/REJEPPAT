<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Positions GPS et adresses des fermes écoles transmises par le réseau (octobre 2026, config fermes.liste) :
 * carte interactive, localisation affichée quand elle manquait, et rattachement à la section régionale
 * d'après la préfecture (config rejeppat.regions). Une région déjà choisie dans l'administration est conservée.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Base vide : le seeder importe la liste complète
        if (DB::table('fermes')->doesntExist()) {
            return;
        }

        $regions = DB::table('regions')->pluck('id', 'slug');

        foreach (config('fermes.liste') as $ferme) {
            if (! isset($ferme['carte'])) {
                continue;
            }

            DB::table('fermes')->where('slug', $ferme['slug'])->update(['carte' => json_encode($ferme['carte'])]);

            if ($ferme['localisation']) {
                DB::table('fermes')->where('slug', $ferme['slug'])->whereNull('localisation')
                    ->update(['localisation' => $ferme['localisation']]);
            }

            foreach (config('rejeppat.regions') as $region) {
                $motif = '/préfecture d(e |es |’|\')('.implode('|', array_map('preg_quote', $region['prefectures'])).')(?![\p{L}\d])/u';

                if ($region['prefectures'] && isset($regions[$region['slug']]) && preg_match($motif, $ferme['carte']['adresse'])) {
                    DB::table('fermes')->where('slug', $ferme['slug'])->whereNull('region_id')
                        ->update(['region_id' => $regions[$region['slug']]]);
                }
            }
        }
    }

    public function down(): void
    {
        // Positions précédentes non conservées : rien à annuler
    }
};
