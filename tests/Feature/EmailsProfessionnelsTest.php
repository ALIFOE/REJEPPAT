<?php

namespace Tests\Feature;

use App\Models\Produit;
use App\Models\Region;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\SentMessage;
use Tests\TestCase;

class EmailsProfessionnelsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_la_page_contact_affiche_les_adresses_professionnelles(): void
    {
        $this->get(route('contact'))->assertOk()
            ->assertSee('mailto:info@rejeppat.org', false)
            ->assertSee('mailto:president@rejeppat.org', false)
            ->assertSee('mailto:secretariat@rejeppat.org', false)
            ->assertSee('mailto:tresorerie@rejeppat.org', false)
            ->assertSee('Trésorerie')
            ->assertDontSee('rejeppatg@gmail.com');
    }

    public function test_un_message_de_contact_est_envoye_a_info(): void
    {
        $this->post(route('contact.envoyer'), [
            'nom' => 'Kossi',
            'email' => 'kossi@example.com',
            'objet' => 'Soutien',
            'message' => 'Bonjour',
        ])->assertSessionHas('succes');

        $this->assertSame(['info@rejeppat.org'], $this->destinataires());
    }

    public function test_une_demande_de_service_est_envoyee_au_secretariat(): void
    {
        $this->post(route('demande.envoyer'), [
            'nom' => 'Ama Dossou',
            'telephone' => '90 00 00 00',
            'service' => 'Coaching sur le parcours entrepreneurial',
            'objet' => 'Formation en maraîchage',
            'besoin' => 'Nous souhaitons former 20 jeunes.',
            'accord' => '1',
        ])->assertSessionHas('succes');

        $this->assertSame(['secretariat@rejeppat.org'], $this->destinataires());
    }

    public function test_un_profil_de_l_annuaire_est_envoye_au_secretariat(): void
    {
        $this->post(route('espace-jeunes.inscrire'), [
            'nom' => 'Essohana Pali',
            'region_id' => Region::where('slug', 'centrale')->sole()->id,
            'filiere' => 'apiculture',
            'presentation' => 'Apiculteur depuis 2019.',
            'accord' => '1',
        ])->assertSessionHas('succes');

        $this->assertSame(['secretariat@rejeppat.org'], $this->destinataires());
    }

    public function test_une_proposition_de_partenariat_est_envoyee_au_president(): void
    {
        $this->post(route('partenaires.store'), [
            'organisation' => 'Coopérative Agri Plus',
            'nom' => 'Komi Ayivi',
            'email' => 'komi@example.com',
            'type' => 'commercial',
            'message' => 'Nous souhaitons distribuer vos produits.',
        ])->assertSessionHas('succes');

        $this->assertSame(['president@rejeppat.org'], $this->destinataires());
    }

    public function test_une_commande_de_la_boutique_est_envoyee_a_la_tresorerie(): void
    {
        $produit = Produit::where('slug', 'carotte-biologique')->first();
        $this->post(route('panier.ajouter', $produit->slug), ['quantite' => 1]);

        $this->post(route('commande.store'), [
            'nom' => 'Yao Tchalim',
            'telephone' => '+228 91 00 00 00',
            'mode_livraison' => 'sokode',
            'ville' => 'Sokodé',
            'adresse' => 'Quartier Komah',
            'mode_paiement' => 'livraison',
        ])->assertRedirect();

        $this->assertSame(['tresorerie@rejeppat.org'], $this->destinataires());
    }

    /**
     * Adresses des destinataires des e-mails envoyés par le mailer « array » des tests.
     *
     * @return list<string>
     */
    private function destinataires(): array
    {
        return Mail::mailer()->getSymfonyTransport()->messages()
            ->flatMap(fn (SentMessage $message) => $message->getOriginalMessage()->getTo())
            ->map(fn ($adresse) => $adresse->getAddress())
            ->values()
            ->all();
    }
}
