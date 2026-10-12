{{--
    Pagination du site public, au style du template (pastilles rondes, flèches).
    Utilisation : {{ $liste->links('partials.pagination') }} dans un bloc .rj-pagination
    (chargement sans rechargement de la page : rejeppat-navigation.js).
--}}
@if ($paginator->hasPages())
    <nav aria-label="{{ __('Pages de la liste') }}">
        <ul class="styled-pagination rj-pages">
            @if ($paginator->onFirstPage())
                <li class="arrow prev desactive" aria-disabled="true"><span><span class="icon-arrow"></span></span></li>
            @else
                <li class="arrow prev"><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('Page précédente') }}"><span class="icon-arrow"></span></a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="desactive" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="arrow next"><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('Page suivante') }}"><span class="icon-arrow"></span></a></li>
            @else
                <li class="arrow next desactive" aria-disabled="true"><span><span class="icon-arrow"></span></span></li>
            @endif
        </ul>
    </nav>
@endif
