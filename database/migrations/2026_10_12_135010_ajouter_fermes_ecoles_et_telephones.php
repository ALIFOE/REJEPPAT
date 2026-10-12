<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Liste des fermes écoles transmise par le réseau (octobre 2026) : nouvelles fermes écoles
 * (avec leur logo dans assets/images/rejeppat/fermes) et numéro de téléphone de chaque ferme.
 * Un téléphone déjà renseigné dans l'administration n'est pas modifié.
 */
return new class extends Migration
{
    /** Spécialités traduites des nouvelles fermes écoles */
    private const SPECIALITES_EN = [
        'groupe-avidja' => 'Agroecology',
        'cata' => 'Agro-pastoralism',
        'shalom-agroeco-farm' => 'Agroecology',
        'cpda-togo' => 'Agroecology',
        'terre-fleurie' => 'Agroecology',
    ];

    public function up(): void
    {
        // Base vide : le seeder importe la liste complète
        if (DB::table('fermes')->doesntExist()) {
            return;
        }

        foreach (config('fermes.liste') as $ferme) {
            if (DB::table('fermes')->where('slug', $ferme['slug'])->exists()) {
                DB::table('fermes')->where('slug', $ferme['slug'])->whereNull('telephone')
                    ->update(['telephone' => $ferme['telephone'] ?? null]);

                continue;
            }

            DB::table('fermes')->insert([
                'slug' => $ferme['slug'],
                'nom' => $ferme['nom'],
                'localisation' => $ferme['localisation'],
                'specialite' => $ferme['specialite'],
                'telephone' => $ferme['telephone'] ?? null,
                'modules' => json_encode($ferme['modules']),
                'carte' => isset($ferme['carte']) ? json_encode($ferme['carte']) : null,
                'image' => $ferme['slug'],
                'traductions' => isset(self::SPECIALITES_EN[$ferme['slug']])
                    ? json_encode(['en' => ['specialite' => self::SPECIALITES_EN[$ferme['slug']]]])
                    : null,
                'ordre' => DB::table('fermes')->max('ordre') + 1,
                'publie' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('fermes')->whereIn('slug', [
            'academie-paysanne-nenonene', 'groupe-avidja', 'crpm', 'cata', 'shalom-agroeco-farm', 'yamefa',
            'tout-est-grace', 'essouwerou', 'cpda-togo', 'fpapae-tg', 'trois-mages', 'terre-fleurie', 'lampougue',
        ])->delete();
    }
};
