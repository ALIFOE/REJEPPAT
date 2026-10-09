@extends('admin.layout')

@section('title', 'Utilisateurs & rôles')
@section('sous_titre', 'Super admins, administrateurs et clients du site')

@php use App\Models\User; @endphp

@section('actions')
    <a href="{{ route('admin.utilisateurs.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau compte</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.utilisateurs.index', request()->except('role', 'page')) }}" class="{{ request('role') ? '' : 'actif' }}">Tous <span>{{ $compteurs->sum() }}</span></a>
                @foreach (User::ROLES as $cle => [$libelle])
                    <a href="{{ route('admin.utilisateurs.index', ['role' => $cle] + request()->except('role', 'page')) }}" class="{{ request('role') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <form class="a-filtres" method="get">
            @if (request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom, e-mail ou téléphone…">
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Nom</th><th>E-mail</th><th>Rôle</th><th>Commandes</th><th>Créé le</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($utilisateurs as $utilisateur)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <span class="a-utilisateur__avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($utilisateur->name, 0, 1)) }}</span>
                                    <span><strong>{{ $utilisateur->name }}</strong>@if ($utilisateur->is(auth()->user()))<small>Vous</small>@elseif ($utilisateur->telephone)<small>{{ $utilisateur->telephone }}</small>@endif</span>
                                </div>
                            </td>
                            <td>{{ $utilisateur->email }}</td>
                            <td>@include('admin.partials.badge', ['statuts' => User::ROLES, 'valeur' => $utilisateur->role])</td>
                            <td>{{ $utilisateur->commandes_count }}</td>
                            <td><small>{{ $utilisateur->created_at?->format('d/m/Y') }}</small></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.utilisateurs.edit', $utilisateur),
                                    'supprimer' => $utilisateur->is(auth()->user()) ? null : route('admin.utilisateurs.destroy', $utilisateur),
                                    'confirmation' => 'Supprimer le compte de ' . $utilisateur->name . ' ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun compte ne correspond à ces critères.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $utilisateurs->links() }}</div>
    </div>

@endsection
