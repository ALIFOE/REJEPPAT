@extends('layouts.app')

@section('title', $actualite->tr('title'))

@php
    $noms = \App\Support\Contenu::CATEGORIES_ACTUALITES;
    $categories = collect($actualite['categories'])->map(fn ($c) => __($noms[$c]))->implode(', ');
    $paragraphes = $actualite->paragraphes();
    $partage = urlencode(route('actualites.show', $actualite['slug']));
@endphp

{{-- Lien partagé : la photo et le début de l'article s'affichent dans l'aperçu au lieu du logo --}}
@section('og_type', 'article')
@section('og_image', $actualite->visuel('-detail'))
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags($paragraphes[0] ?? '')), 200))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __($noms[$actualite['categories'][0]]),
            'texte' => __('Retrouvez ici nos actualités et événements.'),
            'image' => 'actualites.jpg',
            'liens' => [__('Actualités & Événements') => route('actualites.index')],
            'actif' => \Illuminate\Support\Str::limit($actualite->tr('title'), 40),
        ])


        <!-- Start Blog Details Page -->
        <section class="blog-details-page">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-8">
                        <div class="blog-details-page-content">

                            <div class="blog-details-page-top">
                                <div class="content-box">
                                    <div class="content-box-top">
                                        <div class="content-box-top-inner">
                                            <div class="date">
                                                <h3>{{ \App\Support\Contenu::date($actualite['date'], 'd') }}</h3>
                                                <p>{{ \App\Support\Contenu::date($actualite['date'], 'M, Y') }}</p>
                                            </div>
                                            <div class="author">
                                                <div class="img">
                                                    <img src="{{ asset('assets/images/rejeppat/actualites/auteur-45.jpg') }}" alt="REJEPPAT">
                                                </div>
                                                <div class="title">
                                                    <h4>REJEPPAT</h4>
                                                    <p><a href="{{ config('rejeppat.social.facebook') }}" target="_blank" rel="noopener">{{ __('Suivre') }}</a></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="comment-icon">
                                            <div class="icon">
                                                <i class="fa fa-regular fa-clock"></i>
                                            </div>
                                            <p>{{ \App\Support\Contenu::lecture($paragraphes) }} min</p>
                                        </div>
                                    </div>
                                    <div class="content-box-title">
                                        <h2>{{ $actualite->tr('title') }}</h2>
                                    </div>
                                </div>
                                <div class="img-box">
                                    <img src="{{ $actualite->visuel('-detail') }}" alt="{{ $actualite->tr('title') }}">
                                    <div class="category">
                                        <h6>{{ $categories }}</h6>
                                    </div>
                                </div>
                            </div>

                            {{-- Texte mis en forme dans l'administration, nettoyé à l'enregistrement (App\Support\TexteRiche) --}}
                            <div class="blog-details-text1 article-contenu">
                                {!! $actualite->corpsHtml() !!}
                            </div>

                            @if (count($actualite['gallery']))
                            <div class="blog-details-text2">
                                <div class="row">
                                    @foreach ($actualite->galerie() as $photo)
                                        <div class="col-md-6 mb-4">
                                            <a class="lightbox-image" data-fancybox="galerie"
                                                href="{{ $photo['full'] }}">
                                                <img src="{{ $photo['mini'] }}" alt="{{ $actualite->tr('title') }}">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if (count($actualite['tags']))
                            <div class="blog-details-tag11">
                                <div class="title">
                                    <h4>{{ __('Mots-clés') }}</h4>
                                </div>
                                <ul class="clearfix">
                                    @foreach ($actualite['tags'] as $tag)
                                        <li><a href="{{ route('actualites.index', ['q' => $tag]) }}">{{ $tag }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="blog-details-author">
                                <div class="blog-details-author-inner">
                                    <div class="img-box">
                                        <img src="{{ asset('assets/images/rejeppat/actualites/auteur-120.jpg') }}" alt="REJEPPAT">
                                    </div>
                                    <div class="content-box">
                                        <div class="top">
                                            <h4>{{ __('Publié par') }}</h4>
                                            <h3>REJEPPAT</h3>
                                        </div>
                                        <div class="text">
                                            <p>{{ __(config('rejeppat.full_name')) }} : {{ __(config('rejeppat.about')) }}</p>
                                        </div>
                                        <div class="btn-box">
                                            <a href="{{ route('actualites.index') }}">
                                                {{ __('Toutes les publications') }}
                                                <i class="icon-arrow"></i>
                                            </a>
                                        </div>
                                        <div class="social-links">
                                            <ul>
                                                <li>
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $partage }}" target="_blank" rel="noopener">
                                                        <span class="icon-facebook"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://x.com/intent/post?url={{ $partage }}" target="_blank" rel="noopener">
                                                        <span class="icon-twitter"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $partage }}" target="_blank" rel="noopener">
                                                        <span class="fab fa-linkedin-in"></span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="https://wa.me/?text={{ $partage }}" target="_blank" rel="noopener">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="blog-details-prev-next-option">
                                <div class="single-box left">
                                    @if ($precedente)
                                    <div class="title-box">
                                        <div class="button-box">
                                            <a href="{{ route('actualites.show', $precedente['slug']) }}">
                                                <span class="icon-arrow"></span>
                                                {{ __('Article précédent') }}
                                            </a>
                                        </div>
                                        <h3>
                                            <a href="{{ route('actualites.show', $precedente['slug']) }}">{{ \Illuminate\Support\Str::limit($precedente->tr('title'), 60) }}</a>
                                        </h3>
                                    </div>
                                    @endif
                                </div>
                                <div class="single-box right">
                                    @if ($suivante)
                                    <div class="title-box">
                                        <div class="button-box">
                                            <a href="{{ route('actualites.show', $suivante['slug']) }}">
                                                {{ __('Article suivant') }}
                                                <span class="icon-arrow"></span>
                                            </a>
                                        </div>
                                        <h3>
                                            <a href="{{ route('actualites.show', $suivante['slug']) }}">{{ \Illuminate\Support\Str::limit($suivante->tr('title'), 60) }}</a>
                                        </h3>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="back-to-blog-post-btn">
                                <a href="{{ route('actualites.index') }}">
                                    <i class="icon-wheat"></i>
                                    {{ __('Retour aux actualités') }}
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Start Blog Details Page Sidebar -->
                    <div class="col-xl-4 col-lg-4">

                        <div class="blog-page-style2__sidebar blog-page-style2__sidebar--instyle2">

                            <div class="blog-page-style2__sidebar-single blog-page-style2__sidebar-search">
                                <div class="blog-page-style2__sidebar-single-title">
                                    <i class="icon-hat"></i>
                                    <h3>{{ __('Rechercher') }}</h3>
                                </div>
                                <form class="search-form" action="{{ route('actualites.index') }}">
                                    <input placeholder="{{ __('Rechercher...') }}" type="text" name="q">
                                    <button type="submit">
                                        <i class="icon-search"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="blog-page-style2__sidebar-single blog-page-style2__sidebar-categories">
                                <div class="blog-page-style2__sidebar-single-title">
                                    <i class="icon-hat"></i>
                                    <h3>{{ __('Catégories') }}</h3>
                                </div>
                                <ul class="blog-page-sidebar__categories-list clearfix">
                                    @foreach ($noms as $cle => $nom)
                                        <li><a href="{{ route('actualites.index', ['categorie' => $cle]) }}">{{ __($nom) }}</a> <span>({{ $compteurs[$cle] }})</span></li>
                                    @endforeach
                                </ul>
                            </div>

                            @include('partials.sidebar-actualites', ['actualites' => $recentes, 'classe' => 'blog-page-style2__sidebar-single'])

                            @include('partials.sidebar-contact')

                            @if ($tags->isNotEmpty())
                            <div class="blog-page-style2__sidebar-single blog-page-style2__sidebar-tag">
                                <div class="blog-page-style2__sidebar-single-title">
                                    <i class="icon-hat"></i>
                                    <h3>{{ __('Mots-clés') }}</h3>
                                </div>
                                <ul class="blog-page-sidebar__tag clearfix">
                                    @foreach ($tags as $tag)
                                        <li><a href="{{ route('actualites.index', ['q' => $tag]) }}">{{ $tag }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                        </div>
                    </div>
                    <!-- Start Blog Details Page Sidebar -->

                </div>
            </div>
        </section>
        <!-- End Blog Details Page -->

@endsection
