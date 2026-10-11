@extends('layouts.app')

@section('title', __('Axes stratégiques'))

@section('body_class', 'body-bg-1 page-reseau')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Axes stratégiques'),
            'texte' => __('Les priorités qui guident l’action du réseau sur le terrain.'),
            'image' => 'offres.jpg',
        ])

        <section class="rj-section">
            <div class="container">
                <div class="rj-intro">
                    <div class="sec-title text-center">
                        <div class="sub-title">
                            <div class="icon"><i class="icon-hat"></i></div>
                            <h4>{{ __('Le Réseau') }}</h4>
                        </div>
                        <h2>{{ __('Nos axes stratégiques') }}</h2>
                    </div>
                    <p>{{ __('À travers ses différents domaines d’intervention, le REJEPPAT accompagne les jeunes et les acteurs ruraux dans le développement de leurs activités, le renforcement de leurs compétences et la création d’opportunités durables.') }}</p>
                </div>

                <div class="rj-grille rj-grille--4">
                    @foreach ($axes as $axe)
                        <article class="rj-carte">
                            <div class="rj-carte__image">
                                <img src="{{ asset('assets/images/rejeppat/offres/domaine-' . $axe['slug'] . '.jpg') }}" alt="{{ __($axe['titre']) }}" loading="lazy">
                            </div>
                            <div class="rj-carte__corps">
                                <ul class="rj-meta">
                                    <li>{{ __('Axe :numero', ['numero' => $loop->iteration]) }}</li>
                                </ul>
                                <h3>{{ __($axe['titre']) }}</h3>
                                <p>{{ __($axe['texte']) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($presentation)
                    <div class="rj-bloc" style="max-width: 760px; margin-left: auto; margin-right: auto">
                        <h3 class="rj-titre-bloc">{{ __('Pour aller plus loin') }}</h3>
                        <article class="rj-document">
                            <span class="rj-document__icone">{{ $presentation->format() }}</span>
                            <div>
                                <h3>{{ $presentation->tr('titre') }}</h3>
                                @if ($presentation->tr('description'))<p>{{ $presentation->tr('description') }}</p>@endif
                                <a href="{{ route('espace-jeunes.ressources.telecharger', $presentation) }}" class="rj-bouton-petit"><i class="fas fa-download"></i> {{ __('Télécharger') }}</a>
                            </div>
                        </article>
                    </div>
                @endif

                <div class="text-center" style="margin-top: 50px">
                    <a class="btn-one" href="{{ route('offres') }}">
                        <i class="icon-arrow"></i>
                        <span class="txt">{{ __('Nos offres & services') }}</span>
                    </a>
                </div>
            </div>
        </section>

@endsection
