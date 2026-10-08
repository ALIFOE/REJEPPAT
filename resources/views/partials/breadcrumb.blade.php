{{--
    Fil d'Ariane du template.
    Paramètres : $titre, $texte (optionnel), $image (fichier dans images/rejeppat/breadcrumb),
    $liens (optionnel) : [libellé => url] entre « Accueil » et la page courante.
--}}
        <!--Start breadcrumb Style1-->
        <section class="breadcrumb-style1">
            <div class="breadcrumb-style1-bg" style="background-image: url({{ asset('assets/images/rejeppat/breadcrumb/' . ($image ?? 'offres.jpg')) }});">
                <div class="breadcrumb-style1-bg__overlay"></div>
            </div>
            <div class="container">
                <div class="inner-content">
                    <div class="title">
                        <h2>{{ $titre }}</h2>
                        <p>{{ $texte ?? 'Pour une agriculture durable portée par la jeunesse.' }}</p>
                    </div>
                    <div class="breadcrumb-menu">
                        <ul class="clearfix">
                            <li><a href="{{ route('home') }}">Accueil</a></li>
                            @foreach ($liens ?? [] as $libelle => $url)
                                <li><span class="icon-arrow"></span></li>
                                <li><a href="{{ $url }}">{{ $libelle }}</a></li>
                            @endforeach
                            <li><span class="icon-arrow"></span></li>
                            <li class="active">{{ $actif ?? $titre }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!--End breadcrumb Style1-->
