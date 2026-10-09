@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Créer un compte',
            'texte' => 'Commandez plus vite et suivez toutes vos commandes au même endroit.',
            'image' => 'fermes.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-user-plus"></i></span>
                        <h2>Créer mon compte</h2>
                        <p>Quelques informations suffisent. Un e-mail vous sera envoyé pour confirmer votre adresse.</p>
                    </div>

                    <form action="{{ route('inscription') }}" method="post" class="compte-form">
                        @csrf
                        @include('compte._champ', ['nom' => 'name', 'label' => 'Nom complet', 'requis' => true, 'attributs' => 'autocomplete="name" maxlength="120" autofocus'])
                        @include('compte._champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'requis' => true, 'attributs' => 'autocomplete="email" maxlength="160"'])
                        @include('compte._champ', ['nom' => 'telephone', 'label' => 'Téléphone / WhatsApp', 'type' => 'tel', 'aide' => 'Facultatif : il sera proposé lors de vos commandes.', 'attributs' => 'autocomplete="tel" maxlength="40"'])
                        @include('compte._champ', ['nom' => 'password', 'label' => 'Mot de passe', 'type' => 'password', 'requis' => true, 'aide' => 'Au moins 8 caractères.', 'attributs' => 'autocomplete="new-password" minlength="8"'])
                        @include('compte._champ', ['nom' => 'password_confirmation', 'label' => 'Confirmer le mot de passe', 'type' => 'password', 'requis' => true, 'attributs' => 'autocomplete="new-password" minlength="8"'])

                        <label class="compte-case @error('accord') compte-case--erreur @enderror">
                            <input type="checkbox" name="accord" value="1" required @checked(old('accord'))>
                            J’accepte que mes informations soient utilisées pour gérer mon compte et mes commandes.
                        </label>
                        @error('accord') <p class="compte-champ__erreur">{{ $message }}</p> @enderror

                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">Créer mon compte</span>
                        </button>
                    </form>

                    <p class="compte-carte__pied">
                        Déjà inscrit ? <a href="{{ route('connexion') }}">Se connecter</a>
                    </p>
                </div>
            </div>
        </section>

@endsection
