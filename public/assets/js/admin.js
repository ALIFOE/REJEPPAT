/*
 * Administration REJEPPAT : menu mobile, confirmations et graphiques (Chart.js).
 * Chaque graphique est un <canvas data-graphique='{...}'> (voir admin/partials/graphique).
 */
(function () {
    // Menu latéral sur mobile
    var sidebar = document.querySelector('.a-sidebar');
    var voile = document.querySelector('.a-voile');
    document.querySelectorAll('[data-menu-admin]').forEach(function (bouton) {
        bouton.addEventListener('click', function () {
            sidebar.classList.toggle('ouvert');
            voile.classList.toggle('ouvert');
        });
    });

    // Confirmation avant suppression
    document.addEventListener('submit', function (event) {
        var message = event.target.getAttribute('data-confirmer');
        if (message && !window.confirm(message)) {
            event.preventDefault();
        }
    });

    // Aperçu de la photo choisie
    document.querySelectorAll('input[type="file"][data-apercu]').forEach(function (champ) {
        champ.addEventListener('change', function () {
            var image = document.getElementById(champ.getAttribute('data-apercu'));
            if (image && champ.files[0]) {
                image.src = URL.createObjectURL(champ.files[0]);
            }
        });
    });

    if (!window.Chart) {
        return;
    }

    // Couleurs de la charte REJEPPAT
    var COULEURS = {
        vert: '#2e6b3a',
        jaune: '#f4ca4e',
        vif: '#53d58a',
        nuit: '#1f4a2c',
        sauge: '#a9c2ad',
        bleu: '#3d7fc4',
        violet: '#8461bf',
        rouge: '#c8473b',
        gris: '#9fb8a3',
        terre: '#c98b4a'
    };
    var PALETTE = ['vert', 'jaune', 'vif', 'terre', 'bleu', 'nuit', 'violet', 'sauge', 'rouge', 'gris'];

    var couleur = function (nom, index) {
        return COULEURS[nom] || nom || COULEURS[PALETTE[index % PALETTE.length]];
    };
    var transparence = function (hex, alpha) {
        var n = parseInt(hex.slice(1), 16);
        return 'rgba(' + (n >> 16) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + alpha + ')';
    };
    var nombre = function (valeur) {
        return Math.round(valeur).toLocaleString('fr-FR').replace(/ | /g, ' ');
    };

    Chart.defaults.font.family = '"Albert Sans", sans-serif';
    Chart.defaults.font.size = 13;
    Chart.defaults.color = '#4f6b52';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.tooltip.backgroundColor = '#0c2213';
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;

    document.querySelectorAll('canvas[data-graphique]').forEach(function (canvas) {
        var config = JSON.parse(canvas.getAttribute('data-graphique'));
        var circulaire = config.type === 'doughnut' || config.type === 'pie';
        var monnaie = !!config.monnaie;

        var datasets = config.series.map(function (serie, i) {
            var base = couleur(serie.couleur, i);
            var jeu = {
                label: serie.label,
                data: serie.data,
                type: serie.type || undefined,
                yAxisID: serie.axe || 'y',
                order: serie.type === 'line' ? 0 : 1
            };

            if (circulaire) {
                jeu.backgroundColor = serie.data.map(function (_, j) { return couleur((config.couleurs || [])[j], j); });
                jeu.borderColor = '#ffffff';
                jeu.borderWidth = 3;
                jeu.hoverOffset = 6;
            } else if ((serie.type || config.type) === 'line') {
                jeu.borderColor = base;
                jeu.backgroundColor = transparence(base, .12);
                jeu.fill = true;
                jeu.tension = .35;
                jeu.borderWidth = 2.5;
                jeu.pointRadius = 3;
                jeu.pointBackgroundColor = base;
            } else {
                jeu.backgroundColor = config.couleurs
                    ? serie.data.map(function (_, j) { return couleur(config.couleurs[j], j); })
                    : base;
                jeu.borderRadius = 6;
                jeu.maxBarThickness = 38;
            }

            return jeu;
        });

        var echelles = {};
        if (!circulaire) {
            var axeValeurs = {
                beginAtZero: true,
                grid: { color: '#eaf3e4' },
                border: { display: false },
                ticks: { precision: 0, callback: function (v) { return monnaie ? nombre(v) : v; } }
            };
            var axeLibelles = { grid: { display: false }, border: { display: false } };
            if (config.horizontal) {
                // Libellés longs raccourcis pour rester lisibles dans les cartes étroites
                axeLibelles.ticks = {
                    callback: function (valeur) {
                        var libelle = String(this.getLabelForValue(valeur));
                        return libelle.length > 20 ? libelle.slice(0, 19) + '…' : libelle;
                    }
                };
            }

            if (config.horizontal) {
                echelles = { x: axeValeurs, y: axeLibelles };
            } else {
                echelles = { x: axeLibelles, y: axeValeurs };
                if (config.series.some(function (s) { return s.axe === 'y2'; })) {
                    echelles.y2 = {
                        beginAtZero: true,
                        position: 'right',
                        grid: { display: false },
                        border: { display: false },
                        ticks: { precision: 0 }
                    };
                }
            }
        }

        new Chart(canvas, {
            type: config.type,
            data: { labels: config.labels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: config.horizontal ? 'y' : 'x',
                cutout: config.type === 'doughnut' ? '68%' : undefined,
                scales: echelles,
                plugins: {
                    legend: {
                        display: circulaire || config.series.length > 1,
                        position: circulaire ? 'bottom' : 'top',
                        align: circulaire ? 'center' : 'end'
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                var valeur = circulaire ? ctx.parsed : (config.horizontal ? ctx.parsed.x : ctx.parsed.y);
                                var libelle = circulaire ? ctx.label : ctx.dataset.label;
                                var enMonnaie = monnaie && ctx.dataset.yAxisID !== 'y2';
                                return ' ' + libelle + ' : ' + nombre(valeur) + (enMonnaie ? ' CFA' : '');
                            }
                        }
                    }
                }
            }
        });
    });
})();
