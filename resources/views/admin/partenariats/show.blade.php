@extends('admin.layout')

@section('title', 'Partenariat : ' . $demande->organisation)
@section('sous_titre', 'Reçue le ' . $demande->created_at->locale('fr')->translatedFormat('j F Y à H\hi'))

@php use App\Models\DemandePartenariat; @endphp

@section('actions')
    <a href="{{ route('admin.partenariats.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Demandes de partenariat</a>
@endsection

@section('content')

    <div class="a-grille a-grille--large">
        <div>
            <div class="a-carte a-section">
                <div class="a-carte__entete">
                    <div><h2>{{ $demande->organisation }}</h2><p>{{ $demande->typeLibelle() }}</p></div>
                    @include('admin.partials.badge', ['statuts' => DemandePartenariat::STATUTS, 'valeur' => $demande->statut])
                </div>
                <div class="a-carte__corps">
                    <p class="a-texte-long" style="margin: 0">{{ $demande->message }}</p>
                </div>
                <div class="a-form__pied" style="justify-content: flex-start">
                    <a href="mailto:{{ $demande->email }}?subject={{ rawurlencode('Votre proposition de partenariat avec le REJEPPAT') }}" class="a-btn"><i class="fas fa-reply"></i> Répondre par e-mail</a>
                </div>
            </div>

            <div class="a-carte a-section">
                <div class="a-carte__entete"><h2>Contact</h2></div>
                <div class="a-carte__corps">
                    <dl class="a-details">
                        <dt>Nom</dt><dd>{{ $demande->nom }}</dd>
                        <dt>Fonction</dt><dd>{{ $demande->fonction ?: '–' }}</dd>
                        <dt>E-mail</dt><dd><a href="mailto:{{ $demande->email }}">{{ $demande->email }}</a></dd>
                        <dt>Téléphone</dt><dd>@if ($demande->telephone)<a href="tel:{{ $demande->telephone }}">{{ $demande->telephone }}</a>@else – @endif</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div>
            <form action="{{ route('admin.partenariats.update', $demande) }}" method="post" class="a-carte a-section">
                @csrf
                @method('put')
                <div class="a-carte__entete"><h2>Suivi</h2></div>
                <div class="a-carte__corps a-form">
                    @include('admin.partials.champ', ['nom' => 'statut', 'label' => 'Statut', 'type' => 'select', 'valeur' => $demande->statut, 'options' => collect(DemandePartenariat::STATUTS)->map(fn ($s) => $s[0])])
                    @include('admin.partials.champ', ['nom' => 'note_admin', 'label' => 'Note interne', 'type' => 'textarea', 'valeur' => $demande->note_admin, 'lignes' => 5, 'aide' => 'Ex. : rendez-vous fixé, convention en préparation…'])
                </div>
                <div class="a-form__pied">
                    <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
                </div>
            </form>

            <form action="{{ route('admin.partenariats.destroy', $demande) }}" method="post" data-confirmer="Supprimer définitivement cette demande ?">
                @csrf
                @method('delete')
                <button type="submit" class="a-btn a-btn--danger a-btn--petit"><i class="fas fa-trash-alt"></i> Supprimer la demande</button>
            </form>
        </div>
    </div>

@endsection
