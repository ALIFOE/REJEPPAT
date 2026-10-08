<?php

/*
|--------------------------------------------------------------------------
| Boutique (source : https://rejeppat.org/boutique/)
|--------------------------------------------------------------------------
|
| Prix en francs CFA. « image » : visuel des cartes produit,
| « slug » : visuels de la fiche produit (…-detail, …-thumb, …-mini).
|
*/

return [

    'categories' => [
        'bio' => 'Bio',
        'cereales' => 'Céréales & légumineuses',
        'transformes' => 'Produits transformés',
        'elevage' => 'Élevage',
    ],

    /*
    | Modes de livraison proposés à la commande (frais en francs CFA).
    | À ajuster selon les conditions réelles du REJEPPAT.
    */
    'livraisons' => [
        'retrait' => ['label' => 'Retrait au siège du REJEPPAT (Sokodé)', 'frais' => 0],
        'sokode' => ['label' => 'Livraison à domicile à Sokodé', 'frais' => 500],
        'togo' => ['label' => 'Expédition dans une autre ville du Togo', 'frais' => 2000],
    ],

    /*
    | Modes de paiement. Mobile Money : le client envoie le montant puis
    | indique la référence de la transaction, vérifiée depuis l'administration.
    */
    'paiements' => [
        'livraison' => [
            'label' => 'Paiement à la livraison / au retrait',
            'texte' => 'Vous payez en espèces ou par Mobile Money à la réception de votre commande.',
        ],
        'tmoney' => [
            'label' => 'T-Money (Togocom)',
            'texte' => 'Envoyez le montant total au (+228) 91 87 33 16, puis indiquez la référence de la transaction.',
            'reference' => true,
        ],
        'flooz' => [
            'label' => 'Flooz (Moov Africa)',
            'texte' => 'Envoyez le montant total au (+228) 98 92 63 50, puis indiquez la référence de la transaction.',
            'reference' => true,
        ],
    ],

    'produits' => [
        [
            'slug' => 'carotte-biologique',
            'nom' => 'Carotte biologique',
            'categorie' => 'bio',
            'image' => 'carotte-biologique.png',
            'prix' => 2500,
            'prix_initial' => 3000,
            'resume' => 'Découvrez nos carottes biologiques, cultivées dans le respect de l’environnement et selon des pratiques agricoles responsables.',
            'description' => [
                'Découvrez nos carottes biologiques, cultivées dans le respect de l’environnement et selon des pratiques agricoles responsables. Fraîches, croquantes et naturellement savoureuses, elles sont soigneusement sélectionnées pour garantir une qualité optimale.',
                'Riches en nutriments et faciles à intégrer dans l’alimentation quotidienne, les carottes peuvent être consommées crues, cuites, en salade, en soupe, en jus ou comme accompagnement de nombreux plats.',
            ],
            'points_forts' => [
                'Culture respectueuse de l’environnement',
                'Produit frais et naturel',
                'Texture croquante et saveur agréable',
                'Soigneusement sélectionné',
                'Idéal pour une alimentation saine et équilibrée',
            ],
        ],
        [
            'slug' => 'oignon-bio',
            'nom' => 'Oignon – pour une vie saine',
            'categorie' => 'bio',
            'image' => 'oignon.png',
            'prix' => 33,
            'prix_initial' => 43,
            'resume' => 'Découvrez nos oignons biologiques, cultivés dans le respect de l’environnement et selon des pratiques agricoles responsables.',
            'description' => [
                'Découvrez nos oignons biologiques, cultivés dans le respect de l’environnement et selon des pratiques agricoles responsables. Frais, naturels et soigneusement sélectionnés, ils offrent une saveur authentique et une excellente qualité.',
                'Idéal pour accompagner vos plats, sauces, salades, grillades et différentes préparations culinaires, l’oignon biologique constitue un produit essentiel pour une alimentation saine et naturelle.',
            ],
            'points_forts' => [
                'Culture respectueuse de l’environnement',
                'Produit frais et naturel',
                'Saveur authentique',
                'Sélectionné avec soin',
                'Idéal pour la cuisine quotidienne',
            ],
        ],
        [
            'slug' => 'tomates',
            'nom' => 'Tomates',
            'categorie' => 'bio',
            'image' => 'tomates.png',
            'prix' => 300,
            'prix_initial' => 500,
            // À valider : la fiche de l'ancien site ne contenait qu'un texte de démonstration.
            'resume' => 'Des tomates fraîches et biologiques, cultivées par les producteurs du réseau REJEPPAT.',
            'description' => [
                'Découvrez nos tomates biologiques, cultivées dans le respect de l’environnement et selon des pratiques agricoles responsables par les jeunes producteurs du réseau.',
                'Fraîches et savoureuses, elles accompagnent vos sauces, salades et préparations culinaires du quotidien.',
            ],
            'points_forts' => [
                'Culture respectueuse de l’environnement',
                'Produit frais et naturel',
                'Idéal pour la cuisine quotidienne',
            ],
        ],
    ],

];
