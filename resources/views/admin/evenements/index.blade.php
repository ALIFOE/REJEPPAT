@extends('admin.layout')

@section('title', 'Agenda')
@section('sous_titre', $aVenir . ' rendez-vous à venir : ateliers, formations, panels, foires…')

@php use App\Models\Evenement; @endphp

@section('actions')
    <a href="{{ route('admin.evenements.create') }}" class="a-btn"><i class="fas fa-plus"></i> Nouvel événement</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.evenements.index', request()->except('periode', 'page')) }}" class="{{ request('periode') ? '' : 'actif' }}">Tous</a>
                <a href="{{ route('admin.evenements.index', ['periode' => 'passes'] + request()->except('periode', 'page')) }}" class="{{ request('periode') === 'passes' ? 'actif' : '' }}">Passés</a>
            </div>
        </div>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Événement</th><th>Type</th><th>Date</th><th>Lieu</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($evenements as $evenement)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $evenement->visuel() }}" alt="">
                                    <span><strong>{{ $evenement->titre }}</strong></span>
                                </div>
                            </td>
                            <td>{{ Evenement::TYPES[$evenement->type] ?? $evenement->type }}</td>
                            <td>{{ $evenement->debut->format('d/m/Y H:i') }}@if ($evenement->fin)<br><small>au {{ $evenement->fin->format('d/m/Y H:i') }}</small>@endif</td>
                            <td>{{ $evenement->lieu }}</td>
                            <td><span class="a-badge a-badge--{{ $evenement->publie ? 'vert' : 'gris' }}">{{ $evenement->publie ? 'Publié' : 'Masqué' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'voir' => $evenement->publie ? route('agenda.show', $evenement->slug) : null,
                                    'modifier' => route('admin.evenements.edit', $evenement),
                                    'supprimer' => route('admin.evenements.destroy', $evenement),
                                    'confirmation' => 'Supprimer l’événement « ' . $evenement->titre . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun événement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $evenements->links() }}</div>
    </div>

@endsection
