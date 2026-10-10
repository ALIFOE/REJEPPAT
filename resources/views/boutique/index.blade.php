@extends('layouts.app')

@section('title', __('Boutique'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Boutique'),
            'texte' => $recherche !== '' ? __('Résultats pour « :recherche »', ['recherche' => $recherche]) : __('Achetez local et frais, directement chez les producteurs.'),
            'image' => 'boutique.jpg',
        ])


        <!--Start Product Page -->
        <section class="product-page">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5">
                        <div class="product-page__left">
                            <div class="product-page__sidebar">
                                <!--Start Product Page sidebar Single -->
                                <div class="product-page__sidebar-search-box product-page__sidebar-single">
                                    <div class="product-page__sidebar-title-box">
                                        <div class="icon">
                                            <span class="icon-hat"></span>
                                        </div>
                                        <div class="title">
                                            <h4>{{ __('Rechercher') }}</h4>
                                        </div>
                                    </div>
                                    <form class="search-form" action="{{ route('boutique.index') }}">
                                        <input placeholder="{{ __('Rechercher un produit...') }}" type="text" name="q" value="{{ $recherche }}">
                                        <button type="submit">
                                            <i class="icon-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <!--End Product Page sidebar Single -->

                                <!--Start Product Page sidebar Single -->
                                <div class="product-page__sidebar-categories-box product-page__sidebar-single">
                                    <div class="product-page__sidebar-title-box">
                                        <div class="icon">
                                            <span class="icon-hat"></span>
                                        </div>
                                        <div class="title">
                                            <h4>{{ __('Catégories') }}</h4>
                                        </div>
                                    </div>
                                    <div class="product-page__sidebar-categories-list-box">
                                        <ul class="product-page__sidebar-categories-list">
                                            <li class="{{ $categorie ? '' : 'active' }}"><a href="{{ route('boutique.index') }}">{{ __('Tous les produits') }} <span>({{ $tous->count() }})</span> </a></li>
                                            @foreach (config('boutique.categories') as $cle => $nom)
                                                @continue($tous->where('categorie', $cle)->isEmpty())
                                                <li class="{{ $categorie === $cle ? 'active' : '' }}"><a href="{{ route('boutique.index', ['categorie' => $cle]) }}">{{ __($nom) }} <span>({{ $tous->where('categorie', $cle)->count() }})</span> </a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <!--End Product Page sidebar Single -->

                                <!--Start Product Page sidebar Single -->
                                <div class="product-page__sidebar-recent-post-box product-page__sidebar-single">
                                    <div class="product-page__sidebar-title-box">
                                        <div class="icon">
                                            <span class="icon-hat"></span>
                                        </div>
                                        <div class="title">
                                            <h4>{{ __('Nos produits') }}</h4>
                                        </div>
                                    </div>
                                    <div class="product-page__sidebar-recent-post__list-box">
                                        <ul class="product-page__sidebar-recent-post__list">
                                            @foreach ($tous as $produit)
                                            <li>
                                                <div class="product-page__sidebar-recent-post-img">
                                                    <img src="{{ $produit->visuel('-mini') }}"
                                                        alt="{{ $produit->tr('nom') }}">
                                                </div>
                                                <div class="product-page__sidebar-recent-post-content">
                                                    <div class="product-page__sidebar-recent-post-date-box">
                                                        <div class="product-page__sidebar-recent-post-date-icon-box">
                                                            <div class="icon">
                                                                <span class="fas fa-tag"></span>
                                                            </div>
                                                            <div class="text">
                                                                <p>{{ \App\Support\Contenu::prix($produit['prix']) }}</p>
                                                            </div>
                                                        </div>
                                                        <h5 class="product-page__sidebar-recent-post-date-title"><a
                                                                href="{{ route('boutique.show', $produit['slug']) }}">{{ $produit->tr('nom') }}</a></h5>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <!--End Product Page sidebar Single -->
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-7">
                        <div class="product-page__right">
                            <div class="row">

                                @forelse ($produits as $produit)
                                <!-- Start Single Shop Style1 -->
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    @include('boutique._carte', ['produit' => $produit])
                                </div>
                                <!-- End Single Shop Style1 -->
                                @empty
                                <div class="col-xl-12">
                                    <p>{{ __('Aucun produit ne correspond à votre recherche.') }}
                                        <a href="{{ route('boutique.index') }}">{{ __('Voir tous les produits') }}</a>.</p>
                                </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Product Page -->

@endsection
