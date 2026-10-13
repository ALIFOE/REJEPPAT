/*
 * Arrière-plan vidéo du héros de l'accueil : les vidéos [data-hero-videos] se succèdent en fondu
 * (15 s au plus chacune). Sur téléphone, en mode économie de données ou si l'appareil demande
 * moins d'animations, seule l'image de la première vidéo est affichée et rien n'est téléchargé.
 */
(function () {
    var conteneur = document.querySelector('[data-hero-videos]');
    if (!conteneur) {
        return;
    }

    var connexion = navigator.connection || {};
    if (window.matchMedia('(max-width: 767px), (prefers-reduced-motion: reduce)').matches || connexion.saveData) {
        return;
    }

    var videos = Array.prototype.slice.call(conteneur.querySelectorAll('video'));
    var DUREE_MAX = 15;
    var courante = 0;

    function preparer(video) {
        if (!video.getAttribute('src')) {
            video.src = video.dataset.src;
            video.preload = 'auto';
        }
    }

    function lire(index) {
        var video = videos[index];
        preparer(video);
        video.currentTime = 0;
        var lecture = video.play();
        if (lecture && lecture.catch) {
            lecture.catch(function () {});
        }
        videos.forEach(function (autre, i) {
            autre.classList.toggle('actif', i === index);
        });
        // La suivante se charge pendant la lecture de la courante
        preparer(videos[(index + 1) % videos.length]);
    }

    function suivante() {
        var precedente = videos[courante];
        courante = (courante + 1) % videos.length;
        lire(courante);
        setTimeout(function () { precedente.pause(); }, 1200);
    }

    videos.forEach(function (video, index) {
        video.addEventListener('ended', function () {
            if (index === courante) {
                suivante();
            }
        });
        video.addEventListener('timeupdate', function () {
            if (index === courante && video.currentTime >= DUREE_MAX) {
                suivante();
            }
        });
    });

    lire(0);
})();
