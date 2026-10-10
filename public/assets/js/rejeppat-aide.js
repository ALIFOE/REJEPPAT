/*
 * Contact rapide (bulle en bas de page) : ouverture du panneau, réponses aux questions
 * fréquentes et envoi du message sans recharger la page.
 */
(function () {
    var aide = document.querySelector('[data-contact-rapide]');
    if (!aide) {
        return;
    }

    var panneau = aide.querySelector('.rj-aide__panneau');
    var bouton = aide.querySelector('[data-aide-ouvrir]');
    var formulaire = aide.querySelector('[data-aide-formulaire]');
    var statut = aide.querySelector('[data-aide-statut]');

    function basculer(ouvrir) {
        panneau.hidden = !ouvrir;
        bouton.setAttribute('aria-expanded', ouvrir ? 'true' : 'false');
    }

    bouton.addEventListener('click', function () {
        basculer(panneau.hidden);
    });

    aide.querySelector('[data-aide-fermer]').addEventListener('click', function () {
        basculer(false);
    });

    aide.querySelectorAll('[data-aide-question]').forEach(function (question) {
        question.addEventListener('click', function () {
            aide.querySelectorAll('[data-aide-reponse]').forEach(function (reponse) {
                reponse.hidden = reponse.getAttribute('data-aide-reponse') !== question.getAttribute('data-aide-question');
            });
        });
    });

    // Message envoyé sans quitter la page ; sans JavaScript, le formulaire est envoyé normalement.
    formulaire.addEventListener('submit', function (evenement) {
        if (!window.fetch) {
            return;
        }
        evenement.preventDefault();
        statut.classList.remove('rj-aide__statut--erreur');
        statut.textContent = '…';

        fetch(formulaire.action, {
            method: 'POST',
            body: new FormData(formulaire),
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        }).then(function (reponse) {
            return reponse.json().then(function (json) {
                if (!reponse.ok) {
                    var erreurs = json.errors ? Object.values(json.errors)[0][0] : json.message;
                    throw new Error(erreurs);
                }
                statut.textContent = json.message;
                formulaire.reset();
            });
        }).catch(function (erreur) {
            statut.classList.add('rj-aide__statut--erreur');
            statut.textContent = erreur.message;
        });
    });

    // Retour d'un envoi sans JavaScript : le panneau s'ouvre sur le message de confirmation
    if (statut.textContent.trim() !== '') {
        basculer(true);
    }
})();
