@extends('admin.layout')

@section('title', 'Ressources téléchargeables')
@section('sous_titre', 'Guides, manuels et supports de formation · ' . $telechargements . ' téléchargement' . ($telechargements > 1 ? 's' : '') . ' au total')

@php use App\Models\Ressource; @endphp

@section('actions')
    <a href="{{ route('admin.ressources.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvelle ressource</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.ressources.index') }}" class="{{ request('categorie') ? '' : 'actif' }}">Toutes</a>
                @foreach (Ressource::CATEGORIES as $cle => $libelle)
                    <a href="{{ route('admin.ressources.index', ['categorie' => $cle]) }}" class="{{ request('categorie') === $cle ? 'actif' : '' }}">{{ $libelle }}</a>
                @endforeach
            </div>
        </div>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Document</th><th>Catégorie</th><th>Fichier</th><th class="num">Téléchargements</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($ressources as $ressource)
                        <tr>
                            <td><strong>{{ $ressource->titre }}</strong><br><small>{{ \Illuminate\Support\Str::limit($ressource->description, 80) }}</small></td>
                            <td>{{ Ressource::CATEGORIES[$ressource->categorie] ?? $ressource->categorie }}</td>
                            <td><a class="a-lien-document" href="{{ asset('uploads/' . $ressource->fichier) }}" target="_blank" rel="noopener"><i class="fas fa-file-alt"></i> {{ $ressource->format() }}</a> <small>{{ $ressource->taille() }}</small></td>
                            <td class="num">{{ $ressource->telechargements }}</td>
                            <td><span class="a-badge a-badge--{{ $ressource->publie ? 'vert' : 'gris' }}">{{ $ressource->publie ? 'Publiée' : 'Masquée' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.ressources.edit', $ressource),
                                    'supprimer' => route('admin.ressources.destroy', $ressource),
                                    'confirmation' => 'Supprimer la ressource « ' . $ressource->titre . ' » et son fichier ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucune ressource.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $ressources->links() }}</div>
    </div>

@endsection
