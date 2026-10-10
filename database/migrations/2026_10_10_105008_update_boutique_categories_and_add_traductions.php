<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Cahier des charges :
 * - catégories de la boutique (Fruits & Légumes, Tubercules & Céréales, Produits transformés,
 *   Boissons & Jus, Miel & Dérivés, Épices) ;
 * - version anglaise facultative des contenus (« traductions » : {"en": {"champ": "valeur"}}).
 */
return new class extends Migration
{
    /** Ancienne catégorie => nouvelle catégorie */
    private const CATEGORIES = [
        'bio' => 'fruits-legumes',
        'cereales' => 'tubercules-cereales',
        'elevage' => 'transformes',
    ];

    private const TABLES_TRADUITES = ['actualites', 'projets', 'fermes', 'offres', 'produits'];

    public function up(): void
    {
        foreach (self::CATEGORIES as $ancienne => $nouvelle) {
            DB::table('produits')->where('categorie', $ancienne)->update(['categorie' => $nouvelle]);
        }

        foreach (self::TABLES_TRADUITES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->json('traductions')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES_TRADUITES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('traductions');
            });
        }

        foreach (self::CATEGORIES as $ancienne => $nouvelle) {
            DB::table('produits')->where('categorie', $nouvelle)->update(['categorie' => $ancienne]);
        }
    }
};
