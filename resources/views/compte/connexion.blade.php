@extends('layouts.app')

@section('title', 'Connexion')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Connexion',
            'texte' => 'Accédez à votre espace client et au suivi de vos commandes.',
            'image' => 'fermes.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-user"></i></span>
                        <h2>Se connecter</h2>
                        <p>Heureux de vous revoir ! Entrez vos identifiants pour continuer.</p>
                    </div>

                    @if (session('succes'))
                        <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                    @endif

                    <form action="{{ route('connexion') }}" method="post" class="compte-form">
                        @csrf
                        @include('compte._champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'requis' => true, 'attributs' => 'autocomplete="username" autofocus'])
                        @include('compte._champ', ['nom' => 'password', 'label' => 'Mot de passe', 'type' => 'password', 'requis' => true, 'attributs' => 'autocomplete="current-password"'])

                        <div class="compte-form__ligne">
                            <label class="compte-case">
                                <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Rester connecté
                            </label>
                            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                        </div>

                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">Se connecter</span>
                        </button>
                    </form>

                    <p class="compte-carte__pied">
                        Pas encore de compte ? <a href="{{ route('inscription') }}">Créer un compte</a>
                    </p>
                </div>
            </div>
        </section>

@endsection
