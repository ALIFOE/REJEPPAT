<?php

namespace App\Providers;

use App\Models\Commande;
use App\Models\DemandeService;
use App\Models\MessageContact;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compteurs du menu de l'administration (éléments à traiter)
        View::composer('admin.layout', function ($view) {
            $view->with('aTraiter', [
                'commandes' => Commande::where('statut', 'en_attente')->count(),
                'demandes' => DemandeService::where('statut', 'nouvelle')->count(),
                'messages' => MessageContact::nonLu()->count(),
            ]);
        });
    }
}
