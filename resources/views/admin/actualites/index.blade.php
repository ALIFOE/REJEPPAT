@extends('admin.layout')

@section('title', 'Articles & événements')
@section('sous_titre', 'Rubrique « Actualités & Événements » du site')

@php
    use App\Models\Actualite;
    use App\Support\Contenu;
    use Illuminate\Support\Str;
@endphp

@section('actions')
    <a href="{{ route('admin.actualites.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvel article</a>
@endsection

@section('content')

    <div class="a-grille a-grille--2 a-section">
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Publications par mois</h2><p>12 derniers mois, par catégorie</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'labels' => $graphiques['mois'],
                    'series' => $graphiques['publications']->map(fn ($s, $i) => $s + ['couleur' => $i ? 'jaune' : 'vert'])->all(),
                    'hauteur' => 240,
                ])
            </div>
        </div>
        <div class="a-carte">
            <div class="a-carte__entete"><div><h2>Articles les plus lus</h2><p>Nombre de lectures sur le site</p></div></div>
            <div class="a-carte__corps">
                @include('admin.partials.graphique', [
                    'type' => 'bar',
                    'horizontal' => true,
                    'labels' => $graphiques['lus']->pluck('title')->map(fn ($t) => Str::limit($t, 32)),
                    'series' => [['label' => 'Lectures', 'data' => $graphiques['lus']->pluck('vues'), 'couleur' => 'vert']],
                    'hauteur' => 240,
                    'vide_texte' => 'Le nombre de lectures s’affichera dès les premières visites.',
                ])
            </div>
        </div>
    </div>

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher un article…">
            <select name="categorie" onchange="this.form.submit()">
                <option value="">Toutes les catégories</option>
                @foreach (Actualite::CATEGORIES as $cle => $nom)
                    <option value="{{ $cle }}" @selected(request('categorie') === $cle)>{{ $nom }}</option>
                @endforeach
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Article</th><th>Catégories</th><th>Date</th><th class="num">Lectures</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($actualites as $actualite)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $actualite->visuel('-thumb') }}" alt="">
                                    <span><strong>{{ Str::limit($actualite->title, 70) }}</strong><small>{{ count($actualite->gallery ?? []) }} photo(s) · {{ count($actualite->tags ?? []) }} mot(s)-clé(s)</small></span>
                                </div>
                            </td>
                            <td>{{ collect($actualite->categories)->map(fn ($c) => Actualite::CATEGORIES[$c] ?? $c)->implode(', ') }}</td>
                            <td><small>{{ Contenu::date($actualite->date, 'j M Y') }}</small></td>
                            <td class="num">{{ $actualite->vues }}</td>
                            <td><span class="a-badge a-badge--{{ $actualite->publie ? 'vert' : 'gris' }}">{{ $actualite->publie ? 'Publié' : 'Brouillon' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $actualite->publie ? route('actualites.show', $actualite->slug) : null,
                                    'modifier' => route('admin.actualites.edit', $actualite),
                                    'supprimer' => route('admin.actualites.destroy', $actualite),
                                    'confirmation' => 'Supprimer cet article et ses photos ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun article.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $actualites->links() }}</div>
    </div>

@endsection
