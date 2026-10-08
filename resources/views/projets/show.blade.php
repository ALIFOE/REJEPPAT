@extends('layouts.app')

@section('title', $projet['titre'])

@php
    $nomsCategories = collect($projet['categories'])->map(fn ($c) => $categories[$c])->implode(', ');
    $partage = urlencode(route('projets.show', $projet['slug']));
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $projet['titre_court'],
            'texte' => $projet['resume'],
            'image' => 'projets.jpg',
            'liens' => ['Programmes & Projets' => route('projets.index')],
        ])


        <!-- Start Project Details Style1 -->
        <section class="project-details-style1">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="project-details-style1__img">
                            <img src="{{ asset('assets/images/rejeppat/projets/' . $projet['slug'] . '-detail.jpg') }}" alt="{{ $projet['titre'] }}">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="project-details-style1__info">
                            <ul class="project-details-style1__info-list">
                                <li>
                                    <span>Porteur :</span>
                                    <p>REJEPPAT</p>
                                </li>
                                <li>
                                    <span>Catégorie :</span>
                                    <p>{{ $nomsCategories }}</p>
                                </li>
                                <li>
                                    <span>Publié le :</span>
                                    <p>{{ \App\Support\Contenu::date($projet['date'], 'j F Y') }}</p>
                                </li>
                                <li>
                                    <span>Zone :</span>
                                    <p>Togo</p>
                                </li>
                                <li>
                                    <span>Contact :</span>
                                    <p><a href="mailto:{{ config('rejeppat.emails.0') }}">{{ config('rejeppat.emails.0') }}</a></p>
                                </li>
                            </ul>
                            <div class="project-details-style1__social">
                                <div class="project-details-style1__social-title">
                                    <h6>Partager le projet</h6>
                                </div>
                                <ul>
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ $partage }}" target="_blank" rel="noopener"><i class="icon-facebook"></i></a></li>
                                    <li><a href="https://x.com/intent/post?url={{ $partage }}" target="_blank" rel="noopener"><i class="icon-twitter"></i></a></li>
                                    <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $partage }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a></li>
                                    <li><a href="https://wa.me/?text={{ $partage }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="project-details-style1__content">
                            <div class="project-details-style1__content-title">
                                <h2>Description du projet</h2>
                            </div>
                            <div class="project-details-style1__content-text">
                                @foreach ($projet['description'] as $paragraphe)
                                    <p>{{ $paragraphe }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Project Details Style1 -->

        @if (count($projet['actions']))
        <!-- Start Project Details Style2 -->
        <section class="project-details-style2">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="project-details-style2__content">
                            <div class="project-details-style2__content-title">
                                <h2>Nos actions</h2>
                            </div>
                            <div class="project-details-style2__content-text">
                                <p>{{ $projet['resume'] }}</p>
                            </div>
                            <ul class="project-details-style2__content-list">
                                @foreach ($projet['actions'] as $action)
                                <li>
                                    <div class="box"></div>
                                    <div class="text">
                                        <h3>{{ $action['titre'] }}</h3>
                                        <p>{{ $action['texte'] }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="project-details-style2__img">
                            <img src="{{ asset('assets/images/rejeppat/projets/' . $projet['slug'] . '-side.jpg') }}" alt="{{ $projet['titre_court'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Project Details Style2 -->
        @endif


        <!-- Start Project Details Style3 -->
        <section class="project-details-style3">
            <div class="container">
                <div class="project-details-style3__content">
                    <div class="project-details-style3__content-title">
                        <h2>Résultats &amp; points clés</h2>
                    </div>
                    @if (count($projet['resultats']))
                    <div class="project-details-style3__content-text">
                        @foreach ($projet['resultats'] as $paragraphe)
                            <p>{{ $paragraphe }}</p>
                        @endforeach
                    </div>
                    @endif
                    <ul class="project-details-style3__content-list">
                        @foreach ($projet['points'] as $point)
                        <li>
                            <div class="dot"></div>
                            <div class="text">
                                <p>{{ $point }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
        <!-- End Project Details Style3 -->

@endsection
