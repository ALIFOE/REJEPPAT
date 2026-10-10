<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_connexion_commune_envoie_chaque_role_sur_son_tableau_de_bord(): void
    {
        foreach ([
            [User::factory()->superAdmin()->create(), route('admin.tableau')],
            [User::factory()->admin()->create(), route('admin.tableau')],
            [User::factory()->create(), route('compte.index')],
        ] as [$user, $tableau]) {
            $this->post(route('connexion'), ['email' => $user->email, 'password' => 'password'])->assertRedirect($tableau);
            $this->assertAuthenticatedAs($user);
            $this->post(route('deconnexion'));
        }
    }

    public function test_l_ancienne_adresse_de_connexion_de_l_administration_redirige(): void
    {
        $this->get('/admin/connexion')->assertRedirect('/connexion');
    }

    public function test_un_utilisateur_connecte_qui_ouvre_la_connexion_va_sur_son_tableau_de_bord(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get(route('connexion'))->assertRedirect(route('admin.tableau'));
    }

    public function test_un_administrateur_accede_a_l_administration_mais_pas_aux_comptes(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.tableau'))->assertOk()->assertDontSee('Utilisateurs &amp; rôles', false);
        $this->actingAs($admin)->get(route('admin.utilisateurs.index'))->assertForbidden();
        $this->actingAs($admin)->post(route('admin.utilisateurs.store'), [
            'name' => 'Intrus',
            'email' => 'intrus@example.com',
            'role' => User::SUPER_ADMIN,
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
        ])->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'intrus@example.com']);
    }

    public function test_le_super_admin_cree_un_compte_avec_un_role(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->get(route('admin.utilisateurs.index'))->assertOk();
        $this->actingAs($superAdmin)->post(route('admin.utilisateurs.store'), [
            'name' => 'Nouvel admin',
            'email' => 'nouvel.admin@example.com',
            'role' => User::ADMIN,
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
        ])->assertRedirect(route('admin.utilisateurs.index'));

        $cree = User::where('email', 'nouvel.admin@example.com')->sole();
        $this->assertSame(User::ADMIN, $cree->role);
        $this->assertNotNull($cree->email_verified_at);
    }

    public function test_le_super_admin_change_le_role_d_un_client(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $client = User::factory()->create();

        $this->actingAs($superAdmin)->put(route('admin.utilisateurs.update', $client), [
            'name' => $client->name,
            'email' => $client->email,
            'role' => User::ADMIN,
        ])->assertRedirect(route('admin.utilisateurs.index'));

        $this->assertSame(User::ADMIN, $client->fresh()->role);
    }

    public function test_un_role_inconnu_est_refuse(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $client = User::factory()->create();

        $this->actingAs($superAdmin)->put(route('admin.utilisateurs.update', $client), [
            'name' => $client->name,
            'email' => $client->email,
            'role' => 'dieu',
        ])->assertSessionHasErrors(['role' => 'Veuillez choisir un rôle.']);

        $this->assertSame(User::CLIENT, $client->fresh()->role);
    }

    public function test_le_super_admin_ne_peut_pas_modifier_son_propre_role(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->put(route('admin.utilisateurs.update', $superAdmin), [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => User::CLIENT,
        ])->assertSessionHasErrors(['role' => 'Vous ne pouvez pas modifier votre propre rôle.']);

        $this->assertSame(User::SUPER_ADMIN, $superAdmin->fresh()->role);
    }

    public function test_chaque_role_a_son_tableau_de_bord(): void
    {
        $this->actingAs(User::factory()->superAdmin()->create())->get(route('admin.tableau'))
            ->assertOk()->assertViewIs('admin.tableaux.super-admin')->assertSee('Comptes par rôle')->assertSee('Équipe d’administration');

        $this->actingAs(User::factory()->admin()->create())->get(route('admin.tableau'))
            ->assertOk()->assertViewIs('admin.tableaux.administrateur')->assertSee('Commandes en attente')->assertDontSee('Comptes par rôle');

        $this->actingAs(User::factory()->create())->get(route('compte.index'))
            ->assertOk()->assertViewIs('compte.index')->assertSee('Commandes passées');
    }

    public function test_tableau_de_bord_client_avec_une_commande_en_cours(): void
    {
        $client = User::factory()->create();
        $commande = $client->commandes()->create([
            'numero' => 'RJ-261009-ENCO',
            'nom' => $client->name,
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'sous_total' => 2500,
            'total' => 2500,
            'mode_paiement' => 'livraison',
            'statut' => 'confirmee',
        ]);

        $this->actingAs($client)->get(route('compte.index'))
            ->assertOk()->assertSee('Ma commande en cours')->assertSee($commande->numero)->assertSee('2 500 CFA');
        $this->actingAs($client)->get(route('compte.commandes'))->assertOk()->assertSee($commande->numero);
    }

    public function test_profil_de_l_administrateur(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.profil'))->assertOk()->assertSee('Mes droits')->assertSee('Administrateur');

        $this->actingAs($admin)->put(route('admin.profil.update'), [
            'name' => 'Nom modifié',
            'email' => $admin->email,
            'telephone' => '90000000',
        ])->assertSessionHas('succes');
        $this->assertSame('Nom modifié', $admin->fresh()->name);

        $this->actingAs($admin)->put(route('admin.profil.mot-de-passe'), [
            'current_password' => 'faux',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertSessionHasErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);

        $this->actingAs($admin)->put(route('admin.profil.mot-de-passe'), [
            'current_password' => 'password',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertSessionHas('succes');
        $this->assertTrue(Hash::check('nouveau-mdp-123', $admin->fresh()->password));
    }

    public function test_un_client_n_a_pas_de_profil_dans_l_administration(): void
    {
        $this->actingAs(User::factory()->create())->get(route('admin.profil'))->assertRedirect(route('compte.index'));
        $this->actingAs(User::factory()->create())->get(route('compte.profil'))->assertOk()->assertSee('Mon rôle')->assertSee('Client');
    }

    public function test_le_seeder_cree_le_super_admin_et_un_compte_de_test_par_role_sans_doublon(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertSame(User::SUPER_ADMIN, User::where('email', 'admin@rejeppat.org')->sole()->role);

        foreach (UserSeeder::COMPTES_TEST as $compte) {
            $user = User::where('email', $compte['email'])->sole();
            $this->assertSame($compte['role'], $user->role);
            $this->assertNotNull($user->email_verified_at);
        }

        $this->assertSame(4, User::count());
    }
}
