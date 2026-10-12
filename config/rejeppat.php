<?php

/*
|--------------------------------------------------------------------------
| Informations générales du REJEPPAT
|--------------------------------------------------------------------------
|
| Coordonnées, liens et contenus partagés par plusieurs pages du site.
| Source : https://rejeppat.org
|
*/

return [

    'name' => 'REJEPPAT',
    'full_name' => 'Réseau des Jeunes Producteurs et Professionnels Agricoles du Togo',
    'tagline' => 'Plateforme des producteurs agricoles du Togo',
    'about' => 'Faîtière des organisations paysannes de jeunes créée le 10 juillet 2010 et membre de la CTOP, pour une agriculture durable et inclusive au Togo.',

    'phones' => [
        ['label' => '(+228) 98 92 63 50', 'tel' => '+22898926350'],
        ['label' => '(+228) 91 87 33 16', 'tel' => '+22891873316'],
    ],

    'whatsapp' => '22898926350',

    'emails' => [
        'info@rejeppat.org',
        'rejeppatg@gmail.com',
    ],

    'address' => 'Route Nationale N°1, 2ème von après l’hôpital Puits de Jacob, Quartier Ataworo, Sokodé',
    'address_short' => 'Quartier Ataworo, Sokodé - Togo',
    'po_box' => 'BP : 388, Sokodé',
    'map_query' => 'Quartier Ataworo, Sokodé, Togo',

    'social' => [
        'facebook' => 'https://www.facebook.com/profile.php?id=61591557034199',
        'twitter' => 'https://x.com/rejeppattogo',
        'linkedin' => 'https://www.linkedin.com/in/rajeppat-togo-bb7048420/',
        'youtube' => 'https://www.youtube.com/watch?v=0bCiARbU0R4',
    ],

    /*
    | Vidéo « Notre réseau, notre histoire » de l'accueil (ouverte dans une fenêtre sur le site)
    */
    'video_presentation' => 'https://www.youtube.com/watch?v=JMFDra6FbWQ',

    /*
    | Nos offres & services (https://rejeppat.org/nos-offres-services/)
    */
    'offres' => [
        ['titre' => 'Formation aux pratiques résilientes aux changements climatiques', 'icone' => 'fas fa-cloud-sun-rain', 'texte' => 'Organisation de formations, d’ateliers et de sessions pratiques pour permettre aux jeunes et aux acteurs ruraux d’adopter des pratiques agricoles résilientes face aux changements climatiques.'],
        ['titre' => 'REJEPPAT-Fina', 'icone' => 'fas fa-hand-holding-usd', 'texte' => 'Un mécanisme interne d’appui en matériels et en équipements pour les jeunes porteurs de projets.'],
        ['titre' => 'Coaching sur le parcours entrepreneurial', 'icone' => 'fas fa-chalkboard-teacher', 'texte' => 'Accompagnement des producteurs dans l’amélioration des pratiques agricoles et de gestion de leurs exploitations, pour le développement d’entreprises agricoles durables adaptées aux réalités locales.'],
        ['titre' => 'Transformation agroalimentaire', 'icone' => 'fas fa-mortar-pestle', 'texte' => 'Promotion de la transformation et de la valorisation des produits agricoles locaux afin d’améliorer leur valeur ajoutée et de créer de nouvelles opportunités.'],
        ['titre' => 'Entrepreneuriat des jeunes', 'icone' => 'fas fa-briefcase', 'texte' => 'Soutien aux jeunes entrepreneurs dans le développement de leurs activités, le renforcement de leurs capacités et la consolidation de leurs initiatives économiques.'],
        ['titre' => 'Mise en réseau et partenariats', 'icone' => 'fas fa-handshake', 'texte' => 'Mise en relation des jeunes, producteurs, organisations, institutions et partenaires afin de favoriser les échanges, la collaboration et les opportunités.'],
        ['titre' => 'Le plaidoyer', 'icone' => 'fas fa-bullhorn', 'texte' => 'Le REJEPPAT conduit des plaidoyers pour l’amélioration des conditions de vie et de travail des jeunes, ainsi que pour l’accès à la terre, aux financements adaptés, aux cadres de décision et aux innovations, et pour l’inclusion et l’équité de genre.'],
    ],

    /*
    | Domaines d'intervention (page d'accueil)
    */
    'domaines' => [
        ['slug' => 'agriculture', 'titre' => 'Agriculture & élevage', 'court' => 'Agri', 'texte' => 'REJEPPAT Togo accompagne les jeunes producteurs dans le développement de l’agriculture et de l’élevage à travers la formation, l’innovation, le renforcement des capacités et la promotion de pratiques agricoles durables.'],
        ['slug' => 'agroalimentaire', 'titre' => 'Agroalimentaire', 'court' => 'Agro', 'texte' => 'REJEPPAT Togo soutient le développement de l’agroalimentaire en favorisant la transformation locale, la création de valeur, l’innovation et l’entrepreneuriat des jeunes.'],
        ['slug' => 'entrepreneuriat', 'titre' => 'Entrepreneuriat', 'court' => 'Emploi', 'texte' => 'REJEPPAT Togo promeut l’entrepreneuriat des jeunes à travers la formation, l’accompagnement, l’innovation et le développement de projets créateurs de valeur.'],
        ['slug' => 'formation', 'titre' => 'Formation / santé', 'court' => 'Savoir', 'texte' => 'REJEPPAT Togo développe des initiatives dans les domaines de la formation et de la santé afin de renforcer les compétences et d’améliorer le bien-être des populations.'],
    ],

    /*
    | Formulaire de demande de services
    */
    'services_demande' => [
        'Formation aux pratiques résilientes aux changements climatiques',
        'REJEPPAT-Fina',
        'Coaching sur le parcours entrepreneurial',
        'Transformation agroalimentaire',
        'Entrepreneuriat des jeunes',
        'Mise en réseau et partenariats',
        'Le plaidoyer',
        'Autre besoin',
    ],

    'objets_contact' => [
        'Demande de renseignements généraux',
        'Informations sur le produit',
        'Soutien',
    ],

    'temoignages' => [
        ['nom' => 'Kossi Mensah', 'role' => 'Jeune entrepreneur agricole', 'image' => 'kossi-mensah.jpg', 'titre' => 'Des revenus stables pour ma famille', 'texte' => 'Grâce aux formations du REJEPPAT, j’ai pu développer mon exploitation agricole et améliorer mes techniques de production. Aujourd’hui, mon activité génère des revenus stables pour ma famille.'],
        ['nom' => 'Ama Dossou', 'role' => 'Productrice maraîchère', 'image' => 'ama-dossou.jpg', 'titre' => 'J’ai gagné en confiance', 'texte' => 'Le REJEPPAT m’a permis d’acquérir des compétences en agroécologie et en gestion coopérative. J’ai gagné en confiance et je participe activement au développement de ma communauté.'],
        ['nom' => 'Yao Tchalim', 'role' => 'Membre d’une coopérative agricole', 'image' => 'yao-tchalim.jpg', 'titre' => 'Des pratiques plus durables', 'texte' => 'L’accompagnement du REJEPPAT a changé notre manière de travailler. Nous utilisons désormais des pratiques agricoles durables qui améliorent nos rendements tout en protégeant l’environnement.'],
    ],

    'partenaires' => [
        ['nom' => 'CTOP', 'image' => 'ctop.png', 'type' => 'institutionnel', 'description' => 'Coordination Togolaise des Organisations Paysannes et de producteurs agricoles, dont le REJEPPAT est membre.'],
        ['nom' => 'FAO', 'image' => 'fao.png', 'type' => 'institutionnel'],
        ['nom' => 'Andreas Hermes Akademie / DBV', 'image' => 'aha-dbv.png', 'type' => 'technique'],
        ['nom' => 'AFDI', 'image' => 'afdi.png', 'type' => 'technique'],
        ['nom' => 'FAR', 'image' => 'far.png', 'type' => 'technique'],
        ['nom' => 'Federcasse', 'image' => 'federcasse.png', 'type' => 'financier'],
        ['nom' => 'Ministère de l’Agriculture', 'type' => 'institutionnel'],
        ['nom' => 'FFF (Forest and Farm Facility)', 'type' => 'technique'],
        ['nom' => 'GIZ', 'type' => 'technique', 'site' => 'https://www.giz.de'],
    ],

    /*
    | Vidéos d'origine du site, à placer dans storage/app/private/videos (hors du dossier public)
    | puis importées dans la médiathèque et mises en avant sur l'accueil (ContenuSeeder).
    */
    'videos' => [
        ['titre' => 'Présentation du REJEPPAT', 'fichier' => 'videos/presentation-rejeppat.mp4', 'description' => 'Le réseau, ses missions et ses jeunes producteurs en images.'],
    ],

    /*
    | Sections régionales (page de chaque région complétée ensuite par la région)
    | « prefectures » : rattachement des fermes écoles d'après leur adresse.
    */
    'regions' => [
        ['slug' => 'maritime', 'nom' => 'Région Maritime', 'chef_lieu' => 'Lomé', 'prefectures' => []],
        ['slug' => 'plateaux', 'nom' => 'Région des Plateaux', 'chef_lieu' => 'Atakpamé', 'prefectures' => ['Amou', 'Haho']],
        ['slug' => 'centrale', 'nom' => 'Région Centrale', 'chef_lieu' => 'Sokodé', 'prefectures' => ['Sotouboua', 'Tchaoudjo', 'Blitta', 'Tchamba'], 'presentation' => [
            'La Région Centrale accueille le siège national du REJEPPAT, à Sokodé, et la plupart des fermes écoles du réseau.',
            'La section accompagne les coopératives de jeunes des préfectures de Tchaoudjo, Sotouboua, Blitta et Tchamba dans la production agroécologique, la transformation et l’accès aux marchés.',
        ]],
        ['slug' => 'kara', 'nom' => 'Région de la Kara', 'chef_lieu' => 'Kara', 'prefectures' => []],
        ['slug' => 'savanes', 'nom' => 'Région des Savanes', 'chef_lieu' => 'Dapaong', 'prefectures' => []],
    ],

];
