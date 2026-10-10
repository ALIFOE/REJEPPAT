<?php

use App\Http\Middleware\DefinirLangue;
use App\Http\Middleware\EstAdministrateur;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['admin' => EstAdministrateur::class]);
        // Langue du site public : français ou anglais
        $middleware->web(append: [DefinirLangue::class]);
        // Rappel envoyé par PayGate Global après un paiement (serveur à serveur, sans jeton CSRF)
        $middleware->preventRequestForgery(except: ['paiement/paygate/rappel']);
        // Une seule page de connexion pour tous les rôles ; un utilisateur déjà connecté va sur son tableau de bord
        $middleware->redirectGuestsTo(fn () => route('connexion'));
        $middleware->redirectUsersTo(fn (Request $request) => $request->user()?->tableauDeBord() ?? route('home'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
