@extends('layouts.app')

@section('title', 'Page introuvable')

@section('content')

        <!--Start Error Page-->
        <section class="error-page">
            <div class="error-page__bg" style="background-image: url({{ asset('assets/images/backgrounds/error-page-bg.jpg') }});">
            </div>
            <div class="container">
                <div class="error-page__inner-content">
                    <div class="error-page__inner-content-top">
                        <div class="error-page__img">
                            <img src="{{ asset('assets/images/resources/error-page-img-1.png') }}" alt="">
                        </div>
                        <h2 class="error-page__title">Oups ! Cette page est introuvable...</h2>
                        <p class="error-page__text">La page que vous recherchez n’existe pas ou a été déplacée.
                            Utilisez le menu pour poursuivre votre visite <br> ou revenez à la page d’accueil du
                            REJEPPAT.</p>
                    </div>
                    <div class="error-page__menu-and-button">
                        <div class="breadcrumb-menu">
                            <ul class="clearfix">
                                <li><a href="{{ route('home') }}">Accueil</a></li>
                                <li><span class="icon-arrow"></span></li>
                                <li class="active">404</li>
                            </ul>
                        </div>
                        <div class="error-page__button">
                            <a class="btn-one" href="{{ route('home') }}">
                                <i class="icon-arrow"></i>
                                <span class="txt">Retour à l’accueil</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--End Error Page-->

@endsection
