@extends('layouts.app')

@section('title', __('Commande enregistrée'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Merci pour votre commande'),
            'texte' => __('Votre commande a bien été enregistrée.'),
            'image' => 'boutique.jpg',
            'liens' => [__('Boutique') => route('boutique.index')],
            'actif' => __('Confirmation'),
        ])

        <section class="checkout-area">
            <div class="container">
                <div class="commande-merci">
                    <span class="icon-check-mark"></span>
                    <h2>{{ __('Merci :nom !', ['nom' => $commande->nom]) }}</h2>
                    <p>
                        {!! __('Votre commande <strong>:numero</strong> a bien été enregistrée. Notre équipe vous contactera au <strong>:telephone</strong> pour confirmer la disponibilité des produits et organiser la :etape.', [
                            'numero' => e($commande->numero),
                            'telephone' => e($commande->telephone),
                            'etape' => $commande->mode_livraison === 'retrait' ? __('remise') : __('livraison'),
                        ]) !!}
                    </p>
                    @if ($commande->estPayee() && $commande->paiementEnLigne())
                        <p class="paiement-recu">
                            <i class="fas fa-check-circle"></i>
                            {{ __('Paiement de :montant reçu par :mode', ['montant' => \App\Support\Contenu::prix($commande->total), 'mode' => __($commande->modePaiementLibelle())]) }}
                            @if ($commande->reference_paiement)({{ __('réf.') }} {{ $commande->reference_paiement }})@endif.
                        </p>
                    @elseif ($commande->paiementEnLigne() && $commande->dernierPaiement)
                        <p class="paiement-recu paiement-recu--attente">
                            <i class="fas fa-clock"></i>
                            {{ __('Le paiement n’est pas encore confirmé.') }} <a href="{{ route('paiement.show', $commande->numero) }}">{{ __('Finaliser le paiement') }}</a>
                        </p>
                    @endif
                    <p>{{ __('Conservez votre numéro de commande : il vous permet de') }} <a href="{{ route('commande.suivi', ['numero' => $commande->numero]) }}">{{ __('suivre votre commande') }}</a>.
                        <a href="{{ route('commande.facture', $commande->numero) }}" target="_blank" rel="noopener"><i class="fas fa-file-invoice"></i> {{ __('Voir la facture') }}</a></p>
                </div>

                @include('boutique._recapitulatif', ['commande' => $commande])

                <div class="commande-merci__actions">
                    <a class="btn-one" href="{{ route('boutique.index') }}">
                        <i class="icon-arrow"></i>
                        <span class="txt">{{ __('Retour à la boutique') }}</span>
                    </a>
                    <a class="btn-one black" href="https://wa.me/{{ config('rejeppat.whatsapp') }}?text={{ rawurlencode(__('Bonjour, je viens de passer la commande :numero sur le site du REJEPPAT.', ['numero' => $commande->numero])) }}" target="_blank" rel="noopener">
                        <i class="icon-arrow"></i>
                        <span class="txt">{{ __('Nous écrire sur WhatsApp') }}</span>
                    </a>
                </div>
            </div>
        </section>

@endsection
