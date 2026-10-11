<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Nouveaux intitulés de deux projets demandés par le REJEPPAT :
 * « Reboisement et Environnement » devient « Éducation Forestière » et
 * « Autonomisation des Femmes Rurales » devient « Autonomisation des Jeunes Femmes Rurales ».
 * Les adresses des pages (slugs) ne changent pas.
 */
return new class extends Migration
{
    /** slug => [ancien intitulé, nouvel intitulé] (fr et en) */
    private const PROJETS = [
        'reboisement-protection-environnement' => [
            'avant' => ['titre' => 'Projet de Reboisement et de Protection de l’Environnement', 'titre_court' => 'Reboisement et Environnement', 'en' => ['titre' => 'Reforestation and Environmental Protection Project', 'titre_court' => 'Reforestation and Environment']],
            'apres' => ['titre' => 'Projet d’Éducation Forestière', 'titre_court' => 'Éducation Forestière', 'en' => ['titre' => 'Forest Education Project', 'titre_court' => 'Forest Education']],
        ],
        'autonomisation-femmes-rurales' => [
            'avant' => ['titre' => 'Projet d’Autonomisation des Femmes Rurales', 'titre_court' => 'Autonomisation des Femmes Rurales', 'en' => ['titre' => 'Rural Women Empowerment Project', 'titre_court' => 'Empowering Rural Women']],
            'apres' => ['titre' => 'Projet d’Autonomisation des Jeunes Femmes Rurales', 'titre_court' => 'Autonomisation des Jeunes Femmes Rurales', 'en' => ['titre' => 'Young Rural Women Empowerment Project', 'titre_court' => 'Empowering Young Rural Women']],
        ],
    ];

    public function up(): void
    {
        $this->appliquer('apres');
    }

    public function down(): void
    {
        $this->appliquer('avant');
    }

    private function appliquer(string $version): void
    {
        foreach (self::PROJETS as $slug => $intitules) {
            $projet = DB::table('projets')->where('slug', $slug)->first();
            if (! $projet) {
                continue;
            }

            $nouveau = $intitules[$version];
            $traductions = json_decode($projet->traductions ?? '', true) ?: [];
            $traductions['en'] = array_merge($traductions['en'] ?? [], $nouveau['en']);

            DB::table('projets')->where('slug', $slug)->update([
                'titre' => $nouveau['titre'],
                'titre_court' => $nouveau['titre_court'],
                'traductions' => json_encode($traductions, JSON_UNESCAPED_UNICODE),
            ]);
        }
    }
};
