@extends('layouts.app')

@section('title', __('Témoignages & success stories'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Témoignages & success stories'),
            'texte' => __('Ils racontent ce que le réseau a changé pour eux.'),
            'image' => 'demande.jpg',
            'liens' => [__('Espace Jeunes') => route('espace-jeunes.index')],
        ])

        <section class="rj-section rj-section--clair">
            <div class="container">
                @include('espace-jeunes._menu')

                @if ($stories->isNotEmpty())
                    <h2 class="rj-titre-bloc">{{ __('Success stories') }}</h2>
                    @foreach ($stories as $story)
                        <article class="rj-story">
                            <img src="{{ $story->visuel() }}" alt="{{ $story->nom }}" loading="lazy">
                            <div>
                                <h3>{{ $story->tr('titre') }}</h3>
                                <blockquote>
                                    @foreach (preg_split('/\R+/', $story->tr('texte')) as $paragraphe)
                                        <p>{{ $paragraphe }}</p>
                                    @endforeach
                                </blockquote>
                                <div class="rj-auteur" style="justify-content: inherit">
                                    <div><strong>{{ $story->nom }}</strong><span>{{ $story->tr('role') }}</span></div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif

                @if ($temoignages->isNotEmpty())
                    <h2 class="rj-titre-bloc" @if ($stories->isNotEmpty()) style="margin-top: 70px" @endif>{{ __('Témoignages') }}</h2>
                    <div class="rj-grille rj-grille--3">
                        @foreach ($temoignages as $temoignage)
                            <div class="rj-temoignage">
                                @if ($temoignage->tr('titre'))<h3 style="font-size: 20px; margin: 0 0 12px">{{ $temoignage->tr('titre') }}</h3>@endif
                                <p>“{{ $temoignage->tr('texte') }}”</p>
                                <div class="rj-auteur">
                                    <img src="{{ $temoignage->visuel() }}" alt="{{ $temoignage->nom }}" loading="lazy">
                                    <div>
                                        <strong>{{ $temoignage->nom }}</strong>
                                        <span>{{ $temoignage->tr('role') }}@if ($temoignage->ferme) – <a href="{{ route('fermes.show', $temoignage->ferme->slug) }}">{{ __('Ferme école :nom', ['nom' => $temoignage->ferme->nom]) }}</a>@endif</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($stories->isEmpty() && $temoignages->isEmpty())
                    <div class="rj-vide"><i class="far fa-comment-dots"></i><p>{{ __('Les premiers témoignages seront bientôt publiés.') }}</p></div>
                @endif
            </div>
        </section>

@endsection
