<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ContenuSeeder::class);

        // Premier compte administrateur (à changer après la première connexion)
        User::firstOrCreate(['email' => env('ADMIN_EMAIL', 'admin@rejeppat.org')], [
            'name' => 'Administrateur REJEPPAT',
            'password' => env('ADMIN_PASSWORD', 'Rejeppat@2026'),
            'is_admin' => true,
        ]);
    }
}
