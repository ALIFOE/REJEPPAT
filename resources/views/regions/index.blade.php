@extends('layouts.app')

@section('title', __('Sections régionales'))

@section('body_class', 'body-bg-1 page-reseau')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Sections régionales'),
            'texte' => __('Un réseau présent sur tout le territoire togolais.'),
            'image' => 'fermes.jpg',
        ])

        <section class="rj-section">
            <div class="container">
                <div class="rj-intro">
                    <div class="sec-title text-center">
                        <div class="sub-title">
                            <div class="icon"><i class="icon-hat"></i></div>
                            <h4>{{ __('Le réseau en région') }}</h4>
                        </div>
                        <h2>{{ __('Nos 5 sections régionales') }}</h2>
                    </div>
                    <p>{{ __('Les coopératives membres du REJEPPAT sont représentées par des sections régionales. Chacune accompagne les jeunes producteurs de sa région au plus près du terrain.') }}</p>
                </div>

                <div class="rj-grille rj-grille--3">
                    @foreach ($regions as $region)
                        <article class="rj-carte">
                            <a href="{{ route('regions.show', $region->slug) }}" class="rj-carte__image">
                                <img src="{{ $region->visuel() }}" alt="{{ $region->nom }}" loading="lazy">
                            </a>
                            <div class="rj-carte__corps">
                                <h3><a href="{{ route('regions.show', $region->slug) }}">{{ __($region->nom) }}</a></h3>
                                <ul class="rj-meta">
                                    @if ($region->chef_lieu)<li><i class="icon-map-point"></i> {{ $region->chef_lieu }}</li>@endif
                                    <li><i class="icon-farm-house-1"></i> {{ trans_choice(':count ferme école|:count fermes écoles', $region->fermes_count) }}</li>
                                    @if ($region->nb_cooperatives)<li><i class="fas fa-users"></i> {{ trans_choice(':count coopérative|:count coopératives', $region->nb_cooperatives) }}</li>@endif
                                </ul>
                                <p>{{ $region->tr('presentation') ? \Illuminate\Support\Str::limit($region->tr('presentation')[0], 140) : __('Page en cours de rédaction avec la section régionale.') }}</p>
                                <a href="{{ route('regions.show', $region->slug) }}" class="rj-lien-fleche">{{ __('Découvrir la section') }} <i class="icon-arrow"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

@endsection
