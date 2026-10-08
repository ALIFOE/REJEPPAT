<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Boutique en ligne : produits, commandes et lignes de commande.
 * Les montants sont en francs CFA (entiers).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nom');
            $table->string('categorie');
            $table->string('image')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('prix');
            $table->unsignedInteger('prix_initial')->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->text('resume');
            $table->json('description');
            $table->json('points_forts');
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('nom');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->string('ville')->nullable();
            $table->string('adresse')->nullable();
            $table->string('mode_livraison');
            $table->unsignedInteger('frais_livraison')->default(0);
            $table->unsignedInteger('sous_total');
            $table->unsignedInteger('total');
            $table->string('mode_paiement');
            $table->string('reference_paiement')->nullable();
            $table->string('statut')->default('en_attente')->index();
            $table->string('statut_paiement')->default('en_attente')->index();
            $table->text('note')->nullable();
            $table->text('note_admin')->nullable();
            $table->timestamps();
        });

        Schema::create('commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nom_produit');
            $table->unsignedInteger('prix_unitaire');
            $table->unsignedInteger('quantite');
            $table->unsignedInteger('total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_lignes');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('produits');
    }
};
