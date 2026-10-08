{{-- Boutons Modifier / Supprimer d'une ligne. Paramètres : $modifier (url), $supprimer (url), $confirmation, $voir (url, optionnel) --}}
<div class="a-table__actions">
    @isset($voir)
        <a href="{{ $voir }}" class="a-btn a-btn--clair a-btn--icone" title="Voir sur le site" target="_blank" rel="noopener"><i class="fas fa-eye"></i></a>
    @endisset
    @isset($modifier)
        <a href="{{ $modifier }}" class="a-btn a-btn--clair a-btn--icone" title="Modifier"><i class="fas fa-pen"></i></a>
    @endisset
    @isset($supprimer)
        <form action="{{ $supprimer }}" method="post" data-confirmer="{{ $confirmation ?? 'Supprimer définitivement cet élément ?' }}">
            @csrf
            @method('delete')
            <button type="submit" class="a-btn a-btn--danger a-btn--icone" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
        </form>
    @endisset
</div>
