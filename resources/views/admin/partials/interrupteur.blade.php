{{-- Case oui / non. Paramètres : $nom, $label, $valeur, $aide --}}
<div class="a-champ">
    <input type="hidden" name="{{ $nom }}" value="0">
    <label class="a-interrupteur">
        <input type="checkbox" name="{{ $nom }}" value="1" @checked(old($nom, $valeur))>
        <span></span>
        {{ $label }}
    </label>
    @if (! empty($aide))
        <p class="a-champ__aide">{{ $aide }}</p>
    @endif
</div>
