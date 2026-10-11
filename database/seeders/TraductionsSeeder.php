<?php

namespace Database\Seeders;

use App\Models\Actualite;
use App\Models\Ferme;
use App\Models\Media;
use App\Models\Offre;
use App\Models\Produit;
use App\Models\Projet;
use App\Models\Region;
use App\Models\Temoignage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Version anglaise des contenus d'origine du site (titres, résumés, fiches produits…).
 * Seuls les contenus sans traduction sont complétés : une version anglaise saisie
 * dans l'administration n'est jamais écrasée. Le texte des articles reste en français.
 */
class TraductionsSeeder extends Seeder
{
    private const PROJETS = [
        'fermes-ecoles-agroecologiques' => ['titre' => 'Agroecological Farm Schools Project', 'titre_court' => 'Agroecological Farm Schools', 'resume' => 'REJEPPAT has set up several agroecological farm schools for the hands-on training of young farmers.'],
        'formation-insertion-jeunes-agriculteurs' => ['titre' => 'Young Farmers Training and Integration Project', 'titre_court' => 'Training and Integration of Young People', 'resume' => 'This project supports young people into employment through training and entrepreneurial coaching.'],
        'promotion-agroecologie' => ['titre' => 'Agroecology Promotion Project', 'titre_court' => 'Promoting Agroecology', 'resume' => 'REJEPPAT raises farmers’ awareness of agroecological practices to promote sustainable agriculture.'],
        'reboisement-protection-environnement' => ['titre' => 'Forest Education Project', 'titre_court' => 'Forest Education', 'resume' => 'To fight environmental degradation, REJEPPAT is stepping up reforestation and land restoration initiatives.'],
        'autonomisation-femmes-rurales' => ['titre' => 'Young Rural Women Empowerment Project', 'titre_court' => 'Empowering Young Rural Women', 'resume' => 'REJEPPAT works to include rural women in farming activities and value chains.'],
        'agriculture-intelligente-climat' => ['titre' => 'Climate-Smart Agriculture Project', 'titre_court' => 'Climate-Smart Agriculture', 'resume' => 'This project helps farmers adopt farming practices adapted to climate change.'],
    ];

    private const FERMES = [
        'ma-joie' => 'Livestock & beekeeping',
        'cadete' => 'Agroecology & market gardening',
        'terre-benie' => 'Agroecology',
        'teoufema' => 'Pig farming',
        'pain-de-vie' => 'Soybean processing',
        'togo-food' => 'Intensive rice growing (SRI)',
        'ngnakpeboni' => 'Cashew & bio-inputs',
        'sath-agro-business' => 'Laying hens',
        'albaraka' => 'Processing & agroforestry',
        'pirenadou' => 'Market gardening & livestock',
        'capable-plus' => 'Processing & irrigation',
        'les-merveilles-de-dieu' => 'Agroecological market gardening',
    ];

    private const PRODUITS = [
        'carotte-biologique' => [
            'nom' => 'Organic carrot',
            'resume' => 'Discover our organic carrots, grown with respect for the environment using responsible farming practices.',
            'description' => [
                'Discover our organic carrots, grown with respect for the environment using responsible farming practices. Fresh, crunchy and naturally tasty, they are carefully selected to guarantee the best quality.',
                'Rich in nutrients and easy to include in your daily diet, carrots can be eaten raw, cooked, in salads, soups, juices or as a side dish for many meals.',
            ],
            'points_forts' => ['Environmentally friendly farming', 'Fresh, natural product', 'Crunchy texture and pleasant flavour', 'Carefully selected', 'Ideal for a healthy, balanced diet'],
        ],
        'oignon-bio' => [
            'nom' => 'Onion – for a healthy life',
            'resume' => 'Discover our organic onions, grown with respect for the environment using responsible farming practices.',
            'description' => [
                'Discover our organic onions, grown with respect for the environment using responsible farming practices. Fresh, natural and carefully selected, they offer an authentic flavour and excellent quality.',
                'Perfect with your dishes, sauces, salads, grills and all kinds of recipes, organic onion is an essential product for a healthy, natural diet.',
            ],
            'points_forts' => ['Environmentally friendly farming', 'Fresh, natural product', 'Authentic flavour', 'Carefully selected', 'Ideal for everyday cooking'],
        ],
        'tomates' => [
            'nom' => 'Tomatoes',
            'resume' => 'Fresh organic tomatoes grown by the producers of the REJEPPAT network.',
            'description' => [
                'Discover our organic tomatoes, grown by the network’s young producers with respect for the environment using responsible farming practices.',
                'Fresh and tasty, they go perfectly with your sauces, salads and everyday recipes.',
            ],
            'points_forts' => ['Environmentally friendly farming', 'Fresh, natural product', 'Ideal for everyday cooking'],
        ],
    ];

