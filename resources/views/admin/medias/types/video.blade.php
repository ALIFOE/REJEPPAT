{{-- Formulaire d'une vidéo : fichier envoyé sur le site (lecteur du site, possible sur l'accueil) ou lien YouTube --}}
<div class="a-carte__corps a-grille a-grille--large">
    <div class="a-form">
        @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $media->titre, 'requis' => true])
        @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 4, 'valeur' => $media->description])
        @include('admin.medias._source', ['label' => 'Provenance de la vidéo'])

        <div class="a-champ @error('fichier') a-champ--erreur @enderror" data-affiche-si="source=fichier">
            <label for="champ-fichier">Fichier vidéo @if (! $media->estVideoProtegee())<span aria-hidden="true">*</span>@endif</label>
            <input type="file" id="champ-fichier" name="fichier" accept=".mp4,.webm,video/mp4,video/webm">
            <p class="a-champ__aide">MP4 conseillé, 300 Mo maximum. La vidéo est lue avec le lecteur du site, sans bouton de téléchargement, et n’a pas d’adresse publique.@if ($media->estVideoProtegee()) Envoyez un nouveau fichier pour remplacer la vidéo actuelle.@endif</p>
            @if ($media->estVideoProtegee())
                <div style="margin-top: 10px; max-width: 520px">@include('partials.lecteur-video', ['media' => $media])</div>
            @endif
            @error('fichier') <p class="a-champ__erreur">{{ $message }}</p> @enderror
        </div>

        <div data-affiche-si="source=youtube">
            @include('admin.partials.champ', ['nom' => 'url', 'label' => 'Lien de la vidéo YouTube', 'type' => 'url', 'valeur' => $media->url, 'requis' => true, 'attributs' => 'placeholder="https://www.youtube.com/watch?v=…"', 'aide' => 'Liens youtube.com/watch?v=…, youtu.be/…, YouTube Shorts acceptés. La vignette YouTube est utilisée si vous ne choisissez pas de vignette.'])
            @if ($media->youtubeId())
                <div class="a-video-youtube">
                    <iframe src="https://www.youtube-nocookie.com/embed/{{ $media->youtubeId() }}" title="{{ $media->titre }}" allowfullscreen loading="lazy"></iframe>
                </div>
            @endif
        </div>
    </div>
    <div class="a-form">
        @include('admin.partials.photo', ['modele' => $media, 'label' => 'Vignette (facultative)'])
        @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date', 'type' => 'date', 'valeur' => optional($media->date)->format('Y-m-d'), 'requis' => true])
        @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée dans la médiathèque', 'valeur' => $media->publie])
        <div data-affiche-si="source=fichier">
            @include('admin.partials.interrupteur', ['nom' => 'accueil', 'label' => 'Vidéo affichée sur la page d’accueil', 'valeur' => $media->accueil, 'aide' => 'La section « Le REJEPPAT en vidéo » s’affiche juste après les sections régionales.'])
            @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre sur l’accueil', 'type' => 'number', 'valeur' => $media->ordre, 'attributs' => 'min="0"', 'aide' => 'La première vidéo est lue en grand, les suivantes sont proposées à côté.'])
        </div>
        <p class="a-champ__aide">Sans vignette, les vidéos YouTube utilisent leur image d’aperçu et les fichiers vidéo leur première image.</p>
    </div>
</div>
