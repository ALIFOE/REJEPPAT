<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\ProjetController;
use Illuminate\Support\Facades\Route;

// Pages du site, identiques au menu de https://rejeppat.org
Route::get('/', [PageController::class, 'accueil'])->name('home');

Route::get('/nos-offres-services', [PageController::class, 'offres'])->name('offres');
Route::get('/formulaire-de-demande-de-services', [PageController::class, 'demande'])->name('demande');
Route::post('/formulaire-de-demande-de-services', [PageController::class, 'envoyerDemande'])->middleware('throttle:5,1')->name('demande.envoyer');

Route::get('/les-fermes-ecoles', [FermeController::class, 'index'])->name('fermes.index');
Route::get('/les-fermes-ecoles/{slug}', [FermeController::class, 'show'])->name('fermes.show');

Route::get('/nos-programmes-et-projets', [ProjetController::class, 'index'])->name('projets.index');
Route::get('/nos-programmes-et-projets/{slug}', [ProjetController::class, 'show'])->name('projets.show');

Route::get('/actualites-evenements', [ActualiteController::class, 'index'])->name('actualites.index');
Route::get('/actualites-evenements/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');

// Boutique en ligne : panier, commande et suivi
Route::get('/boutique', [BoutiqueController::class, 'index'])->name('boutique.index');
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::patch('/panier', [PanierController::class, 'modifier'])->name('panier.modifier');
Route::post('/panier/{slug}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::delete('/panier/{produit}', [PanierController::class, 'retirer'])->whereNumber('produit')->name('panier.retirer');
Route::get('/commande', [CommandeController::class, 'create'])->name('commande.create');
Route::post('/commande', [CommandeController::class, 'store'])->middleware('throttle:10,1')->name('commande.store');
Route::get('/commande/{numero}/confirmation', [CommandeController::class, 'confirmation'])->name('commande.confirmation');
Route::get('/suivi-commande', [CommandeController::class, 'suivi'])->middleware('throttle:20,1')->name('commande.suivi');
Route::get('/boutique/{slug}', [BoutiqueController::class, 'show'])->name('boutique.show');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'envoyerContact'])->middleware('throttle:5,1')->name('contact.envoyer');

// Administration
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/connexion', [Admin\ConnexionController::class, 'create'])->name('connexion');
        Route::post('/connexion', [Admin\ConnexionController::class, 'store'])->middleware('throttle:6,1');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/deconnexion', [Admin\ConnexionController::class, 'destroy'])->name('deconnexion');

        Route::get('/', [Admin\TableauDeBordController::class, 'index'])->name('tableau');

        Route::post('/actualites/image', [Admin\ActualiteController::class, 'image'])->name('actualites.image');
        Route::resource('actualites', Admin\ActualiteController::class)->except('show')->parameters(['actualites' => 'actualite']);
        Route::resource('projets', Admin\ProjetController::class)->except('show');
        Route::resource('fermes', Admin\FermeController::class)->except('show');
        Route::resource('offres', Admin\OffreController::class)->except('show');
        Route::resource('produits', Admin\ProduitController::class)->except('show');

        Route::resource('commandes', Admin\CommandeController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('demandes', Admin\DemandeController::class)->only(['index', 'show', 'update', 'destroy'])->parameters(['demandes' => 'demande']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::resource('utilisateurs', Admin\UtilisateurController::class)->except('show')->parameters(['utilisateurs' => 'utilisateur']);
    });
});