    private const TEMOIGNAGES = [
        'Kossi Mensah' => ['role' => 'Young agricultural entrepreneur', 'titre' => 'A stable income for my family', 'texte' => 'Thanks to REJEPPAT’s training, I was able to develop my farm and improve my production techniques. Today, my business provides a stable income for my family.'],
        'Ama Dossou' => ['role' => 'Market gardener', 'titre' => 'I gained confidence', 'texte' => 'REJEPPAT helped me acquire skills in agroecology and cooperative management. I gained confidence and I take an active part in developing my community.'],
        'Yao Tchalim' => ['role' => 'Member of an agricultural cooperative', 'titre' => 'More sustainable practices', 'texte' => 'REJEPPAT’s support has changed the way we work. We now use sustainable farming practices that increase our yields while protecting the environment.'],
    ];

    private const ACTUALITES = [
        'formation-15-nouveaux-coachs' => 'REJEPPAT trains 15 new coaches to strengthen capacities',
        'entrepreneuriat-forestier-22-maitres-formateurs-sotouboua' => 'Forest entrepreneurship: REJEPPAT builds the skills of 22 master trainers in Sotouboua',
        'education-forestiere-mission-suivi-ugp-rome' => 'Forest Education project: REJEPPAT hosts a monitoring mission from the Rome PMU',
        'andreas-hermes-akademie-renforce-rejeppat-renafat-montage-projet' => 'The Andreas Hermes Akademie trains REJEPPAT and ReNaFAT in project design',
        'nouveaux-outils-formation-entrepreneuriat-forestier' => 'REJEPPAT acquires new training tools to boost forest entrepreneurship',
        'jeunes-mobilisent-agroecologie' => 'Young people rally for agroecology',
        'rejeppat-maritime-redynamise-gouvernance' => 'REJEPPAT-Maritime revitalises its governance and strategy',
        'rejeppat-intensifie-actions-protection-environnement' => 'REJEPPAT steps up its environmental protection work',
        'rejeppat-renforce-entrepreneuriat-agricole-jeunes' => 'REJEPPAT strengthens youth agricultural entrepreneurship in Togo',
    ];

    /** Offres d'origine : titre français => version anglaise */
    private const OFFRES = [
        'Formation aux pratiques résilientes aux changements climatiques' => ['titre' => 'Training in climate-resilient practices', 'texte' => 'Training courses, workshops and hands-on sessions enabling young people and rural stakeholders to adopt farming practices that are resilient to climate change.'],
        'REJEPPAT-Fina' => ['titre' => 'REJEPPAT-Fina', 'texte' => 'An internal mechanism providing materials and equipment to young project leaders.'],
        'Coaching sur le parcours entrepreneurial' => ['titre' => 'Coaching along the entrepreneurial journey', 'texte' => 'Support for producers in improving their farming and farm management practices, to build sustainable agricultural businesses adapted to local realities.'],
        'Transformation agroalimentaire' => ['titre' => 'Food processing', 'texte' => 'Promoting the processing and adding value to local agricultural products to increase their value and create new opportunities.'],
        'Entrepreneuriat des jeunes' => ['titre' => 'Youth entrepreneurship', 'texte' => 'Support for young entrepreneurs in developing their businesses, building their skills and consolidating their economic initiatives.'],
        'Mise en réseau et partenariats' => ['titre' => 'Networking and partnerships', 'texte' => 'Connecting young people, producers, organisations, institutions and partners to encourage exchanges, collaboration and opportunities.'],
        'Le plaidoyer' => ['titre' => 'Advocacy', 'texte' => 'REJEPPAT advocates for better living and working conditions for young people, for access to land, suitable financing, decision-making bodies and innovation, and for inclusion and gender equity.'],
    ];

    public function run(): void
    {
        foreach (self::PROJETS as $slug => $anglais) {
            $this->traduire(Projet::where('slug', $slug)->first(), $anglais);
        }

        foreach (self::FERMES as $slug => $specialite) {
            $this->traduire(Ferme::where('slug', $slug)->first(), ['specialite' => $specialite]);
        }

        foreach (self::PRODUITS as $slug => $anglais) {
            $this->traduire(Produit::where('slug', $slug)->first(), $anglais);
        }

        foreach (self::TEMOIGNAGES as $nom => $anglais) {
            $this->traduire(Temoignage::where('nom', $nom)->first(), $anglais);
        }

        foreach (self::ACTUALITES as $slug => $titre) {
            $this->traduire(Actualite::where('slug', $slug)->first(), ['title' => $titre]);
        }

        foreach (self::OFFRES as $titre => $anglais) {
            $this->traduire(Offre::where('titre', $titre)->first(), $anglais);
        }

        $this->traduire(Media::where('fichier', 'videos/videojournal.mp4')->first(), ['titre' => 'REJEPPAT video news', 'description' => 'The network’s activities in pictures.']);

        $this->traduire(Region::where('slug', 'centrale')->first(), ['presentation' => [
            'The Central Region is home to REJEPPAT’s national headquarters in Sokodé and to most of the network’s farm schools.',
            'The section supports youth cooperatives in the prefectures of Tchaoudjo, Sotouboua, Blitta and Tchamba in agroecological production, processing and access to markets.',
        ]]);
    }

    /** @param  array<string, mixed>  $anglais */
    private function traduire(?Model $contenu, array $anglais): void
    {
        if ($contenu && empty($contenu->traductions)) {
            $contenu->update(['traductions' => ['en' => $anglais]]);
        }
    }
}
