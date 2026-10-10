{{-- Rendez-vous de l'agenda. Paramètres : $evenement, $passe (facultatif) --}}
@php
    $debut = $evenement->debut->locale(app()->getLocale());
@endphp
<article class="rj-evenement {{ ($passe ?? false) ? 'rj-evenement--passe' : '' }}">
    <div class="rj-date">
        <strong>{{ $debut->format('d') }}</strong>
        <span>{{ $debut->translatedFormat('M Y') }}</span>
    </div>
    <div>
        <span class="rj-pastille">{{ $evenement->typeLibelle() }}</span>
        <h3><a href="{{ route('agenda.show', $evenement->slug) }}">{{ $evenement->tr('titre') }}</a></h3>
        <ul class="rj-meta">
            <li><i class="far fa-clock"></i> {{ $debut->translatedFormat(app()->getLocale() === 'fr' ? 'l j F Y, H\hi' : 'l, F j, Y, g:i A') }}@if ($evenement->fin && ! $evenement->fin->isSameDay($evenement->debut)) → {{ $evenement->fin->locale(app()->getLocale())->translatedFormat('j F Y') }}@endif</li>
            <li><i class="icon-map-point"></i> {{ $evenement->lieu }}</li>
        </ul>
        @if ($evenement->tr('description'))
            <p>{{ \Illuminate\Support\Str::limit($evenement->tr('description')[0], 180) }}</p>
        @endif
    </div>
</article>
