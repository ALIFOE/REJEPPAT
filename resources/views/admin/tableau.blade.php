@extends('admin.layout')

@section('title', 'Tableau de bord')
@section('sous_titre', 'Bonjour ' . auth()->user()->name . ', voici l’activité du site et de la boutique.')

@php
    use App\Models\Commande;
    use App\Models\DemandeService;
    use App\Support\Contenu;

    $g = $graphiques;
    $kpis = [
        ['valeur' => Contenu::prix($chiffres['ca_mois']), 'libelle' => 'Ventes du mois', 'icone' => 'fas fa-coins', 'style' => 'sombre', 'lien' => route('admin.commandes.index')],
        ['valeur' => $chiffres['commandes_attente'], 'libelle' => 'Commandes à traiter (' . $chiffres['commandes_mois'] . ' ce mois)', 'icone' => 'icon-empty-cart', 'style' => 'jaune', 'lien' => route('admin.commandes.index', ['statut' => 'en_attente'])],
        ['valeur' => $chiffres['demandes_nouvelles'], 'libelle' => 'Nouvelles demandes de services', 'icone' => 'icon-handshake', 'style' => '', 'lien' => route('admin.demandes.index', ['statut' => 'nouvelle'])],
        ['valeur' => $chiffres['messages_non_lus'], 'libelle' => 'Messages non lus', 'icone' => 'icon-mail', 'style' => $chiffres['messages_non_lus'] ? 'rouge' : '', 'lien' => route('admin.messages.index', ['etat' => 'non-lus'])],
    ];
    $contenus = [
        ['valeur' => Contenu::prix($chiffres['ca_total']), 'libelle' => 'Chiffre d’affaires total', 'icone' => 'fas fa-chart-line', 'lien' => route('admin.commandes.index')],
        ['valeur' => $chiffres['produits'] . ($chiffres['ruptures'] ? ' · ' . $chiffres['ruptures'] . ' en rupture' : ''), 'libelle' => 'Produits en vente', 'icone' => 'icon-fruit-box', 'lien' => route('admin.produits.index')],
        ['valeur' => $chiffres['actualites'], 'libelle' => 'Articles publiés', 'icone' => 'far fa-newspaper', 'lien' => route('admin.actualites.index')],
        ['valeur' => $chiffres['fermes'] . ' fermes · ' . $chiffres['projets'] . ' projets', 'libelle' => 'Réseau et programmes', 'icone' => 'icon-farm-house-1', 'lien' => route('admin.fermes.index')],
    ];
@endphp

