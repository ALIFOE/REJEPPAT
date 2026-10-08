<?php

/*
|--------------------------------------------------------------------------
| Nos Programmes & Projets (source : https://rejeppat.org/our-projects/)
|--------------------------------------------------------------------------
|
| « resume » reprend le texte de présentation de chaque projet sur l'ancien
| site. Les autres textes sont repris des pages Qui sommes-nous, Les Fermes
| Écoles et des actualités du REJEPPAT qui traitent du même sujet.
|
*/

return [

    'categories' => [
        'formation' => 'Formation',
        'agroecologie' => 'Agroécologie',
        'environnement' => 'Environnement',
        'inclusion' => 'Inclusion',
    ],

    'liste' => [
        [
            'slug' => 'fermes-ecoles-agroecologiques',
            'titre' => 'Projet de Fermes Écoles Agroécologiques',
            'titre_court' => 'Fermes Écoles Agroécologiques',
            'icone' => 'icon-farm-house-1',
            'categories' => ['formation', 'agroecologie'],
            'date' => '2025-06-12',
            'resume' => 'Le REJEPPAT a mis en place plusieurs fermes écoles agroécologiques destinées à la formation pratique des jeunes agriculteurs.',
            'description' => [
                'Depuis plusieurs années, le REJEPPAT a développé des fermes écoles agroécologiques permettant la formation pratique des jeunes agriculteurs.',
                'Ces centres de formation permettent aux jeunes producteurs agricoles d’acquérir des compétences pratiques en agroécologie, gestion durable des sols, compostage biologique et entrepreneuriat agricole.',
            ],
            'actions' => [
                ['titre' => 'Une formation dans des conditions réelles de production', 'texte' => 'Les participants réalisent des travaux pratiques et participent aux activités de production afin de maîtriser les techniques enseignées.'],
                ['titre' => 'Un réseau de fermes écoles dans les régions', 'texte' => 'Le REJEPPAT compte 25 fermes écoles mises en place à travers le Togo pour rapprocher la formation des jeunes ruraux.'],
            ],
            'resultats' => [
                'L’organisation a déjà formé plusieurs centaines de jeunes ruraux et contribué à la diffusion des pratiques agroécologiques dans différentes régions du Togo.',
            ],
            'points' => [
                '25 fermes écoles mises en place',
                'Plus de 285 jeunes formés',
                'Diffusion des pratiques agroécologiques',
                'Appui à l’installation des jeunes',
            ],
        ],
        [
            'slug' => 'formation-insertion-jeunes-agriculteurs',
            'titre' => 'Projet de Formation et d’Insertion des Jeunes Agriculteurs',
            'titre_court' => 'Formation et Insertion des Jeunes',
            'icone' => 'icon-farmer',
            'categories' => ['formation', 'inclusion'],
            'date' => '2025-06-12',
            'resume' => 'Ce projet vise à accompagner les jeunes dans leur insertion professionnelle à travers la formation et le coaching entrepreneurial.',
            'description' => [
                'Le REJEPPAT continue d’accompagner les jeunes producteurs agricoles à travers des programmes de formation professionnelle et de coaching entrepreneurial.',
                'Ces initiatives visent à offrir aux jeunes ruraux les compétences nécessaires pour créer et gérer efficacement leurs propres exploitations agricoles.',
            ],
            'actions' => [
                ['titre' => 'Des formations sur plusieurs thématiques', 'texte' => 'Les bénéficiaires sont formés à la gestion des exploitations agricoles, aux techniques agroécologiques, à la transformation des produits agricoles et à l’élaboration de plans d’affaires.'],
                ['titre' => 'Un accompagnement de proximité', 'texte' => 'Des coachs formés par le REJEPPAT assurent l’accompagnement, le suivi et l’encadrement des bénéficiaires des programmes et projets agricoles.'],
            ],
            'resultats' => [
                'Grâce à cet accompagnement, plusieurs jeunes entrepreneurs agricoles ont réussi à développer des activités génératrices de revenus et participent aujourd’hui activement au développement économique de leurs communautés.',
            ],
            'points' => [
                'Gestion des exploitations agricoles',
                'Techniques agroécologiques',
                'Transformation des produits agricoles',
                'Élaboration de plans d’affaires',
            ],
        ],
        [
            'slug' => 'promotion-agroecologie',
            'titre' => 'Projet de Promotion de l’Agroécologie',
            'titre_court' => 'Promotion de l’Agroécologie',
            'icone' => 'icon-seedling',
            'categories' => ['agroecologie'],
            'date' => '2025-06-12',
            'resume' => 'Le REJEPPAT sensibilise les producteurs agricoles aux pratiques agroécologiques afin de promouvoir une agriculture durable.',
            'description' => [
                'Le REJEPPAT a développé des initiatives de promotion des pratiques agroécologiques et de restauration des paysages forestiers à travers la formation des jeunes agriculteurs dans les fermes écoles, l’organisation des jeunes en coopératives agricoles, l’initiation des marchés des produits agroécologiques, la transformation des produits locaux et l’appui à l’installation des jeunes.',
            ],
            'actions' => [
                ['titre' => 'Production de compost, de Bokashi et d’Apichi', 'texte' => 'Les matières premières nécessaires (résidus de récolte, déjections animales, son de riz ou de maïs, cendres, plantes à effet biopesticide) sont valorisées localement par les jeunes producteurs.'],
                ['titre' => 'Des marchés pour les produits agroécologiques', 'texte' => 'Le réseau développe des stratégies de valorisation des produits agroécologiques et renforce la résilience des jeunes agriculteurs.'],
            ],
            'resultats' => [],
            'points' => [
                'Compostage biologique',
                'Bokashi et biopesticides',
                'Gestion durable des sols',
                'Marchés des produits agroécologiques',
            ],
        ],
        [
            'slug' => 'reboisement-protection-environnement',
            'titre' => 'Projet de Reboisement et de Protection de l’Environnement',
            'titre_court' => 'Reboisement et Environnement',
            'icone' => 'icon-eco-friendly',
            'categories' => ['environnement'],
            'date' => '2025-06-12',
            'resume' => 'Dans le cadre de la lutte contre la dégradation environnementale, le REJEPPAT multiplie les initiatives de reboisement et de restauration des terres.',
            'description' => [
                'Dans le cadre de la lutte contre les effets des changements climatiques, le REJEPPAT multiplie les initiatives de reboisement et de restauration des terres dégradées dans plusieurs communautés rurales du Togo.',
                'À travers ses campagnes environnementales, le réseau sensibilise les producteurs agricoles à l’importance de la préservation des ressources naturelles et à l’utilisation de pratiques agricoles durables.',
            ],
            'actions' => [
                ['titre' => 'Distribution de plants forestiers', 'texte' => 'Plus de 10 000 plants forestiers ont déjà été distribués aux communautés rurales afin de contribuer à la restauration des paysages forestiers et à la protection des écosystèmes agricoles.'],
                ['titre' => 'Des arbres fertilitaires dans les exploitations', 'texte' => 'Le REJEPPAT encourage l’introduction d’arbres fertilitaires dans les exploitations agricoles afin d’améliorer durablement la fertilité des sols.'],
            ],
            'resultats' => [
                'Ces actions traduisent l’engagement du réseau en faveur d’une agriculture durable conciliant production agricole, sécurité alimentaire et protection de l’environnement.',
            ],
            'points' => [
                'Plus de 10 000 plants forestiers distribués',
                'Restauration des terres dégradées',
                'Arbres fertilitaires dans les exploitations',
                'Résilience face aux aléas climatiques',
            ],
        ],
        [
            'slug' => 'autonomisation-femmes-rurales',
            'titre' => 'Projet d’Autonomisation des Femmes Rurales',
            'titre_court' => 'Autonomisation des Femmes Rurales',
            'icone' => 'icon-handshake',
            'categories' => ['inclusion'],
            'date' => '2025-06-12',
            'resume' => 'Le REJEPPAT œuvre pour l’inclusion des femmes rurales dans les activités agricoles et les chaînes de valeur.',
            'description' => [
                'Le réseau met un accent particulier sur l’autonomisation des jeunes femmes rurales afin de favoriser leur inclusion dans les chaînes de valeur agricoles.',
                'Les femmes représentent 44 % des membres actifs du REJEPPAT. Le réseau compte 70 coopératives féminines et 25 coopératives mixtes comptant en moyenne 60 % de jeunes femmes.',
            ],
            'actions' => [
                ['titre' => 'Des coopératives féminines', 'texte' => 'L’organisation des femmes en coopératives agricoles renforce leur accès aux formations, aux marchés et aux opportunités économiques.'],
                ['titre' => 'Des formations dans les fermes écoles', 'texte' => 'La Ferme-École REJEPPAT contribue à promouvoir l’autonomisation économique des femmes à travers la formation pratique.'],
            ],
            'resultats' => [],
            'points' => [
                '44 % de femmes parmi les membres actifs',
                '70 coopératives féminines',
                '60 % de jeunes femmes dans les coopératives mixtes',
            ],
        ],
        [
            'slug' => 'agriculture-intelligente-climat',
            'titre' => 'Projet d’Agriculture Intelligente face au Climat',
            'titre_court' => 'Agriculture Intelligente face au Climat',
            'icone' => 'icon-smart-farming',
            'categories' => ['agroecologie', 'environnement'],
            'date' => '2025-06-12',
            'resume' => 'Ce projet accompagne les producteurs agricoles dans l’adoption de pratiques agricoles adaptées aux changements climatiques.',
            'description' => [
                'L’objectif est de favoriser l’insertion professionnelle des jeunes tout en promouvant des pratiques agricoles respectueuses de l’environnement et adaptées aux changements climatiques.',
                'Les fermes écoles du REJEPPAT forment les jeunes à l’agriculture durable et intelligente face au climat ainsi qu’aux techniques de production innovantes.',
            ],
            'actions' => [],
            'resultats' => [
                'Le REJEPPAT ambitionne d’étendre ce modèle afin d’accompagner davantage de jeunes vers une agriculture moderne, résiliente et créatrice d’emplois durables.',
            ],
            'points' => [
                'Pratiques adaptées aux changements climatiques',
                'Techniques de production innovantes',
                'Agriculture moderne et résiliente',
            ],
        ],
    ],

];
