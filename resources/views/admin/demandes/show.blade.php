@extends('admin.layout')

@section('title', 'Demande de service')
@section('sous_titre', 'Reçue le ' . $demande->created_at->locale('fr')->translatedFormat('j F Y à H\hi'))

@php use App\Models\DemandeService; @endphp

@section('actions')
    <a href="{{ route('admin.demandes.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Demandes</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large">
        <div>
            <div class="a-carte a-section">
                <div class="a-carte__entete">
                    <div>
                        <h2>{{ $demande->objet }}</h2>
                        <p>{{ $demande->service }}</p>
                    </div>
                    @include('admin.partials.badge', ['statuts' => DemandeService::STATUTS, 'valeur' => $demande->statut])
                </div>
                <div class="a-carte__corps">
                    <p class="a-texte-long" style="margin: 0">{{ $demande->besoin }}</p>
                </div>
            </div>

            <div class="a-carte a-section">
                <div class="a-carte__entete"><h2>Demandeur</h2></div>
                <div class="a-carte__corps">
                    <dl class="a-details">
                        <dt>Nom</dt><dd>{{ $demande->nom }}</dd>
                        <dt>Téléphone</dt><dd><a href="tel:{{ $demande->telephone }}">{{ $demande->telephone }}</a></dd>
                        <dt>E-mail</dt><dd>@if ($demande->email)<a href="mailto:{{ $demande->email }}?subject={{ rawurlencode('Votre demande au REJEPPAT : ' . $demande->objet) }}">{{ $demande->email }}</a>@else – @endif</dd>
                        <dt>Organisation</dt><dd>{{ $demande->organisation ?: '–' }}</dd>
                        <dt>Région / localité</dt><dd>{{ $demande->region ?: '–' }}</dd>
                    </dl>
                    <p style="margin: 20px 0 0; display: flex; flex-wrap: wrap; gap: 8px">
                        <a href="{{ $demande->lienWhatsapp() }}" class="a-btn a-btn--vert a-btn--petit" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Répondre sur WhatsApp</a>
                        @if ($demande->email)
                            <a href="mailto:{{ $demande->email }}?subject={{ rawurlencode('Votre demande au REJEPPAT : ' . $demande->objet) }}" class="a-btn a-btn--clair a-btn--petit"><i class="fas fa-envelope"></i> Répondre par e-mail</a>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('admin.demandes.update', $demande) }}" method="post" class="a-carte a-section">
                @csrf
                @method('put')
                <div class="a-carte__entete"><h2>Suivi</h2></div>
                <div class="a-carte__corps a-form">
                    @include('admin.partials.champ', ['nom' => 'statut', 'label' => 'Statut', 'type' => 'select', 'valeur' => $demande->statut, 'options' => collect(DemandeService::STATUTS)->map(fn ($s) => $s[0])])
                    @include('admin.partials.champ', ['nom' => 'note_admin', 'label' => 'Note interne', 'type' => 'textarea', 'valeur' => $demande->note_admin, 'lignes' => 5, 'aide' => 'Ex. : personne contactée, rendez-vous fixé, suite donnée…'])
                </div>
                <div class="a-form__pied">
                    <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>

            <form action="{{ route('admin.demandes.destroy', $demande) }}" method="post" data-confirmer="Supprimer définitivement cette demande ?">
                @csrf
                @method('delete')
                <button type="submit" class="a-btn a-btn--danger a-btn--petit"><i class="fas fa-trash-alt"></i> Supprimer la demande</button>
            </form>
        </div>
    </div>

@endsection
