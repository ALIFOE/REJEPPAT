@extends('layouts.app')

@section('title', __($region->nom))

@section('body_class', 'body-bg-1 page-reseau')

@php
    $presentation = $region->tr('presentation') ?? [];
    $filieres = $region->tr('filieres') ?? [];
    $gouverneurs = $region->listeGouverneurs();
    $activites = $region->tr('activites') ?? [];
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __($region->nom),
            'texte' => $region->chef_lieu ? __('Section régionale du REJEPPAT – :ville', ['ville' => $region->chef_lieu]) : __('Section régionale du REJEPPAT'),
            'image' => 'fermes.jpg',
            'liens' => [__('Sections régionales') => route('regions.index')],
        ])

        <section class="rj-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <img src="{{ $region->visuel() }}" alt="{{ $region->nom }}" style="width: 100%; border-radius: 10px; margin-bottom: 35px; max-height: 420px; object-fit: cover">

                        <h2 class="rj-titre-bloc">{{ __('Présentation de la section') }}</h2>
                        @if ($presentation)
                            <div class="rj-texte">
                                @foreach ($presentation as $paragraphe)
                                    <p>{{ $paragraphe }}</p>
                                @endforeach
                            </div>
                        @else
                            <div class="rj-region__appel">
                                <p>{{ __('La page de cette section régionale est en cours de rédaction : elle sera complétée avec les informations transmises par la région (activités, filières, coopératives, contacts).') }}</p>
                                <a href="{{ route('contact') }}" class="rj-bouton-petit">{{ __('Contacter le REJEPPAT') }} <i class="icon-arrow"></i></a>
                            </div>
                        @endif

                        @if ($region->nb_cooperatives || $region->nb_membres || $fermes->isNotEmpty())
                            <div class="rj-chiffres">
                                @if ($region->nb_cooperatives)
                                    <div class="rj-chiffre"><strong>{{ $region->nb_cooperatives }}</strong><span>{{ __('coopératives') }}</span></div>
                                @endif
                                @if ($region->nb_membres)
                                    <div class="rj-chiffre"><strong>{{ number_format($region->nb_membres, 0, ',', ' ') }}</strong><span>{{ __('producteurs membres') }}</span></div>
                                @endif
                                <div class="rj-chiffre"><strong>{{ $fermes->count() }}</strong><span>{{ __('fermes écoles') }}</span></div>
                            </div>
                        @endif

                        @if ($gouverneurs)
                            <div class="rj-bloc">
                                <h2>{{ trans_choice('Gouverneur de la section|Gouverneurs de la section', count($gouverneurs)) }}</h2>
                                <div class="rj-grille">
                                    @foreach ($gouverneurs as $gouverneur)
                                        <div class="rj-membre">
                                            <span class="rj-avatar">
                                                @if ($gouverneur['photo_url'])<img src="{{ $gouverneur['photo_url'] }}" alt="{{ $gouverneur['nom'] }}">@else{{ $gouverneur['initiales'] }}@endif
                                            </span>
                                            <div>
                                                <h3>{{ $gouverneur['nom'] }}</h3>
                                                <p>{{ $gouverneur['fonction'] ?: __('Gouverneur de la section') }}</p>
                                                @if ($gouverneur['telephone'])<a href="tel:{{ preg_replace('/[^\d+]/', '', $gouverneur['telephone']) }}"><i class="icon-incoming-call"></i> {{ $gouverneur['telephone'] }}</a>@endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($evenements->isNotEmpty() || $activites)
                            <div class="rj-bloc">
                                <h2>{{ __('Activités et informations à venir') }}</h2>
                                @foreach ($evenements as $evenement)
                                    @include('agenda._evenement', ['evenement' => $evenement])
                                @endforeach
                                @if ($activites)
                                    <ul class="rj-region-activites" @if ($evenements->isNotEmpty()) style="margin-top: 25px" @endif>
                                        @foreach ($activites as $activite)
                                            <li><span class="rj-region-activites__date"><i class="fas fa-bullhorn"></i></span><span>{{ $activite }}</span></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endif

                        @if ($filieres)
                            <h3 class="rj-titre-bloc" style="font-size: 24px">{{ __('Filières principales') }}</h3>
                            <ul class="rj-liste-puces">
                                @foreach ($filieres as $filiere)
                                    <li class="rj-pastille">{{ $filiere }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if ($fermes->isNotEmpty())
                            <div class="rj-bloc">
                                <h2>{{ __('Les fermes écoles de la région') }}</h2>
                                <div class="rj-grille">
                                    @foreach ($fermes as $ferme)
                                        <article class="rj-carte">
                                            <a href="{{ route('fermes.show', $ferme->slug) }}" class="rj-carte__image"><img src="{{ $ferme->visuel() }}" alt="{{ $ferme->nom }}" loading="lazy"></a>
                                            <div class="rj-carte__corps">
                                                <h3><a href="{{ route('fermes.show', $ferme->slug) }}">{{ $ferme->nom }}</a></h3>
                                                <p>{{ $ferme->tr('specialite') }}</p>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($membres->isNotEmpty())
                            <div class="rj-bloc">
                                <h2>{{ __('Des membres de la région') }}</h2>
                                <div class="rj-grille">
                                    @foreach ($membres as $membre)
                                        @include('espace-jeunes._membre', ['membre' => $membre])
                                    @endforeach
                                </div>
                                <p style="margin-top: 25px"><a class="rj-lien-fleche" href="{{ route('espace-jeunes.annuaire', ['region' => $region->slug]) }}">{{ __('Tous les membres de la région dans l’annuaire') }} <i class="icon-arrow"></i></a></p>
                            </div>
                        @endif
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <aside class="rj-encadre">
                            <h3>{{ __('Contact de la section') }}</h3>
                            <ul>
                                @if ($region->responsable)<li><i class="fas fa-user"></i> <span>{{ $region->responsable }}</span></li>@endif
                                @if ($region->chef_lieu)<li><i class="icon-map-point"></i> <span>{{ $region->chef_lieu }}</span></li>@endif
                                @if ($region->telephone)<li><i class="icon-incoming-call"></i> <a href="tel:{{ preg_replace('/[^\d+]/', '', $region->telephone) }}">{{ $region->telephone }}</a></li>@endif
                                @if ($region->email)<li><i class="icon-mail"></i> <a href="mailto:{{ $region->email }}">{{ $region->email }}</a></li>@endif
                                @unless ($region->telephone || $region->email)
                                    <li><i class="icon-incoming-call"></i> <a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a></li>
                                    <li><i class="icon-mail"></i> <a href="mailto:{{ config('rejeppat.emails.0') }}">{{ config('rejeppat.emails.0') }}</a></li>
                                @endunless
                            </ul>
                        </aside>
                        <aside class="rj-encadre">
                            <h3>{{ __('Les autres régions') }}</h3>
                            <ul>
                                @foreach ($regions as $autre)
                                    <li class="{{ $autre->is($region) ? 'actif' : '' }}"><i class="icon-arrow"></i> <a href="{{ route('regions.show', $autre->slug) }}">{{ __($autre->nom) }}</a></li>
                                @endforeach
                            </ul>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

@endsection
