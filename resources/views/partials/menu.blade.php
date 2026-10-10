{{-- Menu principal (utilisé par les deux en-têtes), organisé selon le plan du site du cahier des charges --}}
<ul class="main-menu__list">
    <li class="{{ request()->routeIs('home') ? 'current' : '' }}">
        <a href="{{ route('home') }}">{{ __('Accueil') }}</a>
    </li>
    <li class="dropdown {{ request()->routeIs('offres', 'demande', 'regions.*', 'partenaires.*') ? 'current' : '' }}">
        <a href="{{ route('offres') }}">{{ __('Le Réseau') }}</a>
        <ul>
            <li><a href="{{ route('offres') }}">{{ __('Nos offres & services') }}</a></li>
            <li><a href="{{ route('demande') }}">{{ __('Demande de service') }}</a></li>
            <li><a href="{{ route('regions.index') }}">{{ __('Sections régionales') }}</a></li>
            <li><a href="{{ route('partenaires.index') }}">{{ __('Partenaires') }}</a></li>
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('fermes.*') ? 'current' : '' }}">
        <a href="{{ route('fermes.index') }}">{{ __('Fermes Écoles') }}</a>
        <ul>
            <li><a href="{{ route('fermes.index') }}">{{ __('Les Fermes Écoles') }}</a></li>
            @foreach (\App\Support\Contenu::fermes() as $ferme)
                <li><a href="{{ route('fermes.show', $ferme['slug']) }}">{{ $ferme['nom'] }}</a></li>
            @endforeach
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('projets.*') ? 'current' : '' }}">
        <a href="{{ route('projets.index') }}">{{ __('Nos Projets') }}</a>
        <ul>
            <li><a href="{{ route('projets.index') }}">{{ __('Nos Programmes & Projets') }}</a></li>
            @foreach (\App\Support\Contenu::projets() as $projet)
                <li><a href="{{ route('projets.show', $projet['slug']) }}">{{ $projet->tr('titre_court') }}</a></li>
            @endforeach
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('actualites.*', 'agenda.*', 'mediatheque') ? 'current' : '' }}">
        <a href="{{ route('actualites.index') }}">{{ __('Actualités') }}</a>
        <ul>
            <li><a href="{{ route('actualites.index') }}">{{ __('Actualités & Événements') }}</a></li>
            @foreach (\App\Support\Contenu::CATEGORIES_ACTUALITES as $cle => $nom)
                <li><a href="{{ route('actualites.index', ['categorie' => $cle]) }}">{{ __($nom) }}</a></li>
            @endforeach
            <li><a href="{{ route('agenda.index') }}">{{ __('Agenda') }}</a></li>
            <li><a href="{{ route('mediatheque') }}">{{ __('Médiathèque') }}</a></li>
        </ul>
    </li>
    <li class="dropdown {{ request()->routeIs('espace-jeunes.*') ? 'current' : '' }}">
        <a href="{{ route('espace-jeunes.index') }}">{{ __('Espace Jeunes') }}</a>
        <ul>
            <li><a href="{{ route('espace-jeunes.annuaire') }}">{{ __('Annuaire des membres') }}</a></li>
            <li><a href="{{ route('espace-jeunes.temoignages') }}">{{ __('Témoignages & success stories') }}</a></li>
            <li><a href="{{ route('espace-jeunes.opportunites') }}">{{ __('Opportunités') }}</a></li>
            <li><a href="{{ route('espace-jeunes.ressources') }}">{{ __('Ressources') }}</a></li>
        </ul>
    </li>
    <li class="{{ request()->routeIs('boutique.*') ? 'current' : '' }}">
        <a href="{{ route('boutique.index') }}">{{ __('Boutique') }}</a>
    </li>
    <li class="{{ request()->routeIs('contact') ? 'current' : '' }}">
        <a href="{{ route('contact') }}">{{ __('Contact') }}</a>
    </li>
</ul>
