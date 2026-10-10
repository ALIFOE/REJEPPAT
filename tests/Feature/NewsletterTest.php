<?php

namespace Tests\Feature;

use App\Mail\LettreInformation;
use App\Models\Abonne;
use App\Models\Lettre;
use App\Models\MessageContact;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_inscription_puis_desabonnement_et_reinscription(): void
    {
        $this->from(route('contact'))->post(route('newsletter.abonner'), ['email' => ' Afi@Example.com '])
            ->assertRedirect(route('contact').'#newsletter')
            ->assertSessionHas('newsletter');

        $abonne = Abonne::sole();
        $this->assertSame('afi@example.com', $abonne->email);

        $this->get($abonne->lienDesabonnement())->assertOk()->assertSee('afi@example.com');
        $this->assertNotNull($abonne->fresh()->desabonne_le);

        // Une nouvelle inscription réactive l'abonnement, sans doublon
        $this->post(route('newsletter.abonner'), ['email' => 'afi@example.com']);
        $this->assertSame(1, Abonne::count());
        $this->assertNull($abonne->fresh()->desabonne_le);
    }

    public function test_adresse_invalide(): void
    {
        $this->post(route('newsletter.abonner'), ['email' => 'pas-une-adresse'])
            ->assertSessionHasErrorsIn('newsletter', ['email' => 'L’adresse e-mail n’est pas valide.']);

        $this->assertSame(0, Abonne::count());
    }

    public function test_un_jeton_inconnu_ne_desabonne_personne(): void
    {
        $this->get(route('newsletter.desabonnement', 'jeton-inconnu'))->assertNotFound();
    }

    public function test_envoi_d_une_lettre_aux_seuls_abonnes_actifs(): void
    {
        Mail::fake();
        $admin = User::factory()->admin()->create();
        $actif = Abonne::create(['email' => 'actif@example.com']);
        Abonne::create(['email' => 'parti@example.com', 'desabonne_le' => now()]);

        $this->actingAs($admin)->post(route('admin.lettres.store'), [
            'sujet' => 'Les nouvelles d’octobre',
            'contenu' => '<p>Bonjour !</p><script>alert(1)</script>',
        ]);
        $lettre = Lettre::sole();
        $this->assertStringNotContainsString('script', $lettre->contenu);

        $this->post(route('admin.lettres.envoyer', $lettre))->assertRedirect(route('admin.lettres.index'));

        Mail::assertQueued(LettreInformation::class, 1);
        Mail::assertQueued(LettreInformation::class, fn (LettreInformation $mail) => $mail->hasTo('actif@example.com') && $mail->abonne->is($actif));
        $this->assertSame(1, $lettre->fresh()->destinataires);

        // Une lettre envoyée ne peut pas être renvoyée
        $this->post(route('admin.lettres.envoyer', $lettre))->assertForbidden();
    }

    public function test_export_des_abonnes(): void
    {
        $admin = User::factory()->admin()->create();
        Abonne::create(['email' => 'actif@example.com']);
        Abonne::create(['email' => 'parti@example.com', 'desabonne_le' => now()]);

        $contenu = $this->actingAs($admin)->get(route('admin.abonnes.export'))->assertOk()->streamedContent();

        $this->assertStringContainsString('actif@example.com', $contenu);
        $this->assertStringNotContainsString('parti@example.com', $contenu);
    }

    public function test_contact_rapide_sans_adresse_e_mail(): void
    {
        Mail::fake();

        $this->postJson(route('contact.rapide'), ['nom' => 'Yao', 'telephone' => '90 00 00 00', 'message' => 'Avez-vous du miel ?'])
            ->assertOk()
            ->assertJsonPath('message', 'Merci ! Votre message a bien été envoyé. Nous vous recontactons très vite.');

        $message = MessageContact::sole();
        $this->assertSame('Contact rapide', $message->objet);
        $this->assertNull($message->email);
    }

    public function test_contact_rapide_incomplet(): void
    {
        $this->postJson(route('contact.rapide'), ['nom' => 'Yao'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['telephone' => 'Ce champ est obligatoire.', 'message' => 'Ce champ est obligatoire.']);
    }
}
