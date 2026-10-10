<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Le réseau : sections régionales, partenaires et demandes de partenariat,
 * fiches complètes des fermes écoles et état d'avancement des projets.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nom');
            $table->string('chef_lieu')->nullable();
            $table->json('presentation');
            $table->json('filieres');
            $table->unsignedInteger('nb_cooperatives')->nullable();
            $table->unsignedInteger('nb_membres')->nullable();
            $table->string('responsable')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type')->index();
            $table->text('description')->nullable();
            $table->string('site')->nullable();
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('publie')->default(true);
            $table->json('traductions')->nullable();
            $table->timestamps();
        });

        Schema::create('demandes_partenariat', function (Blueprint $table) {
            $table->id();
            $table->string('organisation');
            $table->string('nom');
            $table->string('fonction')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('type');
            $table->text('message');
            $table->string('statut')->default('nouvelle')->index();
            $table->text('note_admin')->nullable();
            $table->timestamps();
        });

        Schema::table('fermes', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('localisation')->constrained()->nullOnDelete();
            $table->json('description')->nullable()->after('specialite');
            $table->json('productions')->nullable()->after('modules');
            $table->json('actions')->nullable()->after('productions');
            $table->string('responsable')->nullable()->after('actions');
            $table->string('telephone')->nullable()->after('responsable');
            $table->string('email')->nullable()->after('telephone');
            $table->json('galerie')->nullable()->after('photo');
        });

        Schema::table('projets', function (Blueprint $table) {
            $table->string('statut')->default('en_cours')->after('date')->index();
        });
    }

    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropColumn('statut');
        });

        Schema::table('fermes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
            $table->dropColumn(['description', 'productions', 'actions', 'responsable', 'telephone', 'email', 'galerie']);
        });

        Schema::dropIfExists('demandes_partenariat');
        Schema::dropIfExists('partenaires');
        Schema::dropIfExists('regions');
    }
};
