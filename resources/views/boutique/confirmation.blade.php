@extends('layouts.app')

@section('title', 'Commande enregistrée')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Merci pour votre commande',
            'texte' => 'Votre commande a bien été enregistrée.',
            'image' => 'boutique.jpg',
            'liens' => ['Boutique' => route('boutique.index')],
            'actif' => 'Confirmation',
        ])

        <section class="checkout-area">
            <div class="container">
                <div class="commande-merci">
                    <span class="icon-check-mark"></span>
                    <h2>Merci {{ $commande->nom }} !</h2>
                    <p>
                        Votre commande <strong>{{ $commande->numero }}</strong> a bien été enregistrée.
                        Notre équipe vous contactera au <strong>{{ $commande->telephone }}</strong> pour confirmer la
                        disponibilité des produits et organiser la {{ $commande->mode_livraison === 'retrait' ? 'remise' : 'livraison' }}.
                    </p>
                    <p>Conservez votre numéro de commande : il vous permet de <a href="{{ route('commande.suivi', ['numero' => $commande->numero]) }}">suivre votre commande</a>.</p>
                </div>

                @include('boutique._recapitulatif', ['commande' => $commande])

                <div class="commande-merci__actions">
                    <a class="btn-one" href="{{ route('boutique.index') }}">
                        <i class="icon-arrow"></i>
                        <span class="txt">Retour à la boutique</span>
                    </a>
                    <a class="btn-one black" href="https://wa.me/{{ config('rejeppat.whatsapp') }}?text={{ rawurlencode('Bonjour, je viens de passer la commande ' . $commande->numero . ' sur le site du REJEPPAT.') }}" target="_blank" rel="noopener">
                        <i class="icon-arrow"></i>
                        <span class="txt">Nous écrire sur WhatsApp</span>
                    </a>
                </div>
            </div>
        </section>

@endsection
