@extends('admin.layout')

@section('title', 'Administrateurs')
@section('sous_titre', 'Comptes ayant accès à l’administration')

@section('actions')
    <a href="{{ route('admin.utilisateurs.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau compte</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Nom</th><th>E-mail</th><th>Créé le</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($utilisateurs as $utilisateur)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <span class="a-utilisateur__avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($utilisateur->name, 0, 1)) }}</span>
                                    <span><strong>{{ $utilisateur->name }}</strong>@if ($utilisateur->is(auth()->user()))<small>Vous</small>@endif</span>
                                </div>
                            </td>
                            <td>{{ $utilisateur->email }}</td>
                            <td><small>{{ $utilisateur->created_at?->format('d/m/Y') }}</small></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.utilisateurs.edit', $utilisateur),
                                    'supprimer' => $utilisateur->is(auth()->user()) ? null : route('admin.utilisateurs.destroy', $utilisateur),
                                    'confirmation' => 'Supprimer le compte de ' . $utilisateur->name . ' ?',
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
