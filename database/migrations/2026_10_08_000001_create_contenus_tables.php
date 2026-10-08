<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Contenus du site gérés depuis l'administration
 * (auparavant dans config/actualites.php, projets.php, fermes.php, boutique.php).
 *
 * « image » : nom des visuels d'origine dans public/assets/images/rejeppat/…
 * « photo » : visuel envoyé depuis l'administration (public/uploads/…), prioritaire.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->date('date');
            $table->json('categories');
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->json('content');
            $table->json('tags');
            $table->json('gallery');
            $table->unsignedInteger('vues')->default(0);
            $table->boolean('publie')->default(true);
            $table->timestamps();
        });

        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titre');
            $table->string('titre_court');
            $table->string('icone')->default('icon-farm-house-1');
            $table->json('categories');
            $table->date('date');
            $table->text('resume');
            $table->json('description');
            $table->json('actions');
            $table->json('resultats');
            $table->json('points');
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->timestamps();
        });

        Schema::create('fermes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nom');
            $table->string('localisation')->nullable();
            $table->string('specialite');
            $table->json('modules');
            $table->json('carte')->nullable();
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('accueil')->default(false);
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->timestamps();
        });

        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('texte');
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
        Schema::dropIfExists('fermes');
        Schema::dropIfExists('projets');
        Schema::dropIfExists('actualites');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
