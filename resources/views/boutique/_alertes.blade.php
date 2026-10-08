{{-- Messages de la boutique (ajout au panier, stock, validation) --}}
@if (session('succes'))
    <div class="alert alert-success">{{ session('succes') }}</div>
@endif
@if (session('erreur_panier'))
    <div class="alert alert-danger">{{ session('erreur_panier') }}</div>
@endif
@error('panier')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
