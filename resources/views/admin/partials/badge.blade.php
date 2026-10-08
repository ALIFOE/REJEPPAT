{{-- Badge de statut. Paramètres : $statuts (constante STATUTS du modèle), $valeur --}}
@php [$libelle, $couleur] = $statuts[$valeur] ?? [$valeur, 'gris']; @endphp
<span class="a-badge a-badge--{{ $couleur }}">{{ $libelle }}</span>
