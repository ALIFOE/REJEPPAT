@extends('admin.layout')

@section('title', 'Demandes de services')
@section('sous_titre', 'Demandes envoyées depuis le formulaire « Demande de service »')

@php use App\Models\DemandeService; @endphp

@section('content')

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Demandes par service</h2><p>Les besoins exprimés par les visiteurs</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $parService->keys()->map(fn ($s) => \Illuminate\Support\Str::limit($s, 36)),
                    'series' => [['label' => 'Demandes', 'data' => $parService->values(), 'couleur' => 'vert']],
                    'hauteur' => 240,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Avancement</h2></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => collect(DemandeService::STATUTS)->pluck(0),
                    'series' => [['label' => 'Demandes', 'data' => collect(DemandeService::STATUTS)->keys()->map(fn ($k) => $compteurs[$k] ?? 0)]],
                    'couleurs' => collect(DemandeService::STATUTS)->pluck(1),
                    'hauteur' => 240,
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.demandes.index', request()->except('statut', 'page')) }}" class="{{ request('statut') ? '' : 'actif' }}">Toutes <span>{{ $compteurs->sum() }}</span></a>
                @foreach (DemandeService::STATUTS as $cle => [$libelle])
                    <a href="{{ route('admin.demandes.index', ['statut' => $cle] + request()->except('statut', 'page')) }}" class="{{ request('statut') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <form class="a-filtres" method="get">
            @if (request('statut'))<input type="hidden" name="statut" value="{{ request('statut') }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, objet, organisation, téléphone…">
            <select name="service" onchange="this.form.submit()">
                <option value="">Tous les services</option>
                @foreach ($services as $service)
                    <option value="{{ $service }}" @selected(request('service') === $service)>{{ $service }}</option>
                @endforeach
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Demande</th><th>Demandeur</th><th>Service</th><th>Reçue</th><th>Statut</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($demandes as $demande)
                        <tr class="{{ $demande->statut === 'nouvelle' ? 'non-lu' : '' }}">
                            <td><a href="{{ route('admin.demandes.show', $demande) }}">{{ \Illuminate\Support\Str::limit($demande->objet, 60) }}</a></td>
                            <td>{{ $demande->nom }}<br><small>{{ $demande->organisation ?: $demande->telephone }}</small></td>
                            <td>{{ $demande->service }}</td>
                            <td><small>{{ $demande->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>@include('admin.partials.badge', ['statuts' => DemandeService::STATUTS, 'valeur' => $demande->statut])</td>
                            <td>
                                <div class="a-table__actions">
                                    <a href="{{ route('admin.demandes.show', $demande) }}" class="a-btn a-btn--clair a-btn--petit">Traiter</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucune demande ne correspond à ces critères.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $demandes->links() }}</div>
    </div>

@endsection
