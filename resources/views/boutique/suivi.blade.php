@extends('layouts.app')

@section('title', __('Suivre ma commande'))

@section('content')

        @include('partials.breadcrumb', [
            'titre' => __('Suivre ma commande'),
            'texte' => __('Retrouvez l’état de votre commande avec son numéro.'),
            'image' => 'boutique.jpg',
            'liens' => [__('Boutique') => route('boutique.index')],
        ])

        <section class="checkout-area">
            <div class="container">
                <div class="checkout-form suivi-commande">
                    <div class="shop-page-title">
                        <h2>{{ __('Rechercher ma commande') }}</h2>
                        <div class="border-box"></div>
                    </div>
                    <form action="{{ route('commande.suivi') }}" method="get">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="field-input">
                                    <input type="text" name="numero" value="{{ request('numero') }}" placeholder="{{ __('Numéro (ex. RJ-261008-AB12) *') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-input">
                                    <input type="tel" name="telephone" value="{{ request('telephone') }}" placeholder="{{ __('Téléphone de la commande *') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn-one" type="submit">
                                    <i class="icon-arrow"></i>
                                    <span class="txt">{{ __('Rechercher') }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($commande)
                    @include('boutique._recapitulatif', ['commande' => $commande])
                @elseif ($recherche)
                    <div class="alert alert-warning">{{ __('Aucune commande ne correspond à ce numéro et à ce téléphone.') }}</div>
                @endif
            </div>
        </section>

@endsection
