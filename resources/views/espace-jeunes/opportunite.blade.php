@extends('layouts.app')

@section('title', $opportunite->tr('titre'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $opportunite->typeLibelle(),
            'texte' => $opportunite->organisme ?: __('Une opportunité relayée par le REJEPPAT.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index'), __('Opportunités') => route('espace-jeunes.opportunites')],
            'actif' => \Illuminate\Support\Str::limit($opportunite->tr('titre'), 40),
        ])

        <section class="rj-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <span class="rj-pastille {{ $opportunite->estOuverte() ? '' : 'rj-pastille--gris' }}">{{ $opportunite->estOuverte() ? $opportunite->typeLibelle() : __('Clôturée') }}</span>
                        <h2 style="margin: 14px 0 18px">{{ $opportunite->tr('titre') }}</h2>
                        <div class="rj-texte">
                            @foreach ($opportunite->tr('description') ?? [] as $paragraphe)
                                <p>{{ $paragraphe }}</p>
                            @endforeach
                        </div>
                        @if ($opportunite->lien || $opportunite->fichier)
                            <p style="display: flex; flex-wrap: wrap; gap: 14px; margin-top: 30px">
                                @if ($opportunite->lien)
                                    <a href="{{ $opportunite->lien }}" class="btn-one" target="_blank" rel="noopener"><i class="icon-arrow"></i><span class="txt">{{ __('Postuler / en savoir plus') }}</span></a>
                                @endif
                                @if ($opportunite->fichier)
                                    <a href="{{ $opportunite->fichierUrl() }}" class="rj-bouton-petit" target="_blank" rel="noopener" style="align-self: center"><i class="fas fa-file-download"></i> {{ __('Télécharger le document') }}</a>
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <aside class="rj-encadre">
                            <h3>{{ __('En bref') }}</h3>
                            <ul>
                                <li><i class="fas fa-tag"></i> <span>{{ $opportunite->typeLibelle() }}</span></li>
                                @if ($opportunite->organisme)<li><i class="fas fa-building"></i> <span>{{ $opportunite->organisme }}</span></li>@endif
                                @if ($opportunite->lieu)<li><i class="icon-map-point"></i> <span>{{ $opportunite->lieu }}</span></li>@endif
                                <li><i class="far fa-calendar-alt"></i> <span>{{ __('Date limite') }} : {{ $opportunite->date_limite ? \App\Support\Contenu::date($opportunite->date_limite, 'j F Y') : __('aucune') }}</span></li>
                            </ul>
                        </aside>
                        @if ($autres->isNotEmpty())
                            <aside class="rj-encadre">
                                <h3>{{ __('Autres opportunités') }}</h3>
                                <ul>
                                    @foreach ($autres as $autre)
                                        <li><i class="icon-arrow"></i> <a href="{{ route('espace-jeunes.opportunite', $autre->slug) }}">{{ $autre->tr('titre') }}</a></li>
                                    @endforeach
                                </ul>
                            </aside>
                        @endif
                    </div>
                </div>
            </div>
        </section>

@endsection
