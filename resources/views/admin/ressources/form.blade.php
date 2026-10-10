@extends('admin.layout')

@section('title', $ressource->exists ? 'Modifier la ressource' : 'Nouvelle ressource')
@section('sous_titre', 'Espace Jeunes – Ressources téléchargeables')

@php use App\Models\Ressource; @endphp

@section('actions')
    <a href="{{ route('admin.ressources.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Ressources</a>
@endsection

@section('content')

    <form action="{{ $ressource->exists ? route('admin.ressources.update', $ressource) : route('admin.ressources.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($ressource->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre du document', 'valeur' => $ressource->titre, 'requis' => true, 'aide' => 'Ex. : Guide de production du compost'])
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Description', 'type' => 'textarea', 'lignes' => 4, 'valeur' => $ressource->description])
                <div class="a-champ @error('fichier') a-champ--erreur @enderror">
                    <label for="champ-fichier">Fichier @unless ($ressource->exists)<span aria-hidden="true">*</span>@endunless</label>
                    <input type="file" id="champ-fichier" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.zip" @required(! $ressource->exists)>
                    <p class="a-champ__aide">PDF conseillé. Word, Excel, PowerPoint ou ZIP acceptés, 20 Mo maximum.@if ($ressource->exists) Laisser vide pour garder le fichier actuel.@endif</p>
                    @if ($ressource->exists)
                        <p class="a-champ__aide"><a class="a-lien-document" href="{{ asset('uploads/' . $ressource->fichier) }}" target="_blank" rel="noopener"><i class="fas fa-file-alt"></i> Fichier actuel ({{ $ressource->format() }}, {{ $ressource->taille() ?? 'introuvable' }})</a></p>
                    @endif
                    @error('fichier') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'categorie', 'label' => 'Catégorie', 'type' => 'select', 'valeur' => $ressource->categorie, 'options' => Ressource::CATEGORIES, 'requis' => true])
                @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $ressource->ordre, 'attributs' => 'min="0"'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée sur le site', 'valeur' => $ressource->publie])
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $ressource])

        <div class="a-form__pied">
            <a href="{{ route('admin.ressources.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
