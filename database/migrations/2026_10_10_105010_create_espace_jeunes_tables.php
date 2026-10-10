<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Espace Jeunes / Membres : annuaire des membres, témoignages et success stories,
 * opportunités (emplois, appels à projets, financements, bourses) et ressources téléchargeables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nom');
            $table->string('organisation')->nullable();
            $table->foreignId('region_id')->nullable()->constrained()->nullOnDelete();
            $table->string('localite')->nullable();
            $table->string('filiere');
            $table->json('presentation');
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('publie')->default(false)->index();
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('temoignages', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('nom');
            $table->string('role')->nullable();
            $table->string('titre')->nullable();
            $table->text('texte');
            $table->foreignId('ferme_id')->nullable()->constrained()->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('accueil')->default(false);
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunites', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titre');
            $table->string('type')->index();
            $table->string('organisme')->nullable();
            $table->string('lieu')->nullable();
            $table->date('date_limite')->nullable();
            $table->json('description');
            $table->string('lien')->nullable();
            $table->string('fichier')->nullable();
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('ressources', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('categorie')->index();
            $table->text('description')->nullable();
            $table->string('fichier');
            $table->unsignedInteger('telechargements')->default(0);
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ressources');
        Schema::dropIfExists('opportunites');
        Schema::dropIfExists('temoignages');
        Schema::dropIfExists('membres');
    }
};
