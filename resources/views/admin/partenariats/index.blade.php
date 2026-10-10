@extends('admin.layout')

@section('title', 'Demandes de partenariat')
@section('sous_titre', 'Propositions envoyées depuis la page « Partenaires »')

@php use App\Models\DemandePartenariat; @endphp

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.partenariats.index', request()->except('statut', 'page')) }}" class="{{ request('statut') ? '' : 'actif' }}">Toutes <span>{{ $compteurs->sum() }}</span></a>
                @foreach (DemandePartenariat::STATUTS as $cle => [$libelle])
                    <a href="{{ route('admin.partenariats.index', ['statut' => $cle] + request()->except('statut', 'page')) }}" class="{{ request('statut') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <form class="a-filtres" method="get">
            @if (request('statut'))<input type="hidden" name="statut" value="{{ request('statut') }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Organisation, nom, e-mail…">
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>

        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Organisation</th><th>Contact</th><th>Forme de partenariat</th><th>Reçue</th><th>Statut</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($demandes as $demande)
                        <tr class="{{ $demande->statut === 'nouvelle' ? 'non-lu' : '' }}">
                            <td><a href="{{ route('admin.partenariats.show', $demande) }}">{{ $demande->organisation }}</a></td>
                            <td>{{ $demande->nom }}<br><small>{{ $demande->email }}</small></td>
                            <td>{{ \Illuminate\Support\Str::before($demande->typeLibelle(), ' (') }}</td>
                            <td><small>{{ $demande->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>@include('admin.partials.badge', ['statuts' => DemandePartenariat::STATUTS, 'valeur' => $demande->statut])</td>
                            <td><div class="a-table__actions"><a href="{{ route('admin.partenariats.show', $demande) }}" class="a-btn a-btn--clair a-btn--petit">Traiter</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucune demande de partenariat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $demandes->links() }}</div>
    </div>

@endsection
