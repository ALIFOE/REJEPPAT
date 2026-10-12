{{-- Formulaire d'une photo de la médiathèque (ouverte en grand dans la galerie du site) --}}
<div class="a-carte__corps a-grille a-grille--large">
    <div class="a-form">
        @include('admin.partials.photo', ['modele' => $media, 'label' => 'Photo *'])
        @include('admin.partials.champ', ['nom' => 'titre', 'label' => 'Titre', 'valeur' => $media->titre, 'requis' => true])
        @include('admin.partials.champ', ['nom' => 'description', 'label' => 'Légende', 'type' => 'textarea', 'lignes' => 4, 'valeur' => $media->description, 'aide' => 'Affichée sous la photo dans la médiathèque.'])
    </div>
    <div class="a-form">
        @include('admin.partials.champ', ['nom' => 'date', 'label' => 'Date de la prise de vue', 'type' => 'date', 'valeur' => optional($media->date)->format('Y-m-d'), 'requis' => true])
        @include('admin.partials.interrupteur', ['nom' => 'publie', 'label' => 'Publiée dans la médiathèque', 'valeur' => $media->publie])
    </div>
</div>
