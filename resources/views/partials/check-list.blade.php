{{-- Liste à coches du template (services-details). Paramètres : $titre, $elements --}}
<div class="services-details__content-list-single">
    <div class="services-details__content-list-title">
        <h3>{{ $titre }}</h3>
    </div>
    <ul>
        @foreach ($elements as $element)
            <li>
                <div class="icon">
                    <span class="icon-check-mark"><span class="path1"></span><span
                            class="path2"></span><span class="path3"></span><span
                            class="path4"></span><span class="path5"></span><span
                            class="path6"></span><span class="path7"></span></span>
                </div>
                <div class="text">
                    <p>{{ $element }}</p>
                </div>
            </li>
        @endforeach
    </ul>
</div>
