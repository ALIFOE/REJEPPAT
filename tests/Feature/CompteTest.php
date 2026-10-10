<?php

namespace Tests\Feature;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class CompteTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_pages_publiques_s_affichent(): void
    {
        $this->get(route('connexion'))->assertOk()->assertSee('Se connecter');
        $this->get(route('inscription'))->assertOk()->assertSee('Créer mon compte');
        $this->get(route('password.request'))->assertOk()->assertSee('Mot de passe oublié');
        $this->get(route('password.reset', ['token' => 'jeton', 'email' => 'a@example.com']))->assertOk()->assertSee('a@example.com');
    }

    public function test_inscription_cree_le_compte_et_envoie_la_verification(): void
    {
        Notification::fake();

        $this->post(route('inscription'), [
            'name' => 'Afi Mensah',
            'email' => 'Afi@Example.com',
            'telephone' => '+228 90 00 00 00',
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
            'accord' => '1',
        ])->assertRedirect(route('verification.notice'));

        $user = User::sole();
        $this->assertSame('afi@example.com', $user->email);
        $this->assertSame(User::CLIENT, $user->role);
        $this->assertNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_inscription_refusee_si_l_adresse_existe_ou_mots_de_passe_differents(): void
    {
        User::factory()->create(['email' => 'pris@example.com']);

        $this->from(route('inscription'))->post(route('inscription'), [
            'name' => 'Kofi',
            'email' => 'PRIS@example.com',
            'password' => 'motdepasse123',
            'password_confirmation' => 'autre-chose',
        ])->assertRedirect(route('inscription'))->assertSessionHasErrors([
            'email' => 'Un compte existe déjà avec cette adresse e-mail.',
            'password' => 'Les deux mots de passe ne correspondent pas.',
            'accord' => 'Vous devez accepter cette condition.',
        ]);

        $this->assertGuest();
        $this->assertSame(1, User::count());
    }

    public function test_le_role_ne_peut_pas_etre_choisi_a_l_inscription(): void
    {
        Notification::fake();

        $this->post(route('inscription'), [
            'name' => 'Pirate',
            'email' => 'pirate@example.com',
            'password' => 'motdepasse123',
            'password_confirmation' => 'motdepasse123',
            'accord' => '1',
            'role' => User::SUPER_ADMIN,
        ]);

        $this->assertSame(User::CLIENT, User::sole()->role);
    }

    public function test_connexion_et_deconnexion(): void
    {
        $user = User::factory()->create();

        $this->post(route('connexion'), ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('compte.index'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('deconnexion'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_connexion_refusee_avec_un_mauvais_mot_de_passe(): void
    {
        $user = User::factory()->create();

        $this->from(route('connexion'))->post(route('connexion'), ['email' => $user->email, 'password' => 'faux'])
            ->assertRedirect(route('connexion'))
            ->assertSessionHasErrors(['email' => 'Adresse e-mail ou mot de passe incorrect.']);
        $this->assertGuest();
    }

    public function test_redirections_selon_l_etat_de_connexion(): void
    {
        $this->get(route('compte.index'))->assertRedirect(route('connexion'));
        $this->get(route('admin.tableau'))->assertRedirect(route('connexion'));

        $this->actingAs(User::factory()->create())->get(route('connexion'))->assertRedirect(route('compte.index'));
        $this->actingAs(User::factory()->unverified()->create())->get(route('compte.index'))->assertRedirect(route('verification.notice'));
    }

    public function test_les_pages_du_compte_s_affichent(): void
    {
        $this->actingAs(User::factory()->unverified()->create())->get(route('verification.notice'))->assertOk()->assertSee('Renvoyer l’e-mail');
        $this->actingAs(User::factory()->create())->get(route('compte.profil'))->assertOk()->assertSee('Changer mon mot de passe');
    }

    public function test_lien_de_verification_confirme_l_adresse(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)->get($url)->assertRedirect(route('compte.index'));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_lien_de_verification_d_un_autre_compte_refuse(): void
    {
        $user = User::factory()->unverified()->create();
        $autre = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addHour(), [
            'id' => $autre->id,
            'hash' => sha1($autre->email),
        ]);

        $this->actingAs($user)->get($url)->assertForbidden();
        $this->assertFalse($autre->fresh()->hasVerifiedEmail());
    }

    public function test_mot_de_passe_oublie_envoie_le_lien_sans_reveler_les_comptes(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $message = 'Si un compte existe avec cette adresse, vous allez recevoir un e-mail contenant un lien pour choisir un nouveau mot de passe.';

        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('succes', $message);
        Notification::assertSentTo($user, ResetPassword::class);

        $this->post(route('password.email'), ['email' => 'inconnu@example.com'])->assertSessionHas('succes', $message);
    }

    public function test_reinitialisation_du_mot_de_passe(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertRedirect(route('connexion'));

        $this->assertTrue(Hash::check('nouveau-mdp-123', $user->fresh()->password));
    }

    public function test_reinitialisation_refusee_avec_un_jeton_invalide(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.update'), [
            'token' => 'jeton-invalide',
            'email' => $user->email,
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertSessionHasErrors(['email' => 'Ce lien de réinitialisation n’est plus valide. Faites une nouvelle demande.']);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_la_commande_d_un_client_connecte_apparait_dans_son_compte(): void
    {
        $this->seed(ContenuSeeder::class);
        $user = User::factory()->create();
        $produit = Produit::first();

        $this->actingAs($user)->post(route('panier.ajouter', $produit->slug), ['quantite' => 1]);
        $this->actingAs($user)->post(route('commande.store'), [
            'nom' => $user->name,
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'mode_paiement' => 'livraison',
        ]);

        $commande = Commande::sole();
        $this->assertTrue($commande->user->is($user));

        $this->actingAs($user)->get(route('compte.index'))->assertOk()->assertSee($commande->numero);
        $this->actingAs($user)->get(route('compte.commande', $commande->numero))->assertOk()->assertSee($produit->nom);
    }

    public function test_un_client_ne_voit_pas_la_commande_d_un_autre(): void
    {
        $proprietaire = User::factory()->create();
        $commande = $proprietaire->commandes()->create([
            'numero' => 'RJ-261009-TEST',
            'nom' => 'Client',
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'sous_total' => 1000,
            'total' => 1000,
            'mode_paiement' => 'livraison',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('compte.commande', $commande->numero))
            ->assertNotFound();
    }

    public function test_modifier_le_profil_et_l_adresse_e_mail(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('compte.profil.update'), [
            'name' => 'Nouveau Nom',
            'email' => $user->email,
            'telephone' => '91000000',
        ])->assertSessionHas('succes');
        $this->assertSame('Nouveau Nom', $user->fresh()->name);
        $this->assertNotNull($user->fresh()->email_verified_at);

        $this->actingAs($user)->patch(route('compte.profil.update'), [
            'name' => 'Nouveau Nom',
            'email' => 'nouvelle@example.com',
        ])->assertRedirect(route('verification.notice'));
        $this->assertNull($user->fresh()->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_changer_de_mot_de_passe_exige_le_mot_de_passe_actuel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('compte.mot-de-passe'), [
            'current_password' => 'faux',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertSessionHasErrorsIn('motDePasse', ['current_password' => 'Le mot de passe actuel est incorrect.']);

        $this->actingAs($user)->put(route('compte.mot-de-passe'), [
            'current_password' => 'password',
            'password' => 'nouveau-mdp-123',
            'password_confirmation' => 'nouveau-mdp-123',
        ])->assertSessionHas('succes');

        $this->assertTrue(Hash::check('nouveau-mdp-123', $user->fresh()->password));
    }

    public function test_supprimer_son_compte_conserve_les_commandes(): void
    {
        $user = User::factory()->create();
        $commande = $user->commandes()->create([
            'numero' => 'RJ-261009-SUPP',
            'nom' => 'Client',
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'sous_total' => 1000,
            'total' => 1000,
            'mode_paiement' => 'livraison',
        ]);

        $this->actingAs($user)->delete(route('compte.supprimer'), ['password' => 'password'])
            ->assertRedirect(route('home'));

        $this->assertGuest();
        $this->assertModelMissing($user);
        $this->assertNull($commande->fresh()->user_id);
    }

    public function test_un_administrateur_ne_supprime_pas_son_compte_depuis_l_espace_client(): void
    {
        $admin = User::factory()->unverified()->admin()->create();

        $this->actingAs($admin)->get(route('compte.index'))->assertOk();
        $this->actingAs($admin)->delete(route('compte.supprimer'), ['password' => 'password'])
            ->assertSessionHasErrorsIn('suppression', 'password');

        $this->assertModelExists($admin);
    }
}
