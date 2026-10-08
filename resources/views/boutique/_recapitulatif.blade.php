{{-- Récapitulatif d'une commande (confirmation et suivi). Paramètre : $commande --}}
@php use App\Support\Contenu; @endphp
<div class="recap-commande">
    <div class="recap-commande__entete">
        <div>
            <p>Commande</p>
            <h3>{{ $commande->numero }}</h3>
        </div>
        <div>
            <p>Passée le</p>
            <h3>{{ $commande->created_at->locale('fr')->translatedFormat('j F Y à H\hi') }}</h3>
        </div>
        <div>
            <p>Statut</p>
            <h3><span class="statut-commande statut-commande--{{ $commande->statut }}">{{ $commande->statutLibelle() }}</span></h3>
        </div>
        <div>
            <p>Paiement</p>
            <h3>{{ $commande->statutPaiementLibelle() }}</h3>
        </div>
    </div>

    <table class="recap-commande__table">
        <thead>
            <tr><th>Produit</th><th>Prix</th><th>Qté</th><th>Total</th></tr>
        </thead>
        <tbody>
            @foreach ($commande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->nom_produit }}</td>
                    <td>{{ Contenu::prix($ligne->prix_unitaire) }}</td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>{{ Contenu::prix($ligne->total) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr><td colspan="3">Sous-total</td><td>{{ Contenu::prix($commande->sous_total) }}</td></tr>
            <tr><td colspan="3">Livraison ({{ $commande->modeLivraisonLibelle() }})</td><td>{{ $commande->frais_livraison ? Contenu::prix($commande->frais_livraison) : 'Gratuit' }}</td></tr>
            <tr class="recap-commande__total"><td colspan="3">Total</td><td>{{ Contenu::prix($commande->total) }}</td></tr>
        </tfoot>
    </table>

    <ul class="recap-commande__infos">
        <li><strong>Client :</strong> {{ $commande->nom }} – {{ $commande->telephone }}</li>
        @if ($commande->ville || $commande->adresse)
            <li><strong>Adresse :</strong> {{ trim($commande->ville . ', ' . $commande->adresse, ', ') }}</li>
        @endif
        <li><strong>Paiement :</strong> {{ $commande->modePaiementLibelle() }}@if ($commande->reference_paiement) (réf. {{ $commande->reference_paiement }})@endif</li>
    </ul>
</div>
