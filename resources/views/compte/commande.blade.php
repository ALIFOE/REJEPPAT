@extends('layouts.app')

@section('title', __('Commande :numero', ['numero' => $commande->numero]))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Commande :numero', ['numero' => $commande->numero]),
            'texte' => __('Le détail et l’état de votre commande.'),
            'image' => 'fermes.jpg',
            'liens' => [__('Mon compte') => route('compte.index'), __('Mes commandes') => route('compte.commandes')],
        ])

        <section class="compte-section">
            <div class="container">
                <div class="compte-espace">
                    @include('compte._menu')

                    <div class="compte-espace__contenu">
                        @include('boutique._recapitulatif', ['commande' => $commande])

                        <p class="compte-retour">
                            <a href="{{ route('compte.commandes') }}"><i class="fas fa-arrow-left"></i> {{ __('Toutes mes commandes') }}</a>
                            &nbsp;·&nbsp;
                            <a href="{{ route('commande.facture', $commande->numero) }}" target="_blank" rel="noopener"><i class="fas fa-file-invoice"></i> {{ __('Voir la facture') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </section>

@endsection
