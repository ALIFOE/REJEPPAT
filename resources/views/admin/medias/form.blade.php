{{--
    Formulaire d'un média : un formulaire par type (admin/medias/types/photo, video, podcast).
    Le type est fixé à la création (bouton « Ajouter une photo / une vidéo / un podcast »).
--}}
@php
    $titres = [
        'photo' => ['Nouvelle photo', 'Modifier la photo'],
        'video' => ['Nouvelle vidéo', 'Modifier la vidéo'],
        'podcast' => ['Nouveau podcast', 'Modifier le podcast'],
    ];
@endphp

@extends('admin.layout')

@section('title', $titres[$media->type][$media->exists ? 1 : 0])
@section('sous_titre', 'Médiathèque')

@section('actions')
    <a href="{{ route('admin.medias.index', ['type' => $media->type]) }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Médiathèque</a>
@endsection

@section('content')

    <form action="{{ $media->exists ? route('admin.medias.update', $media) : route('admin.medias.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($media->exists) @method('put') @endif
        <input type="hidden" name="type" value="{{ $media->type }}">

        @include('admin.medias.types.' . $media->type)

        @include('admin.partials.traduction', ['modele' => $media])

        <div class="a-form__pied">
            <a href="{{ route('admin.medias.index', ['type' => $media->type]) }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
