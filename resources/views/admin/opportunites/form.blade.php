@extends('admin.layout')

@section('title', $opportunite->exists ? 'Modifier l’opportunité' : 'Nouvelle opportunité')
@section('sous_titre', 'Espace Jeunes – Opportunités')

@php use App\Models\Opportunite; @endphp

@section('actions')
    <a href="{{ route('admin.opportunites.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Opportunités</a>
@endsection

@section('content')

    <form action="{{ $opportunite->exists ? route('admin.opportunites.update', $opportunite) : route('admin.opportunites.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($opportunite->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $opportunite->titre, 'requis' => true, 'aide' => 'Ex. : Appel à projets jeunes agripreneurs 2027'])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'organisme', 'label' => 'Organisme', 'valeur' => $opportunite->organisme, 'aide' => 'Qui propose l’opportunité.'])
                    @include('admin.partials.champ', ['nom' => 'lieu', 'label' => 'Lieu', 'valeur' => $opportunite->lieu, 'aide' => 'Ex. : Sokodé, tout le Togo, en ligne'])
                </div>
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 9, 'valeur' => implode("\n", $opportunite->description ?? []), 'requis' => true, 'aide' => 'Un paragraphe par ligne : public visé, conditions, pièces à fournir, comment postuler…'])
                @include('admin.partials.champ', ['nom' => 'lien', 'label' => 'Lien pour postuler ou en savoir plus', 'type' => 'url', 'valeur' => $opportunite->lien, 'attributs' => 'placeholder="https://"'])
            </div>
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'type', 'label' => 'Type', 'type' => 'select', 'valeur' => $opportunite->type, 'options' => Opportunite::TYPES, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'date_limite', 'label' => 'Date limite', 'type' => 'date', 'valeur' => optional($opportunite->date_limite)->format('Y-m-d'), 'aide' => 'Après cette date, l’opportunité passe dans les archives.'])
                <div class="a-champ @error('fichier') a-champ--erreur @enderror">
                    <label for="champ-fichier">Document joint (avis, formulaire…)</label>
                    <input type="file" id="champ-fichier" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.zip">
                    <p class="a-champ__aide">PDF, Word, Excel, PowerPoint ou ZIP, 20 Mo maximum.</p>
                    @if ($opportunite->fichier)
                        <p class="a-champ__aide"><a class="a-lien-document" href="{{ $opportunite->fichierUrl() }}" target="_blank" rel="noopener"><i class="fas fa-paperclip"></i> {{ basename($opportunite->fichier) }}</a></p>
                        <label class="a-case"><input type="checkbox" name="retirer_fichier" value="1"> Retirer le document</label>
                    @endif
                    @error('fichier') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>
                @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $opportunite->slug])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée sur le site', 'valeur' => $opportunite->publie])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $opportunite])

        <div class="a-form__pied">
            <a href="{{ route('admin.opportunites.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
