<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Demandes de services et messages du formulaire de contact,
 * désormais enregistrés pour être suivis depuis l'administration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_services', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('organisation')->nullable();
            $table->string('service');
            $table->string('region')->nullable();
            $table->string('objet');
            $table->text('besoin');
            $table->string('statut')->default('nouvelle')->index();
            $table->text('note_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('messages_contact', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('telephone')->nullable();
            $table->string('email');
            $table->string('objet');
            $table->text('message');
            $table->timestamp('lu_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_contact');
        Schema::dropIfExists('demandes_services');
    }
};
