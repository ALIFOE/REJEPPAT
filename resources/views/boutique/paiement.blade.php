@extends('layouts.app')

@section('title', __('Paiement de la commande :numero', ['numero' => $commande->numero]))

@php
    use App\Support\Contenu;

    $operateur = config("boutique.paiements.{$paiement->operateur}");
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Paiement Mobile Money'),
            'texte' => __('Validez le paiement sur votre téléphone.'),
            'image' => 'boutique.jpg',
            'liens' => [__('Boutique') => route('boutique.index')],
            'actif' => __('Paiement'),
        ])

        <section class="checkout-area">
            <div class="container">
                <div class="paiement-attente" id="paiement-attente"
                    data-statut-url="{{ route('paiement.statut', $commande->numero) }}"
                    data-statut="{{ $paiement->statut }}">

                    <img class="paiement-attente__logo" src="{{ asset($operateur['logo']) }}" alt="{{ __($operateur['label']) }}">

                    <p class="paiement-attente__montant">{{ Contenu::prix($paiement->montant) }}</p>
                    <p class="paiement-attente__commande">{{ __('Commande') }} <strong>{{ $commande->numero }}</strong></p>

                    @if ($paiement->estEnAttente())
                        <div class="paiement-attente__etat" data-etat="attente">
                            <span class="paiement-attente__roue" aria-hidden="true"></span>
                            <h2>{{ __('Validez le paiement sur votre téléphone') }}</h2>
                            <ol class="paiement-attente__etapes">
                                <li>{!! __('Une demande de paiement :operateur a été envoyée au <strong>:telephone</strong>.', ['operateur' => e(__($operateur['label'])), 'telephone' => e($paiement->telephone)]) !!}</li>
                                <li>{{ __('Ouvrez la notification (ou composez le menu :menu) et vérifiez le montant.', ['menu' => $paiement->operateur === 'flooz' ? 'Flooz' : 'Mixx']) }}</li>
                                <li>{!! __('Confirmez avec votre <strong>code secret</strong> Mobile Money.') !!}</li>
                            </ol>
                            <p class="paiement-attente__info" role="status" aria-live="polite">{{ __('En attente de votre confirmation… Cette page se met à jour automatiquement.') }}</p>

                            @if ($simulation)
                                <div class="paiement-simulation">
                                    <p>{!! __('<strong>Mode simulation</strong> : aucun argent n’est débité. Choisissez la réponse du téléphone :') !!}</p>
                                    <form action="{{ route('paiement.simuler', $commande->numero) }}" method="post">
                                        @csrf
                                        <button class="btn-one" type="submit" name="accepte" value="1"><i class="icon-arrow"></i><span class="txt">{{ __('Valider le paiement') }}</span></button>
                                        <button class="btn-one black" type="submit" name="accepte" value="0"><i class="icon-arrow"></i><span class="txt">{{ __('Refuser') }}</span></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="paiement-attente__etat paiement-attente__etat--echec">
                            <span class="paiement-attente__icone"><i class="fas fa-exclamation"></i></span>
                            <h2>{{ __('Le paiement n’a pas abouti') }}</h2>
                            <p>{{ $paiement->message ? __($paiement->message) : __('La demande de paiement a été refusée ou a expiré.') }} {{ __('Votre commande est conservée : vous pouvez réessayer.') }}</p>
                        </div>
                    @endif

                    <details class="paiement-relance" @if (! $paiement->estEnAttente() || $errors->any()) open @endif>
                        <summary>{{ $paiement->estEnAttente() ? __('Rien reçu ? Changer de numéro ou d’opérateur') : __('Réessayer le paiement') }}</summary>
                        <form action="{{ route('paiement.relancer', $commande->numero) }}" method="post" class="checkout-form">
                            @csrf
                            <div class="choix-boutique">
                                @foreach ($operateurs as $cle => $mode)
                                    <label class="choix-boutique__option choix-boutique__option--logo">
                                        <input type="radio" name="operateur" value="{{ $cle }}" @checked(old('operateur', $paiement->operateur) === $cle)>
                                        <img class="choix-boutique__logo" src="{{ asset($mode['logo']) }}" alt="">
                                        <span class="choix-boutique__texte"><strong>{{ __($mode['label']) }}</strong></span>
                                    </label>
                                @endforeach
                                @error('operateur') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="field-input">
                                <input type="tel" name="telephone_paiement" value="{{ old('telephone_paiement', $paiement->telephone) }}" placeholder="{{ __('Numéro Mobile Money *') }}" required>
                                @error('telephone_paiement') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <button class="btn-one" type="submit"><i class="icon-arrow"></i><span class="txt">{{ __('Envoyer une nouvelle demande') }}</span></button>
                        </form>
                    </details>

                    <p class="commande-aide">
                        {{ __('Un souci ?') }} <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}?text={{ rawurlencode(__('Bonjour, j’ai un souci pour payer la commande :numero.', ['numero' => $commande->numero])) }}" target="_blank" rel="noopener">{{ __('Écrivez-nous sur WhatsApp') }}</a>
                        {{ __('ou appelez le') }} <a href="tel:{{ config('rejeppat.phones.0.tel') }}">{{ config('rejeppat.phones.0.label') }}</a>.
                    </p>
                </div>
            </div>
        </section>

@endsection

@push('scripts')
    <script>
        // Vérifie le paiement toutes les 5 secondes tant qu'il est en attente
        (function () {
            var bloc = document.getElementById('paiement-attente');
            if (bloc.dataset.statut !== 'en_attente') {
                return;
            }
            var essais = 0;
            var verifier = function () {
                essais++;
                fetch(bloc.dataset.statutUrl, { headers: { 'Accept': 'application/json' } })
                    .then(function (reponse) { return reponse.ok ? reponse.json() : null; })
                    .then(function (donnees) {
                        if (donnees && donnees.redirection) {
                            window.location.href = donnees.redirection;
                        } else if (donnees && donnees.statut !== 'en_attente') {
                            window.location.reload();
                        } else if (essais < 60) {
                            setTimeout(verifier, 5000);
                        }
                    })
                    .catch(function () { if (essais < 60) { setTimeout(verifier, 8000); } });
            };
            setTimeout(verifier, 4000);
        })();
    </script>
@endpush
