{{--
    Graphique Chart.js (initialisé par assets/js/admin.js).
    Paramètres : $type (bar, line, doughnut, pie), $labels, $series [['label', 'data', 'couleur'?, 'type'?, 'axe'?]],
    $couleurs (optionnel, une couleur par libellé), $horizontal, $monnaie, $hauteur (px).
--}}
@php
    $config = [
        'type' => $type,
        'labels' => collect($labels)->values(),
        'series' => collect($series)->map(fn ($s) => $s + ['data' => []])->map(fn ($s) => array_merge($s, ['data' => collect($s['data'])->values()]))->values(),
        'couleurs' => $couleurs ?? null,
        'horizontal' => $horizontal ?? false,
        'monnaie' => $monnaie ?? false,
    ];
    $vide = collect($series)->every(fn ($s) => collect($s['data'])->sum() == 0);
@endphp
<div class="a-graphique" style="height: {{ $hauteur ?? 280 }}px">
    @if ($vide)
        <div class="a-graphique__vide">
            <p><i class="fas fa-chart-bar"></i><br>{{ $vide_texte ?? 'Pas encore de données à afficher.' }}</p>
        </div>
    @else
        <canvas data-graphique="{{ json_encode($config) }}" role="img" aria-label="{{ $titre ?? 'Graphique' }}"></canvas>
    @endif
</div>
