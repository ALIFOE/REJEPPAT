@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@php
    use App\Support\Contenu;

    $livraisons = config('boutique.livraisons');
    $paiements = config('boutique.paiements');
    $livraisonChoisie = old('mode_livraison', array_key_first($livraisons));
    $paiementChoisi = old('mode_paiement', array_key_first($paiements));
@endphp

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Finaliser ma commande',
            'texte' => 'Vos coordonnées, la livraison et le paiement.',
            'image' => 'boutique.jpg',
            'liens' => ['Boutique' => route('boutique.index'), 'Mon panier' => route('panier.index')],
        ])


        <!--Start Checkout area-->
        <section class="checkout-area">
            <div class="container">
                @include('boutique._alertes')
                @if ($errors->any() && ! $errors->has('panier'))
                    <div class="alert alert-danger">Veuillez corriger les champs signalés.</div>
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
                                            <h2>Vos coordonnées</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="field-input">
                                                    <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Nom complet *" required>
                                                    @error('nom') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="Téléphone / WhatsApp *" required>
                                                    @error('telephone') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Adresse e-mail">
                                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Form Box1-->

                                    <!--Start Form Box2-->
                                    <div class="form-box2">
                                        <div class="shop-page-title">
                                            <h2>Livraison</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        <div class="choix-boutique">
                                            @foreach ($livraisons as $cle => $livraison)
                                                <label class="choix-boutique__option">
                                                    <input type="radio" name="mode_livraison" value="{{ $cle }}" data-frais="{{ $livraison['frais'] }}" @checked($livraisonChoisie === $cle)>
                                                    <span class="choix-boutique__texte">
                                                        <strong>{{ $livraison['label'] }}</strong>
                                                        <em>{{ $livraison['frais'] ? Contenu::prix($livraison['frais']) : 'Gratuit' }}</em>
                                                    </span>
                                                </label>
                                            @endforeach
                                            @error('mode_livraison') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="row" id="champs-adresse">
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="text" name="ville" value="{{ old('ville') }}" placeholder="Ville / Localité *">
                                                    @error('ville') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="field-input">
                                                    <input type="text" name="adresse" value="{{ old('adresse') }}" placeholder="Quartier, repère, adresse *">
                                                    @error('adresse') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field-input">
                                            <textarea name="note" rows="3" placeholder="Précisions pour votre commande (facultatif)">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                    <!--End Form Box2-->

                                    <!--Start Payment Info-->
                                    <div class="form-box2">
                                        <div class="shop-page-title">
                                            <h2>Mode de paiement</h2>
                                            <div class="border-box"></div>
                                        </div>
                                        <div class="choix-boutique">
                                            @foreach ($paiements as $cle => $paiement)
                                                <label class="choix-boutique__option">
                                                    <input type="radio" name="mode_paiement" value="{{ $cle }}" data-reference="{{ ($paiement['reference'] ?? false) ? 1 : 0 }}" @checked($paiementChoisi === $cle)>
                                                    <span class="choix-boutique__texte">
                                                        <strong>{{ $paiement['label'] }}</strong>
                                                        <span>{{ $paiement['texte'] }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="field-input" id="champ-reference">
                                            <input type="text" name="reference_paiement" value="{{ old('reference_paiement') }}" placeholder="Référence de la transaction Mobile Money *">
                                            @error('reference_paiement') <small class="text-danger">{{ $message }}</small> @enderror
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
                                                <h5>Sous-total</h5>
                                                <p><span>{{ Contenu::prix($sousTotal) }}</span></p>
                                            </li>
                                            <li>
                                                <h5>Livraison</h5>
                                                <p id="montant-livraison">{{ Contenu::prix($livraisons[$livraisonChoisie]['frais']) }}</p>
                                            </li>
                                        </ul>
                                        <div class="total-value-box">
                                            <h3>Total à payer</h3>
                                            <h4 id="montant-total" data-sous-total="{{ $sousTotal }}">{{ Contenu::prix($sousTotal + $livraisons[$livraisonChoisie]['frais']) }}</h4>
                                        </div>

                                        <div class="button-box">
                                            <button class="btn-one" type="submit">
                                                <i class="icon-arrow"></i>
                                                <span class="txt">Valider ma commande</span>
                                            </button>
                                        </div>
                                        <p class="commande-aide">
                                            Une question ? <a href="https://wa.me/{{ config('rejeppat.whatsapp') }}" target="_blank" rel="noopener">Écrivez-nous sur WhatsApp</a>
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
                document.getElementById('champ-reference').hidden = !(paiement && paiement.dataset.reference === '1');
            }

            form.addEventListener('change', actualiser);
            actualiser();
        })();
    </script>
@endpush
