{{-- Carte produit du template (single-shop-style1). Paramètre : $produit --}}
@php
    $lien = route('boutique.show', $produit['slug']);
    $commande = 'https://wa.me/' . config('rejeppat.whatsapp') . '?text=' . rawurlencode('Bonjour, je souhaite commander : ' . $produit['nom'] . ' (' . \App\Support\Contenu::prix($produit['prix']) . ').');
@endphp
<div class="single-shop-style1">
    <div class="single-shop-style1__inner">
        <div class="single-shop-style1__img">
            <img src="{{ asset('assets/images/rejeppat/shop/' . $produit['image']) }}" alt="{{ $produit['nom'] }}">
        </div>
        <div class="single-shop-style1__content">
            <div class="single-shop-style1__content-text">
                <p>{{ config('boutique.categories')[$produit['categorie']] }}</p>
                <h4><a href="{{ $lien }}">{{ $produit['nom'] }}</a></h4>
            </div>
            <div class="single-shop-style1__quantity">
                <div class="single-shop-style1__quantity-price">
                    <p>{{ \App\Support\Contenu::prix($produit['prix']) }} <del>{{ number_format($produit['prix_initial'], 0, ',', ' ') }}</del></p>
                </div>
                <div class="single-shop-style1__quantity-input">
                    <input class="quantity-spinner" type="text" value="1" name="quantity">
                </div>
            </div>
        </div>
    </div>
    <ul class="single-shop-style1__icon">
        <li>
            <a href="{{ $commande }}" target="_blank" rel="noopener">
                <i class="icon-empty-cart">
                    <span class="text">Commander</span>
                </i>
            </a>
        </li>
        <li>
            <a href="{{ $lien }}">
                <i class="icon-show">
                    <span class="text">Voir le produit</span>
                </i>
            </a>
        </li>
        <li>
            <a href="https://wa.me/?text={{ urlencode($lien) }}" target="_blank" rel="noopener">
                <i class="icon-favorite">
                    <span class="text">Partager</span>
                </i>
            </a>
        </li>
    </ul>
</div>