@section('content')

    <div class="a-grille a-grille--4 a-section">
        @foreach ($kpis as $kpi)
            <a href="{{ $kpi['lien'] }}" class="a-carte a-kpi a-kpi--{{ $kpi['style'] }}">
                <span class="a-kpi__icone"><i class="{{ $kpi['icone'] }}"></i></span>
                <span class="a-kpi__texte">
                    <span class="a-kpi__valeur">{{ $kpi['valeur'] }}</span>
                    <span class="a-kpi__libelle">{{ $kpi['libelle'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Ventes de la boutique</h2>
                    <p>Chiffre d’affaires et nombre de commandes sur 12 mois (hors commandes annulées)</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $g['mois'],
                    'series' => [
                        ['label' => 'Chiffre d’affaires', 'data' => $g['ventes'], 'couleur' => 'vert'],
                        ['label' => 'Commandes', 'data' => $g['commandes'], 'couleur' => 'jaune', 'type' => 'line', 'axe' => 'y2'],
                    ],
                    'monnaie' => true,
                    'hauteur' => 300,
                    'vide_texte' => 'Les ventes apparaîtront ici dès les premières commandes.',
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Commandes par statut</h2>
                    <p>Toutes les commandes</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => $g['statuts']->pluck('libelle'),
                    'series' => [['label' => 'Commandes', 'data' => $g['statuts']->pluck('total')]],
                    'couleurs' => $g['statuts']->pluck('couleur'),
                    'hauteur' => 300,
                ])
            </div>
        </div>
    </div>

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Demandes et messages reçus</h2>
                    <p>Formulaires « Demande de service » et « Contact » sur 12 mois</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'line',
                    'labels' => $g['mois'],
                    'series' => [
                        ['label' => 'Demandes de services', 'data' => $g['demandes'], 'couleur' => 'vert'],
                        ['label' => 'Messages de contact', 'data' => $g['messages'], 'couleur' => 'terre'],
                    ],
                    'hauteur' => 280,
                    'vide_texte' => 'Les demandes et messages apparaîtront ici.',
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Suivi des demandes</h2>
                    <p>Répartition par statut</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'pie',
                    'labels' => $g['demandes_statuts']->pluck('libelle'),
                    'series' => [['label' => 'Demandes', 'data' => $g['demandes_statuts']->pluck('total')]],
                    'couleurs' => $g['demandes_statuts']->pluck('couleur'),
                    'hauteur' => 280,
                ])
            </div>
        </div>
    </div>

    <div class="a-grille a-grille--3 a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Services les plus demandés</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $g['services']->pluck('service')->map(fn ($s) => \Illuminate\Support\Str::limit($s, 28)),
                    'series' => [['label' => 'Demandes', 'data' => $g['services']->pluck('total'), 'couleur' => 'vert']],
                    'hauteur' => 260,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Produits les plus vendus</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $g['top_produits']->pluck('nom_produit')->map(fn ($s) => \Illuminate\Support\Str::limit($s, 24)),
                    'series' => [['label' => 'Quantités vendues', 'data' => $g['top_produits']->pluck('quantite'), 'couleur' => 'jaune']],
                    'hauteur' => 260,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Modes de paiement</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => $g['paiements']->pluck('libelle'),
                    'series' => [['label' => 'Commandes', 'data' => $g['paiements']->pluck('total')]],
                    'couleurs' => ['vert', 'jaune', 'bleu'],
                    'hauteur' => 260,
                ])
            </div>
        </div>
    </div>

    <div class="a-grille a-grille--4 a-section">
        @foreach ($contenus as $kpi)
            <a href="{{ $kpi['lien'] }}" class="a-carte a-kpi">
                <span class="a-kpi__icone"><i class="{{ $kpi['icone'] }}"></i></span>
                <span class="a-kpi__texte">
                    <span class="a-kpi__valeur" style="font-size: 18px; white-space: normal">{{ $kpi['valeur'] }}</span>
                    <span class="a-kpi__libelle">{{ $kpi['libelle'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="a-grille a-grille--3 a-section">
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Dernières commandes</h2>
                <a href="{{ route('admin.commandes.index') }}" class="a-btn a-btn--clair a-btn--petit">Tout voir</a>
            </div>
            <ul class="a-liste-mini">
                @forelse ($dernieresCommandes as $commande)
                    <li>
                        <a href="{{ route('admin.commandes.show', $commande) }}">
                            <strong>{{ $commande->numero }}</strong>
                            <small>{{ $commande->nom }} · {{ $commande->created_at->diffForHumans() }}</small>
                        </a>
                        <span style="text-align: right">
                            <strong>{{ Contenu::prix($commande->total) }}</strong>
                            @include('admin.partials.badge', ['statuts' => Commande::STATUTS, 'valeur' => $commande->statut])
                        </span>
                    </li>
                @empty
                    <li class="a-vide">Aucune commande pour le moment.</li>
                @endforelse
            </ul>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Dernières demandes</h2>
                <a href="{{ route('admin.demandes.index') }}" class="a-btn a-btn--clair a-btn--petit">Tout voir</a>
            </div>
            <ul class="a-liste-mini">
                @forelse ($dernieresDemandes as $demande)
                    <li>
                        <a href="{{ route('admin.demandes.show', $demande) }}">
                            <strong>{{ \Illuminate\Support\Str::limit($demande->objet, 40) }}</strong>
                            <small>{{ $demande->nom }} · {{ $demande->created_at->diffForHumans() }}</small>
                        </a>
                        @include('admin.partials.badge', ['statuts' => DemandeService::STATUTS, 'valeur' => $demande->statut])
                    </li>
                @empty
                    <li class="a-vide">Aucune demande pour le moment.</li>
                @endforelse
            </ul>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Articles les plus lus</h2>
                <a href="{{ route('admin.actualites.index') }}" class="a-btn a-btn--clair a-btn--petit">Articles</a>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $g['articles_lus']->pluck('title')->map(fn ($s) => \Illuminate\Support\Str::limit($s, 26)),
                    'series' => [['label' => 'Lectures', 'data' => $g['articles_lus']->pluck('vues'), 'couleur' => 'vert']],
                    'hauteur' => 250,
                    'vide_texte' => 'Le nombre de lectures s’affichera dès les premières visites.',
                ])
            </div>
        </div>
    </div>

@endsection
