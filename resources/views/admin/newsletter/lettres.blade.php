@extends('admin.layout')

@section('title', 'Lettres d’information')
@section('sous_titre', 'Newsletter · ' . $actifs . ' abonné' . ($actifs > 1 ? 's' : ''))

@section('actions')
    <a href="{{ route('admin.abonnes.index') }}" class="a-btn a-btn--clair"><i class="fas fa-users"></i> Abonnés</a>
    <a href="{{ route('admin.lettres.create') }}" class="a-btn"><i class="fas fa-pen"></i> Rédiger une lettre</a>
@endsection

@section('content')

    <div class="a-carte">
        <div class="a-table-conteneur">
            <table class="a-table">
                <thead>
                    <tr><th>Sujet</th><th>Créée le</th><th>État</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($lettres as $lettre)
                        <tr>
                            <td><a href="{{ route('admin.lettres.edit', $lettre) }}">{{ $lettre->sujet }}</a></td>
                            <td><small>{{ $lettre->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                @if ($lettre->estEnvoyee())
                                    <span class="a-badge a-badge--vert">Envoyée</span><br><small>le {{ $lettre->envoyee_le->format('d/m/Y') }} à {{ $lettre->destinataires }} abonné(s)</small>
                                @else
                                    <span class="a-badge a-badge--jaune">Brouillon</span>
                                @endif
                            </td>
                            <td>
                                @include('admin.partials.actions', [
                                    'modifier' => route('admin.lettres.edit', $lettre),
                                    'supprimer' => route('admin.lettres.destroy', $lettre),
                                    'confirmation' => 'Supprimer la lettre « ' . $lettre->sujet . ' » ?',
                                ])
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="a-vide">Aucune lettre rédigée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="a-pagination">{{ $lettres->links() }}</div>
    </div>

@endsection
