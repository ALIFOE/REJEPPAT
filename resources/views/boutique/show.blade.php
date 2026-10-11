@extends('layouts.app')

@section('title', $produit->tr('nom'))

@php
    $categorie = $produit->categorieNom();
    $reduction = $produit->reduction();
    $galerie = $produit->galerie();
@endphp

{{-- Lien partagé : la photo et le résumé du produit s'affichent dans l'aperçu au lieu du logo --}}
@section('og_image', $galerie[0]['grande'])
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags((string) $produit->tr('resume'))), 200))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => $produit->tr('nom'),
            'texte' => __('Achetez local et frais, directement chez les producteurs.'),
            'image' => 'boutique.jpg',
            'liens' => [__('Boutique') => route('boutique.index')],
        ])


        <!--Start Product Details-->
        <section class="product-details">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-xl-6">
                        <div class="product-details__left">
                            <div class="product-details__left-inner">
                                <div class="product-details__content-box">
                                    <div class="swiper-container" id="shop-details-one__carousel">
                                        <div class="swiper-wrapper">
                                            @foreach ($galerie as $photo)
                                            <div class="swiper-slide">
                                                <div class="product-details__img">
                                                    <img src="{{ $photo['grande'] }}" alt="{{ $produit->tr('nom') }}@if (! $loop->first) – {{ __('photo') }} {{ $loop->iteration }}@endif">
                                                </div>
                                            </div><!-- /.swiper-slide -->
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="product-details__nav">
                                        <div class="swiper-button-next" id="product-details__swiper-button-prev">
                                            <i class="icon-arrow-right"></i>
                                        </div>
                                        <div class="swiper-button-prev" id="product-details__swiper-button-next">
                                            <i class="icon-arrow"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details__thumb-box">
                                    <div class="swiper-container" id="shop-details-one__thumb">
                                        <div class="swiper-wrapper">
                                            @foreach ($galerie as $photo)
                                            <div class="swiper-slide">
                                                <div class="product-details__thumb-img">
                                                    <img src="{{ $photo['mini'] }}" alt="{{ $produit->tr('nom') }}">
                                                </div>
                                            </div><!-- /.swiper-slide -->
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="product-details__right">
                            <div class="product-details__content">
                                <p class="product-details__sub-title">{{ $categorie }}</p>
                                <h3 class="product-details__title">{{ $produit->tr('nom') }}</h3>
                                <div class="product-details__rating-and-stock-box">
                                    <div class="product-details__rating-box">
                                        <div class="product-details__rating-icon">
                                            <span class="icon-rate-star-button"></span>
                                        </div>
                                        <p class="product-details__rating-text">{{ $reduction ? '-' . $reduction . ' %' : __('Prix producteur') }}</p>
                                    </div>
                                    <div class="product-details__stock-box">
                                        <div class="product-details__stock-img">
                                            <img src="{{ asset('assets/images/icon/product-details-stock-icon.png') }}" alt="">
                                        </div>
                                        <p class="product-details__stock-text">{{ $produit->enStock() ? ($produit->stock !== null ? __(':nombre en stock', ['nombre' => $produit->stock]) : __('Produit local')) : __('Rupture de stock') }}</p>
                                    </div>
                                </div>
                                <div class="product-details__price-box">
                                    <h3 class="product-details__price">{{ \App\Support\Contenu::prix($produit['prix']) }} @if ($reduction)<span>{{ \App\Support\Contenu::prix($produit['prix_initial']) }}</span>@endif </h3>
                                </div>
                                <p class="product-details__text-1">{{ $produit->tr('resume') }}</p>
                                <div class="product-quantity-box-outer">
                                    @include('boutique._alertes')
                                    <form class="product-quantity-box" id="commande-produit" action="{{ route('panier.ajouter', $produit['slug']) }}" method="post">
                                        @csrf
                                        <div class="input-box">
                                            <input class="quantity-spinner" type="text" value="1" name="quantite" aria-label="{{ __('Quantité') }}">
                                        </div>
                                        <div class="right">
                                            <div class="cart-box">
                                                <button class="btn-one" type="submit" @disabled(! $produit->enStock())>
                                                    <i class="icon-arrow"></i>
                                                    <span class="txt">{{ __('Ajouter au panier') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="product-wishlist-btn">
                                        @foreach ($galerie as $photo)
                                            <a href="{{ $photo['grande'] }}" class="lightbox-image" data-fancybox="produit" @unless ($loop->first) style="display: none" @endunless><span class="icon-resize"></span></a>
                                        @endforeach
                                        <a href="https://wa.me/?text={{ urlencode(route('boutique.show', $produit['slug'])) }}" target="_blank" rel="noopener"><span class="icon-favorite"></span></a>
                                    </div>
                                </div>
                                <div class="product-details__catagory-and-tag">
                                    <p class="product-details__catagory"> <span>{{ __('Catégorie :') }}</span> {{ $categorie }}</p>
                                    <p class="product-details__tag">
                                        <span>{{ __('Commande :') }}</span>
                                        <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener">WhatsApp,</a>
                                        <a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Product Details-->

        <!--Start Product Details Tab-->
        <section class="product-details-tab">
            <div class="container">
                <div class="product-details-tab__inner">
                    <div class="products-details-tab-box">

                        <div class="products-details__tab-btn">
                            <ul class="tabs-button-box clearfix">
                                <li data-tab="#product-type1" class="tab-btn-item active-btn-item">
                                    <div class="thumb-image-box">
                                        <p>{{ __('Description') }}</p>
                                    </div>
                                </li>
                                <li data-tab="#product-type2" class="tab-btn-item">
                                    <div class="thumb-image-box">
                                        <p>{{ __('Points forts') }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="tabs-content-box">
                            <!--Start Tab-->
                            <div class="tab-content-box-item tab-content-box-item-active" id="product-type1">
                                <div class="products-details-tab-content-box-item">
                                    <div class="products-details-single-content-box">
                                        @foreach ($produit->tr('description') ?? [] as $paragraphe)
                                            <p class="products-details-single-content-text">{{ $paragraphe }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--End Tab-->
                            <!--Start Tab-->
                            <div class="tab-content-box-item" id="product-type2">
                                <div class="products-details-tab-content-box-item">
                                    <div class="products-details-single-content-box">
                                        @foreach ($produit->tr('points_forts') ?? [] as $point)
                                            <p class="products-details-single-content-text">✓ {{ $point }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--End Tab-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Product Details Tab-->

        @if ($similaires->isNotEmpty())
        <!--Start Related Product-->
        <section class="related-product">
            <div class="container">
                <h2 class="related-product__title">{{ __('Autres produits') }}</h2>
                <div class="row">
                    @foreach ($similaires as $autre)
                    <!-- Start Single Shop Style1 -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        @include('boutique._carte', ['produit' => $autre])
                    </div>
                    <!-- End Single Shop Style1 -->
                    @endforeach
                </div>
            </div>
        </section>
        <!--End Related Product-->
        @endif

@endsection
