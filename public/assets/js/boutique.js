/*
 * Boutique REJEPPAT : ajout au panier depuis les cartes produit.
 * Le lien porte l'adresse d'ajout (data-ajout-panier) ; on envoie un formulaire POST
 * avec la quantité saisie sur la carte. Sans JavaScript, le lien ouvre la fiche produit.
 */
(function () {
    var jeton = document.querySelector('meta[name="csrf-token"]');

    document.addEventListener('click', function (event) {
        var lien = event.target.closest('[data-ajout-panier]');
        if (!lien || !jeton) {
            return;
        }
        event.preventDefault();

        var carte = lien.closest('.single-shop-style1');
        var champ = carte ? carte.querySelector('input[name="quantite"]') : null;
        var quantite = Math.max(1, parseInt(champ ? champ.value : 1, 10) || 1);

        var form = document.createElement('form');
        form.method = 'post';
        form.action = lien.getAttribute('data-ajout-panier');
        [['_token', jeton.getAttribute('content')], ['quantite', quantite]].forEach(function (paire) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = paire[0];
            input.value = paire[1];
            form.appendChild(input);
        });
        document.body.appendChild(form);
        form.submit();
    });
})();
