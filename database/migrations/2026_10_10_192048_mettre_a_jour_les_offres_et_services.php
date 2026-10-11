<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Offres et services revus par le REJEPPAT : trois offres renommées (« Agriculture et élevage »,
 * « Accompagnement des initiatives », « Formation et renforcement des capacités »), ajout du
 * plaidoyer et une icône par offre, choisie d'après sa description.
 * Les demandes de service déjà reçues suivent le nouvel intitulé de leur offre.
 */
return new class extends Migration
{
    /** Intitulé d'origine => [nouvelle offre (fr et en), offre d'origine (fr et en)] */
    private const OFFRES_RENOMMEES = [
        'Formation et renforcement des capacités' => [
            'apres' => [
                'titre' => 'Formation aux pratiques résilientes aux changements climatiques',
                'texte' => 'Organisation de formations, d’ateliers et de sessions pratiques pour permettre aux jeunes et aux acteurs ruraux d’adopter des pratiques agricoles résilientes face aux changements climatiques.',
                'en' => ['titre' => 'Training in climate-resilient practices', 'texte' => 'Training courses, workshops and hands-on sessions enabling young people and rural stakeholders to adopt farming practices that are resilient to climate change.'],
            ],
            'avant' => [
                'texte' => 'Organisation de formations, d’ateliers et de sessions pratiques pour développer les compétences techniques, professionnelles et entrepreneuriales des jeunes et des acteurs ruraux.',
                'en' => ['titre' => 'Training and capacity building', 'texte' => 'Training courses, workshops and hands-on sessions to develop the technical, professional and entrepreneurial skills of young people and rural stakeholders.'],
            ],
        ],
        'Accompagnement des initiatives' => [
            'apres' => [
                'titre' => 'REJEPPAT-Fina',
                'texte' => 'Un mécanisme interne d’appui en matériels et en équipements pour les jeunes porteurs de projets.',
                'en' => ['titre' => 'REJEPPAT-Fina', 'texte' => 'An internal mechanism providing materials and equipment to young project leaders.'],
            ],
            'avant' => [
                'texte' => 'Appui aux porteurs de projets et aux organisations dans la structuration, la mise en œuvre et le développement de leurs initiatives.',
                'en' => ['titre' => 'Support for initiatives', 'texte' => 'Support for project leaders and organisations in structuring, implementing and developing their initiatives.'],
            ],
        ],
        'Agriculture et élevage' => [
            'apres' => [
                'titre' => 'Coaching sur le parcours entrepreneurial',
                'texte' => 'Accompagnement des producteurs dans l’amélioration des pratiques agricoles et de gestion de leurs exploitations, pour le développement d’entreprises agricoles durables adaptées aux réalités locales.',
                'en' => ['titre' => 'Coaching along the entrepreneurial journey', 'texte' => 'Support for producers in improving their farming and farm management practices, to build sustainable agricultural businesses adapted to local realities.'],
            ],
            'avant' => [
                'texte' => 'Accompagnement des producteurs dans l’amélioration des pratiques agricoles et d’élevage et dans le développement d’activités durables adaptées aux réalités locales.',
                'en' => ['titre' => 'Farming and livestock', 'texte' => 'Support for producers in improving farming and livestock practices and developing sustainable activities adapted to local realities.'],
            ],
        ],
    ];

    private const PLAIDOYER = [
        'titre' => 'Le plaidoyer',
        'texte' => 'Le REJEPPAT conduit des plaidoyers pour l’amélioration des conditions de vie et de travail des jeunes, ainsi que pour l’accès à la terre, aux financements adaptés, aux cadres de décision et aux innovations, et pour l’inclusion et l’équité de genre.',
        'en' => ['titre' => 'Advocacy', 'texte' => 'REJEPPAT advocates for better living and working conditions for young people, for access to land, suitable financing, decision-making bodies and innovation, and for inclusion and gender equity.'],
    ];

    /** Intitulé (après mise à jour) => icône */
    private const ICONES = [
        'Formation aux pratiques résilientes aux changements climatiques' => 'fas fa-cloud-sun-rain',
        'REJEPPAT-Fina' => 'fas fa-tools',
        'Coaching sur le parcours entrepreneurial' => 'fas fa-chalkboard-teacher',
        'Transformation agroalimentaire' => 'fas fa-mortar-pestle',
        'Entrepreneuriat des jeunes' => 'fas fa-briefcase',
        'Mise en réseau et partenariats' => 'fas fa-handshake',
        'Le plaidoyer' => 'fas fa-bullhorn',
    ];

    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->string('icone')->default('fas fa-seedling')->after('texte');
        });

        foreach (self::OFFRES_RENOMMEES as $ancienTitre => $versions) {
            $this->renommer($ancienTitre, $versions['apres']['titre'], $versions['apres']);
        }

        if (DB::table('offres')->exists() && DB::table('offres')->where('titre', self::PLAIDOYER['titre'])->doesntExist()) {
            DB::table('offres')->insert([
                'titre' => self::PLAIDOYER['titre'],
                'texte' => self::PLAIDOYER['texte'],
                'ordre' => DB::table('offres')->max('ordre') + 1,
                'actif' => true,
                'traductions' => json_encode(['en' => self::PLAIDOYER['en']], JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (self::ICONES as $titre => $icone) {
            DB::table('offres')->where('titre', $titre)->update(['icone' => $icone]);
        }
    }

    public function down(): void
    {
        DB::table('offres')->where('titre', self::PLAIDOYER['titre'])->delete();

        foreach (self::OFFRES_RENOMMEES as $ancienTitre => $versions) {
            $this->renommer($versions['apres']['titre'], $ancienTitre, $versions['avant']);
        }

        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn('icone');
        });
    }

    /** @param  array{texte: string, en: array{titre: string, texte: string}}  $contenu */
    private function renommer(string $titre, string $nouveauTitre, array $contenu): void
    {
        $offre = DB::table('offres')->where('titre', $titre)->first();
        if (! $offre) {
            return;
        }

        $traductions = json_decode($offre->traductions ?? '', true) ?: [];
        $traductions['en'] = array_merge($traductions['en'] ?? [], $contenu['en']);

        DB::table('offres')->where('id', $offre->id)->update([
            'titre' => $nouveauTitre,
            'texte' => $contenu['texte'],
            'traductions' => json_encode($traductions, JSON_UNESCAPED_UNICODE),
        ]);

        DB::table('demandes_services')->where('service', $titre)->update(['service' => $nouveauTitre]);
    }
};
