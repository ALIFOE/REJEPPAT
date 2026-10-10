{{--
    Emplacements de photos d'une galerie (champs photos[i], cases retirer_photos[]).
    Paramètres : $photos (chemins dans public/uploads), $emplacements, $label, $aide, $premier (numéro de la première photo, 1 par défaut).
--}}
@php $premier = $premier ?? 1; @endphp
<div class="a-champ @error('photos*') a-champ--erreur @enderror">
    <span class="a-champ__label">{{ $label }} ({{ $emplacements }} maximum)</span>
    <div class="a-photos">
        @for ($i = 0; $i < $emplacements; $i++)
            @php $existante = ($photos ?? [])[$i] ?? null; @endphp
            <div class="a-photos__emplacement">
                <input type="file" id="photo-supp-{{ $i }}" name="photos[{{ $i }}]" accept="image/jpeg,image/png,image/webp" data-apercu="apercu-photo-supp-{{ $i }}">
                <label class="a-photos__apercu" for="photo-supp-{{ $i }}" title="{{ $existante ? 'Remplacer cette photo' : 'Choisir une photo' }}">
                    <img id="apercu-photo-supp-{{ $i }}" src="{{ $existante ? asset('uploads/' . $existante) : '' }}" alt="Photo {{ $i + $premier }}" @if (! $existante) hidden @endif>
                    <span data-apercu-vide @if ($existante) hidden @endif><i class="fas fa-plus"></i> Photo {{ $i + $premier }}</span>
                </label>
                @if ($existante)
                    <label class="a-case"><input type="checkbox" name="retirer_photos[]" value="{{ $i }}"> Retirer</label>
                @endif
            </div>
        @endfor
    </div>
    <p class="a-champ__aide">{{ $aide }}</p>
    @foreach ($errors->get('photos*') as $messages)
        <p class="a-champ__erreur">{{ $messages[0] }}</p>
    @endforeach
</div>
