@extends('layouts.app')

@section('title', __('Ferme école :nom', ['nom' => $ferme['nom']]))

@php
    $modules = $ferme->tr('modules') ?? [];
    $moitie = (int) ceil(count($modules) / 2);
    $description = $ferme->tr('description') ?? [];
    $productions = $ferme->tr('productions') ?? [];
    $actions = $ferme->tr('actions') ?? [];
    $galerie = $ferme->galerieUrls();
    $whatsapp = preg_replace('/\D+/', '', (string) $ferme->telephone);
    $whatsapp = strlen($whatsapp) === 8 ? '228' . $whatsapp : $whatsapp;
@endphp

{{-- Lien partagé : une photo de la ferme (sinon son visuel) et sa présentation s'affichent dans l'aperçu --}}
@section('og_image', $galerie[0] ?? $ferme->visuel('-top'))
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags($description[0] ?? __('Spécialité') . ' : ' . $ferme->tr('specialite'))), 200))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Ferme école :nom', ['nom' => $ferme['nom']]),
            'texte' => $ferme['localisation'] ?? __('Une ferme école du réseau REJEPPAT.'),
            'image' => 'fermes.jpg',
            'liens' => [__('Fermes Écoles') => route('fermes.index')],
            'actif' => $ferme['nom'],
        ])


        <!--Start Services Details-->
        <section class="services-details">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4 col-lg-5 order222">
                        @include('fermes._sidebar', ['actif' => $ferme['slug']])
                    </div>

                    <!-- Start Service Details Content -->
                    <div class="col-xl-8 col-lg-7 order111">
                        <div class="services-details__content">

                            <div class="services-details__content-top">
                                <div class="services-details__content-top-img">
                                    <img src="{{ $ferme->visuel('-top') }}" alt="{{ __('Ferme école :nom', ['nom' => $ferme['nom']]) }}">
                                </div>
                                <div class="services-details__content-top-text">
                                    <h2>{{ __('Ferme école :nom', ['nom' => $ferme['nom']]) }}</h2>
                                    @forelse ($description as $paragraphe)
                                        <p>{{ $paragraphe }}</p>
                                    @empty
                                        <p>
                                            {{ __('La ferme école :nom fait partie du réseau des fermes écoles du REJEPPAT : un espace de formation, d’innovation et d’expérimentation où les participants sont formés dans des conditions réelles de production.', ['nom' => $ferme['nom'] . ($ferme['localisation'] ? ' (' . $ferme['localisation'] . ')' : '')]) }}
                                        </p>
                                    @endforelse
                                    <p>{{ __('Spécialité') }} : <strong>{{ $ferme->tr('specialite') }}</strong>.@if ($ferme->region) {{ __('Section régionale') }} : <a href="{{ route('regions.show', $ferme->region->slug) }}">{{ __($ferme->region->nom) }}</a>.@endif</p>
                                </div>
                            </div>

                            <div class="services-details__content-list">
                                <div class="row">
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => __('Domaines de formation'), 'elements' => array_slice($modules, 0, $moitie)])
                                    </div>
                                    @if (count($modules) > 1)
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => __('Et aussi'), 'elements' => array_slice($modules, $moitie)])
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if ($productions)
                                <div class="rj-bloc">
                                    <h2>{{ __('Productions') }}</h2>
                                    <ul class="rj-liste-puces">
                                        @foreach ($productions as $production)
                                            <li class="rj-pastille">{{ $production }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($actions)
                                <div class="rj-bloc">
                                    <h2>{{ __('Actions majeures') }}</h2>
                                    <ol class="rj-etapes">
                                        @foreach ($actions as $action)
                                            <li><h4>{{ $action['titre'] }}</h4>@if ($action['texte'])<p>{{ $action['texte'] }}</p>@endif</li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif

                            @if ($galerie)
                                <div class="rj-bloc">
                                    <h2>{{ __('En images') }}</h2>
                                    <div class="rj-galerie">
                                        @foreach ($galerie as $photo)
                                            <a href="{{ $photo }}" data-fancybox="ferme-{{ $ferme->slug }}"><img src="{{ $photo }}" alt="{{ __('Ferme école :nom', ['nom' => $ferme['nom']]) }}" loading="lazy"></a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($ferme->responsable || $ferme->telephone || $ferme->email)
                                <div class="rj-bloc">
                                    <div class="rj-encadre">
                                        <h3>{{ __('Responsable et contacts') }}</h3>
                                        <ul>
                                            @if ($ferme->responsable)<li><i class="fas fa-user"></i> <span>{{ $ferme->responsable }}</span></li>@endif
                                            @if ($ferme->telephone)
                                                <li><i class="icon-incoming-call"></i> <a href="tel:{{ preg_replace('/[^\d+]/', '', $ferme->telephone) }}">{{ $ferme->telephone }}</a></li>
                                                <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener">WhatsApp</a></li>
                                            @endif
                                            @if ($ferme->email)<li><i class="icon-mail"></i> <a href="mailto:{{ $ferme->email }}">{{ $ferme->email }}</a></li>@endif
                                            @if ($ferme->carte['adresse'] ?? null)<li><i class="icon-map-point"></i> <span>{{ $ferme->carte['adresse'] }}</span></li>@endif
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if ($temoignages->isNotEmpty())
                                <div class="rj-bloc">
                                    <h2>{{ __('Témoignages d’apprenants') }}</h2>
                                    <div class="rj-grille">
                                        @foreach ($temoignages as $temoignage)
                                            <div class="rj-temoignage" style="background: var(--rej-green-50); box-shadow: none">
                                                <p>“{{ $temoignage->tr('texte') }}”</p>
                                                <div class="rj-auteur">
                                                    <img src="{{ $temoignage->visuel() }}" alt="{{ $temoignage->nom }}" loading="lazy">
                                                    <div><strong>{{ $temoignage->nom }}</strong><span>{{ $temoignage->tr('role') }}</span></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="services-details__faq rj-bloc">
                                <div class="services-details__faq-title">
                                    <h2>{{ __('Foire aux questions') }}</h2>
                                </div>
                                <div class="services-details__faq-content">
                                    @include('partials.faq', ['questions' => config('fermes.faq')])
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Service Details Content -->

                </div>
            </div>
        </section>
        <!--End Services Details-->

@endsection
