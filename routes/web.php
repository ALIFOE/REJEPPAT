<?php

use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\FermeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjetController;
use Illuminate\Support\Facades\Route;

// Pages du site, identiques au menu de https://rejeppat.org
Route::get('/', [PageController::class, 'accueil'])->name('home');

Route::get('/nos-offres-services', [PageController::class, 'offres'])->name('offres');
Route::get('/formulaire-de-demande-de-services', [PageController::class, 'demande'])->name('demande');

Route::get('/les-fermes-ecoles', [FermeController::class, 'index'])->name('fermes.index');
Route::get('/les-fermes-ecoles/{slug}', [FermeController::class, 'show'])->name('fermes.show');

Route::get('/nos-programmes-et-projets', [ProjetController::class, 'index'])->name('projets.index');
Route::get('/nos-programmes-et-projets/{slug}', [ProjetController::class, 'show'])->name('projets.show');

Route::get('/actualites-evenements', [ActualiteController::class, 'index'])->name('actualites.index');
Route::get('/actualites-evenements/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');

Route::get('/boutique', [BoutiqueController::class, 'index'])->name('boutique.index');
Route::get('/boutique/{slug}', [BoutiqueController::class, 'show'])->name('boutique.show');

Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'envoyerContact'])->middleware('throttle:5,1')->name('contact.envoyer');
