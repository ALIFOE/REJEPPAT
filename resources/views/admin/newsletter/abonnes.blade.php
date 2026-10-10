@extends('admin.layout')

@section('title', 'Newsletter')
@section('sous_titre', $actifs . ' abonné' . ($actifs > 1 ? 's' : '') . ' · inscriptions depuis le pied de page du site')

@section('actions')
    <a href="{{ route('admin.abonnes.export') }}" class="a-btn a-btn--clair"><i class="fas fa-file-csv"></i> Exporter (Excel)</a>
    <a href="{{ route('admin.lettres.create') }}" class="a-btn"><i class="fas fa-pen"></i> Rédiger une lettre</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large">
        <div class="a-carte">
            <div class="a-filtres">
                <div class="a-onglets">
                    <a href="{{ route('admin.abonnes.index') }}" class="{{ request('etat') ? '' : 'actif' }}">Abonnés <span>{{ $actifs }}</span></a>
                    <a href="{{ route('admin.abonnes.index', ['etat' => 'desabonnes']) }}" class="{{ request('etat') === 'desabonnes' ? 'actif' : '' }}">Désabonnés <span>{{ $desabonnes }}</span></a>
                </div>
            </div>
            <form class="a-filtres" method="get">
                @if (request('etat'))<input type="hidden" name="etat" value="{{ request('etat') }}">@endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher une adresse…">
                <button type="submit" class="a-btn a-btn--vert"><i class="fas fa-search"></i> Rechercher</button>
            </form>
            <div class="a-table-conteneur">
                <table class="a-table">
                    <thead>
                        <tr><th>E-mail</th><th>Langue</th><th>Inscription</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse ($abonnes as $abonne)
                            <tr>
                                <td>{{ $abonne->email }}@if ($abonne->desabonne_le)<br><small>Désabonné le {{ $abonne->desabonne_le->format('d/m/Y') }}</small>@endif</td>
                                <td>{{ strtoupper($abonne->locale) }}</td>
                                <td><small>{{ $abonne->created_at->format('d/m/Y') }}</small></td>
                                <td>@include('admin.partials.actions', ['supprimer' => route('admin.abonnes.destroy', $abonne), 'confirmation' => 'Supprimer l’abonné ' . $abonne->email . ' ?'])</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="a-vide">Aucun abonné pour le moment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="a-pagination">{{ $abonnes->links() }}</div>
        </div>

        <div class="a-carte">
            <div class="a-carte__entete">
                <div><h2>Dernières lettres</h2><p>Brouillons et lettres envoyées</p></div>
                <a href="{{ route('admin.lettres.index') }}" class="a-btn a-btn--clair a-btn--petit">Toutes</a>
            </div>
            <div class="a-carte__corps">
                @forelse ($lettres as $lettre)
                    <p style="margin: 0 0 12px">
                        <a href="{{ route('admin.lettres.edit', $lettre) }}"><strong>{{ $lettre->sujet }}</strong></a><br>
                        <small>{{ $lettre->estEnvoyee() ? 'Envoyée le ' . $lettre->envoyee_le->format('d/m/Y') . ' à ' . $lettre->destinataires . ' abonné(s)' : 'Brouillon' }}</small>
                    </p>
                @empty
                    <p class="a-vide" style="margin: 0">Aucune lettre rédigée.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
