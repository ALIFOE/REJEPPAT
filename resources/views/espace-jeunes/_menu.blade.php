{{-- Navigation entre les rubriques de l'Espace Jeunes / Membres --}}
<nav class="rj-filtres" aria-label="{{ __('Espace Jeunes') }}">
    <a href="{{ route('espace-jeunes.index') }}" class="{{ request()->routeIs('espace-jeunes.index') ? 'actif' : '' }}"><i class="fas fa-seedling"></i> {{ __('Accueil de l’espace') }}</a>
    <a href="{{ route('espace-jeunes.annuaire') }}" class="{{ request()->routeIs('espace-jeunes.annuaire', 'espace-jeunes.membre', 'espace-jeunes.rejoindre') ? 'actif' : '' }}"><i class="fas fa-address-book"></i> {{ __('Annuaire des membres') }}</a>
    <a href="{{ route('espace-jeunes.temoignages') }}" class="{{ request()->routeIs('espace-jeunes.temoignages') ? 'actif' : '' }}"><i class="far fa-comment-dots"></i> {{ __('Témoignages & success stories') }}</a>
    <a href="{{ route('espace-jeunes.opportunites') }}" class="{{ request()->routeIs('espace-jeunes.opportunites', 'espace-jeunes.opportunite') ? 'actif' : '' }}"><i class="fas fa-bullhorn"></i> {{ __('Opportunités') }}</a>
    <a href="{{ route('espace-jeunes.ressources') }}" class="{{ request()->routeIs('espace-jeunes.ressources') ? 'actif' : '' }}"><i class="fas fa-file-download"></i> {{ __('Ressources') }}</a>
</nav>
