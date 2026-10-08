@extends('admin.layout')

@section('title', 'Offres & services')
@section('sous_titre', 'Page « Nos offres & services » et liste du formulaire de demande')

@section('actions')
    <a href="{{ route('admin.offres.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvelle offre</a>
@endsection

@section('content')

    <div class="a-carte a-section">
        <div class="a-carte__entete"><div><h2>Demandes reçues par offre</h2><p>Chaque offre active apparaît dans le formulaire « Demande de service »</p></div></div>
        <div class="a-carte__corps">
            @include('admin.partials.graphique', [
                'type' => 'bar',
                'labels' => $offres->pluck('titre')->push('Autre besoin')->map(fn ($t) => \Illuminate\Support\Str::limit($t, 30)),
                'series' => [['label' => 'Demandes', 'data' => $offres->pluck('titre')->push('Autre besoin')->map(fn ($t) => $demandesParService[$t] ?? 0), 'couleur' => 'vert']],
                'hauteur' => 240,
                'vide_texte' => 'Les demandes reçues pour chaque offre apparaîtront ici.',
            ])
        </div>
    </div>

    <div class="a-carte">
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th class="num">Ordre</th><th>Offre</th><th class="num">Demandes</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($offres as $offre)
                        <tr>
                            <td class="num">{{ $offre->ordre }}</td>
                            <td><strong style="color: var(--a-noir)">{{ $offre->titre }}</strong><br><small>{{ \Illuminate\Support\Str::limit($offre->texte, 110) }}</small></td>
                            <td class="num"><a href="{{ route('admin.demandes.index', ['service' => $offre->titre]) }}">{{ $demandesParService[$offre->titre] ?? 0 }}</a></td>
                            <td><span class="a-badge a-badge--{{ $offre->actif ? 'vert' : 'gris' }}">{{ $offre->actif ? 'Active' : 'Masquée' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.offres.edit', $offre),
                                    'supprimer' => route('admin.offres.destroy', $offre),
                                    'confirmation' => 'Supprimer l’offre « ' . $offre->titre . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="a-vide">Aucune offre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
