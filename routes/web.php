<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\Compte;
use App\Http\Controllers\EspaceJeunesController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\LangueController;
use App\Http\Controllers\MediathequeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OpportuniteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

// Pages du site, identiques au menu de https://rejeppat.org
Route::get('/', [PageController::class, 'accueil'])->name('home');

Route::get('/nos-offres-services', [PageController::class, 'offres'])->name('offres');
Route::get('/axes-strategiques', [PageController::class, 'axes'])->name('axes');
Route::get('/formulaire-de-demande-de-services', [PageController::class, 'demande'])->name('demande');
Route::post('/formulaire-de-demande-de-services', [PageController::class, 'envoyerDemande'])->middleware('throttle:5,1')->name('demande.envoyer');

Route::get('/les-fermes-ecoles', [FermeController::class, 'index'])->name('fermes.index');
Route::get('/les-fermes-ecoles/{slug}', [FermeController::class, 'show'])->name('fermes.show');

Route::get('/nos-programmes-et-projets', [ProjetController::class, 'index'])->name('projets.index');
Route::get('/nos-programmes-et-projets/{slug}', [ProjetController::class, 'show'])->name('projets.show');

Route::get('/actualites-evenements', [ActualiteController::class, 'index'])->name('actualites.index');
Route::get('/actualites-evenements/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');

// Agenda (ateliers, formations, panels, foires) et médiathèque (photos, vidéos, podcasts)
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::get('/agenda/{slug}', [AgendaController::class, 'show'])->name('agenda.show');
Route::get('/mediatheque', [MediathequeController::class, 'index'])->name('mediatheque');
// Vidéos envoyées sur le site : lien signé et temporaire, lu uniquement par le lecteur du site
Route::get('/videos/{media}/lecture', [VideoController::class, 'lecture'])->whereNumber('media')->middleware('signed')->name('video.lecture');

// Sections régionales et partenaires
Route::get('/sections-regionales', [RegionController::class, 'index'])->name('regions.index');
Route::get('/sections-regionales/{slug}', [RegionController::class, 'show'])->name('regions.show');
Route::get('/partenaires', [PartenaireController::class, 'index'])->name('partenaires.index');
Route::post('/partenaires', [PartenaireController::class, 'store'])->middleware('throttle:5,1')->name('partenaires.store');

// Espace Jeunes / Membres
Route::prefix('espace-jeunes')->name('espace-jeunes.')->group(function () {
    Route::get('/', [EspaceJeunesController::class, 'index'])->name('index');
    Route::get('/annuaire', [EspaceJeunesController::class, 'annuaire'])->name('annuaire');
    Route::get('/annuaire/{slug}', [EspaceJeunesController::class, 'membre'])->name('membre');
    Route::get('/rejoindre-l-annuaire', [EspaceJeunesController::class, 'rejoindre'])->name('rejoindre');
    Route::post('/rejoindre-l-annuaire', [EspaceJeunesController::class, 'inscrire'])->middleware('throttle:5,1')->name('inscrire');
    Route::get('/temoignages', [EspaceJeunesController::class, 'temoignages'])->name('temoignages');
    Route::get('/opportunites', [OpportuniteController::class, 'index'])->name('opportunites');
    Route::get('/opportunites/{slug}', [OpportuniteController::class, 'show'])->name('opportunite');
    Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources');
    Route::get('/ressources/{ressource}/telecharger', [RessourceController::class, 'telecharger'])->whereNumber('ressource')->name('ressources.telecharger');
});

// Boutique en ligne : panier, commande et suivi
Route::get('/boutique', [BoutiqueController::class, 'index'])->name('boutique.index');
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::patch('/panier', [PanierController::class, 'modifier'])->name('panier.modifier');
Route::post('/panier/{slug}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::delete('/panier/{produit}', [PanierController::class, 'retirer'])->whereNumber('produit')->name('panier.retirer');
Route::get('/commande', [CommandeController::class, 'create'])->name('commande.create');
Route::post('/commande', [CommandeController::class, 'store'])->middleware('throttle:10,1')->name('commande.store');
Route::get('/commande/{numero}/confirmation', [CommandeController::class, 'confirmation'])->name('commande.confirmation');
Route::get('/commande/{numero}/facture', [CommandeController::class, 'facture'])->name('commande.facture');
// Paiement en ligne Mixx by Yas / Moov Money Flooz (PayGate Global)
Route::get('/commande/{numero}/paiement', [PaiementController::class, 'show'])->name('paiement.show');
Route::get('/commande/{numero}/paiement/statut', [PaiementController::class, 'statut'])->middleware('throttle:30,1')->name('paiement.statut');
Route::post('/commande/{numero}/paiement', [PaiementController::class, 'relancer'])->middleware('throttle:6,1')->name('paiement.relancer');
Route::post('/commande/{numero}/paiement/simulation', [PaiementController::class, 'simuler'])->name('paiement.simuler');
Route::post('/paiement/paygate/rappel', [PaiementController::class, 'rappel'])->middleware('throttle:60,1')->name('paiement.rappel');
Route::get('/suivi-commande', [CommandeController::class, 'suivi'])->middleware('throttle:20,1')->name('commande.suivi');
Route::get('/boutique/{slug}', [BoutiqueController::class, 'show'])->name('boutique.show');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'envoyerContact'])->middleware('throttle:5,1')->name('contact.envoyer');
Route::post('/contact-rapide', [PageController::class, 'envoyerContactRapide'])->middleware('throttle:5,1')->name('contact.rapide');

