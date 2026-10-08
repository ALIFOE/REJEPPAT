<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ContenuSeeder::class);

        // Premier compte administrateur : identifiants lus dans le .env (ADMIN_EMAIL, ADMIN_PASSWORD).
        // Aucun mot de passe n'est écrit dans le code : sans ADMIN_PASSWORD, un mot de passe aléatoire est généré et affiché.
        $email = env('ADMIN_EMAIL', 'admin@rejeppat.org');

        if (User::where('email', $email)->doesntExist()) {
            $motDePasse = env('ADMIN_PASSWORD') ?: Str::password(16);

            User::create([
                'name' => 'Administrateur REJEPPAT',
                'email' => $email,
                'password' => $motDePasse,
                'is_admin' => true,
            ]);

            if (! env('ADMIN_PASSWORD')) {
                $this->command?->warn("Compte administrateur créé : {$email} / {$motDePasse} (notez ce mot de passe).");
            }
        }
    }
}
