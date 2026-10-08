<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Connexion || Administration {{ config('rejeppat.name') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/rejeppat/favicons/favicon-32x32.png?v=logo') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}" />
</head>

<body class="admin">
    <div class="a-connexion">
        <div class="a-connexion__visuel" style="background-image: url({{ asset('assets/images/rejeppat/slides/slide-2.jpg') }});">
            <h2>Pour une agriculture durable portée par la jeunesse.</h2>
            <p>Espace d’administration du site du {{ config('rejeppat.full_name') }}.</p>
        </div>

        <div class="a-connexion__formulaire">
            <div class="a-connexion__boite">
                <img src="{{ asset('assets/images/rejeppat/logo/logo-rejeppat-officiel.png') }}" alt="{{ config('rejeppat.name') }}">
                <h1>Connexion</h1>
                <p>Connectez-vous pour gérer le site et la boutique.</p>

                @if (session('succes'))
                    <div class="a-alerte" role="status">{{ session('succes') }}</div>
                @endif

                <form action="{{ route('admin.connexion') }}" method="post" class="a-form">
                    @csrf
                    @include('admin.partials.champ', ['nom' => 'email', 'label' => 'Adresse e-mail', 'type' => 'email', 'requis' => true, 'attributs' => 'autocomplete="username" autofocus'])
                    @include('admin.partials.champ', ['nom' => 'password', 'label' => 'Mot de passe', 'type' => 'password', 'requis' => true, 'valeur' => '', 'attributs' => 'autocomplete="current-password"'])
                    <label class="a-case">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Rester connecté
                    </label>
                    <button type="submit" class="a-btn">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>

                <a href="{{ route('home') }}" class="a-connexion__retour"><i class="fas fa-arrow-left"></i> Retour au site</a>
            </div>
        </div>
    </div>
</body>

</html>
