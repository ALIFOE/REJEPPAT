@extends('admin.layout')

@section('title', 'Partenaires')
@section('sous_titre', 'Logos et présentations affichés sur la page « Partenaires » et sur l’accueil')

@php use App\Models\Partenaire; @endphp

@section('actions')
    <a href="{{ route('admin.partenariats.index') }}" class="a-btn a-btn--clair"><i class="fas fa-hands-helping"></i> Demandes reçues</a>
    <a href="{{ route('admin.partenaires.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau partenaire</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.partenaires.index') }}" class="{{ request('type') ? '' : 'actif' }}">Tous <span>{{ $compteurs->sum() }}</span></a>
                @foreach (Partenaire::TYPES as $cle => $libelle)
                    <a href="{{ route('admin.partenaires.index', ['type' => $cle]) }}" class="{{ request('type') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Partenaire</th><th>Type</th><th>Site internet</th><th class="num">Ordre</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($partenaires as $partenaire)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $partenaire->visuel() }}" alt="" style="object-fit: contain; background: #fff">
                                    <span><strong>{{ $partenaire->nom }}</strong><small>{{ \Illuminate\Support\Str::limit($partenaire->description, 60) ?: 'Sans description' }}</small></span>
                                </div>
                            </td>
                            <td>{{ Partenaire::TYPES[$partenaire->type] ?? $partenaire->type }}</td>
                            <td>@if ($partenaire->site)<a href="{{ $partenaire->site }}" target="_blank" rel="noopener">{{ parse_url($partenaire->site, PHP_URL_HOST) }}</a>@else – @endif</td>
                            <td class="num">{{ $partenaire->ordre }}</td>
                            <td><span class="a-badge a-badge--{{ $partenaire->publie ? 'vert' : 'gris' }}">{{ $partenaire->publie ? 'Affiché' : 'Masqué' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.partenaires.edit', $partenaire),
                                    'supprimer' => route('admin.partenaires.destroy', $partenaire),
                                    'confirmation' => 'Supprimer le partenaire « ' . $partenaire->nom . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun partenaire.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $partenaires->links() }}</div>
    </div>

@endsection
