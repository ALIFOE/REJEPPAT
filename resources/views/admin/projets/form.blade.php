@extends('admin.layout')

@section('title', $projet->exists ? 'Modifier le projet' : 'Nouveau projet')
@section('sous_titre', 'Nos Programmes & Projets')

@php use App\Models\Projet; @endphp

@section('actions')
    <a href="{{ route('admin.projets.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Projets</a>
@endsection

@section('content')

    <form action="{{ $projet->exists ? route('admin.projets.update', $projet) : route('admin.projets.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($projet->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre complet', 'valeur' => $projet->titre, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'titre_court', 'label' => 'Titre court', 'valeur' => $projet->titre_court, 'requis' => true, 'aide' => 'Utilisé dans le menu et les cartes.'])
                @include('admin.partials.champ', ['nom' => 'resume', 'label' => 'Résumé', 'type' => 'textarea', 'lignes' => 3, 'valeur' => $projet->resume, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 6, 'valeur' => implode("\n", $projet->description ?? []), 'requis' => true, 'aide' => 'Un paragraphe par ligne.'])
                @include('admin.partials.champ', ['nom' => 'actions', 'label' => 'Actions menées', 'type' => 'textarea', 'lignes' => 5, 'valeur' => collect($projet->actions ?? [])->map(fn ($a) => $a['titre'] . ' | ' . $a['texte'])->implode("\n"), 'aide' => 'Une action par ligne, au format : Titre | Texte'])
                @include('admin.partials.champ', ['nom' => 'resultats', 'label' => 'Résultats', 'type' => 'textarea', 'lignes' => 3, 'valeur' => implode("\n", $projet->resultats ?? []), 'aide' => 'Un paragraphe par ligne.'])
                @include('admin.partials.champ', ['nom' => 'points', 'label' => 'Points clés', 'type' => 'textarea', 'lignes' => 4, 'valeur' => implode("\n", $projet->points ?? []), 'aide' => 'Un point par ligne (ex. « 25 fermes écoles mises en place »).'])
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $projet, 'label' => 'Visuel du projet'])
                <div class="a-champ @error('categories') a-champ--erreur @enderror">
                    <span class="a-champ__label">Domaines *</span>
                    <div class="a-cases">
                        @foreach (config('projets.categories') as $cle => $nom)
                            <label class="a-case">
                                <input type="checkbox" name="categories[]" value="{{ $cle }}" @checked(in_array($cle, old('categories', $projet->categories ?? [])))> {{ $nom }}
                            </label>
                        @endforeach
                    </div>
                    @error('categories') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>
                @include('admin.partials.champ', ['nom' => 'icone', 'label' => 'Icône', 'type' => 'select', 'valeur' => $projet->icone, 'options' => Projet::ICONES])
                @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date', 'type' => 'date', 'valeur' => optional($projet->date)->format('Y-m-d'), 'requis' => true])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $projet->ordre, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $projet->slug])
                </div>
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié sur le site', 'valeur' => $projet->publie])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.projets.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
