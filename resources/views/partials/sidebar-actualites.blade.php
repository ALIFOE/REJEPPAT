{{-- Bloc « Actualités récentes » des barres latérales. Paramètres : $actualites, $classe (optionnel) --}}
<!--Start Sidebar Style1 Single-->
<div class="sidebar-style1__single {{ $classe ?? '' }} sidebar-style1__recent-posts">
    <div class="title-box">
        <div class="icon">
            <span class="icon-hat"></span>
        </div>
        <h3>Actualités récentes</h3>
    </div>
    <ul class="sidebar-style1__recent-posts-list">
        @foreach ($actualites as $article)
            <li>
                <div class="img-box">
                    <img src="{{ asset('assets/images/rejeppat/actualites/' . $article['image'] . '-thumb.jpg') }}" alt="">
                    <div class="overlay-content">
                        <a href="{{ route('actualites.show', $article['slug']) }}"><i class="fa fa-link" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="text-box">
                    <p><span class="fa fa-solid fa-calendar-day"></span> {{ \App\Support\Contenu::date($article['date']) }}</p>
                    <h4><a href="{{ route('actualites.show', $article['slug']) }}">{{ \Illuminate\Support\Str::limit($article['title'], 45) }}</a></h4>
                </div>
            </li>
        @endforeach
    </ul>
</div>
<!--End Sidebar Style1 Single-->
