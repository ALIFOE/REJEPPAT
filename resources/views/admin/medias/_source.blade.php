{{--
    Provenance d'une vidéo ou d'un podcast (fichier envoyé ou lien en ligne).
    Les blocs [data-affiche-si="source=…"] du formulaire s'affichent selon le choix (admin.js).
--}}
<div class="a-champ @error('source') a-champ--erreur @enderror">
    <span class="a-champ__label">{{ $label }} <span aria-hidden="true">*</span></span>
    <div class="a-cases">
        @foreach (\App\Models\Media::SOURCES[$media->type] as $cle => $libelle)
            <label class="a-case">
                <input type="radio" name="source" value="{{ $cle }}" @checked(old('source', $media->source()) === $cle)>
                {{ $libelle }}
            </label>
        @endforeach
    </div>
    @error('source') <p class="a-champ__erreur">{{ $message }}</p> @enderror
</div>
