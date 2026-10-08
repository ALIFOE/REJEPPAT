@extends('admin.layout')

@section('title', $actualite->exists ? 'Modifier l’article' : 'Nouvel article')
@section('sous_titre', 'Actualités & Événements')

@php use App\Models\Actualite; @endphp

@section('actions')
    <a href="{{ route('admin.actualites.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Articles</a>
@endsection

@section('content')

    <form action="{{ $actualite->exists ? route('admin.actualites.update', $actualite) : route('admin.actualites.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($actualite->exists) @method('put') @endif

        <div class="a-carte__corps a-form" style="padding-bottom: 0">
            @include('admin.partials.champ', ['nom' => 'title', 'label' => 'Titre', 'valeur' => $actualite->title, 'requis' => true])

            {{-- Éditeur de texte (TinyMCE), barre d'outils proche de Word --}}
            <div class="a-champ @error('corps') a-champ--erreur @enderror">
                <label for="editeur-article">Texte de l’article <span aria-hidden="true">*</span></label>
                <textarea id="editeur-article" name="corps" rows="20">{{ old('corps', $actualite->exists ? $actualite->corpsHtml() : '') }}</textarea>
                <p class="a-champ__aide">Mettez en forme comme dans Word : titres, gras, couleurs, listes, tableaux, images, liens… Le premier paragraphe sert d’extrait dans les listes d’articles.</p>
                @error('corps') <p class="a-champ__erreur">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'tags', 'label' => 'Mots-clés', 'valeur' => implode(', ', $actualite->tags ?? []), 'aide' => 'Séparés par des virgules, ex. : FAO, Agroécologie, Togo'])

                <div class="a-champ @error('galerie.*') a-champ--erreur @enderror">
                    <span class="a-champ__label">Galerie photos</span>
                    @if (count($actualite->gallery ?? []))
                        <div class="a-galerie">
                            @foreach ($actualite->galerie() as $index => $photo)
                                <label title="Cocher pour retirer cette photo">
                                    <input type="checkbox" name="retirer_galerie[]" value="{{ $actualite->gallery[$index] }}">
                                    <img src="{{ $photo['mini'] }}" alt="">
                                </label>
                            @endforeach
                        </div>
                        <p class="a-champ__aide">Cochez les photos à retirer.</p>
                    @endif
                    <input type="file" name="galerie[]" accept="image/jpeg,image/png,image/webp" multiple>
                    <p class="a-champ__aide">Ajoutez jusqu’à 12 photos à la fois (4 Mo maximum chacune).</p>
                    @error('galerie.*') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $actualite, 'label' => 'Image principale'])
                @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date', 'type' => 'date', 'valeur' => optional($actualite->date)->format('Y-m-d'), 'requis' => true])

                <div class="a-champ @error('categories') a-champ--erreur @enderror">
                    <span class="a-champ__label">Catégories *</span>
                    <div class="a-cases">
                        @foreach (Actualite::CATEGORIES as $cle => $nom)
                            <label class="a-case">
                                <input type="checkbox" name="categories[]" value="{{ $cle }}" @checked(in_array($cle, old('categories', $actualite->categories ?? [])))> {{ $nom }}
                            </label>
                        @endforeach
                    </div>
                    @error('categories') <p class="a-champ__erreur">{{ $message }}</p> @enderror
                </div>

                @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $actualite->slug, 'aide' => 'Générée à partir du titre si vide.'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publié sur le site', 'valeur' => $actualite->publie, 'aide' => 'Décochez pour garder l’article en brouillon.'])
            </div>
        </div>

        <div class="a-form__pied">
            <a href="{{ route('admin.actualites.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection

@push('scripts')
    @include('admin.partials.editeur', ['selecteur' => '#editeur-article', 'televersement' => route('admin.actualites.image')])
@endpush
