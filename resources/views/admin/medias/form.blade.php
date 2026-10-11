@extends('admin.layout')

@section('title', $media->exists ? 'Modifier le média' : 'Nouveau média')
@section('sous_titre', 'Médiathèque')

@php use App\Models\Media; @endphp

@section('actions')
    <a href="{{ route('admin.medias.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Médiathèque</a>
@endsection

@section('content')

    <form action="{{ $media->exists ? route('admin.medias.update', $media) : route('admin.medias.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($media->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'type', 'label' => 'Type de média', 'type' => 'select', 'valeur' => $media->type, 'options' => ['photo' => 'Photo', 'video' => 'Vidéo (fichier ou YouTube)', 'podcast' => 'Podcast (audio)'], 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $media->titre, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description / légende', 'type' => 'textarea', 'lignes' => 4, 'valeur' => $media->description])
                @include('admin.partials.champ', ['nom' => 'url', 'label' => 'Lien YouTube ou lien du podcast', 'type' => 'url', 'valeur' => $media->url, 'attributs' => 'placeholder="https://www.youtube.com/watch?v=…"', 'aide' => 'Vidéo : lien YouTube si vous n’envoyez pas de fichier vidéo. Podcast : lien d’écoute (SoundCloud, Spotify…) si vous n’envoyez pas de fichier audio.'])
                <div class="a-champ @error('fichier') a-champ--erreur @enderror">
                    <label for="champ-fichier">Fichier vidéo ou audio</label>
                    <input type="file" id="champ-fichier" name="fichier" accept=".mp4,.webm,video/mp4,video/webm,.mp3,.m4a,.ogg,.wav,audio/*">
                    <p class="a-champ__aide">Vidéo : MP4 conseillé, 300 Mo maximum. Elle est lue avec le lecteur du site, sans bouton de téléchargement, et n’a pas d’adresse publique. Podcast : MP3 conseillé, 40 Mo maximum.</p>
                    @if ($media->estVideoProtegee())
                        <div style="margin-top: 10px; max-width: 520px">@include('partials.lecteur-video', ['media' => $media])</div>
                    @elseif ($media->fichier)
                        <audio controls preload="none" src="{{ $media->fichierUrl() }}" style="width: 100%; margin-top: 8px"></audio>
                    @endif
                    @if ($media->fichier)
                        <label class="a-case" style="margin-top: 8px"><input type="checkbox" name="retirer_fichier" value="1"> Retirer le fichier</label>
                    @endif
                    @error('fichier') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $media, 'label' => 'Photo (ou vignette de la vidéo / du podcast)'])
                @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date', 'type' => 'date', 'valeur' => optional($media->date)->format('Y-m-d'), 'requis' => true])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié dans la médiathèque', 'valeur' => $media->publie])
                @include('admin.partials.interrupteur', ['nom' => 'accueil', 'label' => 'Vidéo affichée sur la page d’accueil', 'valeur' => $media->accueil, 'aide' => 'Pour les fichiers vidéo : la section « Le REJEPPAT en vidéo » s’affiche juste après les sections régionales.'])
                @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre sur l’accueil', 'type' => 'number', 'valeur' => $media->ordre, 'attributs' => 'min="0"', 'aide' => 'La première vidéo est lue en grand, les suivantes sont proposées à côté.'])
                <p class="a-champ__aide">Vignette : sans photo, les vidéos YouTube utilisent leur image d’aperçu et les fichiers vidéo leur première image.</p>
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $media])

        <div class="a-form__pied">
            <a href="{{ route('admin.medias.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
