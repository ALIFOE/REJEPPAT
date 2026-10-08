@extends('admin.layout')

@section('title', 'Fermes écoles')
@section('sous_titre', 'Réseau des fermes écoles du REJEPPAT')

@php
    use App\Models\Ferme;
    use Illuminate\Support\Str;

    $toutes = Ferme::orderBy('ordre')->get(['nom', 'modules', 'carte', 'publie']);
    $situees = $toutes->filter(fn ($f) => ! empty($f->carte))->count();
@endphp

@section('actions')
    <a href="{{ route('admin.fermes.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvelle ferme école</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Domaines de formation par ferme</h2><p>Nombre de modules proposés</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $toutes->pluck('nom')->map(fn ($n) => Str::limit($n, 16)),
                    'series' => [['label' => 'Modules', 'data' => $toutes->map(fn ($f) => count($f->modules)), 'couleur' => 'vert']],
                    'hauteur' => 240,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Présence sur la carte</h2><p>Fermes avec des coordonnées GPS</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => ['Sur la carte', 'Sans coordonnées'],
                    'series' => [['label' => 'Fermes', 'data' => [$situees, $toutes->count() - $situees]]],
                    'couleurs' => ['vert', 'sauge'],
                    'hauteur' => 240,
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une ferme école…">
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Ferme école</th><th>Spécialité</th><th class="num">Modules</th><th>Carte</th><th>Accueil</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($fermes as $ferme)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $ferme->visuel() }}" alt="">
                                    <span><strong>{{ $ferme->nom }}</strong><small>{{ $ferme->localisation ?: 'Localisation non renseignée' }}</small></span>
                                </div>
                            </td>
                            <td>{{ $ferme->specialite }}</td>
                            <td class="num">{{ count($ferme->modules) }}</td>
                            <td>@if ($ferme->carte)<i class="fas fa-map-marker-alt" style="color: var(--a-vert)" title="{{ $ferme->carte['adresse'] ?? '' }}"></i>@else – @endif</td>
                            <td>@if ($ferme->accueil)<span class="a-badge a-badge--jaune">En avant</span>@endif</td>
                            <td><span class="a-badge a-badge--{{ $ferme->publie ? 'vert' : 'gris' }}">{{ $ferme->publie ? 'Publiée' : 'Masquée' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $ferme->publie ? route('fermes.show', $ferme->slug) : null,
                                    'modifier' => route('admin.fermes.edit', $ferme),
                                    'supprimer' => route('admin.fermes.destroy', $ferme),
                                    'confirmation' => 'Supprimer la ferme école « ' . $ferme->nom . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="a-vide">Aucune ferme école.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $fermes->links() }}</div>
    </div>

@endsection
