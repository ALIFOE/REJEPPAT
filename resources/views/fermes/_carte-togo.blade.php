{{-- Carte interactive du Togo avec la position des fermes écoles (amCharts 4, comme sur rejeppat.org).
     Survol d’un point ou d’un nom de la liste : adresse de la ferme ; clic : page de la ferme. Paramètre : $fermes --}}
@php
    $points = $fermes->filter(fn ($ferme) => isset($ferme['carte']))->values()->map(fn ($ferme) => [
        'nom' => $ferme['nom'],
        'adresse' => $ferme['carte']['adresse'],
        'latitude' => $ferme['carte']['lat'],
        'longitude' => $ferme['carte']['lng'],
        'url' => route('fermes.show', $ferme['slug']),
    ]);
@endphp

<div class="carte-fermes">
    <div class="carte-fermes__title">
        <h3>Nos fermes écoles sur la carte</h3>
        <p>Survolez un point rouge pour voir l’adresse d’une ferme école, cliquez dessus pour découvrir sa page.</p>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="carte-fermes__map" id="carte-fermes" role="img"
                aria-label="Carte du Togo montrant l’emplacement des {{ $points->count() }} fermes écoles du REJEPPAT"></div>
        </div>
        <div class="col-md-6">
            <ul class="carte-fermes__liste">
                @foreach ($points as $index => $point)
                    <li>
                        <a href="{{ $point['url'] }}" data-carte-index="{{ $index }}">
                            <span class="carte-fermes__puce"></span>
                            <span>
                                <strong>{{ $point['nom'] }}</strong>
                                <small>{{ $point['adresse'] }}</small>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.amcharts.com/lib/version/4.10.29/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/version/4.10.29/maps.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/geodata/togoHigh.js"></script>
    <script>
        am4core.ready(function () {
            var chart = am4core.create('carte-fermes', am4maps.MapChart);
            chart.geodata = am4geodata_togoHigh;
            chart.projection = new am4maps.projections.Miller();

            // Carte fixe, comme sur rejeppat.org : pas de zoom ni de déplacement (la molette fait défiler la page)
            chart.seriesContainer.draggable = false;
            chart.seriesContainer.resizable = false;
            chart.chartContainer.wheelable = false;
            chart.maxZoomLevel = 1;

            // Régions du Togo
            var regions = chart.series.push(new am4maps.MapPolygonSeries());
            regions.useGeodata = true;
            var region = regions.mapPolygons.template;
            region.fill = am4core.color('#2ca25f');
            region.stroke = am4core.color('#f9f9f9');
            region.strokeWidth = 1;

            // Fermes écoles
            var points = chart.series.push(new am4maps.MapImageSeries());
            points.data = @json($points);
            points.tooltip.getFillFromObject = false;
            points.tooltip.background.fill = am4core.color('#ffffff');
            points.tooltip.background.stroke = am4core.color('#d6e8cc');
            points.tooltip.label.fill = am4core.color('#0c2213');
            points.tooltip.label.maxWidth = 260;
            points.tooltip.label.wrap = true;

            var point = points.mapImages.template;
            point.propertyFields.latitude = 'latitude';
            point.propertyFields.longitude = 'longitude';
            point.propertyFields.url = 'url';
            point.tooltipHTML = '<strong>{nom}</strong><br>{adresse}';
            point.cursorOverStyle = am4core.MouseCursorStyle.pointer;
            point.setStateOnChildren = true;

            var cercle = point.createChild(am4core.Circle);
            cercle.radius = 10;
            cercle.fill = am4core.color('#dd3333');
            cercle.stroke = am4core.color('#ffffff');
            cercle.strokeWidth = 2;
            cercle.states.create('hover').properties.fill = am4core.color('#d1cc38');

            // Survol d’une ferme dans la liste : on met en avant son point sur la carte
            document.querySelectorAll('[data-carte-index]').forEach(function (lien) {
                var image = function () { return points.mapImages.getIndex(+lien.dataset.carteIndex); };
                lien.addEventListener('mouseenter', function () { var i = image(); if (i) { i.setState('hover'); i.showTooltip(); } });
                lien.addEventListener('mouseleave', function () { var i = image(); if (i) { i.setState('default'); i.hideTooltip(); } });
            });
        });
    </script>
@endpush
