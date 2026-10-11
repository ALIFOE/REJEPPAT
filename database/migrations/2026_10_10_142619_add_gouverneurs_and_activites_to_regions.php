<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sections régionales mises en avant sur l'accueil :
 * - « gouverneurs » : le ou les gouverneurs de la section [{nom, fonction, telephone, photo}] ;
 * - « activites » : activités et informations à venir, une par ligne ;
 * - les événements de l'agenda peuvent être rattachés à une région.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            $table->json('gouverneurs')->nullable()->after('filieres');
            $table->json('activites')->nullable()->after('gouverneurs');
        });

        Schema::table('evenements', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('type')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evenements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn(['gouverneurs', 'activites']);
        });
    }
};
