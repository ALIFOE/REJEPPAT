@extends('admin.layout')

@section('title', 'Commandes')
@section('sous_titre', 'Commandes passées sur la boutique en ligne')

@php
    use App\Models\Commande;
    use App\Support\Contenu;
@endphp

@section('content')

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Ventes des 30 derniers jours</h2><p>Montant des commandes du jour, hors annulations</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $ventes['labels'],
                    'series' => [['label' => 'Ventes', 'data' => $ventes['montants'], 'couleur' => 'vert']],
                    'monnaie' => true,
                    'hauteur' => 240,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Répartition par statut</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => collect(Commande::STATUTS)->pluck(0),
                    'series' => [['label' => 'Commandes', 'data' => collect(Commande::STATUTS)->keys()->map(fn ($k) => $compteurs[$k] ?? 0)]],
                    'couleurs' => collect(Commande::STATUTS)->pluck(1),
                    'hauteur' => 240,
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.commandes.index', request()->except('statut', 'page')) }}" class="{{ request('statut') ? '' : 'actif' }}">Toutes <span>{{ $compteurs->sum() }}</span></a>
                @foreach (Commande::STATUTS as $cle => [$libelle])
                    <a href="{{ route('admin.commandes.index', ['statut' => $cle] + request()->except('statut', 'page')) }}" class="{{ request('statut') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <form class="a-filtres" method="get">
            @if (request('statut'))<input type="hidden" name="statut" value="{{ request('statut') }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Numéro, nom ou téléphone…">
            <select name="paiement" onchange="this.form.submit()">
                <option value="">Tous les paiements</option>
                @foreach (Commande::STATUTS_PAIEMENT as $cle => [$libelle])
                    <option value="{{ $cle }}" @selected(request('paiement') === $cle)>{{ $libelle }}</option>
                @endforeach
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Client</th>
                        <th>Articles</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th class="num">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commandes as $commande)
                        <tr>
                            <td>
                                <a href="{{ route('admin.commandes.show', $commande) }}"><strong>{{ $commande->numero }}</strong></a><br>
                                <small>{{ $commande->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>{{ $commande->nom }}<br><small>{{ $commande->telephone }}</small></td>
                            <td>{{ $commande->lignes_count }}</td>
                            <td>
                                @include('admin.partials.badge', ['statuts' => Commande::STATUTS_PAIEMENT, 'valeur' => $commande->statut_paiement])<br>
                                <small>{{ $commande->modePaiementLibelle() }}</small>
                            </td>
                            <td>@include('admin.partials.badge', ['statuts' => Commande::STATUTS, 'valeur' => $commande->statut])</td>
                            <td class="num"><strong>{{ Contenu::prix($commande->total) }}</strong></td>
                            <td>
                                <div class="a-table__actions">
                                    <a href="{{ route('admin.commandes.show', $commande) }}" class="a-btn a-btn--clair a-btn--petit">Gérer</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="a-vide">Aucune commande ne correspond à ces critères.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $commandes->links() }}</div>
    </div>

@endsection
