{{-- Carte produit du template (single-shop-style1). Paramètre : $produit --}}
@php
    $lien = route('boutique.show', $produit['slug']);
@endphp
<div class="single-shop-style1">
    <div class="single-shop-style1__inner">
        <div class="single-shop-style1__img">
            <img src="{{ $produit->visuel() }}" alt="{{ $produit['nom'] }}">
        </div>
        <div class="single-shop-style1__content">
            <div class="single-shop-style1__content-text">
                <p>{{ $produit->categorieNom() }}</p>
                <h4><a href="{{ $lien }}">{{ $produit['nom'] }}</a></h4>
            </div>
            <div class="single-shop-style1__quantity">
                <div class="single-shop-style1__quantity-price">
                    <p>{{ \App\Support\Contenu::prix($produit['prix']) }} @if ($produit->reduction())<del>{{ number_format($produit['prix_initial'], 0, ',', ' ') }}</del>@endif</p>
                </div>
                <div class="single-shop-style1__quantity-input">
                    <input class="quantity-spinner" type="text" value="1" name="quantite" aria-label="Quantité">
                </div>
            </div>
        </div>
    </div>
    <ul class="single-shop-style1__icon">
        <li>
            {{-- Ajout au panier (boutique.js lit la quantité de la carte) ; sans JavaScript, ouvre la fiche produit --}}
            <a href="{{ $lien }}" data-ajout-panier="{{ route('panier.ajouter', $produit['slug']) }}">
                <i class="icon-empty-cart">
                    <span class="text">{{ $produit->enStock() ? 'Ajouter au panier' : 'Rupture de stock' }}</span>
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
