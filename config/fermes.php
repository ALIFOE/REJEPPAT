<?php

/*
|--------------------------------------------------------------------------
| Fermes Écoles du REJEPPAT (source : https://rejeppat.org/les-fermes-ecoles/
| et les pages de chaque ferme école)
|--------------------------------------------------------------------------
|
| « modules » : domaines de formation proposés par chaque ferme école.
| « localisation » : renseignée uniquement quand elle figure sur le site
| ou sur le logo de la ferme.
| « carte » : position GPS et adresse affichées sur la carte interactive
| (reprises de la carte de https://rejeppat.org/les-fermes-ecoles/).
|
*/

return [

    'presentation' => [
        'titre' => 'Apprendre par la pratique, produire pour l’avenir',
        'paragraphes' => [
            'La Ferme-École de REJEPPAT est un espace de formation, d’innovation et d’expérimentation dédié au développement des compétences agricoles et entrepreneuriales.',
            'Les participants sont formés dans des conditions réelles de production afin de développer les compétences nécessaires pour créer ou améliorer leurs propres activités agricoles.',
        ],
        'conclusion' => 'À travers ses formations, ses démonstrations pratiques et son accompagnement technique, la Ferme-École REJEPPAT contribue à renforcer l’employabilité des jeunes, à promouvoir l’autonomisation économique des femmes et à soutenir le développement d’une agriculture productive, respectueuse de l’environnement et créatrice d’opportunités.',
        'domaines' => [
            'L’agriculture durable et intelligente face au climat',
            'Le maraîchage et les cultures vivrières',
            'L’élevage',
            'La transformation des produits agricoles',
            'L’agroentrepreneuriat',
            'Les techniques de production innovantes',
        ],
        'publics' => [
            'Les jeunes',
            'Les femmes',
            'Les agriculteurs',
            'Les entrepreneurs',
            'Les étudiants',
            'Toute personne souhaitant se former au secteur agricole',
        ],
        'contact' => 'Notre équipe reste à votre disposition pour répondre à toutes vos questions. Contactez-nous par téléphone, WhatsApp ou via le formulaire de contact disponible sur le site.',
    ],

    'faq' => [
        [
            'question' => 'Qu’est-ce que la Ferme-École REJEPPAT ?',
            'reponse' => 'La Ferme-École REJEPPAT est un centre de formation pratique qui permet d’acquérir des compétences en agriculture, élevage, transformation des produits agricoles et agroentrepreneuriat grâce à un apprentissage basé sur la pratique.',
        ],
        [
            'question' => 'À qui s’adressent les formations ?',
            'reponse' => 'Nos formations sont ouvertes aux jeunes, aux femmes, aux agriculteurs, aux entrepreneurs, aux étudiants ainsi qu’à toute personne souhaitant développer des compétences dans le secteur agricole.',
        ],
        [
            'question' => 'Les formations sont-elles pratiques ?',
            'reponse' => 'Oui. Notre approche privilégie l’apprentissage sur le terrain. Les participants réalisent des travaux pratiques et participent aux activités de production afin de maîtriser les techniques enseignées.',
        ],
        [
            'question' => 'Comment s’inscrire ?',
            'reponse' => 'Vous pouvez vous inscrire en remplissant le formulaire disponible sur notre site ou en contactant directement notre équipe par téléphone, WhatsApp ou e-mail.',
        ],
        [
            'question' => 'Les formations sont-elles payantes ?',
            'reponse' => 'Certaines formations sont gratuites dans le cadre de projets spécifiques, tandis que d’autres sont payantes. Les coûts et les modalités sont précisés lors de chaque session de formation.',
        ],
        [
            'question' => 'Comment obtenir plus d’informations ?',
            'reponse' => 'Notre équipe reste à votre disposition pour répondre à toutes vos questions. Contactez-nous par téléphone, WhatsApp ou via le formulaire de contact disponible sur le site.',
        ],
    ],

    'liste' => [
        [
            'slug' => 'ma-joie',
            'nom' => 'Ma Joie',
            'localisation' => 'Kpété-Kpété, Région Centrale',
            'specialite' => 'Élevage & apiculture',
            'telephone' => '+228 90 71 71 76',
            'carte' => ['lat' => 8.512204, 'lng' => 0.968365, 'adresse' => 'Sise à Kpètè-Kpètè, commune de Sotouboua 1, préfecture de Sotouboua.'],
            'modules' => [
                'Élevage de petits ruminants',
                'Production de céréales suivant les pratiques agroécologiques',
                'Système intégré de la production animale et de la production végétale',
                'Compostage, utilisation de la fumure et du compost sous les plants',
                'Techniques de lutte contre les ravageurs des cultures',
                'Entrepreneuriat agricole',
                'Gestion d’une exploitation agricole',
                'Apiculture',
            ],
        ],
        [
            'slug' => 'cadete',
            'nom' => 'C.A.DE.T.E',
            'localisation' => 'Région des Plateaux',
            'specialite' => 'Agroécologie & maraîchage',
            'telephone' => '+228 90 70 01 04',
            'carte' => ['lat' => 7.43213, 'lng' => 1.07853, 'adresse' => 'Sise à Wakpa, commune d’Amou 3, préfecture d’Amou.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures en couloirs, agroforesterie et arboriculture…)',
                'Système intégré de production végétale et animale',
                'Techniques de lutte contre les ravageurs des cultures',
                'Transformation du soja en lait et viande',
                'Maraîchage agroécologique',
                'Système d’irrigation',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'terre-benie',
            'nom' => 'Terre Bénie',
            'localisation' => 'Région des Plateaux',
            'specialite' => 'Agroécologie',
            'telephone' => '+228 98 30 80 91',
            'carte' => ['lat' => 6.857819, 'lng' => 1.375105, 'adresse' => 'Sise à Agoto, commune de Haho 1, préfecture de Haho.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures…)',
            ],
        ],
        [
            'slug' => 'teoufema',
            'nom' => 'Teoufema',
            'localisation' => null,
            'specialite' => 'Élevage de porcs',
            'carte' => ['lat' => 8.84818, 'lng' => 1.07091, 'adresse' => 'Sise à Yao-Kopé, canton de Lama-Tessi, commune de Tchaoudjo 2, préfecture de Tchaoudjo.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures en couloirs, agroforesterie et arboriculture…)',
                'Système intégré de production végétale et animale',
                'Techniques de lutte contre les ravageurs des cultures',
                'Élevage des porcs',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'pain-de-vie',
            'nom' => 'Pain de Vie',
            'localisation' => 'Blitta - Tcharé-Baou',
            'specialite' => 'Transformation du soja',
            'carte' => ['lat' => 7.986, 'lng' => 0.816, 'adresse' => 'Sise à Niamtougou-Copé, canton de Tcharebaou, commune de Blitta 2, préfecture de Blitta.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures en couloirs, agroforesterie et arboriculture…)',
                'Système intégré de production végétale et animale',
                'Techniques de lutte contre les ravageurs des cultures',
                'Transformation du soja en lait et viande',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'togo-food',
            'nom' => 'Togo Food',
            'localisation' => null,
            'specialite' => 'Riziculture intensive (SRI)',
            'telephone' => '+228 93 26 45 02',
            'carte' => ['lat' => 8.3306, 'lng' => 1.044222, 'adresse' => 'Sise à Tchangaïdè, commune de Blitta 1, préfecture de Blitta.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures en couloirs, agroforesterie et arboriculture…)',
                'Système intégré de production végétale et animale',
                'Techniques de lutte contre les ravageurs des cultures',
                'Système de Riziculture Intensive (SRI)',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'ngnakpeboni',
            'nom' => 'N’Gnakpeboni',
            'localisation' => null,
            'specialite' => 'Anacarde & biointrants',
            'telephone' => '+228 92 15 94 28',
            'carte' => ['lat' => 9.044812, 'lng' => 1.533262, 'adresse' => 'Sise à Kabrebrè, commune de Tchamba 1, préfecture de Tchamba.'],
            'modules' => [
                'Agroécologie & production de céréales, légumineuses et tubercules (gestion durable des terres, compostage, association des cultures, fumure, cultures en couloirs, agroforesterie et arboriculture…)',
                'Système intégré de production végétale et animale',
                'Techniques de lutte contre les ravageurs des cultures',
                'Production d’anacarde',
                'Compostage, bokashi et biointrants',
                'Entrepreneuriat agricole',
                'Apiculture',
                'Maraîchage',
            ],
        ],
        [
            'slug' => 'sath-agro-business',
            'nom' => 'Sath Agro Business',
            'localisation' => null,
            'specialite' => 'Poules pondeuses',
            'telephone' => '+228 93 28 11 76',
            'carte' => ['lat' => 9.035597, 'lng' => 1.405767, 'adresse' => 'Sise à Koutaboni, commune de Tchamba 1, préfecture de Tchamba.'],
            'modules' => [
                'Élevage de poules pondeuses',
                'Production de céréales suivant les pratiques agroécologiques',
                'Élevage de petits ruminants',
                'Système intégré production animale et production végétale',
                'Compostage, utilisation de la fumure et du compost sous les plants',
                'Gestion d’une exploitation agricole',
                'Techniques de lutte contre les ravageurs des cultures',
                'Maraîchage',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'albaraka',
            'nom' => 'Albaraka',
            'localisation' => null,
            'specialite' => 'Transformation & agroforesterie',
            'telephone' => '+228 90 95 02 55',
            'carte' => ['lat' => 9.008431, 'lng' => 1.193741, 'adresse' => 'Sise à Tchavadé, commune de Tchaoudjo 1, préfecture de Tchaoudjo.'],
            'modules' => [
                'Transformation agroalimentaire (fruits, légumes, tubercules et céréales)',
                'Techniques de l’agroforesterie (culture associée à l’anacardier, plantes forestières non ligneuses, stratégies de régénération, plan de fertilisation des plants)',
                'Techniques de gestion d’une exploitation',
                'Techniques de lutte contre les ravageurs',
                'Techniques de Régénération Naturelle Assistée (RNA)',
                'Élevage des lapins et des poules locales',
                'Pratiques agroécologiques',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'pirenadou',
            'nom' => 'Pirenadou',
            'localisation' => 'Kéletou - Tchamba',
            'specialite' => 'Maraîchage & élevage',
            'telephone' => '+228 70 53 63 60',
            'carte' => ['lat' => 8.841166, 'lng' => 1.534472, 'adresse' => 'Sise à Kélétou, commune de Tchamba 2, préfecture de Tchamba.'],
            'modules' => [
                'Production de céréales et tubercules suivant les pratiques agroécologiques (compostage, bokashi)',
                'Diagnostic d’une exploitation agricole',
                'Itinéraires techniques des cultures céréalières, légumineuses et tubercules',
                'Système intégré de production végétale et animale',
                'Gestion d’une exploitation et notions en entrepreneuriat',
                'Techniques de lutte contre les ravageurs des cultures',
                'Confection des planches, préparation du sol, mise en place des pépinières, repiquage',
                'Engraissage, entretien et traitement',
                'Maraîchage agroécologique',
                'Élevage des porcs',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'capable-plus',
            'nom' => 'Capable-Plus',
            'localisation' => null,
            'specialite' => 'Transformation & irrigation',
            'telephone' => '+228 90 55 29 52',
            'carte' => ['lat' => 8.45488, 'lng' => 0.981525, 'adresse' => 'Sise à Tchébébé, commune de Sotouboua 3, préfecture de Sotouboua.'],
            'modules' => [
                'Transformation agroalimentaire',
                'Maraîchage agroécologique',
                'Installation du système d’irrigation',
                'Pratiques agroécologiques (compostage, bokashi, biofertilisants)',
                'Production des plants en pépinières',
                'Reboisement',
                'Entrepreneuriat agricole',
            ],
        ],
        [
            'slug' => 'les-merveilles-de-dieu',
            'nom' => 'Les Merveilles de Dieu',
            'localisation' => null,
            'specialite' => 'Maraîchage agroécologique',
            'carte' => ['lat' => 8.31667, 'lng' => 0.98333, 'adresse' => 'Sise à Kélébo, canton de Katchenke, commune de Blitta 3, préfecture de Blitta.'],
            'modules' => [
                'Pratiques agroécologiques en maraîchage : étude du sol, stratégie de régénération, plan de fertilisation',
                'Confection des planches et pépinières',
                'Techniques d’irrigation',
                'Techniques de gestion d’une exploitation',
                'Techniques de lutte contre les ravageurs',
                'Entrepreneuriat agricole',
            ],
        ],

        // Fermes écoles ajoutées d'après la liste du réseau (octobre 2026) : spécialité, adresse
        // et domaines de formation à compléter depuis l'administration.
        ['slug' => 'academie-paysanne-nenonene', 'nom' => 'Académie paysanne Nénonéné', 'localisation' => 'Zogbépimé, Région Maritime', 'specialite' => '', 'carte' => ['lat' => 6.432972, 'lng' => 0.99175, 'adresse' => 'Sise à Zogbépimé, commune d’Avé 1, préfecture d’Avé.'], 'telephone' => '+228 99 17 00 32', 'modules' => []],
        ['slug' => 'groupe-avidja', 'nom' => 'Groupe AVIDJA', 'localisation' => 'Agbodrafo, Région Maritime', 'specialite' => 'Agroécologie', 'carte' => ['lat' => 6.200886, 'lng' => 1.458086, 'adresse' => 'Sise à Agbodrafo, commune des Lacs 3, préfecture des Lacs.'], 'telephone' => '+228 92 20 47 36', 'modules' => []],
        ['slug' => 'crpm', 'nom' => 'CRPM', 'localisation' => 'Kôdzo, Région Maritime', 'specialite' => '', 'carte' => ['lat' => 6.482902, 'lng' => 1.247385, 'adresse' => 'Sise à Kôdzo, commune de Zio 1, préfecture de Zio.'], 'telephone' => '+228 90 82 47 43', 'modules' => []],
        ['slug' => 'cata', 'nom' => 'CATA', 'localisation' => 'Adokpoé, Agbélouvé', 'specialite' => 'Agro-pastoralisme', 'carte' => ['lat' => 6.634098, 'lng' => 1.19368, 'adresse' => 'Sise à Adokpoé, commune de Zio 3, préfecture de Zio.'], 'telephone' => '+228 90 10 69 79', 'modules' => []],
        ['slug' => 'shalom-agroeco-farm', 'nom' => 'Shalom AgroEco Farm', 'localisation' => 'Komla-Kopé, Région des Plateaux', 'specialite' => 'Agroécologie', 'carte' => ['lat' => 7.132015, 'lng' => 1.236531, 'adresse' => 'Sise à Komla-Kopé, commune de Haho 3, préfecture de Haho.'], 'telephone' => '+228 98 92 63 50', 'modules' => []],
        ['slug' => 'yamefa', 'nom' => 'YAMEFA', 'localisation' => 'Alati-Lété, Région des Plateaux', 'specialite' => '', 'carte' => ['lat' => 7.041432, 'lng' => 1.062222, 'adresse' => 'Sise à Alati-Lété, commune de Haho 3, préfecture de Haho.'], 'telephone' => '+228 92 40 29 66', 'modules' => []],
        ['slug' => 'tout-est-grace', 'nom' => 'Tout est Grâce', 'localisation' => 'Niamtougou-Copé, Région Centrale', 'specialite' => '', 'carte' => ['lat' => 8.066072, 'lng' => 1.024972, 'adresse' => 'Sise à Niamtougou-Copé, commune de Blitta 2, préfecture de Blitta.'], 'telephone' => '+228 92 80 53 57', 'modules' => []],
        ['slug' => 'essouwerou', 'nom' => 'Essouwerou', 'localisation' => 'Kélébo, Région Centrale', 'specialite' => '', 'carte' => ['lat' => 8.212601, 'lng' => 0.725011, 'adresse' => 'Sise à Kélébo, commune de Blitta 3, préfecture de Blitta.'], 'telephone' => '+228 92 72 67 07', 'modules' => []],
        ['slug' => 'cpda-togo', 'nom' => 'CPDA Togo', 'localisation' => 'Nagnine, Région de la Kara', 'specialite' => 'Agroécologie', 'carte' => ['lat' => 9.629157, 'lng' => 0.586525, 'adresse' => 'Sise à Nagnine, commune de Dankpen 3, préfecture de Dankpen.'], 'telephone' => '+228 71 26 26 27', 'modules' => []],
        ['slug' => 'fpapae-tg', 'nom' => 'FPAPAE-TG', 'localisation' => 'Atétou, Région de la Kara', 'specialite' => '', 'carte' => ['lat' => 9.93285, 'lng' => 1.04171, 'adresse' => 'Sise à Atétou, commune de Kéran 1, préfecture de Kéran.'], 'telephone' => '+228 90 78 82 01', 'modules' => []],
        ['slug' => 'trois-mages', 'nom' => '3 Mages', 'localisation' => 'Soumbou, Région de la Kara', 'specialite' => '', 'carte' => ['lat' => 9.476953, 'lng' => 1.208785, 'adresse' => 'Sise à Soumbou, commune de Kozah 3, préfecture de Kozah.'], 'telephone' => '+228 91 61 71 96', 'modules' => []],
        ['slug' => 'terre-fleurie', 'nom' => 'Terre Fleurie', 'localisation' => 'Tomangue, Région des Savanes', 'specialite' => 'Agroécologie', 'carte' => ['lat' => 10.727353, 'lng' => 0.060923, 'adresse' => 'Sise à Tomangue, commune de Tandjouaré 2, préfecture de Tandjouaré.'], 'telephone' => '+228 93 02 74 69', 'modules' => []],
        ['slug' => 'lampougue', 'nom' => 'Lampougue', 'localisation' => 'Tantigou, Région des Savanes', 'specialite' => '', 'carte' => ['lat' => 10.864984, 'lng' => 0.170589, 'adresse' => 'Sise à Tantigou, commune de Dapaong, préfecture de Tône.'], 'telephone' => '+228 92 12 76 69', 'modules' => []],
    ],

];
