<?php

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
        // Visiteur non connecté : connexion de l'administration ou de l'espace client selon la page demandée
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin', 'admin/*') ? route('admin.connexion') : route('connexion'));
        $middleware->redirectUsersTo(fn (Request $request) => $request->is('admin', 'admin/*') && $request->user()?->estAdministrateur()
            ? route('admin.tableau')
            : route('compte.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
