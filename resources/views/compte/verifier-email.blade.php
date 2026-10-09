@extends('layouts.app')

@section('title', 'Confirmez votre adresse e-mail')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Confirmez votre e-mail',
            'texte' => 'Une dernière étape avant d’accéder à votre espace client.',
            'image' => 'fermes.jpg',
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-carte">
                    <div class="compte-carte__entete">
                        <span><i class="fas fa-envelope-open-text"></i></span>
                        <h2>Vérifiez votre boîte de réception</h2>
                        <p>
                            Nous avons envoyé un lien de confirmation à <strong>{{ auth()->user()->email }}</strong>.
                            Cliquez dessus pour activer votre espace client. Pensez à regarder dans vos courriers indésirables.
                        </p>
                    </div>

                    @if (session('succes'))
                        <div class="alert alert-success" role="status">{{ session('succes') }}</div>
                    @endif

                    <form action="{{ route('verification.send') }}" method="post" class="compte-form">
                        @csrf
                        <button type="submit" class="btn-one compte-form__bouton">
                            <i class="icon-arrow"></i>
                            <span class="txt">Renvoyer l’e-mail</span>
                        </button>
                    </form>

                    <form action="{{ route('deconnexion') }}" method="post" class="compte-carte__pied">
                        @csrf
                        Mauvaise adresse ? <button type="submit" class="compte-lien">Se déconnecter</button> et créer un nouveau compte.
                    </form>
                </div>
            </div>
        </section>

@endsection
