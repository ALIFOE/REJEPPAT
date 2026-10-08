{{-- Menu principal, identique à celui de rejeppat.org (utilisé par les deux en-têtes) --}}
<ul class="main-menu__list">
    <li class="{{ request()->routeIs('home') ? 'current' : '' }}">
        <a href="{{ route('home') }}">Accueil</a>
    </li>
    <li class="dropdown {{ request()->routeIs('offres', 'demande') ? 'current' : '' }}">
        <a href="{{ route('offres') }}">Nos offres</a>
        <ul>
            <li><a href="{{ route('offres') }}">Nos offres &amp; services</a></li>
            <li><a href="{{ route('demande') }}">Demande de service</a></li>
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('fermes.*') ? 'current' : '' }}">
        <a href="{{ route('fermes.index') }}">Fermes Écoles</a>
        <ul>
            <li><a href="{{ route('fermes.index') }}">Les Fermes Écoles</a></li>
            @foreach (config('fermes.liste') as $ferme)
                <li><a href="{{ route('fermes.show', $ferme['slug']) }}">{{ $ferme['nom'] }}</a></li>
            @endforeach
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('projets.*') ? 'current' : '' }}">
        <a href="{{ route('projets.index') }}">Nos Projets</a>
        <ul>
            <li><a href="{{ route('projets.index') }}">Nos Programmes &amp; Projets</a></li>
            @foreach (config('projets.liste') as $projet)
                <li><a href="{{ route('projets.show', $projet['slug']) }}">{{ $projet['titre_court'] }}</a></li>
            @endforeach
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('actualites.*') ? 'current' : '' }}">
        <a href="{{ route('actualites.index') }}">Actualités</a>
        <ul>
            <li><a href="{{ route('actualites.index') }}">Actualités &amp; Événements</a></li>
            @foreach (\App\Support\Contenu::CATEGORIES_ACTUALITES as $cle => $nom)
                <li><a href="{{ route('actualites.index', ['categorie' => $cle]) }}">{{ $nom }}</a></li>
            @endforeach
        </ul>
    </li>
    <li class="{{ request()->routeIs('boutique.*') ? 'current' : '' }}">
        <a href="{{ route('boutique.index') }}">Boutique</a>
    </li>
    <li class="{{ request()->routeIs('contact') ? 'current' : '' }}">
        <a href="{{ route('contact') }}">Contact</a>
    </li>
</ul>
