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
            'carte' => ['lat' => 8.50413, 'lng' => 0.97139, 'adresse' => 'Sise à Kpétè-Kpétè, canton de Sotouboua, commune de Sotouboua 1, préfecture de Sotouboua.'],
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
            'carte' => ['lat' => 7.5207418, 'lng' => 1.0610079, 'adresse' => 'Sise à Wakpa, canton de Temedja, commune d’Amou 3, préfecture d’Amou.'],
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
            'carte' => ['lat' => 6.77658, 'lng' => 1.3312, 'adresse' => 'Sise à Agoto, canton d’Atchavé, commune de Haho 1, préfecture de Haho.'],
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
            'carte' => ['lat' => 8.343848, 'lng' => 1.010407, 'adresse' => 'Sise à Tchangaidè, canton de Blitta village, commune de Blitta 1, préfecture de Blitta.'],
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
            'carte' => ['lat' => 9.03333, 'lng' => 1.41667, 'adresse' => 'Sise à Nandjoubi, canton de Tchamba, commune de Tchamba 1, préfecture de Tchamba.'],
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
            'carte' => ['lat' => 9.033333, 'lng' => 1.416667, 'adresse' => 'Sise à Koutaboni, canton de Tchamba, commune de Tchamba 1, préfecture de Tchamba.'],
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
            'carte' => ['lat' => 8.995, 'lng' => 1.14, 'adresse' => 'Sise à Tchavadi, canton de Sokodé, commune de Tchaoudjo 1, préfecture de Tchaoudjo.'],
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
            'carte' => ['lat' => 8.841417, 'lng' => 1.534483, 'adresse' => 'Sise à Kélétou, canton de Koussountou, commune de Tchamba 2, préfecture de Tchamba.'],
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
            'carte' => ['lat' => 8.4308803, 'lng' => 0.9940179, 'adresse' => 'Sise à Tchébébé, canton de Tchébébé, commune de Sotouboua 3, préfecture de Sotouboua.'],
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
    ],

];
