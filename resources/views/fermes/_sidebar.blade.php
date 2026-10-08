{{-- Barre latérale des pages Fermes Écoles. Paramètres : $fermes, $actualites, $actif (slug ou null) --}}
<div class="sidebar-style1">
    <!--Start Sidebar Style1 Single-->
    <div class="sidebar-style1__single sidebar-style1__services">

        <div class="title-box">
            <div class="icon">
                <span class="icon-hat"></span>
            </div>
            <h3>Nos fermes écoles</h3>
        </div>
        <ul class="sidebar-style1__services-list">
            <li class="{{ $actif === null ? 'active' : '' }}">
                <a href="{{ route('fermes.index') }}">Présentation</a>
            </li>
            @foreach ($fermes as $item)
                <li class="{{ $actif === $item['slug'] ? 'active' : '' }}">
                    <a href="{{ route('fermes.show', $item['slug']) }}">{{ $item['nom'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <!--End Sidebar Style1 Single-->

    @include('partials.sidebar-actualites', ['actualites' => $actualites])

    @include('partials.sidebar-contact')

</div>
