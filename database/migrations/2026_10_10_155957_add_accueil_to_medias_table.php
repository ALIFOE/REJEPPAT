<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Vidéos du site : une vidéo envoyée dans la médiathèque (fichier protégé, hors du dossier public)
 * peut être mise en avant sur la page d'accueil.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->boolean('accueil')->default(false)->after('fichier');
            $table->unsignedInteger('ordre')->default(0)->after('accueil');
        });
    }

    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->dropColumn(['accueil', 'ordre']);
        });
    }
};
