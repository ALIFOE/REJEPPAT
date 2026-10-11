<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Nouveaux partenaires demandés par le REJEPPAT. Leurs logos, à récupérer auprès du réseau,
 * s'ajoutent ensuite depuis l'administration (Partenaires > Modifier).
 */
return new class extends Migration
{
    private const PARTENAIRES = [
        ['nom' => 'Ministère de l’Agriculture', 'type' => 'institutionnel'],
        ['nom' => 'FFF (Forest and Farm Facility)', 'type' => 'technique'],
        ['nom' => 'GIZ', 'type' => 'technique', 'site' => 'https://www.giz.de'],
    ];

    public function up(): void
    {
        if (DB::table('partenaires')->doesntExist()) {
            return;
        }

        foreach (self::PARTENAIRES as $partenaire) {
            if (DB::table('partenaires')->where('nom', $partenaire['nom'])->exists()) {
                continue;
            }

            DB::table('partenaires')->insert($partenaire + [
                'ordre' => DB::table('partenaires')->max('ordre') + 1,
                'publie' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('partenaires')->whereIn('nom', array_column(self::PARTENAIRES, 'nom'))->delete();
    }
};