// Newsletter et langue du site
Route::post('/newsletter', [NewsletterController::class, 'abonner'])->middleware('throttle:5,1')->name('newsletter.abonner');
Route::get('/newsletter/desabonnement/{jeton}', [NewsletterController::class, 'desabonnement'])->name('newsletter.desabonnement');
Route::get('/langue/{langue}', LangueController::class)->name('langue');

// Espace client : connexion, inscription, mot de passe oublié et compte
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [Compte\ConnexionController::class, 'create'])->name('connexion');
    Route::post('/connexion', [Compte\ConnexionController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/inscription', [Compte\InscriptionController::class, 'create'])->name('inscription');
    Route::post('/inscription', [Compte\InscriptionController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/mot-de-passe-oublie', [Compte\MotDePasseController::class, 'demande'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [Compte\MotDePasseController::class, 'envoyerLien'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [Compte\MotDePasseController::class, 'edit'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [Compte\MotDePasseController::class, 'update'])->middleware('throttle:6,1')->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [Compte\ConnexionController::class, 'destroy'])->name('deconnexion');

    Route::get('/verifier-email', [Compte\VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/verifier-email/{id}/{hash}', [Compte\VerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verifier-email', [Compte\VerificationController::class, 'renvoyer'])->middleware('throttle:6,1')->name('verification.send');

    Route::prefix('mon-compte')->name('compte.')->middleware('verified')->group(function () {
        Route::get('/', [Compte\CompteController::class, 'index'])->name('index');
        Route::get('/commandes', [Compte\CompteController::class, 'commandes'])->name('commandes');
        Route::get('/commandes/{numero}', [Compte\CompteController::class, 'commande'])->name('commande');
        Route::get('/profil', [Compte\ProfilController::class, 'edit'])->name('profil');
        Route::patch('/profil', [Compte\ProfilController::class, 'update'])->name('profil.update');
        Route::put('/mot-de-passe', [Compte\ProfilController::class, 'motDePasse'])->name('mot-de-passe');
        Route::delete('/', [Compte\ProfilController::class, 'destroy'])->name('supprimer');
    });
});

// Administration : on se connecte avec la page « Connexion » commune à tous les rôles
Route::permanentRedirect('/admin/connexion', '/connexion');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [Admin\TableauDeBordController::class, 'index'])->name('tableau');

        Route::post('/actualites/image', [Admin\ActualiteController::class, 'image'])->name('actualites.image');
        Route::resource('actualites', Admin\ActualiteController::class)->except('show')->parameters(['actualites' => 'actualite']);
        Route::resource('projets', Admin\ProjetController::class)->except('show');
        Route::resource('fermes', Admin\FermeController::class)->except('show');
        Route::resource('offres', Admin\OffreController::class)->except('show');
        Route::resource('produits', Admin\ProduitController::class)->except('show');

        Route::post('/commandes/{commande}/verifier-paiement', [Admin\CommandeController::class, 'verifierPaiement'])->name('commandes.verifier-paiement');
        Route::get('/commandes/{commande}/facture', [Admin\CommandeController::class, 'facture'])->name('commandes.facture');
        Route::resource('commandes', Admin\CommandeController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('demandes', Admin\DemandeController::class)->only(['index', 'show', 'update', 'destroy'])->parameters(['demandes' => 'demande']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('partenariats', Admin\DemandePartenariatController::class)->only(['index', 'show', 'update', 'destroy']);

        // Réseau, Espace Jeunes, agenda et médiathèque
        Route::resource('regions', Admin\RegionController::class)->except('show');
        Route::resource('partenaires', Admin\PartenaireController::class)->except('show');
        Route::resource('membres', Admin\MembreController::class)->except('show');
        Route::resource('temoignages', Admin\TemoignageController::class)->except('show');
        Route::resource('opportunites', Admin\OpportuniteController::class)->except('show');
        Route::resource('ressources', Admin\RessourceController::class)->except('show');
        Route::resource('evenements', Admin\EvenementController::class)->except('show');
        Route::resource('medias', Admin\MediaController::class)->except('show')->parameters(['medias' => 'media']);

        // Newsletter
        Route::get('/newsletter/abonnes', [Admin\AbonneController::class, 'index'])->name('abonnes.index');
        Route::get('/newsletter/abonnes/export', [Admin\AbonneController::class, 'export'])->name('abonnes.export');
        Route::delete('/newsletter/abonnes/{abonne}', [Admin\AbonneController::class, 'destroy'])->name('abonnes.destroy');
        Route::post('/newsletter/lettres/{lettre}/envoyer', [Admin\LettreController::class, 'envoyer'])->name('lettres.envoyer');
        Route::resource('newsletter/lettres', Admin\LettreController::class)->except('show')->names('lettres')->parameters(['lettres' => 'lettre']);

        // Profil du super admin ou de l'administrateur connecté
        Route::get('/profil', [Admin\ProfilController::class, 'edit'])->name('profil');
        Route::put('/profil', [Admin\ProfilController::class, 'update'])->name('profil.update');
        Route::put('/profil/mot-de-passe', [Admin\ProfilController::class, 'motDePasse'])->name('profil.mot-de-passe');

        // Comptes et rôles : réservé au super admin
        Route::resource('utilisateurs', Admin\UtilisateurController::class)->except('show')->parameters(['utilisateurs' => 'utilisateur'])
            ->middleware('can:gerer-utilisateurs');
    });
});
