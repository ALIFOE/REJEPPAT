<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agenda (ateliers, formations, panels, foires), médiathèque (photos, vidéos, podcasts)
 * newsletter (abonnés et lettres envoyées) ; l'e-mail devient facultatif pour les messages
 * du formulaire de contact rapide (nom, téléphone et message).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titre');
            $table->string('type')->index();
            $table->dateTime('debut')->index();
            $table->dateTime('fin')->nullable();
            $table->string('lieu');
            $table->json('description');
            $table->string('lien_inscription')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('type')->index();
            $table->date('date');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('url')->nullable();
            $table->string('fichier')->nullable();
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('abonnes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('locale', 5)->default('fr');
            $table->string('jeton', 64)->unique();
            $table->timestamp('desabonne_le')->nullable();
            $table->timestamps();
        });

        Schema::create('lettres', function (Blueprint $table) {
            $table->id();
            $table->string('sujet');
            $table->longText('contenu');
            $table->timestamp('envoyee_le')->nullable();
            $table->unsignedInteger('destinataires')->default(0);
            $table->timestamps();
        });

        Schema::table('messages_contact', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages_contact', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });

        Schema::dropIfExists('lettres');
        Schema::dropIfExists('abonnes');
        Schema::dropIfExists('medias');
        Schema::dropIfExists('evenements');
    }
};
