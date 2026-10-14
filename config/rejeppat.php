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

    /*
    | Adresses e-mail professionnelles : affichées sur le site et destinataires des formulaires
    | (contact → info, demandes de service et profils → secrétariat, partenariats → président,
    | commandes de la boutique → trésorerie).
    */
    'emails' => [
        'info' => ['adresse' => 'info@rejeppat.org', 'service' => 'Informations générales'],
        'president' => ['adresse' => 'president@rejeppat.org', 'service' => 'Présidence'],
        'secretariat' => ['adresse' => 'secretariat@rejeppat.org', 'service' => 'Secrétariat'],
        'tresorerie' => ['adresse' => 'tresorerie@rejeppat.org', 'service' => 'Trésorerie'],
    ],

    'address' => 'Route Nationale N°1, 2ème von après l’hôpital Puits de Jacob, Quartier Ataworo, Sokodé',
    'address_short' => 'Quartier Ataworo, Sokodé - Togo',
    'po_box' => 'BP : 388, Sokodé',
    'map_query' => 'Quartier Ataworo, Sokodé, Togo',

    'social' => [
        'facebook' => 'https://www.facebook.com/profile.php?id=61595107210776',
        'twitter' => 'https://x.com/rejeppattogo',
        'linkedin' => 'https://www.linkedin.com/in/rajeppat-togo-bb7048420/',
        'youtube' => 'https://www.youtube.com/@RAJEPPAT',
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
    | Axes stratégiques du réseau (page « Axes stratégiques ») : image reprise des domaines d'intervention
    */
    'axes' => [
        ['image' => 'agriculture', 'titre' => 'Faciliter l’accès aux facteurs de production et aux financements adaptés'],
        ['image' => 'formation', 'titre' => 'Améliorer la gouvernance, le fonctionnement global du réseau, le renforcement de capacités des jeunes et la communication interne et externe du REJEPPAT'],
        ['image' => 'entrepreneuriat', 'titre' => 'Renforcer la mobilisation des ressources internes et externes'],
        ['image' => 'agroalimentaire', 'titre' => 'Développer des stratégies de valorisation des produits agroécologiques et la résilience des jeunes agriculteurs'],
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
    | Photos d'origine de la médiathèque (public/assets/images/rejeppat/mediatheque), importées
    | par ContenuSeeder et la migration « ajouter_photos_mediatheque ». « en » : titre et légende en anglais.
    */
    'photos' => [
        ['photo' => 'atelier-formateurs-photo-de-groupe.jpg', 'date' => '2026-10-02', 'titre' => 'Photo de groupe de l’atelier des maîtres formateurs', 'description' => 'Atelier de formation des maîtres formateurs sur l’entrepreneuriat forestier et l’installation d’un champ-école paysan (FAO), du 28 septembre au 2 octobre 2026 à Sotouboua et à la ferme école Ma Joie.', 'en' => ['titre' => 'Group photo of the master trainers’ workshop', 'description' => 'Training workshop for master trainers on forest entrepreneurship and setting up a farmer field school (FAO), from 28 September to 2 October 2026 in Sotouboua and at the Ma Joie farm school.']],
        ['photo' => 'atelier-formateurs-visite-plantation.jpg', 'date' => '2026-10-02', 'titre' => 'Visite de plantation forestière avec les maîtres formateurs', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Forest plantation visit with the master trainers', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-echanges-plantation.jpg', 'date' => '2026-10-02', 'titre' => 'Échanges en cercle dans la plantation', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Group discussion in the plantation', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-seance-pratique.jpg', 'date' => '2026-10-02', 'titre' => 'Séance pratique en plantation forestière', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Practical session in a forest plantation', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-explications.jpg', 'date' => '2026-10-02', 'titre' => 'Explications du formateur sur la plantation', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'The trainer explains the plantation', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-a-l-ecoute.jpg', 'date' => '2026-10-02', 'titre' => 'Maîtres formateurs à l’écoute', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Master trainers listening', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-lecon-terrain.jpg', 'date' => '2026-10-02', 'titre' => 'Leçon sur le terrain', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Lesson in the field', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'atelier-formateurs-demonstration.jpg', 'date' => '2026-10-02', 'titre' => 'Démonstration en plantation forestière', 'description' => 'Atelier des maîtres formateurs sur l’entrepreneuriat forestier (FAO), septembre-octobre 2026.', 'en' => ['titre' => 'Demonstration in a forest plantation', 'description' => 'Master trainers’ workshop on forest entrepreneurship (FAO), September-October 2026.']],
        ['photo' => 'plantation-forestiere.jpg', 'date' => '2026-10-02', 'titre' => 'Plantation forestière', 'description' => null, 'en' => ['titre' => 'Forest plantation', 'description' => null]],
        ['photo' => 'equipe-rejeppat.jpg', 'date' => '2026-10-12', 'titre' => 'L’équipe du REJEPPAT sur le terrain', 'description' => null, 'en' => ['titre' => 'The REJEPPAT team in the field', 'description' => null]],
        ['photo' => 'capable-plus-recoltes.jpg', 'date' => '2024-03-26', 'titre' => 'Récoltes de la ferme école Capable-Plus', 'description' => 'Papayes, choux, légumes feuilles et aubergines produits à Tchébébé.', 'en' => ['titre' => 'Harvests from the Capable-Plus farm school', 'description' => 'Papayas, cabbages, leafy vegetables and eggplants grown in Tchébébé.']],
        ['photo' => 'capable-plus-papayes-legumes.jpg', 'date' => '2024-03-26', 'titre' => 'Papayes et légumes feuilles de Capable-Plus', 'description' => 'Ferme école Capable-Plus, Tchébébé.', 'en' => ['titre' => 'Papayas and leafy vegetables from Capable-Plus', 'description' => 'Capable-Plus farm school, Tchébébé.']],
        ['photo' => 'capable-plus-legumes-feuilles.jpg', 'date' => '2024-03-26', 'titre' => 'Légumes feuilles de la ferme école Capable-Plus', 'description' => 'Ferme école Capable-Plus, Tchébébé.', 'en' => ['titre' => 'Leafy vegetables from the Capable-Plus farm school', 'description' => 'Capable-Plus farm school, Tchébébé.']],
        ['photo' => 'mini-marche-sokode.jpg', 'date' => '2026-10-12', 'titre' => 'Mini-marché des produits agroécologiques et du terroir à Sokodé', 'description' => null, 'en' => ['titre' => 'Mini-market of agroecological and local products in Sokodé', 'description' => null]],
        ['photo' => 'mini-marche-sokode-partenaires.jpg', 'date' => '2026-10-12', 'titre' => 'Le mini-marché de Sokodé avec nos partenaires', 'description' => null, 'en' => ['titre' => 'The Sokodé mini-market with our partners', 'description' => null]],
        ['photo' => 'lancement-marche-produits-locaux.jpg', 'date' => '2026-10-12', 'titre' => 'Lancement du marché des produits locaux à Sokodé', 'description' => null, 'en' => ['titre' => 'Launch of the local products market in Sokodé', 'description' => null]],
        ['photo' => 'lancement-marche-produits-locaux-clients.jpg', 'date' => '2026-10-12', 'titre' => 'Premiers clients du marché des produits locaux', 'description' => null, 'en' => ['titre' => 'First customers at the local products market', 'description' => null]],
        ['photo' => 'point-de-vente-produits-locaux.jpg', 'date' => '2026-10-12', 'titre' => 'Point de vente des produits locaux', 'description' => null, 'en' => ['titre' => 'Local products shop', 'description' => null]],
        ['photo' => 'point-de-vente-achats.jpg', 'date' => '2026-10-12', 'titre' => 'Achats au point de vente des produits locaux', 'description' => null, 'en' => ['titre' => 'Shopping at the local products shop', 'description' => null]],
        ['photo' => 'recolte-carottes.jpg', 'date' => '2026-10-12', 'titre' => 'Récolte de carottes', 'description' => null, 'en' => ['titre' => 'Carrot harvest', 'description' => null]],
        ['photo' => 'laitues.jpg', 'date' => '2026-10-12', 'titre' => 'Laitues en pleine croissance', 'description' => null, 'en' => ['titre' => 'Growing lettuces', 'description' => null]],
        ['photo' => 'tomates-carottes-persil.jpg', 'date' => '2026-10-12', 'titre' => 'Tomates, carottes et persil', 'description' => null, 'en' => ['titre' => 'Tomatoes, carrots and parsley', 'description' => null]],
        ['photo' => 'legumes-frais.jpg', 'date' => '2026-10-12', 'titre' => 'Légumes frais', 'description' => null, 'en' => ['titre' => 'Fresh vegetables', 'description' => null]],
        ['photo' => 'oignons.jpg', 'date' => '2026-10-12', 'titre' => 'Oignons', 'description' => null, 'en' => ['titre' => 'Onions', 'description' => null]],
    ],

    /*
    | Sections régionales (page de chaque région complétée ensuite par la région)
    | « prefectures » : rattachement des fermes écoles d'après leur adresse.
    */
    'regions' => [
        ['slug' => 'maritime', 'nom' => 'Région Maritime', 'chef_lieu' => 'Lomé', 'prefectures' => ['Zio', 'Lacs', 'Avé']],
        ['slug' => 'plateaux', 'nom' => 'Région des Plateaux', 'chef_lieu' => 'Atakpamé', 'prefectures' => ['Amou', 'Haho']],
        ['slug' => 'centrale', 'nom' => 'Région Centrale', 'chef_lieu' => 'Sokodé', 'prefectures' => ['Sotouboua', 'Tchaoudjo', 'Blitta', 'Tchamba'], 'presentation' => [
            'La Région Centrale accueille le siège national du REJEPPAT, à Sokodé, et la plupart des fermes écoles du réseau.',
            'La section accompagne les coopératives de jeunes des préfectures de Tchaoudjo, Sotouboua, Blitta et Tchamba dans la production agroécologique, la transformation et l’accès aux marchés.',
        ]],
        ['slug' => 'kara', 'nom' => 'Région de la Kara', 'chef_lieu' => 'Kara', 'prefectures' => ['Dankpen', 'Kéran', 'Kozah']],
        ['slug' => 'savanes', 'nom' => 'Région des Savanes', 'chef_lieu' => 'Dapaong', 'prefectures' => ['Tandjouaré', 'Tône']],
    ],

];
