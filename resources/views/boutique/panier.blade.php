@extends('layouts.app')

@section('title', 'Mon panier')

@section('content')

        @include('partials.breadcrumb', [
            'titre' => 'Mon panier',
            'texte' => 'Achetez local et frais, directement chez les producteurs.',
            'image' => 'boutique.jpg',
            'liens' => ['Boutique' => route('boutique.index')],
        ])


        <!--Start cart area-->
        <section class="cart-area">
            <div class="container">
                @include('boutique._alertes')

                @if ($lignes->isEmpty())
                    <div class="panier-vide">
                        <span class="icon-empty-cart"></span>
                        <h3>Votre panier est vide</h3>
                        <p>Découvrez les produits frais et locaux des jeunes producteurs du réseau REJEPPAT.</p>
                        <a class="btn-one" href="{{ route('boutique.index') }}">
                            <i class="icon-arrow"></i>
                            <span class="txt">Voir la boutique</span>
                        </a>
                    </div>
                @else
                <form action="{{ route('panier.modifier') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="cart-table-box">
                                <div class="cart-info">
                                    <div class="left">
                                        <h4>Votre panier : <span>{{ $lignes->sum('quantite') }} article(s)</span></h4>
                                    </div>
                                    <div class="right">
                                        <h4>Total :<span> {{ \App\Support\Contenu::prix($sousTotal) }}</span></h4>
                                    </div>
                                </div>

                                <div class="table-outer">
                                    <table class="cart-table">
                                        <thead class="cart-header clearfix">
                                            <tr>
                                                <th class="prod-column">Produit</th>
                                                <th class="hide-me"></th>
                                                <th>Quantité</th>
                                                <th class="price">Prix</th>
                                                <th>Total</th>
                                                <th>Retirer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($lignes as $ligne)
                                            @php $produit = $ligne['produit']; @endphp
                                            <tr>
                                                <td colspan="2" class="prod-column">
                                                    <div class="column-box">
                                                        <div class="prod-thumb">
                                                            <a href="{{ route('boutique.show', $produit->slug) }}">
                                                                <img src="{{ $produit->visuel('-mini') }}" alt="{{ $produit->nom }}">
                                                            </a>
                                                        </div>
                                                        <div class="title">
                                                            <h3 class="prod-title">
                                                                <a href="{{ route('boutique.show', $produit->slug) }}">{{ $produit->nom }}</a>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="qty">
                                                    <div class="input-box">
                                                        <input class="quantity-spinner" type="text" value="{{ $ligne['quantite'] }}"
                                                            name="quantites[{{ $produit->id }}]" aria-label="Quantité">
                                                    </div>
                                                </td>
                                                <td class="price">{{ \App\Support\Contenu::prix($produit->prix) }}</td>
                                                <td class="sub-total">{{ \App\Support\Contenu::prix($ligne['total']) }}</td>
                                                <td>
                                                    <button type="submit" class="remove" form="retirer-{{ $produit->id }}" aria-label="Retirer {{ $produit->nom }}">
                                                        <span class="icon-forbidden-mark"></span>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="cart-button-box">
                                <div class="apply-coupon">
                                    <div class="inner">
                                        <a class="btn-one" href="{{ route('boutique.index') }}">
                                            <i class="icon-arrow"></i>
                                            <span class="txt">Continuer mes achats</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="update-cart-btn-box">
                                    <button class="btn-one black" type="submit">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">Mettre à jour</span>
                                    </button>
                                    <a class="btn-one" href="{{ route('commande.create') }}">
                                        <i class="icon-arrow"></i>
                                        <span class="txt">Passer la commande</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @foreach ($lignes as $ligne)
                    <form id="retirer-{{ $ligne['produit']->id }}" action="{{ route('panier.retirer', $ligne['produit']->id) }}" method="post" hidden>
                        @csrf
                        @method('delete')
                    </form>
                @endforeach
                @endif

            </div>
        </section>
        <!--End cart area-->

@endsection
