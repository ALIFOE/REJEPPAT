<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Paiements Mobile Money en ligne (Mixx by Yas, Moov Money Flooz) via PayGate Global.
 * Une commande peut avoir plusieurs tentatives de paiement ; une seule aboutit.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained()->cascadeOnDelete();
            $table->string('operateur');
            $table->string('telephone');
            $table->unsignedInteger('montant');
            // Identifiant unique envoyé à PayGate (sert aussi à vérifier le statut)
            $table->string('identifiant')->unique();
            $table->string('tx_reference')->nullable()->index();
            $table->string('reference_operateur')->nullable();
            $table->string('statut')->default('en_attente')->index();
            $table->string('message')->nullable();
            $table->timestamp('paye_le')->nullable();
            $table->timestamps();
        });

        // T-Money est devenu Mixx by Yas
        DB::table('commandes')->where('mode_paiement', 'tmoney')->update(['mode_paiement' => 'mixx']);
    }

    public function down(): void
    {
        DB::table('commandes')->where('mode_paiement', 'mixx')->update(['mode_paiement' => 'tmoney']);

        Schema::dropIfExists('paiements');
    }
};
