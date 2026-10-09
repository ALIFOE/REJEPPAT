<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Comptes du site : php artisan db:seed --class=UserSeeder
 *
 * - Super admin principal : identifiants lus dans le .env (ADMIN_EMAIL, ADMIN_PASSWORD).
 * - Comptes de test (un par rôle, voir COMPTES_TEST) : mot de passe commun lu dans le .env (TEST_PASSWORD).
 *   Ils ne sont jamais créés en production.
 *
 * Aucun mot de passe n'est écrit dans le code : sans valeur dans le .env, un mot de passe aléatoire
 * est généré et affiché une seule fois.
 */
class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /** Un compte de test par rôle */
    public const COMPTES_TEST = [
        ['role' => User::SUPER_ADMIN, 'name' => 'Super admin de test', 'email' => 'superadmin@rejeppat.org'],
        ['role' => User::ADMIN, 'name' => 'Administrateur de test', 'email' => 'administrateur@rejeppat.org'],
        ['role' => User::CLIENT, 'name' => 'Client de test', 'email' => 'client@rejeppat.org'],
    ];

    public function run(): void
    {
        $this->superAdminPrincipal();

        if (! app()->isProduction()) {
            $this->comptesDeTest();
        }
    }

    private function superAdminPrincipal(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@rejeppat.org');

        if (User::where('email', $email)->exists()) {
            return;
        }

        $motDePasse = env('ADMIN_PASSWORD') ?: Str::password(16);

        User::create([
            'name' => 'Administrateur REJEPPAT',
            'email' => $email,
            'password' => $motDePasse,
            'role' => User::SUPER_ADMIN,
        ])->markEmailAsVerified();

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Compte super admin créé : {$email} / {$motDePasse} (notez ce mot de passe).");
        }
    }

    /** Crée ou remet à jour les comptes de test, avec le mot de passe TEST_PASSWORD du .env. */
    private function comptesDeTest(): void
    {
        $motDePasse = env('TEST_PASSWORD') ?: Str::password(14, symbols: false);

        foreach (self::COMPTES_TEST as $compte) {
            $user = User::updateOrCreate(
                ['email' => $compte['email']],
                ['name' => $compte['name'], 'role' => $compte['role'], 'password' => $motDePasse],
            );
            $user->markEmailAsVerified();

            $this->command?->line('Compte de test « '.User::ROLES[$compte['role']][0]." » : {$compte['email']}");
        }

        if (! env('TEST_PASSWORD')) {
            $this->command?->warn("Mot de passe des comptes de test : {$motDePasse} (ou définissez TEST_PASSWORD dans le .env).");
        }
    }
}
