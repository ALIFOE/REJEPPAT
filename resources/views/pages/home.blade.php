@extends('layouts.app', ['accueil' => true])

@section('title', 'Accueil')

@php
    // Contenu repris de https://rejeppat.org (pages Accueil, Qui sommes-nous, Nos offres & services,
    // Les Fermes Écoles, Nos programmes et projets, Boutique, Actualités & Événements).

    $slides = [
        [
            'image' => 'slide-1.jpg',
            'sub_title' => 'Plateforme des producteurs agricoles du Togo',
            'title' => 'Produits locaux <br> et coopératives connectées',
            'text' => 'Un réseau national de jeunes producteurs regroupés en coopératives <br> sur toute l’étendue du territoire togolais.',
            'button' => 'Découvrir le réseau',
            'link' => '#qui-sommes-nous',
        ],
        [
            'image' => 'slide-2.jpg',
            'sub_title' => 'Achetez local et frais',
            'title' => 'Directement <br> chez les producteurs',
            'text' => 'Des produits biologiques et naturels issus des coopératives <br> et des fermes écoles du réseau.',
            'button' => 'Accéder à la boutique',
            'link' => route('boutique.index'),
        ],
        [
            'image' => 'slide-3.jpg',
            'sub_title' => 'Jeunes producteurs engagés',
            'title' => 'Formation <br> et opportunités agricoles',
            'text' => 'Former les jeunes à l’agroécologie et à l’entrepreneuriat agricole <br> pour une agriculture durable et prospère.',
            'button' => 'Découvrir le réseau',
            'link' => route('fermes.index'),
        ],
    ];

    $identite = [
        [
            'image' => 'mission.jpg',
            'icon' => 'icon-seedling',
            'title' => 'Notre mission',
            'text' => 'Contribuer à l’épanouissement économique et social des organisations paysannes, des producteurs et professionnels agricoles membres pour un développement durable et inclusif au Togo.',
        ],
        [
            'image' => 'vision.jpg',
            'icon' => 'icon-eco-friendly',
            'title' => 'Notre vision',
            'text' => 'Un réseau crédible de jeunes agriculteurs, fortement ancré à la base, qui promeut une agriculture durable, faisant d’elle un métier viable et prospère.',
        ],
        [
            'image' => 'valeurs.jpg',
            'icon' => 'icon-handshake',
            'title' => 'Nos valeurs',
            'text' => 'Professionnalisme, Équité et Engagement au service des jeunes producteurs et des communautés rurales.',
        ],
    ];

    $temoignages = config('rejeppat.temoignages');

    $domaines = [
        ['icon' => 'icon-carrot.png', 'title' => 'Agriculture & élevage', 'text' => 'Formation, innovation et pratiques agricoles durables pour les jeunes producteurs.'],
        ['icon' => 'icon-corn.png', 'title' => 'Agroalimentaire', 'text' => 'Transformation locale, création de valeur et innovation.'],
        ['icon' => 'icon-beetroot.png', 'title' => 'Entrepreneuriat', 'text' => 'Accompagnement des jeunes et projets créateurs de valeur.'],
        ['icon' => 'icon-lemon.png', 'title' => 'Formation / santé', 'text' => 'Renforcer les compétences et le bien-être des communautés.'],
    ];

@endphp

