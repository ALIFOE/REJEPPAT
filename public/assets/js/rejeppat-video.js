/*
 * Lecteur vidéo du site : commandes personnalisées (lecture, avance/retour de 10 s, son, volume,
 * barre de progression, plein écran), raccourcis clavier, et pas de menu « Enregistrer la vidéo ».
 * Une liste de vidéos (data-liste-videos) permet de changer la vidéo du lecteur principal.
 */
(function () {
    function formaterTemps(secondes) {
        if (!isFinite(secondes)) {
            return '0:00';
        }
        secondes = Math.floor(secondes);
        var heures = Math.floor(secondes / 3600);
        var minutes = Math.floor((secondes % 3600) / 60);
        var reste = ('0' + (secondes % 60)).slice(-2);
        return heures > 0 ? heures + ':' + ('0' + minutes).slice(-2) + ':' + reste : minutes + ':' + reste;
    }

    function initialiser(lecteur) {
        var video = lecteur.querySelector('video');
        var boutonsLecture = lecteur.querySelectorAll('[data-lecteur="lecture"]');
        var boutonSon = lecteur.querySelector('[data-lecteur="son"]');
        var volume = lecteur.querySelector('[data-lecteur="volume"]');
        var progression = lecteur.querySelector('[data-lecteur="progression"]');
        var avance = lecteur.querySelector('[data-lecteur="avance"]');
        var charge = lecteur.querySelector('[data-lecteur="charge"]');
        var curseur = lecteur.querySelector('[data-lecteur="curseur"]');
        var actuel = lecteur.querySelector('[data-lecteur="actuel"]');
        var duree = lecteur.querySelector('[data-lecteur="duree"]');
        var pleinEcran = lecteur.querySelector('[data-lecteur="plein-ecran"]');
        var minuterie = null;

        // Pas de menu contextuel (« Enregistrer la vidéo sous… ») ni de glisser-déposer de la vidéo
        lecteur.addEventListener('contextmenu', function (e) { e.preventDefault(); });
        video.addEventListener('dragstart', function (e) { e.preventDefault(); });

        function basculerLecture() {
            if (video.paused || video.ended) {
                // Une seule vidéo lue à la fois sur la page
                document.querySelectorAll('[data-lecteur-video] video').forEach(function (autre) {
                    if (autre !== video) { autre.pause(); }
                });
                video.play();
            } else {
                video.pause();
            }
        }

        function afficherCommandes() {
            lecteur.classList.add('rj-lecteur--actif');
            clearTimeout(minuterie);
            if (!video.paused) {
                minuterie = setTimeout(function () { lecteur.classList.remove('rj-lecteur--actif'); }, 2500);
            }
        }

        function majProgression() {
            var pourcentage = video.duration ? (video.currentTime / video.duration) * 100 : 0;
            avance.style.width = pourcentage + '%';
            curseur.style.left = pourcentage + '%';
            progression.setAttribute('aria-valuenow', Math.round(pourcentage));
            actuel.textContent = formaterTemps(video.currentTime);
        }

        function majSon() {
            var muet = video.muted || video.volume === 0;
            boutonSon.innerHTML = '<i class="fas ' + (muet ? 'fa-volume-mute' : video.volume < 0.5 ? 'fa-volume-down' : 'fa-volume-up') + '"></i>';
            boutonSon.setAttribute('aria-label', muet ? lecteur.dataset.libelleSon : lecteur.dataset.libelleMuet);
            volume.value = video.muted ? 0 : video.volume;
        }

        boutonsLecture.forEach(function (bouton) { bouton.addEventListener('click', basculerLecture); });
        video.addEventListener('click', basculerLecture);
        video.addEventListener('dblclick', function () { pleinEcran.click(); });

        video.addEventListener('play', function () {
            lecteur.classList.add('rj-lecteur--lecture');
            lecteur.classList.add('rj-lecteur--demarre');
            boutonsLecture[boutonsLecture.length - 1].innerHTML = '<i class="fas fa-pause"></i>';
            boutonsLecture[boutonsLecture.length - 1].setAttribute('aria-label', lecteur.dataset.libellePause);
            afficherCommandes();
        });
        video.addEventListener('pause', function () {
            lecteur.classList.remove('rj-lecteur--lecture');
            boutonsLecture[boutonsLecture.length - 1].innerHTML = '<i class="fas fa-play"></i>';
            boutonsLecture[boutonsLecture.length - 1].setAttribute('aria-label', lecteur.dataset.libelleLire);
            afficherCommandes();
        });
        video.addEventListener('waiting', function () { lecteur.classList.add('rj-lecteur--chargement'); });
        video.addEventListener('playing', function () { lecteur.classList.remove('rj-lecteur--chargement'); });
        video.addEventListener('loadedmetadata', function () { duree.textContent = formaterTemps(video.duration); majProgression(); });
        video.addEventListener('timeupdate', majProgression);
        video.addEventListener('progress', function () {
            if (video.duration && video.buffered.length) {
                charge.style.width = (video.buffered.end(video.buffered.length - 1) / video.duration) * 100 + '%';
            }
        });
        video.addEventListener('volumechange', majSon);

        lecteur.querySelector('[data-lecteur="reculer"]').addEventListener('click', function () { video.currentTime = Math.max(0, video.currentTime - 10); });
        lecteur.querySelector('[data-lecteur="avancer"]').addEventListener('click', function () { video.currentTime = Math.min(video.duration || 0, video.currentTime + 10); });
        boutonSon.addEventListener('click', function () { video.muted = !video.muted; });
        volume.addEventListener('input', function () { video.volume = parseFloat(volume.value); video.muted = video.volume === 0; });

        // Barre de progression : clic ou glissement
        function positionner(e) {
            var zone = progression.getBoundingClientRect();
            var x = (e.touches ? e.touches[0].clientX : e.clientX) - zone.left;
            if (video.duration) {
                video.currentTime = Math.min(Math.max(x / zone.width, 0), 1) * video.duration;
            }
        }
        var glisse = false;
        progression.addEventListener('pointerdown', function (e) { glisse = true; positionner(e); });
        document.addEventListener('pointermove', function (e) { if (glisse) { positionner(e); } });
        document.addEventListener('pointerup', function () { glisse = false; });
        progression.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowRight') { video.currentTime += 5; e.preventDefault(); }
            if (e.key === 'ArrowLeft') { video.currentTime -= 5; e.preventDefault(); }
        });

        // Plein écran du lecteur (avec ses commandes) ; sur iPhone, plein écran de la vidéo
        pleinEcran.addEventListener('click', function () {
            if (document.fullscreenElement || document.webkitFullscreenElement) {
                (document.exitFullscreen || document.webkitExitFullscreen).call(document);
            } else if (lecteur.requestFullscreen) {
                lecteur.requestFullscreen();
            } else if (lecteur.webkitRequestFullscreen) {
                lecteur.webkitRequestFullscreen();
            } else if (video.webkitEnterFullscreen) {
                video.webkitEnterFullscreen();
            }
        });
        ['fullscreenchange', 'webkitfullscreenchange'].forEach(function (evenement) {
            document.addEventListener(evenement, function () {
                var actif = (document.fullscreenElement || document.webkitFullscreenElement) === lecteur;
                lecteur.classList.toggle('rj-lecteur--plein-ecran', actif);
                pleinEcran.innerHTML = '<i class="fas ' + (actif ? 'fa-compress' : 'fa-expand') + '"></i>';
            });
        });

        // Raccourcis clavier quand le lecteur a le focus
        lecteur.addEventListener('keydown', function (e) {
            if (e.target.matches('input')) { return; }
            if (e.key === ' ' || e.key === 'k') { basculerLecture(); e.preventDefault(); }
            if (e.key === 'm') { video.muted = !video.muted; }
            if (e.key === 'f') { pleinEcran.click(); }
        });

        lecteur.addEventListener('mousemove', afficherCommandes);
        lecteur.addEventListener('touchstart', afficherCommandes, { passive: true });
        lecteur.addEventListener('mouseleave', function () { if (!video.paused) { lecteur.classList.remove('rj-lecteur--actif'); } });

        // Changement de vidéo depuis une liste (accueil)
        lecteur.changerVideo = function (source, affiche, titre) {
            video.pause();
            video.src = source + '#t=0.5';
            if (affiche) { video.setAttribute('poster', affiche); } else { video.removeAttribute('poster'); }
            var entete = lecteur.querySelector('.rj-lecteur__entete strong');
            if (entete) { entete.textContent = titre; }
            lecteur.classList.remove('rj-lecteur--demarre');
            video.load();
            basculerLecture();
        };

        majSon();
    }

    document.querySelectorAll('[data-lecteur-video]').forEach(initialiser);

    document.querySelectorAll('[data-liste-videos]').forEach(function (liste) {
        var lecteur = document.getElementById(liste.dataset.listeVideos);
        liste.querySelectorAll('[data-video-source]').forEach(function (element) {
            element.addEventListener('click', function () {
                liste.querySelectorAll('[data-video-source]').forEach(function (autre) { autre.classList.toggle('actif', autre === element); });
                lecteur.changerVideo(element.dataset.videoSource, element.dataset.videoAffiche, element.dataset.videoTitre);
                lecteur.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    });
})();
