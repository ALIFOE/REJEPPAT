@extends('layouts.app')

@section('title', 'Nos Programmes & Projets')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Nos Programmes & Projets',
            'texte' => 'Des initiatives au service des jeunes, des femmes rurales et de l’environnement.',
            'image' => 'projets.jpg',
        ])


        <!-- Start Project Page Two -->
        <section class="project-page-two">
            <div class="container">
                <div class="project-page-two__top">
                    <div class="sec-title sec-title-animation animation-style2 text-center">
                        <div class="sub-title">
                            <div class="icon">
                                <i class="icon-hat"></i>
                            </div>
                            <h4>REJEPPAT</h4>
                        </div>
                        <h2 class="title-animation">Nos Programmes &amp; Projets</h2>
                    </div>
                    <div class="project-menu-box">
                        <ul class="project-filter text-center clearfix post-filter has-dynamic-filters-counter">
                            <li data-filter=".filter-item" class="active"><span class="filter-text">Tous les projets</span>
                            </li>
                            @foreach ($categories as $cle => $nom)
                                <li data-filter=".{{ $cle }}"><span class="filter-text">{{ $nom }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="row filter-layout masonary-layout">

                    @foreach ($projets as $projet)
                    @php
                        $lien = route('projets.show', $projet['slug']);
                    @endphp
                    <!-- Start Single Project Page Two -->
                    <div class="col-xl-4 col-lg-6 col-md-6 filter-item {{ implode(' ', $projet['categories']) }}">
                        <div class="single-project-page-two">
                            <div class="single-project-page-two__img">
                                <img src="{{ asset('assets/images/rejeppat/projets/' . $projet['slug'] . '.jpg') }}" alt="{{ $projet['titre'] }}">
                                <div class="single-project-page-two__img-icon">
                                    <a href="{{ $lien }}">
                                        <i class="icon-arrow"></i>
                                    </a>
                                </div>
                                <div class="single-project-page-two__img-title">
                                    <h3><a href="{{ $lien }}">{{ $projet['titre_court'] }}</a></h3>
                                </div>
                                <div class="single-project-page-two__img-title-overlay">
                                    <h3><a href="{{ $lien }}">{{ $projet['titre_court'] }}</a></h3>
                                    <p>{{ $projet['resume'] }}</p>
                                </div>
                                <div class="single-project-page-two__img-shape1">
                                    <img src="{{ asset('assets/images/shapes/single-project-page-one__shape1.png') }}" alt="Shape">
                                </div>
                                <div class="single-project-page-two__img-shape2">
                                    <img src="{{ asset('assets/images/shapes/single-project-page-one__shape2.png') }}" alt="Shape">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Project Page Two -->
                    @endforeach

                </div>
                <div class="project-page-two__btn">
                    <a class="btn-one" href="{{ route('demande') }}">
                        <i class="icon-arrow"></i>
                        <span class="txt">Faire une demande de service</span>
                    </a>
                </div>
            </div>
        </section>
        <!-- End Project Page Two -->

@endsection
