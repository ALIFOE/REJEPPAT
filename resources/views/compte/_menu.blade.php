{{-- Menu latéral de l'espace client --}}
<aside class="compte-menu">
    <div class="compte-menu__profil">
        <span>{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
        <div>
            <strong>{{ auth()->user()->name }}</strong>
            <small>{{ auth()->user()->email }}</small>
        </div>
    </div>
    <nav aria-label="Espace client">
        <ul>
            <li><a href="{{ route('compte.index') }}" @class(['actif' => request()->routeIs('compte.index', 'compte.commande')])><i class="fas fa-box"></i> Mes commandes</a></li>
            <li><a href="{{ route('compte.profil') }}" @class(['actif' => request()->routeIs('compte.profil')])><i class="fas fa-user-cog"></i> Mon profil</a></li>
            <li><a href="{{ route('boutique.index') }}"><i class="fas fa-store"></i> La boutique</a></li>
            @if (auth()->user()->estAdministrateur())
                <li><a href="{{ route('admin.tableau') }}"><i class="fas fa-tools"></i> Administration</a></li>
            @endif
            <li>
                <form action="{{ route('deconnexion') }}" method="post">
                    @csrf
                    <button type="submit"><i class="fas fa-sign-out-alt"></i> Se déconnecter</button>
                </form>
            </li>
        </ul>
    </nav>
</aside>
