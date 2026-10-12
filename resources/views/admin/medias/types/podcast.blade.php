{{-- Formulaire d'un podcast : fichier audio envoyé sur le site ou lien d'écoute (SoundCloud, Spotify…) --}}
<div class="a-carte__corps a-grille a-grille--large">
    <div class="a-form">
        @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre de l’épisode', 'valeur' => $media->titre, 'requis' => true])
        @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 4, 'valeur' => $media->description])
        @include('admin.medias._source', ['label' => 'Provenance du podcast'])

        <div class="a-champ @error('fichier') a-champ--erreur @enderror" data-affiche-si="source=fichier">
            <label for="champ-fichier">Fichier audio @if (! ($media->type === 'podcast' && $media->fichier))<span aria-hidden="true">*</span>@endif</label>
            <input type="file" id="champ-fichier" name="fichier" accept=".mp3,.m4a,.ogg,.wav,audio/*">
            <p class="a-champ__aide">MP3 conseillé, 40 Mo maximum. Il est écouté directement dans la médiathèque.@if ($media->fichier) Envoyez un nouveau fichier pour remplacer l’épisode actuel.@endif</p>
            @if ($media->fichier)
                <audio controls preload="none" src="{{ $media->fichierUrl() }}" style="width: 100%; margin-top: 8px"></audio>
            @endif
            @error('fichier') <p class="a-champ__erreur">{{ $message }}</p> @enderror
        </div>

        <div data-affiche-si="source=lien">
            @include('admin.partials.champ', ['nom' => 'url', 'label' => 'Lien d’écoute', 'type' => 'url', 'valeur' => $media->url, 'requis' => true, 'attributs' => 'placeholder="https://soundcloud.com/…"', 'aide' => 'Page de l’épisode sur SoundCloud, Spotify, Anchor…'])
        </div>
    </div>
    <div class="a-form">
        @include('admin.partials.photo', ['modele' => $media, 'label' => 'Vignette (facultative)'])
        @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date de diffusion', 'type' => 'date', 'valeur' => optional($media->date)->format('Y-m-d'), 'requis' => true])
        @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié dans la médiathèque', 'valeur' => $media->publie])
    </div>
</div>
