@extends('admin.layout')

@section('title', 'Opportunités')
@section('sous_titre', $ouvertes . ' opportunité' . ($ouvertes > 1 ? 's' : '') . ' ouverte' . ($ouvertes > 1 ? 's' : '') . ' sur le site : emplois, appels à projets, financements, bourses')

@php use App\Models\Opportunite; @endphp

@section('actions')
    <a href="{{ route('admin.opportunites.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvelle opportunité</a>
@endsection

@section('content')

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une opportunité…">
            <select name="type" onchange="this.form.requestSubmit()">
                <option value="">Tous les types</option>
                @foreach (Opportunite::TYPES as $cle => $libelle)
                    <option value="{{ $cle }}" @selected(request('type') === $cle)>{{ $libelle }}</option>
                @endforeach
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Opportunité</th><th>Type</th><th>Date limite</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($opportunites as $opportunite)
                        <tr>
                            <td><strong>{{ $opportunite->titre }}</strong><br><small>{{ $opportunite->organisme ?: 'REJEPPAT' }}@if ($opportunite->lieu) · {{ $opportunite->lieu }}@endif</small></td>
                            <td>{{ Opportunite::TYPES[$opportunite->type] ?? $opportunite->type }}</td>
                            <td>
                                @if ($opportunite->date_limite)
                                    {{ $opportunite->date_limite->format('d/m/Y') }}
                                    @unless ($opportunite->estOuverte())<br><span class="a-badge a-badge--gris">Clôturée</span>@endunless
                                @else
                                    –
                                @endif
                            </td>
                            <td><span class="a-badge a-badge--{{ $opportunite->publie ? 'vert' : 'gris' }}">{{ $opportunite->publie ? 'Publiée' : 'Masquée' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $opportunite->publie ? route('espace-jeunes.opportunite', $opportunite->slug) : null,
                                    'modifier' => route('admin.opportunites.edit', $opportunite),
                                    'supprimer' => route('admin.opportunites.destroy', $opportunite),
                                    'confirmation' => 'Supprimer l’opportunité « ' . $opportunite->titre . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="a-vide">Aucune opportunité.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $opportunites->links() }}</div>
    </div>

@endsection
