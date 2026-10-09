@extends('layouts.app')

@section('title', 'Commande ' . $commande->numero)

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Commande ' . $commande->numero,
            'texte' => 'Le détail et l’état de votre commande.',
            'image' => 'fermes.jpg',
            'liens' => ['Mon compte' => route('compte.index')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-espace">
                    @include('compte._menu')

                    <div class="compte-espace__contenu">
                        @include('boutique._recapitulatif', ['commande' => $commande])

                        <p class="compte-retour">
                            <a href="{{ route('compte.index') }}"><i class="fas fa-arrow-left"></i> Toutes mes commandes</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

@endsection
