@extends('admin.layout')

@section('title', 'Médiathèque')
@section('sous_titre', 'Photos, vidéos et podcasts du réseau')

@php use App\Models\Media; @endphp

@section('content')

    {{-- Un formulaire d'ajout par type de média --}}
    <div class="a-ajouts-medias">
        <a href="{{ route('admin.medias.create', ['type' => 'photo']) }}">
            <i class="far fa-image"></i>
            <span><strong>Ajouter une photo</strong><small>Image ouverte en grand dans la galerie</small></span>
        </a>
        <a href="{{ route('admin.medias.create', ['type' => 'video']) }}">
            <i class="fas fa-video"></i>
            <span><strong>Ajouter une vidéo</strong><small>Fichier MP4 ou lien YouTube</small></span>
        </a>
        <a href="{{ route('admin.medias.create', ['type' => 'podcast']) }}">
            <i class="fas fa-podcast"></i>
            <span><strong>Ajouter un podcast</strong><small>Fichier audio ou lien d’écoute</small></span>
        </a>
    </div>

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.medias.index') }}" class="{{ request('type') ? '' : 'actif' }}">Tous <span>{{ $compteurs->sum() }}</span></a>
                @foreach (Media::TYPES as $cle => $libelle)
                    <a href="{{ route('admin.medias.index', ['type' => $cle]) }}" class="{{ request('type') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Média</th><th>Type</th><th>Date</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($medias as $media)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $media->visuel() }}" alt="">
                                    <span><strong>{{ $media->titre }}</strong><small>{{ \Illuminate\Support\Str::limit($media->description, 70) }}</small></span>
                                </div>
                            </td>
                            <td>
                                {{ Media::TYPES[$media->type] ?? $media->type }}
                                @if ($media->source())<br><small>{{ ['fichier' => 'Fichier', 'youtube' => 'YouTube', 'lien' => 'Lien d’écoute'][$media->source()] }}@if ($media->accueil) · Accueil @endif</small>@endif
                            </td>
                            <td>{{ $media->date->format('d/m/Y') }}</td>
                            <td><span class="a-badge a-badge--{{ $media->publie ? 'vert' : 'gris' }}">{{ $media->publie ? 'Publié' : 'Masqué' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.medias.edit', $media),
                                    'supprimer' => route('admin.medias.destroy', $media),
                                    'confirmation' => 'Supprimer « ' . $media->titre . ' » de la médiathèque ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="a-vide">La médiathèque est vide.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $medias->links() }}</div>
    </div>

@endsection
