@extends('admin.layout')

@section('title', 'Programmes & projets')
@section('sous_titre', 'Rubrique « Nos Programmes & Projets » du site')

@php
    use App\Models\Projet;
    use Illuminate\Support\Str;

    $categories = config('projets.categories');
    $tous = Projet::get(['categories', 'points', 'titre_court']);
@endphp

@section('actions')
    <a href="{{ route('admin.projets.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau projet</a>
@endsection

@section('content')

    <div class="a-grille a-grille--2 a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Projets par domaine</h2><p>Un projet peut couvrir plusieurs domaines</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'doughnut',
                    'labels' => array_values($categories),
                    'series' => [['label' => 'Projets', 'data' => collect($categories)->keys()->map(fn ($c) => $tous->filter(fn ($p) => in_array($c, $p->categories))->count())]],
                    'hauteur' => 240,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Résultats mis en avant</h2><p>Nombre de points clés par projet</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $tous->pluck('titre_court')->map(fn ($t) => Str::limit($t, 30)),
                    'series' => [['label' => 'Points clés', 'data' => $tous->map(fn ($p) => count($p->points)), 'couleur' => 'jaune']],
                    'hauteur' => 240,
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un projet…">
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Projet</th><th>Domaines</th><th class="num">Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($projets as $projet)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $projet->visuel('-small') }}" alt="">
                                    <span><strong>{{ $projet->titre }}</strong><small>{{ Str::limit($projet->resume, 80) }}</small></span>
                                </div>
                            </td>
                            <td>{{ collect($projet->categories)->map(fn ($c) => $categories[$c] ?? $c)->implode(', ') }}</td>
                            <td class="num">{{ $projet->ordre }}</td>
                            <td><span class="a-badge a-badge--{{ $projet->publie ? 'vert' : 'gris' }}">{{ $projet->publie ? 'Publié' : 'Masqué' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $projet->publie ? route('projets.show', $projet->slug) : null,
                                    'modifier' => route('admin.projets.edit', $projet),
                                    'supprimer' => route('admin.projets.destroy', $projet),
                                    'confirmation' => 'Supprimer ce projet ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="a-vide">Aucun projet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $projets->links() }}</div>
    </div>

@endsection
