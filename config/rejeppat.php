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
    | Nos offres & services (https://rejeppat.org/nos-offres-services/)
    */
    'offres' => [
        ['titre' => 'Formation et renforcement des capacités', 'texte' => 'Organisation de formations, d’ateliers et de sessions pratiques pour développer les compétences techniques, professionnelles et entrepreneuriales des jeunes et des acteurs ruraux.'],
        ['titre' => 'Accompagnement des initiatives', 'texte' => 'Appui aux porteurs de projets et aux organisations dans la structuration, la mise en œuvre et le développement de leurs initiatives.'],
        ['titre' => 'Agriculture et élevage', 'texte' => 'Accompagnement des producteurs dans l’amélioration des pratiques agricoles et d’élevage et dans le développement d’activités durables adaptées aux réalités locales.'],
        ['titre' => 'Transformation agroalimentaire', 'texte' => 'Promotion de la transformation et de la valorisation des produits agricoles locaux afin d’améliorer leur valeur ajoutée et de créer de nouvelles opportunités.'],
        ['titre' => 'Entrepreneuriat des jeunes', 'texte' => 'Soutien aux jeunes entrepreneurs dans le développement de leurs activités, le renforcement de leurs capacités et la consolidation de leurs initiatives économiques.'],
        ['titre' => 'Mise en réseau et partenariats', 'texte' => 'Mise en relation des jeunes, producteurs, organisations, institutions et partenaires afin de favoriser les échanges, la collaboration et les opportunités.'],
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
        'Formation et renforcement des capacités',
        'Accompagnement des initiatives',
        'Agriculture et élevage',
        'Transformation agroalimentaire',
        'Entrepreneuriat des jeunes',
        'Mise en réseau et partenariats',
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
        ['nom' => 'CTOP', 'image' => 'ctop.png'],
        ['nom' => 'FAO', 'image' => 'fao.png'],
        ['nom' => 'Andreas Hermes Akademie / DBV', 'image' => 'aha-dbv.png'],
        ['nom' => 'AFDI', 'image' => 'afdi.png'],
        ['nom' => 'FAR', 'image' => 'far.png'],
        ['nom' => 'Federcasse', 'image' => 'federcasse.png'],
    ],

];
