@extends('layouts.app')

@section('title', 'Ferme école ' . $ferme['nom'])

@php
    $modules = $ferme['modules'];
    $moitie = (int) ceil(count($modules) / 2);
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Ferme école ' . $ferme['nom'],
            'texte' => $ferme['localisation'] ?? 'Une ferme école du réseau REJEPPAT.',
            'image' => 'fermes.jpg',
            'liens' => ['Fermes Écoles' => route('fermes.index')],
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
                                    <img src="{{ asset('assets/images/rejeppat/fermes/' . $ferme['slug'] . '-top.jpg') }}" alt="Ferme école {{ $ferme['nom'] }}">
                                </div>
                                <div class="services-details__content-top-text">
                                    <h2>Ferme école {{ $ferme['nom'] }}</h2>
                                    <p>
                                        La ferme école {{ $ferme['nom'] }}@if ($ferme['localisation']) ({{ $ferme['localisation'] }})@endif
                                        fait partie du réseau des fermes écoles du REJEPPAT : un espace de formation,
                                        d’innovation et d’expérimentation où les participants sont formés dans des
                                        conditions réelles de production.
                                    </p>
                                    <p>Spécialité : <strong>{{ $ferme['specialite'] }}</strong>.</p>
                                </div>
                            </div>

                            <div class="services-details__content-list">
                                <div class="row">
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => 'Domaines de formation', 'elements' => array_slice($modules, 0, $moitie)])
                                    </div>
                                    @if (count($modules) > 1)
                                    <div class="col-xl-6">
                                        @include('partials.check-list', ['titre' => 'Et aussi', 'elements' => array_slice($modules, $moitie)])
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="services-details__faq">
                                <div class="services-details__faq-title">
                                    <h2>Foire aux questions</h2>
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
