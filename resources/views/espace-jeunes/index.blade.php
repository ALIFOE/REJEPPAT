@extends('layouts.app')

@section('title', __('Espace Jeunes / Membres'))

@php
    $rubriques = [
        ['route' => 'espace-jeunes.annuaire', 'icone' => 'fas fa-address-book', 'titre' => __('Annuaire des membres'), 'texte' => trans_choice(':count jeune producteur ou coopérative présenté dans l’annuaire.|:count jeunes producteurs et coopératives présentés dans l’annuaire.', $membres)],
        ['route' => 'espace-jeunes.temoignages', 'icone' => 'far fa-comment-dots', 'titre' => __('Témoignages & success stories'), 'texte' => __('Des parcours de jeunes qui vivent de l’agriculture grâce au réseau.')],
        ['route' => 'espace-jeunes.opportunites', 'icone' => 'fas fa-bullhorn', 'titre' => __('Opportunités'), 'texte' => __('Emplois, appels à projets, financements, bourses et formations.')],
        ['route' => 'espace-jeunes.ressources', 'icone' => 'fas fa-file-download', 'titre' => __('Ressources'), 'texte' => trans_choice(':count guide, manuel ou support de formation à télécharger.|:count guides, manuels et supports de formation à télécharger.', $ressources)],
    ];
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Espace Jeunes / Membres'),
            'texte' => __('L’espace de valorisation des jeunes producteurs du réseau.'),
            'image' => 'demande.jpg',
        ])

        <section class="rj-section">
            <div class="container">
                @include('espace-jeunes._menu')

                <div class="rj-grille rj-grille--4" style="margin-bottom: 70px">
                    @foreach ($rubriques as $rubrique)
                        <a href="{{ route($rubrique['route']) }}" class="rj-carte" style="color: inherit">
                            <div class="rj-carte__corps">
                                <span class="rj-avatar" style="width: 64px; height: 64px; font-size: 24px"><i class="{{ $rubrique['icone'] }}"></i></span>
                                <h3>{{ $rubrique['titre'] }}</h3>
                                <p>{{ $rubrique['texte'] }}</p>
                                <span class="rj-lien-fleche">{{ __('Accéder') }} <i class="icon-arrow"></i></span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                        <h2 class="rj-titre-bloc">{{ __('Dernières opportunités') }}</h2>
                        @forelse ($opportunites as $opportunite)
                            @include('espace-jeunes._opportunite', ['opportunite' => $opportunite])
                        @empty
                            <div class="rj-vide"><i class="fas fa-bullhorn"></i><p>{{ __('Aucune opportunité ouverte pour le moment. Revenez bientôt ou abonnez-vous à la newsletter.') }}</p></div>
                        @endforelse
                    </div>
                    <div class="col-xl-5 col-lg-5">
                        @if ($story)
                            <h2 class="rj-titre-bloc">{{ __('Success story') }}</h2>
                            <div class="rj-temoignage">
                                @if ($story->tr('titre'))<h3 style="font-size: 22px; margin: 0 0 12px">{{ $story->tr('titre') }}</h3>@endif
                                <p>{{ \Illuminate\Support\Str::limit($story->tr('texte'), 320) }}</p>
                                <div class="rj-auteur">
                                    <img src="{{ $story->visuel() }}" alt="{{ $story->nom }}">
                                    <div><strong>{{ $story->nom }}</strong><span>{{ $story->tr('role') }}</span></div>
                                </div>
                            </div>
                            <p style="margin-top: 20px"><a class="rj-lien-fleche" href="{{ route('espace-jeunes.temoignages') }}">{{ __('Toutes les success stories') }} <i class="icon-arrow"></i></a></p>
                        @endif
                        <div class="rj-encadre" @if ($story) style="margin-top: 30px" @endif>
                            <h3>{{ __('Vous êtes membre du réseau ?') }}</h3>
                            <p>{{ __('Présentez votre exploitation ou votre coopérative dans l’annuaire pour vous faire connaître des acheteurs et des partenaires.') }}</p>
                            <a href="{{ route('espace-jeunes.rejoindre') }}" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Rejoindre l’annuaire') }}</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection
