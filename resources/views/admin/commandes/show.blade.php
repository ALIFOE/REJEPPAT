@extends('admin.layout')

@section('title', 'Commande ' . $commande->numero)
@section('sous_titre', 'Passée le ' . $commande->created_at->locale('fr')->translatedFormat('j F Y à H\hi'))

@php
    use App\Models\Commande;
    use App\Support\Contenu;

    $telephone = preg_replace('/\D+/', '', $commande->telephone);
    $whatsapp = 'https://wa.me/' . (strlen($telephone) === 8 ? '228' . $telephone : $telephone)
        . '?text=' . rawurlencode("Bonjour {$commande->nom}, le REJEPPAT vous contacte au sujet de votre commande {$commande->numero} (" . Contenu::prix($commande->total) . ').');
@endphp

@section('actions')
    <a href="{{ route('admin.commandes.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Commandes</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large">
        <div>
            <div class="a-carte a-section">
                <div class="a-carte__entete">
                    <h2>Articles commandés</h2>
                    <span>
                        @include('admin.partials.badge', ['statuts' => Commande::STATUTS, 'valeur' => $commande->statut])
                        @include('admin.partials.badge', ['statuts' => Commande::STATUTS_PAIEMENT, 'valeur' => $commande->statut_paiement])
                    </span>
                </div>
                <div class="a-table-conteneur">
                    <table class="a-table">
                        <thead>
                            <tr><th>Produit</th><th class="num">Prix unitaire</th><th class="num">Quantité</th><th class="num">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($commande->lignes as $ligne)
                                <tr>
                                    <td>
                                        <div class="a-table__media">
                                            @if ($ligne->produit)
                                                <img src="{{ $ligne->produit->visuel('-mini') }}" alt="">
                                            @endif
                                            <span>
                                                <strong>{{ $ligne->nom_produit }}</strong>
                                                @if ($ligne->produit)
                                                    <small>Stock actuel : {{ $ligne->produit->stock ?? 'non suivi' }}</small>
                                                @else
                                                    <small>Produit retiré de la boutique</small>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="num">{{ Contenu::prix($ligne->prix_unitaire) }}</td>
                                    <td class="num">{{ $ligne->quantite }}</td>
                                    <td class="num">{{ Contenu::prix($ligne->total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="3" class="num">Sous-total</td><td class="num">{{ Contenu::prix($commande->sous_total) }}</td></tr>
                            <tr><td colspan="3" class="num">Livraison</td><td class="num">{{ Contenu::prix($commande->frais_livraison) }}</td></tr>
                            <tr><td colspan="3" class="num"><strong>Total</strong></td><td class="num a-total">{{ Contenu::prix($commande->total) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="a-grille a-grille--2 a-section">
                <div class="a-carte">
                    <div class="a-carte__entete"><h2>Client</h2></div>
                    <div class="a-carte__corps">
                        <dl class="a-details">
                            <dt>Nom</dt><dd>{{ $commande->nom }}</dd>
                            <dt>Téléphone</dt><dd><a href="tel:{{ $commande->telephone }}">{{ $commande->telephone }}</a></dd>
                            <dt>E-mail</dt><dd>@if ($commande->email)<a href="mailto:{{ $commande->email }}">{{ $commande->email }}</a>@else – @endif</dd>
                        </dl>
                        <p style="margin: 18px 0 0">
                            <a href="{{ $whatsapp }}" class="a-btn a-btn--vert a-btn--petit" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Écrire sur WhatsApp</a>
                        </p>
                    </div>
                </div>
                <div class="a-carte">
                    <div class="a-carte__entete"><h2>Livraison et paiement</h2></div>
                    <div class="a-carte__corps">
                        <dl class="a-details">
                            <dt>Livraison</dt><dd>{{ $commande->modeLivraisonLibelle() }}</dd>
                            @if ($commande->ville || $commande->adresse)
                                <dt>Adresse</dt><dd>{{ trim($commande->ville . ', ' . $commande->adresse, ', ') }}</dd>
                            @endif
                            <dt>Paiement</dt><dd>{{ $commande->modePaiementLibelle() }}</dd>
                            @if ($commande->reference_paiement)
                                <dt>Référence</dt><dd><strong>{{ $commande->reference_paiement }}</strong></dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            @if ($commande->note)
                <div class="a-carte a-section">
                    <div class="a-carte__entete"><h2>Message du client</h2></div>
                    <div class="a-carte__corps"><p class="a-texte-long" style="margin: 0">{{ $commande->note }}</p></div>
                </div>
            @endif
        </div>

        <div>
            <form action="{{ route('admin.commandes.update', $commande) }}" method="post" class="a-carte a-section">
                @csrf
                @method('put')
                <div class="a-carte__entete"><div><h2>Traitement</h2><p>Annuler une commande remet ses produits en stock.</p></div></div>
                <div class="a-carte__corps a-form">
                    @include('admin.partials.champ', ['nom' => 'statut', 'label' => 'Statut de la commande', 'type' => 'select', 'valeur' => $commande->statut, 'options' => collect(Commande::STATUTS)->map(fn ($s) => $s[0])])
                    @include('admin.partials.champ', ['nom' => 'statut_paiement', 'label' => 'Paiement', 'type' => 'select', 'valeur' => $commande->statut_paiement, 'options' => collect(Commande::STATUTS_PAIEMENT)->map(fn ($s) => $s[0])])
                    @include('admin.partials.champ', ['nom' => 'note_admin', 'label' => 'Note interne', 'type' => 'textarea', 'valeur' => $commande->note_admin, 'lignes' => 4, 'aide' => 'Visible uniquement par l’équipe.'])
                </div>
                <div class="a-form__pied">
                    <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>

            <div class="a-carte a-section">
                <div class="a-carte__corps">
                    <p style="margin-top: 0">Historique : créée le {{ $commande->created_at->format('d/m/Y à H:i') }}, mise à jour {{ $commande->updated_at->diffForHumans() }}.</p>
                    <form action="{{ route('admin.commandes.destroy', $commande) }}" method="post" data-confirmer="Supprimer définitivement la commande {{ $commande->numero }} ? Le stock n’est pas modifié.">
                        @csrf
                        @method('delete')
                        <button type="submit" class="a-btn a-btn--danger a-btn--petit"><i class="fas fa-trash-alt"></i> Supprimer la commande</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