@section('content')

        <!-- Main Slider Style4 -->
        <section class="main-slider-style4">
            <div class="main-slider-style4__shape-bg"
                style="background-image: url({{ asset('assets/images/shapes/section-bottom-shape.png') }});"></div>
            <div class="swiper-container banner-slider">
                <div class="swiper-wrapper">

                    @foreach ($slides as $slide)
                    <!-- Start Slide Item -->
                    <div class="swiper-slide">
                        <div class="main-slider-style4__inner">
                            <div class="image-layer"
                                style="background-image: url({{ asset('assets/images/rejeppat/slides/' . $slide['image']) }});">
                            </div>
                            <div class="container">
                                <div class="content-box">
                                    <div class="sub-title">
                                        <h5>{{ $slide['sub_title'] }}</h5>
                                    </div>
                                    <div class="big-title">
                                        <h2>{!! $slide['title'] !!}</h2>
                                    </div>
                                    <div class="shape-box">
                                        <img src="{{ asset('assets/images/shapes/main-slider-style4-shape-1.png') }}" alt="">
                                    </div>
                                    <div class="text-box">
                                        <p>{!! $slide['text'] !!}</p>
                                    </div>
                                    <div class="btn-box">
                                        <a class="btn-one" href="{{ $slide['link'] }}">
                                            <i class="icon-arrow"></i>
                                            <span class="txt">{{ $slide['button'] }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Slide Item -->
                    @endforeach

                </div>
            </div>

            <div class="main-slider-nav">
                <div class="main-slider-nav__control banner-slider-button-prev">
                    <span><i class="icon-arrow-right" aria-hidden="true"></i></span>
                </div>
                <div class="main-slider-nav__control banner-slider-button-next">
                    <span><i class="icon-arrow" aria-hidden="true"></i></span>
                </div>
            </div>

        </section>
        <!-- End Main Slider Style4 -->


        <!-- Start Products Style1 -->
        <section class="products-style1 products-style1--style2" id="identite">
            <div class="products-style1--style2__bg">
                <div class="section-top-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-top-shape.png') }});"></div>
            </div>
            <div class="container">
                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Notre identité</h4>
                    </div>
                    <h2 class="title-animation">Mission, Vision &amp; Valeurs</h2>
                    <div class="text">
                        <p>Une vision commune pour une agriculture durable <br>et portée par la jeunesse.</p>
                    </div>
                </div>
                <div class="row">

                    @foreach ($identite as $item)
                    <!-- Start Single Products Style1 -->
                    <div class="col-xl-4 col-lg-4">
                        <div class="single-products-style1">
                            <div class="single-products-style1__img">
                                <div class="single-products-style1__img-inner">
                                    <img src="{{ asset('assets/images/rejeppat/identite/' . $item['image']) }}" alt="{{ $item['title'] }}">
                                </div>
                                <div class="single-products-style1__img-overlay">
                                    <div class="single-products-style1__img-overlay-icon">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                    <div class="single-products-style1__img-overlay-title">
                                        <h3><a href="#qui-sommes-nous">{{ $item['title'] }}</a></h3>
                                    </div>
                                    <div class="single-products-style1__img-overlay-count">
                                        <h2>{{ sprintf('%02d', $loop->iteration) }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="single-products-style1__content">
                                <div class="single-products-style1__content-text">
                                    <p>{{ $item['text'] }}</p>
                                </div>
                                <div class="single-products-style1__content-btn">
                                    <a href="#qui-sommes-nous">
                                        <i class="one icon-arrow"></i>
                                        En savoir plus
                                        <i class="two icon-arrow"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Products Style1 -->
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Products Style1 -->


        <!-- Start About Style4 -->
        <section class="about-style4" id="qui-sommes-nous">
            <div class="about-style4__bg" style="background-image: url({{ asset('assets/images/backgrounds/about-v4__bg.png?v=vert') }});">
            </div>
            <div class="about-style4__shape">
                <img src="{{ asset('assets/images/shapes/about-v4__shape2.png') }}" alt="Shape">
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="about-style4__left">
                            <div class="about-style4__img-box">
                                <div class="about-style4__img-box-shape">
                                    <img src="{{ asset('assets/images/shapes/about-v4__shape1.png?v=vert') }}" alt="Shape">
                                </div>
                                <div class="about-style4__img">
                                    <img src="{{ asset('assets/images/rejeppat/about/about-1.png') }}" alt="Jeunes producteurs du REJEPPAT">
                                </div>
                                <div class="about-style4__top-left-fact">
                                    <div class="about-style4__top-left-fact-bg"
                                        style="background-image: url({{ asset('assets/images/backgrounds/activities-v1-fact__bg.png') }});">
                                    </div>
                                    <div class="about-style4__top-left-fact-inner">
                                        <div class="about-style1__top-left-fact-title">
                                            <h4>Engagés <br>depuis</h4>
                                        </div>
                                        <div class="about-style4__top-left-fact-shape">
                                            <img src="{{ asset('assets/images/shapes/activities-v1-fact__shape1.png') }}" alt="Shape">
                                        </div>
                                        <div class="about-style4__top-left-fact-count">
                                            <h2 class="odometer" data-count="2010"></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="about-style4__right">
                            <div class="sec-title sec-title-animation animation-style2">
                                <div class="sub-title">
                                    <div class="icon">
                                        <i class="icon-hat"></i>
                                    </div>
                                    <h4>Qui sommes-nous</h4>
                                </div>
                                <h2 class="title-animation">À propos du REJEPPAT-Togo</h2>
                            </div>
                            <p class="about-style4__text">Le Réseau des Jeunes Producteurs et Professionnels Agricoles du
                                Togo est une faîtière des organisations paysannes de jeunes créée le 10 juillet 2010 et
                                membre de la CTOP. Il rassemble 185 coopératives réparties sur tout le territoire
                                national et représentées par 5 sections régionales.</p>
                            <div class="about-style4__points-box">
                                <h5 class="about-style4__points-title">Notre engagement</h5>
                                <p class="about-style4__points-text">Promouvoir l’agroécologie et <br>
                                    l’installation des jeunes.</p>
                                <ul class="about-style4__points">
                                    <li>
                                        <div class="icon">
                                            <span class="icon-wheat"></span>
                                        </div>
                                        <div class="text">
                                            <p>Formation des jeunes dans <br> les fermes écoles.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span class="icon-wheat"></span>
                                        </div>
                                        <div class="text">
                                            <p>Organisation des jeunes en <br> coopératives agricoles.</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="icon">
                                            <span class="icon-wheat"></span>
                                        </div>
                                        <div class="text">
                                            <p>Transformation et marchés des <br> produits agroécologiques.</p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="about-style4__crops-harvested-box">
                                    <div class="about-style4__crops-harvested-shape"
                                        style="background-image: url({{ asset('assets/images/shapes/about-style4-crops-harvested-shape-1.png?v=vert') }});">
                                    </div>
                                    <div class="about-style4__crops-harvested-icon">
                                        <span class="icon-tractor"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span><span
                                                class="path6"></span><span class="path7"></span></span>
                                    </div>
                                    <div class="about-style4__crops-harvested-text">
                                        <h5>Producteurs membres</h5>
                                    </div>
                                    <div class="about-style4__crops-harvested-count">
                                        <h2 class="odometer" data-count="15751"></h2>
                                        <span class="last">+</span>
                                    </div>
                                </div>
                            </div>
                            <div class="about-style4__btn-and-video">
                                <div class="btn-box">
                                    <a class="btn-one" href="#reseau">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">En savoir plus</span>
                                    </a>
                                </div>
                                <div class="about-style4__video-box">
                                    <a class="video-popup" title="Vidéo REJEPPAT"
                                        href="{{ config('rejeppat.social.youtube') }}">
                                        <span class="fas fa-play"></span>
                                    </a>
                                    <p class="about-style4__video-title">Notre réseau, <br> notre histoire.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End About Style4 -->

        <!-- Start Shop Style1 -->
        <section class="shop-style1 shop-style2" id="boutique">
            <div class="container">
                <div class="sec-title text-left sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Produits les plus vendus</h4>
                    </div>
                    <h2 class="title-animation">Achetez local et frais</h2>
                </div>

                <div class="owl-carousel owl-theme thm-owl__carousel shop-style2-carousel owl-nav-style-one"
                    data-owl-options='{
                    "loop": true,
                    "autoplay": false,
                    "margin": 30,
                    "nav": true,
                    "dots": false,
                    "smartSpeed": 500,
                    "autoplayTimeout": 10000,
                    "navText": ["<span class=\"left icon-arrow-right\"></span>","<span class=\"icon-arrow\"></span>"],
                    "responsive": {
                            "0": {
                                "items": 1
                            },
                            "768": {
                                "items": 2
                            },
                            "992": {
                                "items": 3
                            },
                            "1200": {
                                "items": 4
                            }
                        }
                    }'>

                    @foreach ($produits as $produit)
                    <!-- Start Single Shop Style1 -->
                    <div class="item">
                        @include('boutique._carte', ['produit' => $produit])
                    </div>
                    <!-- End Single Shop Style1 -->
                    @endforeach

                </div>

                <div class="shop-style1__btn text-center">
                    <a href="{{ route('boutique.index') }}">
                        <i class="icon-arrow"></i>
                        Voir tous les produits
                    </a>
                </div>

            </div>
        </section>
        <!-- End Shop Style1 -->

        <!-- Start Why Choose Style2 -->
        <section class="why-choose-style2" id="programmes-projets">
            <div class="why-choose-style2__bg"
                style="background-image: url({{ asset('assets/images/backgrounds/why-choose__v2-bg.jpg') }});">
                <div class="section-top-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-top-shape.png') }});"></div>
                <div class="section-bottom-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-bottom-shape.png') }});"></div>
            </div>
            <div class="container">
                <div class="sec-title withtext white text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>REJEPPAT</h4>
                    </div>
                    <h2 class="title-animation">Nos Programmes &amp; Projets</h2>
                    <div class="text">
                        <p>Des initiatives au service des jeunes agriculteurs, des femmes rurales <br>et de la protection de l’environnement.</p>
                    </div>
                </div>
                <div class="row">

                    @foreach ($projets as $projet)
                    <!-- Start Single Why Choose Style2 -->
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="single-why-choose-style2">
                            <div class="single-why-choose-style2__bg"
                                style="background-image: url({{ asset('assets/images/backgrounds/why-choose__v2-bg2.png?v=vert') }});">
                            </div>
                            <div class="single-why-choose-style2__content">
                                <div class="single-why-choose-style2__icon">
                                    <div class="single-why-choose-style2__icon-bg"
                                        style="background-image: url({{ asset('assets/images/shapes/single-why-choose-style2-icon-bg-shape-1.png?v=vert') }});">
                                    </div>
                                    <i class="{{ $projet['icone'] }}"></i>
                                </div>
                                <div class="single-why-choose-style2__title">
                                    <h3><a href="{{ route('projets.show', $projet['slug']) }}">{{ $projet['titre_court'] }}</a></h3>
                                    <p>{{ $projet['resume'] }}</p>
                                </div>
                                <div class="single-why-choose-style2__btn">
                                    <a href="{{ route('projets.show', $projet['slug']) }}">
                                        <i class="icon-arrow"></i>
                                        Lire plus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Why Choose Style2 -->
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Why Choose Style2 -->

        <!-- Start Farmed Style1 -->
        <section class="farmed-style1" id="offres-services">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6">
                        <div class="farmed-style1__left">
                            <div class="sec-title withtext text-left sec-title-animation animation-style2">
                                <div class="sub-title">
                                    <div class="icon">
                                        <i class="icon-hat"></i>
                                    </div>
                                    <h4>Nos offres &amp; services</h4>
                                </div>
                                <h2 class="title-animation">Nos domaines d’intervention</h2>
                                <div class="text">
                                    <p>À travers ses différents domaines d’intervention, le REJEPPAT accompagne les
                                        jeunes et les acteurs ruraux dans le développement de leurs activités, le
                                        renforcement de leurs compétences et la création d’opportunités durables.</p>
                                </div>
                            </div>
                            <div class="farmed-style1__img">
                                <img src="{{ asset('assets/images/rejeppat/domaines/domaines-1.jpg') }}" alt="Formation pratique en ferme école">
                            </div>
                            <div class="farmed-style1__points-box">
                                <ul class="farmed-style1__points">
                                    @foreach (['Formation &amp; <br> renforcement', 'Accompagnement <br> des initiatives', 'Mise en réseau <br> &amp; partenariats'] as $point)
                                    <li>
                                        <div class="icon">
                                            <span class="icon-check-mark"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span><span
                                                    class="path4"></span><span class="path5"></span><span
                                                    class="path6"></span><span class="path7"></span></span>
                                        </div>
                                        <div class="text">
                                            <h4>{!! $point !!}</h4>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6">
                        <div class="farmed-style1__right">
                            <div class="row">
                                @foreach ($domaines as $domaine)
                                <!-- Start Farmed Style1 Single -->
                                <div class="col-xl-6 col-lg-6 col-md-6">
                                    <div class="farmed-style1__single">
                                        <div class="farmed-style1__icon">
                                            <img src="{{ asset('assets/images/icon/' . $domaine['icon']) }}" alt="">
                                        </div>
                                        <h3 class="farmed-style1__title"><a href="{{ route('offres') }}">{{ $domaine['title'] }}</a></h3>
                                        <p class="farmed-style1__text">{{ $domaine['text'] }}</p>
                                        <div class="farmed-style1__arrow">
                                            <a href="{{ route('offres') }}"><span class="icon-arrow"></span></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Farmed Style1 Single -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Farmed Style1 -->

        <!-- Start Production Progress -->
        <section class="production-progress" id="reseau">
            <div class="production-progress__bg"
                style="background-image: url({{ asset('assets/images/backgrounds/production-progress-v1__bg-2.jpg') }});">
                <div class="section-top-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-top-shape.png') }});"></div>
                <div class="section-bottom-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-bottom-shape.png') }});"></div>
            </div>
            <div class="production-progress__big-title">Notre<br> Réseau</div>
            <div class="container">
                <div class="production-progress__inner">
                    <div class="production-progress__round-box">
                        <div class="production-progress__round-box-bg"
                            style="background-image: url({{ asset('assets/images/shapes/production-progress-bg.png?v=vert') }});"></div>
                        <div class="inner-title">
                            <h3>Notre<br> réseau en<br> chiffres</h3>
                        </div>
                        <div class="production-progress__content">
                            <ul>
                                <li>
                                    <div class="left">
                                        <div class="odometer-counting">
                                            <h2 class="odometer" data-count="44">00</h2>
                                            <span>%</span>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="title-box">
                                            <h5>Femmes parmi les membres actifs</h5>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="left">
                                        <div class="odometer-counting">
                                            <h2 class="odometer" data-count="60">00</h2>
                                            <span>%</span>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="title-box">
                                            <h5>Jeunes femmes par coopérative mixte</h5>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="left">
                                        <div class="odometer-counting">
                                            <h2 class="odometer" data-count="38">00</h2>
                                            <span>%</span>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <div class="title-box">
                                            <h5>Coopératives féminines</h5>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Production Progress -->


        <!-- Start Team Style1 -->
        <section class="team-style1" id="fermes-ecoles">
            <div class="container">
                <div class="team-style1__top">
                    <div class="sec-title sec-title-animation animation-style2">
                        <div class="sub-title">
                            <div class="icon">
                                <i class="icon-hat"></i>
                            </div>
                            <h4>Fermes Écoles</h4>
                        </div>
                        <h2 class="title-animation">Apprendre par la pratique</h2>
                    </div>
                    <div class="team-style1__btn">
                        <a href="{{ route('fermes.index') }}">
                            <i class="icon-arrow"></i>
                            Toutes les fermes écoles
                        </a>
                    </div>
                </div>
                <div class="row">

                    @foreach ($fermes as $ferme)
                        @include('fermes._carte', ['ferme' => $ferme])
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Team Style1 -->


        <!-- Start Testimonials Style3 -->
        <section class="testimonials-style3">
            <div class="testimonials-style3__bg"
                style="background-image: url({{ asset('assets/images/backgrounds/testimonials-v3__bg.jpg') }});">
                <div class="section-top-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-top-shape.png') }});"></div>
                <div class="section-bottom-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-bottom-shape.png') }});"></div>
            </div>
            <div class="container">
                <div class="sec-title withtext text-center white sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Témoignages</h4>
                    </div>
                    <h2 class="title-animation">Ce que nos bénéficiaires disent</h2>
                    <div class="text">
                        <p>Des jeunes producteurs accompagnés par le REJEPPAT <br>partagent leur expérience.</p>
                    </div>
                </div>
                <div class="row">

                    @foreach ($temoignages as $temoignage)
                    <!-- Start Single Testimonials Style2 -->
                    <div class="col-xl-4 col-lg-4">
                        <div class="single-testimonials-style2 {{ $loop->odd ? 'single-testimonials-style2--style2' : '' }}">
                            <div class="single-testimonials-style2__img">
                                <div class="single-testimonials-style2__img-bg"
                                    style="background-image: url({{ asset('assets/images/backgrounds/testimonials-v2-img__bg.png') }});">
                                </div>
                                <div class="single-testimonials-style2__img-inner">
                                    <img src="{{ asset('assets/images/rejeppat/temoignages/' . $temoignage['image'] . '?v=vert') }}" alt="{{ $temoignage['nom'] }}">
                                </div>
                            </div>
                            <div class="single-testimonials-style2__content">
                                <div class="single-testimonials-style2__top">
                                    <div class="single-testimonials-style2__top-inner">
                                        <div class="single-testimonials-style2__top-icon">
                                            <i class="icon-dialog"></i>
                                        </div>
                                        <div class="single-testimonials-style2__top-right">
                                            <div class="single-testimonials-style2__top-right-rating">
                                                <i class="icon-rate-star-button"></i>
                                            </div>
                                            <div class="single-testimonials-style2__top-right-text">
                                                <p>Bénéficiaire</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-testimonials-style2__top-text">
                                        <p>“{{ $temoignage['texte'] }}”</p>
                                    </div>
                                </div>
                                <div class="single-testimonials-style2__bottom">
                                    <div class="single-testimonials-style2__bottom-author">
                                        <h3>{{ $temoignage['nom'] }}</h3>
                                        <p>{{ $temoignage['role'] }}.</p>
                                    </div>
                                    <div class="single-testimonials-style2__bottom-day">
                                        <p>Togo</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Testimonials Style2 -->
                    @endforeach

                </div>
                <div class="testimonials-style3__btn text-center">
                    <a href="{{ route('contact') }}">
                        <i class="icon-arrow"></i>
                        Nous contacter
                    </a>
                </div>
            </div>
        </section>
        <!-- End Testimonials Style3 -->


        <!-- Start Banner Style1 -->
        <section class="banner-style1">
            <div class="banner-style1__bg" style="background-image: url({{ asset('assets/images/backgrounds/banner-v1__bg.jpg?v=vert') }});">
                <div class="section-bottom-shape"
                    style="background-image: url({{ asset('assets/images/shapes/section-bottom-shape.png') }});"></div>
            </div>
            <div class="container">
                <div class="banner-style1__content">
                    <div class="banner-style1__shape1">
                        <img src="{{ asset('assets/images/shapes/banner-v1__shape1.png') }}" alt="Shape">
                    </div>
                    <div class="banner-style1__shape2">
                        <img src="{{ asset('assets/images/shapes/banner-v1__shape5.png') }}" alt="Shape">
                    </div>
                    <div class="banner-style1__shape3">
                        <img src="{{ asset('assets/images/shapes/banner-v1__shape2.png') }}" alt="Shape">
                    </div>
                    <div class="banner-style1__shape4">
                        <img src="{{ asset('assets/images/shapes/banner-v1__shape4.png') }}" alt="Shape">
                    </div>
                    <div class="banner-style1__shape5">
                        <img src="{{ asset('assets/images/shapes/banner-v1__shape3.png') }}" alt="Shape">
                    </div>

                    <div class="banner-style1__content-inner">
                        <div class="banner-style1__curved-top">
                            Produits locaux, biologiques et naturels
                        </div>
                        <div class="banner-style1__curved-bottom">
                            Produits locaux, biologiques et naturels
                        </div>
                        <div class="banner-style1__sub-title">
                            <h5>Bienvenue au REJEPPAT</h5>
                        </div>
                        <div class="banner-style1__big-title">
                            <h2>Achetez local</h2>
                        </div>
                        <div class="banner-style1__title">
                            <h3>Directement chez les producteurs...</h3>
                        </div>
                        <div class="banner-style1__btn">
                            <a class="btn-one" href="{{ route('boutique.index') }}">
                                <i class="icon-arrow"></i>
                                <span class="txt">Accéder à la boutique</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- End Banner Style1 -->


        <!-- Start Blog Style4 -->
        @if ($actualites->isNotEmpty())
        <section class="blog-style4" id="actualites">
            <div class="container">
                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Information</h4>
                    </div>
                    <h2 class="title-animation">Actualités &amp; Événements</h2>
                    <div class="text">
                        <p>Retrouvez ici nos actualités et événements.</p>
                    </div>
                </div>
                <div class="row">
                    @php
                        $une = $actualites->first();
                        $categoriesNoms = \App\Support\Contenu::CATEGORIES_ACTUALITES;
                    @endphp

                    <!-- Start Blog Style4 Left -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="blog-style4__left">
                            <div class="single-blog-style3">
                                <div class="single-blog-style3__img">
                                    <div class="single-blog-style3__img-inner">
                                        <img src="{{ $une->visuel('-accueil-grand') }}" alt="{{ $une['title'] }}">
                                        <div class="single-blog-style3__img-icon">
                                            <a class="lightbox-image" data-fancybox="gallery"
                                                href="{{ $une->visuel('-detail') }}">
                                                <i class="icon-resize"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="single-blog-style3__img-top">
                                        <div class="single-blog-style3__img-category">
                                            <h6>{{ collect($une['categories'])->map(fn ($c) => $categoriesNoms[$c])->implode(', ') }}</h6>
                                        </div><br>
                                        <ul class="single-blog-style3__content-meta">
                                            <li>
                                                <i class="icon-following"></i>
                                                <p>REJEPPAT</p>
                                            </li>
                                            <li>
                                                <i class="fa fa-solid fa-calendar-day"></i>
                                                <p>{{ \App\Support\Contenu::date($une['date']) }}</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="single-blog-style3__img-content">
                                        <div class="single-blog-style3__img-title">
                                            <h3>
                                                <a href="{{ route('actualites.show', $une['slug']) }}"><span>{{ $une['title'] }}</span></a>
                                            </h3>
                                        </div>
                                        <div class="single-blog-style3__img-btn">
                                            <a href="{{ route('actualites.show', $une['slug']) }}">
                                                <i class="fa fa-regular fa-clock"></i>
                                                {{ \App\Support\Contenu::lecture($une['content']) }} min de lecture
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Start Blog Style3 -->
                    </div>
                    <!-- Start Blog Style4 Left -->

                    <!-- Start Blog Style4 Right -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="blog-style4__right">
                            @foreach ($actualites->skip(1) as $article)
                            <!--Start Blog Style2 Single-->
                            <div class="blog-style2__single">
                                <div class="blog-style2__single-inner">
                                    <div class="blog-style2__single-category">
                                        <h6>{{ $categoriesNoms[$article['categories'][0]] }}</h6>
                                    </div>
                                    <div class="blog-style2__single-content">
                                        <ul class="blog-style2__meta">
                                            <li>
                                                <i class="icon-following"></i>
                                                <p>REJEPPAT</p>
                                            </li>
                                            <li>
                                                <i class="fa fa-solid fa-calendar-day"></i>
                                                <p>{{ \App\Support\Contenu::date($article['date']) }}</p>
                                            </li>
                                        </ul>
                                        <div class="blog-style2__title">
                                            <h3><a href="{{ route('actualites.show', $article['slug']) }}">{{ \Illuminate\Support\Str::limit($article['title'], 70) }}</a></h3>
                                        </div>
                                        <div class="blog-style2__btn">
                                            <a href="{{ route('actualites.show', $article['slug']) }}">
                                                <i class="fa fa-regular fa-clock"></i>
                                                {{ \App\Support\Contenu::lecture($article['content']) }} min de lecture
                                            </a>
                                        </div>
                                    </div>
                                    <div class="blog-style2__single-img">
                                        <img src="{{ $article->visuel('-accueil') }}" alt="{{ $article['title'] }}">
                                        <div class="blog-style2__single-img-overlay-icon">
                                            <a class="lightbox-image" data-fancybox="gallery"
                                                href="{{ $article->visuel('-detail') }}">
                                                <i class="icon-resize"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End Blog Style2 Single-->
                            @endforeach
                        </div>
                    </div>
                    <!-- End Blog Style4 Right -->

                </div>
                <div class="blog-style2__btn2 text-center">
                    <a href="{{ route('actualites.index') }}">
                        <i class="icon-arrow"></i>
                        Voir plus d’articles
                    </a>
                </div>
            </div>
        </section>
        @endif
        <!-- End Blog Style4 -->

@endsection
