@extends('layouts.app')

@section('title', __('Nouveau mot de passe'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Nouveau mot de passe'),
            'texte' => __('Choisissez un nouveau mot de passe pour votre compte.'),
            'image' => 'fermes.jpg',
            'liens' => [__('Connexion') => route('connexion')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-lock"></i></span>
                        <h2>{{ __('Choisir un nouveau mot de passe') }}</h2>
                        <p>{{ __('Il doit contenir au moins 8 caractères.') }}</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="post" class="compte-form">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        @include('compte._champ', ['nom' => 'email', 'label' => __('Adresse e-mail'), 'type' => 'email', 'valeur' => $email, 'requis' => true, 'attributs' => 'autocomplete="username"'])
                        @include('compte._champ', ['nom' => 'password', 'label' => __('Nouveau mot de passe'), 'type' => 'password', 'requis' => true, 'attributs' => 'autocomplete="new-password" minlength="8" autofocus'])
                        @include('compte._champ', ['nom' => 'password_confirmation', 'label' => __('Confirmer le mot de passe'), 'type' => 'password', 'requis' => true, 'attributs' => 'autocomplete="new-password" minlength="8"'])

                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">{{ __('Enregistrer le mot de passe') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </section>

@endsection
