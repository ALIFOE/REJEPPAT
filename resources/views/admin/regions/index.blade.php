@extends('admin.layout')

@section('title', 'Sections régionales')
@section('sous_titre', 'Une page par région, complétée avec les informations fournies par chaque section')

@section('actions')
    <a href="{{ route('admin.regions.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvelle région</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Région</th><th>Responsable</th><th class="num">Coopératives</th><th class="num">Fermes écoles</th><th class="num">Membres annuaire</th><th>Page complétée</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($regions as $region)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $region->visuel() }}" alt="">
                                    <span><strong>{{ $region->nom }}</strong><small>{{ $region->chef_lieu ?: 'Chef-lieu non renseigné' }}</small></span>
                                </div>
                            </td>
                            <td>{{ $region->responsable ?: '–' }}</td>
                            <td class="num">{{ $region->nb_cooperatives ?? '–' }}</td>
                            <td class="num">{{ $region->fermes_count }}</td>
                            <td class="num">{{ $region->membres_annuaire_count }}</td>
                            <td>
                                @if ($region->presentation)
                                    <span class="a-badge a-badge--vert">Oui</span>
                                @else
                                    <span class="a-badge a-badge--jaune">Informations à fournir</span>
                                @endif
                            </td>
                            <td><span class="a-badge a-badge--{{ $region->publie ? 'vert' : 'gris' }}">{{ $region->publie ? 'Publiée' : 'Masquée' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $region->publie ? route('regions.show', $region->slug) : null,
                                    'modifier' => route('admin.regions.edit', $region),
                                    'supprimer' => route('admin.regions.destroy', $region),
                                    'confirmation' => 'Supprimer la section « ' . $region->nom . ' » ? Ses fermes et ses membres restent, sans région.',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="a-vide">Aucune section régionale.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
