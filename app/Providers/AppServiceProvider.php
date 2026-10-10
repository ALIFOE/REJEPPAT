<?php

namespace App\Providers;

use App\Models\Commande;
use App\Models\DemandePartenariat;
use App\Models\DemandeService;
use App\Models\Membre;
use App\Models\MessageContact;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
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
                'partenariats' => DemandePartenariat::where('statut', 'nouvelle')->count(),
                'membres' => Membre::where('publie', false)->count(),
            ]);
        });

        // Comptes et rôles : réservés au super admin
        Gate::define('gerer-utilisateurs', fn (User $user) => $user->estSuperAdmin());

        // E-mails de l'espace client en français
        ResetPassword::toMailUsing(fn ($user, string $token) => (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting("Bonjour {$user->name},")
            ->line('Vous recevez cet e-mail car une demande de réinitialisation du mot de passe a été faite pour votre compte.')
            ->action('Choisir un nouveau mot de passe', route('password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]))
            ->line('Ce lien expire dans '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutes.')
            ->line('Si vous n’êtes pas à l’origine de cette demande, ignorez simplement cet e-mail : votre mot de passe reste inchangé.')
            ->salutation('L’équipe du '.config('rejeppat.name')));

        VerifyEmail::toMailUsing(fn ($user, string $url) => (new MailMessage)
            ->subject('Confirmez votre adresse e-mail')
            ->greeting("Bonjour {$user->name},")
            ->line('Merci pour votre inscription sur le site du '.config('rejeppat.name').'. Cliquez sur le bouton ci-dessous pour confirmer votre adresse e-mail.')
            ->action('Confirmer mon adresse e-mail', $url)
            ->line('Si vous n’avez pas créé de compte, ignorez simplement cet e-mail.')
            ->salutation('L’équipe du '.config('rejeppat.name')));
    }
}
