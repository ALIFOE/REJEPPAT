@extends('admin.layout')

@section('title', 'Tableau de bord')
@section('sous_titre', 'Bonjour ' . auth()->user()->name . ', voici la vue d’ensemble du site, de la boutique et des comptes.')

@php
    use App\Models\User;
    use App\Support\Contenu;

    $g = $graphiques;
    $kpis = [
        ['valeur' => Contenu::prix($chiffres['ca_total']), 'libelle' => 'Chiffre d’affaires total (' . Contenu::prix($chiffres['ca_mois']) . ' ce mois)', 'icone' => 'fas fa-chart-line', 'style' => 'sombre', 'lien' => route('admin.commandes.index')],
        ['valeur' => $chiffres['clients'], 'libelle' => 'Comptes clients (+' . $chiffres['clients_mois'] . ' ce mois)', 'icone' => 'fas fa-users', 'style' => '', 'lien' => route('admin.utilisateurs.index', ['role' => User::CLIENT])],
        ['valeur' => $chiffres['equipe'], 'libelle' => 'Membres de l’équipe d’administration', 'icone' => 'fas fa-user-shield', 'style' => '', 'lien' => route('admin.utilisateurs.index', ['role' => User::ADMIN])],
        ['valeur' => $chiffres['a_traiter'], 'libelle' => 'Éléments à traiter (commandes, demandes, messages)', 'icone' => 'fas fa-tasks', 'style' => $chiffres['a_traiter'] ? 'jaune' : '', 'lien' => route('admin.commandes.index', ['statut' => 'en_attente'])],
    ];
    $contenus = [
        ['valeur' => $chiffres['produits'] . ($chiffres['ruptures'] ? ' · ' . $chiffres['ruptures'] . ' en rupture' : ''), 'libelle' => 'Produits en vente', 'icone' => 'icon-fruit-box', 'lien' => route('admin.produits.index')],
        ['valeur' => $chiffres['actualites'], 'libelle' => 'Articles publiés', 'icone' => 'far fa-newspaper', 'lien' => route('admin.actualites.index')],
        ['valeur' => $chiffres['fermes'], 'libelle' => 'Fermes écoles publiées', 'icone' => 'icon-farm-house-1', 'lien' => route('admin.fermes.index')],
        ['valeur' => $chiffres['projets'], 'libelle' => 'Programmes et projets publiés', 'icone' => 'icon-smart-farming', 'lien' => route('admin.projets.index')],
    ];
@endphp

@section('actions')
    <a href="{{ route('admin.utilisateurs.create') }}" class="a-btn"><i class="fas fa-user-plus"></i> Nouveau compte</a>
@endsection

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
                    <h2>Comptes par rôle</h2>
                    <p>Super admins, administrateurs et clients</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => $g['roles']->pluck('libelle'),
                    'series' => [['label' => 'Comptes', 'data' => $g['roles']->pluck('total')]],
                    'couleurs' => $g['roles']->pluck('couleur'),
                    'hauteur' => 300,
                ])
            </div>
        </div>
    </div>

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Nouveaux comptes clients</h2>
                    <p>Inscriptions sur le site, sur 12 mois</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'line',
                    'labels' => $g['mois'],
                    'series' => [['label' => 'Inscriptions', 'data' => $g['inscriptions'], 'couleur' => 'vert']],
                    'hauteur' => 280,
                    'vide_texte' => 'Les inscriptions apparaîtront ici dès les premiers comptes clients.',
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <div>
                    <h2>Commandes avec compte</h2>
                    <p>Clients connectés ou commandes sans compte</p>
                </div>
            </div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => ['Avec un compte client', 'Sans compte'],
                    'series' => [['label' => 'Commandes', 'data' => [$chiffres['commandes_avec_compte'], $chiffres['commandes_sans_compte']]]],
                    'couleurs' => ['vert', 'jaune'],
                    'hauteur' => 280,
                ])
            </div>
        </div>
    </div>

    <div class="a-grille a-grille--3 a-section">
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Derniers clients inscrits</h2>
                <a href="{{ route('admin.utilisateurs.index', ['role' => User::CLIENT]) }}" class="a-btn a-btn--clair a-btn--petit">Tout voir</a>
            </div>
            <ul class="a-liste-mini">
                @forelse ($derniersClients as $client)
                    <li>
                        <a href="{{ route('admin.utilisateurs.edit', $client) }}">
                            <strong>{{ $client->name }}</strong>
                            <small>{{ $client->email }} · {{ $client->created_at->diffForHumans() }}</small>
                        </a>
                        @if ($client->hasVerifiedEmail())
                            <span class="a-badge a-badge--vert">{{ $client->commandes_count }} commande{{ $client->commandes_count > 1 ? 's' : '' }}</span>
                        @else
                            <span class="a-badge a-badge--gris">E-mail à confirmer</span>
                        @endif
                    </li>
                @empty
                    <li class="a-vide">Aucun client inscrit pour le moment.</li>
                @endforelse
            </ul>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Meilleurs clients</h2>
                <a href="{{ route('admin.commandes.index') }}" class="a-btn a-btn--clair a-btn--petit">Commandes</a>
            </div>
            <ul class="a-liste-mini">
                @forelse ($meilleursClients as $client)
                    <li>
                        <a href="{{ route('admin.utilisateurs.edit', $client) }}">
                            <strong>{{ $client->name }}</strong>
                            <small>{{ $client->commandes_count }} commande{{ $client->commandes_count > 1 ? 's' : '' }}</small>
                        </a>
                        <strong>{{ Contenu::prix($client->commandes_sum_total) }}</strong>
                    </li>
                @empty
                    <li class="a-vide">Les clients ayant commandé avec leur compte apparaîtront ici.</li>
                @endforelse
            </ul>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete">
                <h2>Équipe d’administration</h2>
                <a href="{{ route('admin.utilisateurs.index') }}" class="a-btn a-btn--clair a-btn--petit">Gérer</a>
            </div>
            <ul class="a-liste-mini">
                @foreach ($equipe as $membre)
                    <li>
                        <a href="{{ route('admin.utilisateurs.edit', $membre) }}">
                            <strong>{{ $membre->name }}@if ($membre->is(auth()->user())) <small style="display: inline">(vous)</small>@endif</strong>
                            <small>{{ $membre->email }}</small>
                        </a>
                        @include('admin.partials.badge', ['statuts' => User::ROLES, 'valeur' => $membre->role])
                    </li>
                @endforeach
            </ul>
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

@endsection
