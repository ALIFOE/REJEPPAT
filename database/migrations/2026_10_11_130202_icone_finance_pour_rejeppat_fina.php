<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Offre « REJEPPAT-Fina » : icône de financement (main tenant de l'argent) au lieu des outils.
 * Une icône déjà choisie dans l'administration n'est pas modifiée.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('offres')->where('titre', 'REJEPPAT-Fina')->where('icone', 'fas fa-tools')
            ->update(['icone' => 'fas fa-hand-holding-usd']);
    }

    public function down(): void
    {
        DB::table('offres')->where('titre', 'REJEPPAT-Fina')->where('icone', 'fas fa-hand-holding-usd')
            ->update(['icone' => 'fas fa-tools']);
    }
};
