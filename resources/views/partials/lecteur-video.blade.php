{{--
    Lecteur vidéo du site (vidéos envoyées dans la médiathèque) : interface personnalisée aux couleurs
    du REJEPPAT, sans bouton de téléchargement ni menu contextuel. La vidéo est lue par un lien signé
    et temporaire (Media::lienLecture). Paramètres : $media, $titre (afficher le titre, oui par défaut), $id (facultatif).
--}}
<div class="rj-lecteur" data-lecteur-video tabindex="0" @isset($id) id="{{ $id }}" @endisset aria-label="{{ __('Lecteur vidéo : :titre', ['titre' => $media->tr('titre')]) }}"
    data-libelle-lire="{{ __('Lire') }}" data-libelle-pause="{{ __('Pause') }}"
    data-libelle-son="{{ __('Activer le son') }}" data-libelle-muet="{{ __('Couper le son') }}">
    <video class="rj-lecteur__video" preload="metadata" playsinline
        controlslist="nodownload noplaybackrate noremoteplayback" disablepictureinpicture disableremoteplayback
        @if ($media->affiche()) poster="{{ $media->affiche() }}" @endif
        src="{{ $media->lienLecture() }}#t=0.5">
        {{ __('Votre navigateur ne permet pas de lire cette vidéo.') }}
    </video>

    @if ($titre ?? true)
        <div class="rj-lecteur__entete">
            <span class="rj-lecteur__logo"><img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}"></span>
            <strong>{{ $media->tr('titre') }}</strong>
        </div>
    @endif

    <button type="button" class="rj-lecteur__grand-bouton" data-lecteur="lecture" aria-label="{{ __('Lire la vidéo') }}">
        <i class="fas fa-play"></i>
    </button>
    <span class="rj-lecteur__chargement" aria-hidden="true"></span>

    <div class="rj-lecteur__commandes">
        <div class="rj-lecteur__progression" data-lecteur="progression" role="slider" tabindex="0" aria-label="{{ __('Position dans la vidéo') }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <span class="rj-lecteur__charge" data-lecteur="charge"></span>
            <span class="rj-lecteur__avance" data-lecteur="avance"></span>
            <span class="rj-lecteur__curseur" data-lecteur="curseur"></span>
        </div>
        <div class="rj-lecteur__barre">
            <button type="button" data-lecteur="lecture" aria-label="{{ __('Lire') }}"><i class="fas fa-play"></i></button>
            <button type="button" data-lecteur="reculer" aria-label="{{ __('Reculer de 10 secondes') }}"><i class="fas fa-undo-alt"></i></button>
            <button type="button" data-lecteur="avancer" aria-label="{{ __('Avancer de 10 secondes') }}"><i class="fas fa-redo-alt"></i></button>
            <button type="button" data-lecteur="son" aria-label="{{ __('Couper le son') }}"><i class="fas fa-volume-up"></i></button>
            <input type="range" class="rj-lecteur__volume" data-lecteur="volume" min="0" max="1" step="0.05" value="1" aria-label="{{ __('Volume') }}">
            <span class="rj-lecteur__temps"><span data-lecteur="actuel">0:00</span> / <span data-lecteur="duree">0:00</span></span>
            <span class="rj-lecteur__espace"></span>
            <button type="button" data-lecteur="plein-ecran" aria-label="{{ __('Plein écran') }}"><i class="fas fa-expand"></i></button>
        </div>
    </div>
</div>
