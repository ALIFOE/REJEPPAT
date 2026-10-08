{{--
    Champ de formulaire. Paramètres : $nom, $label, $valeur, $type (text par défaut, textarea, select),
    $options (select : [valeur => libellé]), $aide, $requis, $attributs (chaîne HTML), $lignes (textarea).
--}}
@php
    $type = $type ?? 'text';
    $id = 'champ-' . str_replace(['[', ']'], ['-', ''], $nom);
    $valeur = old($nom, $valeur ?? '');
@endphp
<div class="a-champ @error($nom) a-champ--erreur @enderror">
    <label for="{{ $id }}">{{ $label }}@if ($requis ?? false) <span aria-hidden="true">*</span>@endif</label>

    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $nom }}" rows="{{ $lignes ?? 5 }}" @required($requis ?? false) {!! $attributs ?? '' !!}>{{ $valeur }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $nom }}" @required($requis ?? false) {!! $attributs ?? '' !!}>
            @foreach ($options as $cle => $libelle)
                <option value="{{ $cle }}" @selected((string) $valeur === (string) $cle)>{{ $libelle }}</option>
            @endforeach
        </select>
    @else
        <input id="{{ $id }}" type="{{ $type }}" name="{{ $nom }}" value="{{ $valeur }}" @required($requis ?? false) {!! $attributs ?? '' !!}>
    @endif

    @if (! empty($aide))
        <p class="a-champ__aide">{{ $aide }}</p>
    @endif
    @error($nom)
        <p class="a-champ__erreur">{{ $message }}</p>
    @enderror
</div>
