@extends('admin.layout')

@section('title', 'Annuaire des membres')
@section('sous_titre', 'Profils des jeunes producteurs et coopératives affichés dans l’Espace Jeunes')

@php use App\Models\Membre; @endphp

@section('actions')
    <a href="{{ route('admin.membres.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau membre</a>
@endsection

@section('content')

    @if ($enAttente)
        <div class="a-alerte a-section" role="status"><i class="fas fa-user-clock"></i> {{ $enAttente }} profil{{ $enAttente > 1 ? 's' : '' }} proposé{{ $enAttente > 1 ? 's' : '' }} depuis le site attend{{ $enAttente > 1 ? 'ent' : '' }} votre validation : ouvrez le profil, vérifiez-le puis cochez « Publié dans l’annuaire ».</div>
    @endif

    <div class="a-carte">
        <form class="a-filtres" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Nom ou organisation…">
            <select name="region" onchange="this.form.submit()">
                <option value="">Toutes les régions</option>
                @foreach ($regions as $id => $nom)
                    <option value="{{ $id }}" @selected((string) request('region') === (string) $id)>{{ $nom }}</option>
                @endforeach
            </select>
            <select name="etat" onchange="this.form.submit()">
                <option value="">Tous les profils</option>
                <option value="attente" @selected(request('etat') === 'attente')>À valider</option>
            </select>
            <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
        </form>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Membre</th><th>Filière</th><th>Région</th><th>Contact</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($membres as $membre)
                        <tr class="{{ $membre->publie ? '' : 'non-lu' }}">
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $membre->visuel() }}" alt="">
                                    <span><strong>{{ $membre->nom }}</strong><small>{{ $membre->organisation ?: 'Producteur individuel' }}</small></span>
                                </div>
                            </td>
                            <td>{{ Membre::FILIERES[$membre->filiere] ?? $membre->filiere }}</td>
                            <td>{{ $membre->region?->nom ?? '–' }}<br><small>{{ $membre->localite }}</small></td>
                            <td><small>{{ $membre->telephone ?: '–' }}<br>{{ $membre->email }}</small></td>
                            <td><span class="a-badge a-badge--{{ $membre->publie ? 'vert' : 'jaune' }}">{{ $membre->publie ? 'Publié' : 'À valider' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $membre->publie ? route('espace-jeunes.membre', $membre->slug) : null,
                                    'modifier' => route('admin.membres.edit', $membre),
                                    'supprimer' => route('admin.membres.destroy', $membre),
                                    'confirmation' => 'Supprimer le profil de « ' . $membre->nom . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun membre dans l’annuaire.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $membres->links() }}</div>
    </div>

@endsection
