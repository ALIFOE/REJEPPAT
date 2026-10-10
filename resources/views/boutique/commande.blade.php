@extends('layouts.app')

@section('title', __('Finaliser ma commande'))

@php
    use App\Support\Contenu;

    $livraisons = config('boutique.livraisons');
    $livraisonChoisie = old('mode_livraison', array_key_first($livraisons));
    $paiementChoisi = old('mode_paiement', array_key_first($paiements));
    $client = auth()->user();
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Finaliser ma commande'),
            'texte' => __('Vos coordonnées, la livraison et le paiement.'),
            'image' => 'boutique.jpg',
            'liens' => [__('Boutique') => route('boutique.index'), __('Mon panier') => route('panier.index')],
        ])


        <!--Start Checkout area-->
        <section class="checkout-area">
            <div class="container">
                @include('boutique._alertes')
                @if ($errors->any() && ! $errors->has('panier'))
                    <div class="alert alert-danger">{{ __('Veuillez corriger les champs signalés.') }}</div>
                @endif

                <div class="checkout_inner-box">
                    <form method="post" action="{{ route('commande.store') }}" id="formulaire-commande">
                        @csrf
                        <div class="row">

                            <div class="col-xl-8 col-lg-7">
                                <div class="checkout-form">
                                    <!--Start Form Box1-->
                                    <div class="checkout-form-box1">
                                        <div class="shop-page-title">
                                            <h2>{{ __('Vos coordonnées') }}</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        @guest
                                            <p class="commande-compte">
                                                {{ __('Vous avez un compte ?') }} <a href="{{ route('connexion') }}">{{ __('Connectez-vous') }}</a> {{ __('pour retrouver cette commande dans votre espace.') }}
                                            </p>
                                        @endguest
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="field-input">
                                                    <input type="text" name="nom" value="{{ old('nom', $client?->name) }}" placeholder="{{ __('Nom complet *') }}" required>
                                                    @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="tel" name="telephone" value="{{ old('telephone', $client?->telephone) }}" placeholder="{{ __('Téléphone / WhatsApp *') }}" required>
                                                    @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="email" name="email" value="{{ old('email', $client?->email) }}" placeholder="{{ __('Adresse e-mail') }}">
                                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Form Box1-->

                                    <!--Start Form Box2-->
                                    <div class="form-box2">
                                        <div class="shop-page-title">
                                            <h2>{{ __('Livraison') }}</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        <div class="choix-boutique">
                                            @foreach ($livraisons as $cle => $livraison)
                                                <label class="choix-boutique__option">
                                                    <input type="radio" name="mode_livraison" value="{{ $cle }}" data-frais="{{ $livraison['frais'] }}" @checked($livraisonChoisie === $cle)>
                                                    <span class="choix-boutique__texte">
                                                        <strong>{{ __($livraison['label']) }}</strong>
                                                        <em>{{ $livraison['frais'] ? Contenu::prix($livraison['frais']) : 'Gratuit' }}</em>
                                                    </span>
                                                </label>
                                            @endforeach
                                            @error('mode_livraison') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="row" id="champs-adresse">
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="text" name="ville" value="{{ old('ville') }}" placeholder="{{ __('Ville / Localité *') }}">
                                                    @error('ville') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="{{ __('Quartier, repère, adresse *') }}">
                                                    @error('adresse') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-input">
                                            <textarea name="note" rows="3" placeholder="{{ __('Précisions pour votre commande (facultatif)') }}">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                    <!--End Form Box2-->

                                    <!--Start Payment Info-->
                                    <div class="form-box2">
                                        <div class="shop-page-title">
                                            <h2>{{ __('Mode de paiement') }}</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        <div class="choix-boutique">
                                            @foreach ($paiements as $cle => $paiement)
                                                <label @class(['choix-boutique__option', 'choix-boutique__option--logo' => isset($paiement['logo'])])>
                                                    <input type="radio" name="mode_paiement" value="{{ $cle }}" data-en-ligne="{{ isset($paiement['reseau']) ? 1 : 0 }}" @checked($paiementChoisi === $cle)>
                                                    @isset($paiement['logo'])
                                                        <img class="choix-boutique__logo" src="{{ asset($paiement['logo']) }}" alt="{{ __($paiement['label']) }}">
                                                    @endisset
                                                    <span class="choix-boutique__texte">
                                                        <strong>{{ __($paiement['label']) }}</strong>
                                                        <span>{{ __($paiement['texte']) }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                            @error('mode_paiement') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="paiement-mobile" id="champ-telephone-paiement">
                                            <label for="telephone_paiement">{{ __('Numéro Mobile Money qui va payer *') }}</label>
                                            <div class="field-input">
                                                <input type="tel" id="telephone_paiement" name="telephone_paiement" value="{{ old('telephone_paiement', $client?->telephone) }}" placeholder="{{ __('Ex. 90 12 34 56') }}" inputmode="tel" autocomplete="tel">
                                                @error('telephone_paiement') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <p class="paiement-mobile__aide">
                                                <i class="fas fa-lock"></i> {{ __('Après validation, vous recevez une demande de paiement sur ce téléphone : confirmez-la avec votre code secret Mobile Money. Paiement sécurisé par PayGate Global.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <!--End Payment Info-->
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-5">
                                <div class="product-details-info-box">
                                    <div class="product-details-info-box__inner">
                                        <ul class="product-name">
                                            @foreach ($lignes as $ligne)
                                            <li class="product-name_list">
                                                <h4>{{ $ligne['produit']->nom }}</h4>
                                                <p>{{ Contenu::prix($ligne['produit']->prix) }} × {{ $ligne['quantite'] }} = <span>{{ Contenu::prix($ligne['total']) }}</span></p>
                                            </li>
                                            @endforeach
                                        </ul>

                                        <ul class="value-info">
                                            <li>
                                                <h5>{{ __('Sous-total') }}</h5>
                                                <p><span>{{ Contenu::prix($sousTotal) }}</span></p>
                                            </li>
                                            <li>
                                                <h5>{{ __('Livraison') }}</h5>
                                                <p id="montant-livraison">{{ Contenu::prix($livraisons[$livraisonChoisie]['frais']) }}</p>
                                            </li>
                                        </ul>
                                        <div class="total-value-box">
                                            <h3>{{ __('Total à payer') }}</h3>
                                            <h4 id="montant-total" data-sous-total="{{ $sousTotal }}">{{ Contenu::prix($sousTotal + $livraisons[$livraisonChoisie]['frais']) }}</h4>
                                        </div>

                                        <div class="button-box">
                                            <button class="btn-one" type="submit">
                                                <i class="icon-arrow"></i>
                                                <span class="txt">{{ __('Valider ma commande') }}</span>
                                            </button>
                                        </div>
                                        <p class="commande-aide">
                                            {{ __('Une question ?') }} <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener">{{ __('Écrivez-nous sur WhatsApp') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </section>
        <!--End Checkout area-->

@endsection

@push('scripts')
    <script>
        (function () {
            var form = document.getElementById('formulaire-commande');
            var total = document.getElementById('montant-total');
            var prix = function (montant) { return montant.toLocaleString('fr-FR').replace(/ | /g, ' ') + ' CFA'; };

            function actualiser() {
                var livraison = form.querySelector('input[name="mode_livraison"]:checked');
                var paiement = form.querySelector('input[name="mode_paiement"]:checked');
                var frais = livraison ? parseInt(livraison.dataset.frais, 10) : 0;

                document.getElementById('montant-livraison').textContent = frais ? prix(frais) : 'Gratuit';
                total.textContent = prix(parseInt(total.dataset.sousTotal, 10) + frais);
                document.getElementById('champs-adresse').hidden = livraison && livraison.value === 'retrait';
                document.getElementById('champ-telephone-paiement').hidden = !(paiement && paiement.dataset.enLigne === '1');
            }

            form.addEventListener('change', actualiser);

            // Le numéro Mobile Money reprend par défaut le téléphone de contact
            form.elements.telephone.addEventListener('change', function () {
                if (!form.elements.telephone_paiement.value) {
                    form.elements.telephone_paiement.value = form.elements.telephone.value;
                }
            });
            actualiser();
        })();
    </script>
@endpush
