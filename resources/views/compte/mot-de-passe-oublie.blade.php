@extends('layouts.app')

@section('title', __('Mot de passe oublié'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Mot de passe oublié'),
            'texte' => __('Recevez par e-mail un lien pour choisir un nouveau mot de passe.'),
            'image' => 'fermes.jpg',
            'liens' => [__('Connexion') => route('connexion')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-key"></i></span>
                        <h2>{{ __('Mot de passe oublié ?') }}</h2>
                        <p>{{ __('Indiquez l’adresse e-mail de votre compte : nous vous enverrons un lien pour en choisir un nouveau.') }}</p>
                    </div>

                    @if (session('succes'))
                        <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                    @endif

                    <form action="{{ route('password.email') }}" method="post" class="compte-form">
                        @csrf
                        @include('compte._champ', ['nom' => 'email', 'label' => __('Adresse e-mail'), 'type' => 'email', 'requis' => true, 'attributs' => 'autocomplete="email" autofocus'])

                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">{{ __('Envoyer le lien') }}</span>
                        </button>
                    </form>

                    <p class="compte-carte__pied">
                        <a href="{{ route('connexion') }}"><i class="fas fa-arrow-left"></i> {{ __('Retour à la connexion') }}</a>
                    </p>
                </div>
            </div>
        </section>

@endsection
