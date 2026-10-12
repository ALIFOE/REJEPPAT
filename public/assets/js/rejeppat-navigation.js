/*
 * Filtres et pagination sans rechargement (site et administration).
 * Un clic sur un onglet de filtre (.rj-filtres, .a-onglets, liste [data-filtres-ajax]), sur la pagination
 * ou l'envoi d'un formulaire de recherche (form.rj-recherche, form.a-filtres, form[data-recherche-ajax]) charge la page demandée
 * en arrière-plan et remplace seulement les zones concernées : la section ou la carte qui contient
 * ces filtres, et les éléments [data-zone-ajax] (ex. titre du fil d'Ariane). L'adresse est mise à jour,
 * le bouton « Retour » du navigateur fonctionne. En cas d'erreur, la page est chargée normalement.
 */
(function () {
    if (!window.fetch || !window.DOMParser || !window.history.pushState) {
        return;
    }

    var LIENS = '.rj-filtres a, .a-onglets a, [data-filtres-ajax] a, .rj-pagination a, .a-pagination a';
    var FORMULAIRES = 'form.rj-recherche, form.a-filtres, form[data-recherche-ajax]';
    var CONTENEURS = '[data-zone-ajax], .rj-section, .a-carte';
    var requete = null;

    /** Zones remplacées : les éléments [data-zone-ajax] et les sections / cartes qui contiennent des filtres. */
    function zones(racine) {
        return Array.prototype.filter.call(racine.querySelectorAll(CONTENEURS), function (zone) {
            return zone.hasAttribute('data-zone-ajax') || zone.querySelector(LIENS + ', ' + FORMULAIRES);
        });
    }

    function memePage(url) {
        return url.origin === window.location.origin && url.pathname === window.location.pathname;
    }

    function zoneDe(element) {
        var trouvee = null;
        zones(document).forEach(function (zone) {
            if (zone.contains(element)) {
                trouvee = zone;
            }
        });
        return trouvee;
    }

    function charger(url, zoneCible, ajouterHistorique) {
        var actuelles = zones(document);
        var indexCible = actuelles.indexOf(zoneCible);

        if (requete) {
            requete.abort();
        }
        requete = new AbortController();
        actuelles.forEach(function (zone) {
            zone.classList.add('rj-zone--chargement');
            zone.setAttribute('aria-busy', 'true');
        });

        fetch(url, { credentials: 'same-origin', signal: requete.signal })
            .then(function (reponse) {
                if (!reponse.ok || !memePage(new URL(reponse.url))) {
                    throw new Error('Réponse inattendue');
                }
                return reponse.text();
            })
            .then(function (html) {
                var page = new DOMParser().parseFromString(html, 'text/html');
                var nouvelles = zones(page);
                if (nouvelles.length !== actuelles.length) {
                    throw new Error('Structure différente');
                }

                if (ajouterHistorique) {
                    if (!window.history.state || !window.history.state.rejeppat) {
                        window.history.replaceState({ rejeppat: true }, '', window.location.href);
                    }
                    window.history.pushState({ rejeppat: true }, '', url);
                }
                document.title = page.title;

                actuelles.forEach(function (zone, index) {
                    var nouvelle = document.importNode(nouvelles[index], true);
                    zone.replaceWith(nouvelle);
                    document.dispatchEvent(new CustomEvent('rejeppat:contenu-charge', { detail: nouvelle }));
                });

                // Pagination en bas de liste : on remonte au début de la zone
                var cible = zones(document)[indexCible];
                if (cible && ajouterHistorique && cible.getBoundingClientRect().top < 0) {
                    window.scrollTo({ top: window.pageYOffset + cible.getBoundingClientRect().top - 120, behavior: 'smooth' });
                }
            })
            .catch(function (erreur) {
                if (erreur.name !== 'AbortError') {
                    window.location.href = url;
                }
            });
    }

    document.addEventListener('click', function (event) {
        var lien = event.target.closest(LIENS);
        if (!lien || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey
            || (lien.target && lien.target !== '_self') || lien.hasAttribute('download')) {
            return;
        }

        var url = new URL(lien.href, window.location.href);
        var zone = zoneDe(lien);
        if (!zone || !memePage(url)) {
            return;
        }

        event.preventDefault();
        charger(url.href, zone, true);
    });

    document.addEventListener('submit', function (event) {
        var formulaire = event.target;
        if (event.defaultPrevented || !formulaire.matches(FORMULAIRES) || (formulaire.getAttribute('method') || 'get').toLowerCase() !== 'get') {
            return;
        }

        var url = new URL(formulaire.action || window.location.href, window.location.href);
        var zone = zoneDe(formulaire);
        if (!zone || !memePage(url)) {
            return;
        }

        event.preventDefault();
        url.search = new URLSearchParams(new FormData(formulaire)).toString();
        charger(url.href, zone, true);
    });

    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.rejeppat) {
            charger(window.location.href, null, false);
        }
    });
})();
