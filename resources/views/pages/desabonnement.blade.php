@extends('layouts.app')

@section('title', __('Désabonnement'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Newsletter'),
            'texte' => __('Gestion de votre abonnement.'),
            'image' => 'contact.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="far fa-envelope-open"></i></span>
                        <h2>{{ __('Vous êtes désabonné(e)') }}</h2>
                        <p>{{ __('L’adresse :email ne recevra plus la newsletter du REJEPPAT.', ['email' => $abonne->email]) }}</p>
                    </div>
                    <p class="text-center">{{ __('Vous pouvez vous réinscrire à tout moment depuis le bas de chaque page du site.') }}</p>
                    <p class="text-center" style="margin: 25px 0 0">
                        <a href="{{ route('home') }}" class="btn-one"><i class="icon-arrow"></i><span class="txt">{{ __('Retour à l’accueil') }}</span></a>
                    </p>
                </div>
            </div>
        </section>

@endsection
