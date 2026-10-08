{{-- Choix du visuel principal. Paramètres : $modele (avec visuel()), $label --}}
<div class="a-champ @error('photo') a-champ--erreur @enderror">
    <span class="a-champ__label">{{ $label ?? 'Visuel' }}</span>
    <div class="a-photo">
        <img id="apercu-photo" src="{{ $modele->exists ? $modele->visuel() : asset(\App\Models\Produit::VISUEL_PAR_DEFAUT) }}" alt="">
        <div style="flex: 1">
            <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" data-apercu="apercu-photo">
            <p class="a-champ__aide">JPG, PNG ou WEBP, 4 Mo maximum. Format paysage conseillé.</p>
            @if ($modele->photo)
                <label class="a-case" style="margin-top: 8px">
                    <input type="checkbox" name="retirer_photo" value="1"> Retirer la photo envoyée
                </label>
            @endif
        </div>
    </div>
    @error('photo')
        <p class="a-champ__erreur">{{ $message }}</p>
    @enderror
</div>
