<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Photos supplémentaires d'un produit (3 au plus), affichées dans la galerie de la fiche produit.
 * Chemins relatifs à public/uploads.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->json('photos')->nullable()->after('photo');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }
};
