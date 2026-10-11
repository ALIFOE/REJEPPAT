@extends('layouts.app')

@section('title', __('Les Fermes Écoles'))

@php
    $presentation = config('fermes.presentation');
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Les Fermes Écoles'),
            'texte' => __($presentation['titre']),
            'image' => 'fermes.jpg',
        ])


        <!--Start Services Details-->
        <section class="services-details">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4 col-lg-5 order222">
                        @include('fermes._sidebar', ['actif' => null])
                    </div>

                    <!-- Start Service Details Content -->
                    <div class="col-xl-8 col-lg-7 order111">
                        <div class="services-details__content">

                            <div class="services-details__content-top">
                                <div class="services-details__content-top-img">
                                    <img src="{{ asset('assets/images/rejeppat/fermes/page-top.jpg') }}" alt="{{ __('Formation pratique dans une ferme école du REJEPPAT') }}">
                                </div>
                                <div class="services-details__content-top-text">
                                    <h2>{{ __($presentation['titre']) }}</h2>
                                    @foreach ($presentation['paragraphes'] as $paragraphe)
                                        <p>{{ __($paragraphe) }}</p>
                                    @endforeach
                                </div>
                            </div>

                            @include('fermes._carte-togo', ['fermes' => $fermes])

                            <div class="services-details__content-process">
                                <div class="services-details__content-process-text">
                                    <h3>{{ __('Une formation par la pratique') }}</h3>
                                    <p>{{ __($presentation['conclusion']) }}</p>
                                </div>
                                <div class="row">
                                    @foreach ([1, 2, 3] as $numero)
                                    <div class="col-xl-4">
                                        <div class="services-details__process-single">
                                            @if ($numero < 3)
                                            <div class="services-details__process-single-shape">
                                                <img src="{{ asset('assets/images/shapes/services-details__process-shape' . $numero . '.png') }}?v=vert"
                                                    alt="Image">
                                            </div>
                                            @endif
                                            <div class="services-details__process-img">
                                                <img src="{{ \App\Support\Contenu::asset('assets/images/rejeppat/fermes/process-' . $numero . '.jpg') }}"
                                                    alt="{{ __('Ferme école du REJEPPAT') }}">
                                                <div class="services-details__process-img-count">
                                                    <div class="services-details__process-img-count-inner">
                                                        <span>{{ sprintf('%02d', $numero) }}</span>
                                                    </div>
                                                </div>
                                                <div class="services-details__process-img-shape1">
                                                    <img src="{{ asset('assets/images/shapes/single-project-page-one__shape1.png') }}"
                                                        alt="Image">
                                                </div>
                                                <div class="services-details__process-img-shape2">
                                                    <img src="{{ asset('assets/images/shapes/single-project-page-one__shape2.png') }}"
                                                        alt="Image">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="services-details__content-list">
                                <div class="row">
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => __('Domaines de formation'), 'elements' => $presentation['domaines']])
                                    </div>
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => __('À qui s’adressent les formations ?'), 'elements' => $presentation['publics']])
                                    </div>
                                </div>
                            </div>

                            <div class="services-details__faq">
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


        <!-- Start Team Style1 -->
        <section class="team-style1">
            <div class="container">
                <div class="team-style1__top">
                    <div class="sec-title sec-title-animation animation-style2">
                        <div class="sub-title">
                            <div class="icon">
                                <i class="icon-hat"></i>
                            </div>
                            <h4>{{ __('Notre réseau') }}</h4>
                        </div>
                        <h2 class="title-animation">{{ __('Nos fermes écoles') }}</h2>
                    </div>
                    <div class="team-style1__btn">
                        <a href="{{ route('contact') }}">
                            <i class="icon-arrow"></i>
                            {{ __('S’inscrire à une formation') }}
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

@endsection
