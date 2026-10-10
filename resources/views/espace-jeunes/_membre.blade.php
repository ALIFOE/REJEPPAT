{{-- Carte d'un membre de l'annuaire. Paramètre : $membre --}}
<article class="rj-membre">
    <span class="rj-avatar">
        @if ($membre->photo)
            <img src="{{ $membre->photoUrl() }}" alt="{{ $membre->nom }}" loading="lazy">
        @else
            {{ $membre->initiales() }}
        @endif
    </span>
    <div>
        <h3><a href="{{ route('espace-jeunes.membre', $membre->slug) }}">{{ $membre->nom }}</a></h3>
        <p>{{ $membre->organisation ?: __('Producteur individuel') }}@if ($membre->region) · {{ __($membre->region->nom) }}@endif</p>
        <span class="rj-pastille">{{ $membre->filiereLibelle() }}</span>
    </div>
</article>
