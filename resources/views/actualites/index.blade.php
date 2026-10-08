@extends('layouts.app')

@php
    $noms = \App\Support\Contenu::CATEGORIES_ACTUALITES;
    $titre = $noms[$categorie] ?? 'Actualités & Événements';
    $texte = $recherche !== ''
        ? 'Résultats de recherche pour « ' . $recherche . ' »'
        : 'Retrouvez ici nos actualités et événements.';
@endphp

@section('title', $titre)

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $titre,
            'texte' => $texte,
            'image' => 'actualites.jpg',
            'liens' => isset($noms[$categorie]) ? ['Actualités & Événements' => route('actualites.index')] : [],
        ])


        <!-- Start Blog Style1 -->
        <section class="blog-style1 blog-style1--instyle2">
            <div class="container">
                <div class="row">

                    @forelse ($actualites as $article)
                    @php
                        $lien = route('actualites.show', $article['slug']);
                    @endphp
                    <!-- Start Single Blog Style1 -->
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="single-blog-style1">
                            <div class="single-blog-style1__img">
                                <img src="{{ asset('assets/images/rejeppat/actualites/' . $article['image'] . '.jpg') }}" alt="{{ $article['title'] }}">
                                <div class="single-blog-style1__img-overlay-icon">
                                    <a href="{{ $lien }}"><i class="icon-resize"></i></a>
                                </div>
                                <div class="single-blog-style1__img-category">
                                    <h6>{{ $noms[$article['categories'][0]] }}</h6>
                                </div>
                            </div>
                            <div class="single-blog-style1__content">
                                <ul class="single-blog-style1__content-meta">
                                    <li>
                                        <i class="icon-following"></i>
                                        <p>REJEPPAT</p>
                                    </li>
                                    <li>
                                        <i class="fa fa-solid fa-calendar-day"></i>
                                        <p>{{ \App\Support\Contenu::date($article['date']) }}</p>
                                    </li>
                                </ul>
                                <div class="single-blog-style1__content-title">
                                    <h3>
                                        <a href="{{ $lien }}">{{ $article['title'] }}</a>
                                    </h3>
                                </div>
                                <div class="single-blog-style1__content-text">
                                    <p>{{ \App\Support\Contenu::extrait($article['content'], 90) }}</p>
                                </div>
                                <div class="single-blog-style1__content-btn">
                                    <a href="{{ $lien }}">
                                        <i class="fa fa-regular fa-clock"></i>
                                        {{ \App\Support\Contenu::lecture($article['content']) }} min de lecture
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Blog Style1 -->
                    @empty
                    <div class="col-xl-12">
                        <p class="text-center">Aucune publication ne correspond à votre recherche.
                            <a href="{{ route('actualites.index') }}">Voir toutes les actualités</a>.</p>
                    </div>
                    @endforelse

                </div>

                @if ($actualites->hasPages())
                <ul class="styled-pagination styled-pagination--style2 clearfix">
                    <li class="arrow prev">
                        <a href="{{ $actualites->previousPageUrl() ?? '#' }}"><span class="icon-arrow left"></span></a>
                    </li>
                    @foreach ($actualites->getUrlRange(1, $actualites->lastPage()) as $page => $url)
                        <li class="{{ $page === $actualites->currentPage() ? 'active' : '' }}"><a href="{{ $url }}">{{ sprintf('%02d', $page) }}</a></li>
                    @endforeach
                    <li class="arrow next">
                        <a href="{{ $actualites->nextPageUrl() ?? '#' }}"><span class="icon-arrow right"></span></a>
                    </li>
                </ul>
                @endif

            </div>
        </section>
        <!-- End Blog Style1 -->

@endsection
