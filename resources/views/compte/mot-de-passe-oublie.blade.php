@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Mot de passe oublié',
            'texte' => 'Recevez par e-mail un lien pour choisir un nouveau mot de passe.',
            'image' => 'fermes.jpg',
            'liens' => ['Connexion' => route('connexion')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-key"></i></span>
                        <h2>Mot de passe oublié ?</h2>
                        <p>Indiquez l’adresse e-mail de votre compte : nous vous enverrons un lien pour en choisir un nouveau.</p>
                    </div>

                    @if (session('succes'))
                        <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                    @endif

                    <form action="{{ route('password.email') }}" method="post" class="compte-form">
                        @csrf
                        @include('compte._champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'requis' => true, 'attributs' => 'autocomplete="email" autofocus'])

                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">Envoyer le lien</span>
                        </button>
                    </form>

                    <p class="compte-carte__pied">
                        <a href="{{ route('connexion') }}"><i class="fas fa-arrow-left"></i> Retour à la connexion</a>
                    </p>
                </div>
            </div>
        </section>

@endsection
