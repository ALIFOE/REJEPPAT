<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Texte mis en forme des articles (éditeur de l'administration).
 * « content » reste la version texte (paragraphes) utilisée pour les extraits,
 * la durée de lecture et la recherche.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->longText('corps')->nullable()->after('content');
        });

        // Les articles existants reprennent leurs paragraphes
        DB::table('actualites')->orderBy('id')->each(function ($actualite) {
            $corps = collect(json_decode($actualite->content, true) ?: [])
                ->map(fn ($paragraphe) => '<p>' . e($paragraphe) . '</p>')
                ->implode("\n");

            DB::table('actualites')->where('id', $actualite->id)->update(['corps' => $corps]);
        });
    }

    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->dropColumn('corps');
        });
    }
};
