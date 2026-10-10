@extends('admin.layout')

@section('title', 'Témoignages & success stories')
@section('sous_titre', 'Bénéficiaires, jeunes producteurs et apprenants des fermes écoles')

@php use App\Models\Temoignage; @endphp

@section('actions')
    <a href="{{ route('admin.temoignages.create', ['type' => request('type')]) }}" class="a-btn"><i class="fas fa-plus"></i> Nouveau témoignage</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-filtres">
            <div class="a-onglets">
                <a href="{{ route('admin.temoignages.index') }}" class="{{ request('type') ? '' : 'actif' }}">Tous <span>{{ $compteurs->sum() }}</span></a>
                @foreach (Temoignage::TYPES as $cle => $libelle)
                    <a href="{{ route('admin.temoignages.index', ['type' => $cle]) }}" class="{{ request('type') === $cle ? 'actif' : '' }}">{{ $libelle }} <span>{{ $compteurs[$cle] ?? 0 }}</span></a>
                @endforeach
            </div>
        </div>
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Auteur</th><th>Témoignage</th><th>Type</th><th>Accueil</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($temoignages as $temoignage)
                        <tr>
                            <td>
                                <div class="a-table__media">
                                    <img src="{{ $temoignage->visuel() }}" alt="">
                                    <span><strong>{{ $temoignage->nom }}</strong><small>{{ $temoignage->role }}</small></span>
                                </div>
                            </td>
                            <td>@if ($temoignage->titre)<strong>{{ $temoignage->titre }}</strong><br>@endif<small>{{ \Illuminate\Support\Str::limit($temoignage->texte, 90) }}</small></td>
                            <td>{{ Temoignage::TYPES[$temoignage->type] ?? $temoignage->type }}@if ($temoignage->ferme)<br><small>{{ $temoignage->ferme->nom }}</small>@endif</td>
                            <td>@if ($temoignage->accueil)<span class="a-badge a-badge--jaune">En avant</span>@endif</td>
                            <td><span class="a-badge a-badge--{{ $temoignage->publie ? 'vert' : 'gris' }}">{{ $temoignage->publie ? 'Publié' : 'Masqué' }}</span></td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.temoignages.edit', $temoignage),
                                    'supprimer' => route('admin.temoignages.destroy', $temoignage),
                                    'confirmation' => 'Supprimer le témoignage de « ' . $temoignage->nom . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="a-vide">Aucun témoignage.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $temoignages->links() }}</div>
    </div>

@endsection
