<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>@yield('title') || Administration {{ config('rejeppat.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/rejeppat/favicons/favicon-32x32.png?v=logo') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/thm-icons/style.css') }}" />
    <link rel="stylesheet" href="{{ \App\Support\Contenu::asset('assets/css/admin.css') }}" />
    <link rel="stylesheet" href="{{ \App\Support\Contenu::asset('assets/css/rejeppat-video.css') }}" />
</head>

@php
    $menu = [
        'Pilotage' => [
            ['route' => 'admin.tableau', 'actif' => 'admin.tableau', 'icone' => 'fas fa-chart-pie', 'libelle' => 'Tableau de bord'],
        ],
        'Boutique' => [
            ['route' => 'admin.commandes.index', 'actif' => 'admin.commandes.*', 'icone' => 'icon-empty-cart', 'libelle' => 'Commandes', 'compteur' => $aTraiter['commandes']],
            ['route' => 'admin.produits.index', 'actif' => 'admin.produits.*', 'icone' => 'icon-fruit-box', 'libelle' => 'Produits'],
        ],
        'Relations' => [
            ['route' => 'admin.demandes.index', 'actif' => 'admin.demandes.*', 'icone' => 'icon-handshake', 'libelle' => 'Demandes de services', 'compteur' => $aTraiter['demandes']],
            ['route' => 'admin.messages.index', 'actif' => 'admin.messages.*', 'icone' => 'icon-mail', 'libelle' => 'Messages de contact', 'compteur' => $aTraiter['messages']],
            ['route' => 'admin.partenariats.index', 'actif' => 'admin.partenariats.*', 'icone' => 'fas fa-hands-helping', 'libelle' => 'Demandes de partenariat', 'compteur' => $aTraiter['partenariats']],
            ['route' => 'admin.abonnes.index', 'actif' => ['admin.abonnes.*', 'admin.lettres.*'], 'icone' => 'far fa-paper-plane', 'libelle' => 'Newsletter'],
        ],
        'Contenus' => [
            ['route' => 'admin.actualites.index', 'actif' => 'admin.actualites.*', 'icone' => 'far fa-newspaper', 'libelle' => 'Articles & événements'],
            ['route' => 'admin.offres.index', 'actif' => 'admin.offres.*', 'icone' => 'icon-seedling', 'libelle' => 'Offres & services'],
            ['route' => 'admin.fermes.index', 'actif' => 'admin.fermes.*', 'icone' => 'icon-farm-house-1', 'libelle' => 'Fermes écoles'],
            ['route' => 'admin.projets.index', 'actif' => 'admin.projets.*', 'icone' => 'icon-smart-farming', 'libelle' => 'Programmes & projets'],
            ['route' => 'admin.evenements.index', 'actif' => 'admin.evenements.*', 'icone' => 'far fa-calendar-alt', 'libelle' => 'Agenda'],
            ['route' => 'admin.medias.index', 'actif' => 'admin.medias.*', 'icone' => 'fas fa-photo-video', 'libelle' => 'Médiathèque'],
        ],
        'Réseau' => [
            ['route' => 'admin.regions.index', 'actif' => 'admin.regions.*', 'icone' => 'fas fa-map-marked-alt', 'libelle' => 'Sections régionales'],
            ['route' => 'admin.partenaires.index', 'actif' => 'admin.partenaires.*', 'icone' => 'icon-handshake', 'libelle' => 'Partenaires'],
        ],
        'Espace Jeunes' => [
            ['route' => 'admin.membres.index', 'actif' => 'admin.membres.*', 'icone' => 'fas fa-address-book', 'libelle' => 'Annuaire des membres', 'compteur' => $aTraiter['membres']],
            ['route' => 'admin.temoignages.index', 'actif' => 'admin.temoignages.*', 'icone' => 'far fa-comment-dots', 'libelle' => 'Témoignages & stories'],
            ['route' => 'admin.opportunites.index', 'actif' => 'admin.opportunites.*', 'icone' => 'fas fa-bullhorn', 'libelle' => 'Opportunités'],
            ['route' => 'admin.ressources.index', 'actif' => 'admin.ressources.*', 'icone' => 'fas fa-file-download', 'libelle' => 'Ressources'],
        ],
        'Réglages' => array_values(array_filter([
            auth()->user()->can('gerer-utilisateurs')
                ? ['route' => 'admin.utilisateurs.index', 'actif' => 'admin.utilisateurs.*', 'icone' => 'fas fa-user-shield', 'libelle' => 'Utilisateurs & rôles']
                : null,
            ['route' => 'admin.profil', 'actif' => 'admin.profil', 'icone' => 'fas fa-user-cog', 'libelle' => 'Mon profil'],
        ])),
    ];
    $utilisateur = auth()->user();
@endphp

<body class="admin">

    <aside class="a-sidebar" aria-label="Menu de l’administration">
        <a href="{{ route('admin.tableau') }}" class="a-sidebar__logo">
            <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}">
        </a>

        <nav class="a-sidebar__nav">
            @foreach ($menu as $groupe => $liens)
                <p class="a-sidebar__titre">{{ $groupe }}</p>
                @foreach ($liens as $lien)
                    <a href="{{ route($lien['route']) }}" class="a-sidebar__lien {{ request()->routeIs($lien['actif']) ? 'actif' : '' }}">
                        <i class="{{ $lien['icone'] }}"></i>
                        {{ $lien['libelle'] }}
                        @if (! empty($lien['compteur']))
                            <span class="a-sidebar__compteur" title="À traiter">{{ $lien['compteur'] }}</span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="a-sidebar__pied">
            {{ config('rejeppat.full_name') }}<br>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">Voir le site <i class="fas fa-external-link-alt"></i></a>
        </div>
    </aside>
    <div class="a-voile" data-menu-admin></div>

    <div class="a-principal">
        <header class="a-entete">
            <button type="button" class="a-entete__menu" data-menu-admin aria-label="Ouvrir le menu"><i class="fas fa-bars"></i></button>
            <div class="a-entete__titre">
                <h1>@yield('title')</h1>
                @hasSection('sous_titre')
                    <p>@yield('sous_titre')</p>
                @endif
            </div>
            <div class="a-entete__droite">
                @yield('actions')
                <a href="{{ route('home') }}" class="a-btn a-btn--clair a-entete__site" target="_blank" rel="noopener">
                    <i class="fas fa-globe"></i> <span>Site public</span>
                </a>
                <div class="a-utilisateur">
                    <span class="a-utilisateur__avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($utilisateur->name, 0, 1)) }}</span>
                    <a href="{{ route('admin.profil') }}" class="a-utilisateur__nom" title="Mon profil">{{ $utilisateur->name }}<small>{{ $utilisateur->roleLibelle() }} · {{ $utilisateur->email }}</small></a>
                    <form action="{{ route('deconnexion') }}" method="post">
                        @csrf
                        <button type="submit" class="a-btn a-btn--clair a-btn--icone" title="Se déconnecter" aria-label="Se déconnecter">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="a-contenu">
            @if (session('succes'))
                <div class="a-alerte" role="status"><i class="fas fa-check-circle"></i> {{ session('succes') }}</div>
            @endif
            @if ($errors->any())
                <div class="a-alerte a-alerte--erreur" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $errors->count() > 1 ? 'Veuillez corriger les champs signalés.' : $errors->first() }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="{{ asset('assets/vendors/chartjs/chart.umd.min.js') }}"></script>
    <script src="{{ \App\Support\Contenu::asset('assets/js/admin.js') }}"></script>
    <script src="{{ \App\Support\Contenu::asset('assets/js/rejeppat-video.js') }}"></script>
    <script src="{{ \App\Support\Contenu::asset('assets/js/rejeppat-navigation.js') }}"></script>
    @stack('scripts')
</body>

</html>
