@extends('admin.layout')

@section('title', $lettre->exists ? $lettre->sujet : 'Nouvelle lettre d’information')
@section('sous_titre', $lettre->estEnvoyee() ? 'Envoyée le ' . $lettre->envoyee_le->format('d/m/Y à H:i') . ' à ' . $lettre->destinataires . ' abonné(s)' : 'Newsletter – brouillon')

@section('actions')
    <a href="{{ route('admin.lettres.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Lettres</a>
@endsection

@section('content')

    @if ($lettre->estEnvoyee())
        <div class="a-carte">
            <div class="a-carte__entete"><h2>{{ $lettre->sujet }}</h2></div>
            <div class="a-carte__corps a-texte-riche">{!! $lettre->contenu !!}</div>
        </div>
    @else
        <form action="{{ $lettre->exists ? route('admin.lettres.update', $lettre) : route('admin.lettres.store') }}" method="post" class="a-carte a-section">
            @csrf
            @if ($lettre->exists) @method('put') @endif
            <div class="a-carte__corps a-form">
                @include('admin.partials.champ', ['nom' => 'sujet', 'label' => 'Sujet de l’e-mail', 'valeur' => $lettre->sujet, 'requis' => true, 'aide' => 'Ex. : Les nouvelles du REJEPPAT – octobre 2026'])
                @include('admin.partials.champ', ['nom' => 'contenu', 'label' => 'Contenu', 'type' => 'textarea', 'lignes' => 16, 'valeur' => $lettre->contenu])
                <p class="a-champ__aide">Un lien de désabonnement et les coordonnées du REJEPPAT sont ajoutés automatiquement en bas de chaque e-mail.</p>
            </div>
            <div class="a-form__pied">
                <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer le brouillon</button>
            </div>
        </form>

        @if ($lettre->exists)
            <form action="{{ route('admin.lettres.envoyer', $lettre) }}" method="post" class="a-carte" data-confirmer="Envoyer « {{ $lettre->sujet }} » à {{ $actifs }} abonné(s) ? L’envoi est définitif.">
                @csrf
                <div class="a-carte__corps" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between">
                    <p style="margin: 0">Prête ? La lettre sera envoyée à <strong>{{ $actifs }} abonné{{ $actifs > 1 ? 's' : '' }}</strong>. Enregistrez d’abord vos dernières modifications.</p>
                    <button type="submit" class="a-btn a-btn--vert" @disabled($actifs === 0)><i class="far fa-paper-plane"></i> Envoyer aux abonnés</button>
                </div>
            </form>
        @endif
    @endif

@endsection

@unless ($lettre->estEnvoyee())
    @push('scripts')
        @include('admin.partials.editeur', ['selecteur' => '#champ-contenu', 'televersement' => route('admin.actualites.image'), 'hauteur' => 520])
    @endpush
@endunless
