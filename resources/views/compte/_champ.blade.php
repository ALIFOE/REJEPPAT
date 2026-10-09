{{--
    Champ de formulaire de l'espace client. Paramètres : $nom, $label, $type (text par défaut), $valeur,
    $requis, $attributs (chaîne HTML), $aide, $sac (sac d'erreurs, « default » par défaut).
--}}
@php
    $type = $type ?? 'text';
    $id = 'compte-' . $nom . (isset($sac) ? '-' . $sac : '');
    $valeur = $type === 'password' ? '' : old($nom, $valeur ?? '');
    $erreur = $errors->getBag($sac ?? 'default')->first($nom);
@endphp
<div class="compte-champ @if ($erreur) compte-champ--erreur @endif">
    <label for="{{ $id }}">{{ $label }}@if ($requis ?? false) <span aria-hidden="true">*</span>@endif</label>
    <input id="{{ $id }}" type="{{ $type }}" name="{{ $nom }}" value="{{ $valeur }}" @required($requis ?? false)
        @if ($erreur) aria-invalid="true" aria-describedby="{{ $id }}-erreur" @endif {!! $attributs ?? '' !!}>
    @if (! empty($aide))
        <p class="compte-champ__aide">{{ $aide }}</p>
    @endif
    @if ($erreur)
        <p class="compte-champ__erreur" id="{{ $id }}-erreur">{{ $erreur }}</p>
    @endif
</div>
