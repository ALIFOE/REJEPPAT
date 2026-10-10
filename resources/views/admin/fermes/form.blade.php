@extends('admin.layout')

@section('title', $ferme->exists ? 'Ferme école ' . $ferme->nom : 'Nouvelle ferme école')
@section('sous_titre', 'Les Fermes Écoles')

@php use App\Models\Ferme; @endphp

@section('actions')
    <a href="{{ route('admin.fermes.index') }}" class="a-btn a-btn--clair"><i class="fas fa-arrow-left"></i> Fermes écoles</a>
@endsection

@section('content')

    <form action="{{ $ferme->exists ? route('admin.fermes.update', $ferme) : route('admin.fermes.store') }}" method="post" enctype="multipart/form-data" class="a-carte">
        @csrf
        @if ($ferme->exists) @method('put') @endif

        <div class="a-carte__corps a-grille a-grille--large">
            <div class="a-form">
                @include('admin.partials.champ', ['nom' => 'nom', 'label' => 'Nom de la ferme école', 'valeur' => $ferme->nom, 'requis' => true])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'specialite', 'label' => 'Spécialité', 'valeur' => $ferme->specialite, 'requis' => true, 'aide' => 'Ex. : Élevage & apiculture'])
                    @include('admin.partials.champ', ['nom' => 'localisation', 'label' => 'Localisation', 'valeur' => $ferme->localisation, 'aide' => 'Ex. : Kpété-Kpété, Région Centrale'])
                </div>
                @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Présentation de la ferme', 'type' => 'textarea', 'lignes' => 5, 'valeur' => implode("\n", $ferme->description ?? []), 'aide' => 'Un paragraphe par ligne. Facultatif : un texte général est affiché sinon.'])
                @include('admin.partials.champ', ['nom' => 'modules', 'label' => 'Domaines de formation', 'type' => 'textarea', 'lignes' => 7, 'valeur' => implode("\n", $ferme->modules ?? []), 'requis' => true, 'aide' => 'Un module par ligne.'])
                @include('admin.partials.champ', ['nom' => 'productions', 'label' => 'Productions', 'type' => 'textarea', 'lignes' => 4, 'valeur' => implode("\n", $ferme->productions ?? []), 'aide' => 'Une production par ligne (ex. « Miel », « Poulets de chair »).'])
                @include('admin.partials.champ', ['nom' => 'actions', 'label' => 'Actions majeures', 'type' => 'textarea', 'lignes' => 4, 'valeur' => collect($ferme->actions ?? [])->map(fn ($a) => $a['titre'] . ' | ' . $a['texte'])->implode("\n"), 'aide' => 'Une action par ligne, au format : Titre | Texte'])

                <div class="a-carte" style="box-shadow: none">
                    <div class="a-carte__entete"><div><h3>Responsable et contacts</h3><p>Affichés sur la fiche de la ferme.</p></div></div>
                    <div class="a-carte__corps a-form">
                        @include('admin.partials.champ', ['nom' => 'responsable', 'label' => 'Responsable', 'valeur' => $ferme->responsable])
                        <div class="a-grille a-grille--2" style="gap: 14px">
                            @include('admin.partials.champ', ['nom' => 'telephone', 'label' => 'Téléphone', 'type' => 'tel', 'valeur' => $ferme->telephone])
                            @include('admin.partials.champ', ['nom' => 'email', 'label' => 'E-mail', 'type' => 'email', 'valeur' => $ferme->email])
                        </div>
                    </div>
                </div>

                <div class="a-carte" style="box-shadow: none">
                    <div class="a-carte__entete"><div><h3>Position sur la carte des fermes</h3><p>Facultatif : coordonnées GPS (ex. relevées sur Google Maps).</p></div></div>
                    <div class="a-carte__corps a-form">
                        <div class="a-grille a-grille--2" style="gap: 14px">
                            @include('admin.partials.champ', ['nom' => 'latitude', 'label' => 'Latitude', 'valeur' => $ferme->carte['lat'] ?? '', 'attributs' => 'inputmode="decimal" placeholder="8.50413"'])
                            @include('admin.partials.champ', ['nom' => 'longitude', 'label' => 'Longitude', 'valeur' => $ferme->carte['lng'] ?? '', 'attributs' => 'inputmode="decimal" placeholder="0.97139"'])
                        </div>
                        @include('admin.partials.champ', ['nom' => 'adresse', 'label' => 'Adresse affichée sur la carte', 'valeur' => $ferme->carte['adresse'] ?? '', 'attributs' => 'placeholder="Sise à …, canton de …, préfecture de …"'])
                    </div>
                </div>
            </div>
            <div class="a-form">
                @include('admin.partials.photo', ['modele' => $ferme, 'label' => 'Photo de la ferme'])
                @include('admin.partials.galerie', [
                    'photos' => $ferme->galerie,
                    'emplacements' => Ferme::PHOTOS_GALERIE,
                    'label' => 'Galerie photos',
                    'aide' => 'Photos des installations, des formations et des productions, affichées sur la fiche de la ferme. JPG, PNG ou WEBP, 4 Mo maximum chacune.',
                ])
                @include('admin.partials.champ', ['nom' => 'region_id', 'label' => 'Section régionale', 'type' => 'select', 'valeur' => $ferme->region_id, 'options' => ['' => '– Aucune –'] + \App\Models\Region::orderBy('ordre')->pluck('nom', 'id')->all()])
                <div class="a-grille a-grille--2" style="gap: 14px">
                    @include('admin.partials.champ', ['nom' => 'ordre', 'label' => 'Ordre d’affichage', 'type' => 'number', 'valeur' => $ferme->ordre, 'attributs' => 'min="0"'])
                    @include('admin.partials.champ', ['nom' => 'slug', 'label' => 'Adresse (slug)', 'valeur' => $ferme->slug])
                </div>
                @include('admin.partials.interrupteur', ['nom' => 'accueil', 'label' => 'Mettre en avant sur la page d’accueil', 'valeur' => $ferme->accueil, 'aide' => 'Les 4 premières fermes mises en avant sont affichées.'])
                @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée sur le site', 'valeur' => $ferme->publie])
                @if ($ferme->exists)
                    <p class="a-champ__aide">Les témoignages d’apprenants de cette ferme se gèrent dans <a href="{{ route('admin.temoignages.index', ['type' => 'apprenant']) }}">Témoignages</a>.</p>
                @endif
            </div>
        </div>

        @include('admin.partials.traduction', ['modele' => $ferme])

        <div class="a-form__pied">
            <a href="{{ route('admin.fermes.index') }}" class="a-btn a-btn--clair">Annuler</a>
            <button type="submit" class="a-btn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </form>

@endsection
