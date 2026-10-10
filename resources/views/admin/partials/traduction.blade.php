{{--
    Version anglaise facultative d'un contenu (champs « traductions[en][…] »).
    Paramètre : $modele (constante TRADUCTIONS : champ => [libellé, type]).
    Un champ laissé vide affiche le texte français sur la version anglaise du site.
--}}
@php
    $champs = $modele::TRADUCTIONS;
    $remplis = collect($champs)->keys()->filter(fn ($champ) => filled($modele->traduction($champ)))->count();
    $valeurAffichee = function (string $champ, string $type) use ($modele) {
        $valeur = old("traductions.en.{$champ}", $modele->traduction($champ));

        return match (true) {
            is_array($valeur) && $type === 'paires' => collect($valeur)->map(fn ($p) => trim(($p['titre'] ?? '') . ' | ' . ($p['texte'] ?? ''), ' |'))->implode("\n"),
            is_array($valeur) => implode("\n", $valeur),
            default => (string) $valeur,
        };
    };
@endphp
<details class="a-traduction" @if ($errors->has('traductions*')) open @endif>
    <summary>
        <span><i class="fas fa-language"></i> Version anglaise <small>(facultatif)</small></span>
        <span class="a-badge a-badge--{{ $remplis ? 'vert' : 'gris' }}">{{ $remplis }} / {{ count($champs) }} champ{{ count($champs) > 1 ? 's' : '' }} traduit{{ $remplis > 1 ? 's' : '' }}</span>
    </summary>
    <div class="a-traduction__corps a-form">
        <p class="a-champ__aide" style="margin: 0">Les visiteurs qui choisissent l’anglais voient ces textes. Un champ laissé vide affiche le texte français.</p>
        @foreach ($champs as $champ => [$libelle, $type])
            @include('admin.partials.champ', [
                'nom' => "traductions[en][{$champ}]",
                'label' => $libelle . ' (EN)',
                'type' => in_array($type, ['texte'], true) ? 'text' : 'textarea',
                'lignes' => match ($type) { 'html' => 12, 'lignes', 'paires' => 6, default => 3 },
                'valeur' => $valeurAffichee($champ, $type),
                'aide' => match ($type) {
                    'lignes' => 'Un élément (paragraphe, point…) par ligne.',
                    'paires' => 'Une ligne par élément : « Titre | Texte ».',
                    'html' => 'Un paragraphe par ligne, ou du HTML simple (<h2>, <p>, <strong>, <a>…).',
                    default => null,
                },
            ])
        @endforeach
    </div>
</details>
