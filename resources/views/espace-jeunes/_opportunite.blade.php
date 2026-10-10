{{-- Ligne d'une opportunité. Paramètre : $opportunite --}}
<article class="rj-opportunite">
    <div>
        <span class="rj-pastille {{ $opportunite->estOuverte() ? '' : 'rj-pastille--gris' }}">{{ $opportunite->typeLibelle() }}</span>
        <h3><a href="{{ route('espace-jeunes.opportunite', $opportunite->slug) }}">{{ $opportunite->tr('titre') }}</a></h3>
        <ul class="rj-meta">
            @if ($opportunite->organisme)<li><i class="fas fa-building"></i> {{ $opportunite->organisme }}</li>@endif
            @if ($opportunite->lieu)<li><i class="icon-map-point"></i> {{ $opportunite->lieu }}</li>@endif
        </ul>
    </div>
    <div class="rj-echeance">
        @if ($opportunite->date_limite)
            <small>{{ $opportunite->estOuverte() ? __('Date limite') : __('Clôturée le') }}</small>
            <strong>{{ \App\Support\Contenu::date($opportunite->date_limite, 'j M Y') }}</strong>
        @else
            <small>{{ __('Date limite') }}</small>
            <strong>{{ __('Ouverte') }}</strong>
        @endif
    </div>
</article>
