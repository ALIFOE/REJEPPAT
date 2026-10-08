@extends('layouts.app')

@section('title', 'Nos offres & services')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Nos offres & services',
            'texte' => 'Des services adaptés aux jeunes producteurs et aux acteurs du monde rural.',
            'image' => 'offres.jpg',
        ])


        <!--Start Service One-->
        <section class="service-one">
            <div class="service-one__shape">
                <img src="{{ asset('assets/images/shapes/service-one__shape.png') }}" alt="Shape">
            </div>
            <div class="container">
                <div class="row">

                    <div class="col-xl-5 col-lg-5">
                        <div class="service-one__img">
                            <img src="{{ asset('assets/images/rejeppat/offres/service-one.jpg') }}" alt="Jeunes producteurs en formation">
                            <div class="service-one__img-percent">
                                <img src="{{ asset('assets/images/service/service-one-percent.png') }}" alt="Percent">
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-7">
                        <div class="service-one__content">
                            <div class="sec-title-two sec-title-animation animation-style2">
                                <div class="sub-title">
                                    <div class="icon">
                                        <img src="{{ asset('assets/images/icon/sec-title-two-icon.png') }}" alt="">
                                    </div>
                                    <h4>Notre accompagnement</h4>
                                </div>
                                <h2 class="title-animation">Des services pour renforcer les capacités et soutenir les initiatives locales</h2>
                            </div>
                            <div class="service-one__content-text">
                                <p>
                                    Le REJEPPAT propose des services et des solutions adaptés aux besoins des jeunes
                                    producteurs, des organisations et des acteurs du monde rural.
                                </p>
                                <p>
                                    À travers ses différents domaines d’intervention, le REJEPPAT accompagne les jeunes
                                    et les acteurs ruraux dans le développement de leurs activités, le renforcement de
                                    leurs compétences et la création d’opportunités durables.
                                </p>
                            </div>
                            <div class="service-one__content-btn">
                                <a class="btn-one" href="{{ route('demande') }}">
                                    <i class="icon-arrow"></i>
                                    <span class="txt">Faire une demande de service</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!--End Service One-->


        <!-- Start Grown with Care -->
        <section class="grown-with-care">
            <div class="container">
                <div class="sec-title-two sec-title-animation animation-style2 text-center">
                    <div class="sub-title">
                        <div class="icon">
                            <img src="{{ asset('assets/images/icon/sec-title-two-icon.png') }}" alt="">
                        </div>
                        <h4>Nos domaines d’intervention</h4>
                    </div>
                    <h2 class="title-animation">Nos principales offres et services</h2>
                </div>

                <div class="row">

                    @foreach (\App\Support\Contenu::offres() as $offre)
                    <!-- Start Single Grown with Care -->
                    <div class="col-xl-4">
                        <div class="single-grown-with-care">
                            <div class="single-grown-with-care__icon">
                                <img src="{{ asset('assets/images/icon/grown-with-care__icon-' . $loop->iteration . '.png') }}" alt="Icon">
                            </div>
                            <div class="single-grown-with-care__content">
                                <h3><a href="{{ route('demande') }}">{{ $offre['titre'] }}</a></h3>
                                <p>{{ $offre['texte'] }}</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Grown with Care -->
                    @endforeach

                </div>

                <div class="grown-with-care__btn">
                    <a class="btn-one" href="{{ route('demande') }}">
                        <i class="icon-arrow"></i>
                        <span class="txt">Besoin d’un service du REJEPPAT ?</span>
                    </a>
                </div>

            </div>
        </section>
        <!-- End Grown with Care -->


        <!-- Start Service Two -->
        <section class="service-two">
            <div class="container">
                <div class="service-two__big-title">
                    <h2>REJEPPAT</h2>
                </div>
                <div class="row">

                    @foreach (config('rejeppat.domaines') as $domaine)
                    <!-- Start Single Service Two -->
                    <div class="col-xl-3">
                        <div class="single-service-two">
                            <div class="single-service-two__img">
                                <img src="{{ asset('assets/images/rejeppat/offres/domaine-' . $domaine['slug'] . '.jpg') }}" alt="{{ $domaine['titre'] }}">
                                <div class="single-service-two__img-category">
                                    <h6>{{ $domaine['titre'] }}</h6>
                                    <div class="single-service-two__img-category-img">
                                        <img src="{{ asset('assets/images/shapes/single-service-two__shape.png') }}" alt="Shape">
                                    </div>
                                </div>
                                <div class="single-service-two__img-overlay-title">
                                    <h2>{{ $domaine['court'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Service Two -->
                    @endforeach

                </div>
            </div>
        </section>
        <!-- End Service Two -->


        @include('partials.temoignages-partenaires')


        <!-- Start Project Style1 -->
        <section class="project-style1">
            <div class="container">
                <div class="sec-title withtext text-center sec-title-animation animation-style2">
                    <div class="sub-title">
                        <div class="icon">
                            <i class="icon-hat"></i>
                        </div>
                        <h4>Projets</h4>
                    </div>
                    <h2 class="title-animation">Nos Programmes &amp; Projets</h2>
                    <div class="text">
                        <p>Des initiatives au service des jeunes agriculteurs, des femmes rurales <br>et de la protection de l’environnement.</p>
                    </div>
                </div>
                <div class="row">

                    @foreach ($projets as $projet)
                    @php
                        $grand = $loop->index < 2;
                        $lien = route('projets.show', $projet['slug']);
                        $categorie = config('projets.categories')[$projet['categories'][0]];
                    @endphp
                    <!-- Start Single Project Style1 -->
                    <div class="{{ $grand ? 'col-xl-6' : 'col-xl-4' }} col-lg-6 col-md-6">
                        <div class="single-project-style1">
                            <div class="single-project-style1__img">
                                <img src="{{ $projet->visuel($grand ? '-large' : '-small') }}" alt="{{ $projet['titre_court'] }}">
                                <div class="single-project-style1__img-category">
                                    <span>{{ $categorie }}</span>
                                </div>
                                <div class="single-project-style1__img-title">
                                    <h3><a href="{{ $lien }}">{{ $projet['titre_court'] }}</a></h3>
                                </div>
                                <div class="single-project-style1__img-title-overlay">
                                    <h3><a href="{{ $lien }}">{{ $projet['titre_court'] }}</a></h3>
                                    <p>{{ $projet['resume'] }}</p>
                                </div>
                                <div class="single-project-style1__img-icon">
                                    <a href="{{ $lien }}">
                                        <i class="icon-arrow"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Project Style1 -->
                    @endforeach

                </div>
                <div class="project-style1__btn text-center">
                    <a href="{{ route('projets.index') }}">
                        <i class="icon-arrow"></i>
                        Voir tous les projets
                    </a>
                </div>
            </div>
        </section>
        <!-- End Project Style1 -->

@endsection
