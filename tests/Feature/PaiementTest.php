<?php

namespace Tests\Feature;

use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\User;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_paiement_mixx_valide_en_mode_simulation(): void
    {
        $reponse = $this->commander('mixx', '+228 90 12 34 56');

        $commande = Commande::sole();
        $reponse->assertRedirect(route('paiement.show', $commande->numero));
        $paiement = $commande->dernierPaiement;
        $this->assertSame(['mixx', '90123456', 'en_attente', $commande->total], [$paiement->operateur, $paiement->telephone, $paiement->statut, $paiement->montant]);

        $this->get(route('paiement.show', $commande->numero))->assertOk()->assertSee('Validez le paiement sur votre téléphone')->assertSee('Mode simulation');
        $this->getJson(route('paiement.statut', $commande->numero))->assertJson(['statut' => 'en_attente', 'redirection' => null]);

        $this->post(route('paiement.simuler', $commande->numero), ['accepte' => '1']);

        // La page de paiement d'une commande payée renvoie vers la confirmation
        $this->get(route('paiement.show', $commande->numero))->assertRedirect(route('commande.confirmation', $commande->numero));
        $this->getJson(route('paiement.statut', $commande->numero))
            ->assertJson(['statut' => 'paye', 'redirection' => route('commande.confirmation', $commande->numero)]);
        $commande->refresh();
        $this->assertSame('paye', $commande->statut_paiement);
        $this->assertNotNull($commande->reference_paiement);
        $this->get(route('commande.confirmation', $commande->numero))->assertOk()->assertSee('reçu par Mixx by Yas');
    }

    public function test_paiement_refuse_puis_relance_avec_flooz(): void
    {
        $this->commander('mixx', '90123456');
        $commande = Commande::sole();

        $this->post(route('paiement.simuler', $commande->numero), ['accepte' => '0']);
        $this->get(route('paiement.show', $commande->numero))->assertOk()->assertSee('Le paiement n’a pas abouti');
        $this->assertSame('annule', $commande->dernierPaiement->statut);

        $this->post(route('paiement.relancer', $commande->numero), ['operateur' => 'flooz', 'telephone_paiement' => '96 00 00 00'])
            ->assertRedirect(route('paiement.show', $commande->numero));

        $commande->refresh();
        $this->assertSame(2, $commande->paiements()->count());
        $this->assertSame(['flooz', '96000000', 'en_attente'], [$commande->dernierPaiement->operateur, $commande->dernierPaiement->telephone, $commande->dernierPaiement->statut]);
        $this->assertSame('flooz', $commande->mode_paiement);
        $this->assertSame('en_attente', $commande->statut_paiement);
    }

    public function test_paiement_reel_avec_paygate_et_rappel(): void
    {
        config(['services.paygate.mode' => 'live', 'services.paygate.token' => 'cle-api-test']);
        Http::fake([
            'paygateglobal.com/api/v1/pay' => Http::response(['tx_reference' => 987654, 'status' => 0]),
            'paygateglobal.com/api/v2/status' => Http::response(['tx_reference' => 987654, 'payment_reference' => 'MP2610091234', 'status' => 0]),
        ]);

        $this->commander('flooz', '228 96 11 22 33');
        $commande = Commande::sole();
        $paiement = $commande->dernierPaiement;

        Http::assertSent(fn (Request $requete) => $requete->url() === 'https://paygateglobal.com/api/v1/pay'
            && $requete['auth_token'] === 'cle-api-test'
            && $requete['phone_number'] === '96112233'
            && $requete['network'] === 'FLOOZ'
            && $requete['amount'] === $commande->total
            && $requete['identifier'] === $paiement->identifiant);
        $this->assertSame('987654', (string) $paiement->tx_reference);

        // Rappel de PayGate (sans jeton CSRF) : le statut est redemandé à PayGate
        $this->post(route('paiement.rappel'), ['tx_reference' => '987654', 'identifier' => $paiement->identifiant])->assertOk();

        Http::assertSent(fn (Request $requete) => $requete->url() === 'https://paygateglobal.com/api/v2/status'
            && $requete['identifier'] === $paiement->identifiant);
        $this->assertSame('paye', $paiement->fresh()->statut);
        $this->assertSame(['paye', 'MP2610091234'], [$commande->fresh()->statut_paiement, $commande->fresh()->reference_paiement]);
    }

    public function test_un_faux_rappel_ne_valide_pas_la_commande(): void
    {
        config(['services.paygate.mode' => 'live', 'services.paygate.token' => 'cle-api-test']);
        Http::fake([
            'paygateglobal.com/api/v1/pay' => Http::response(['tx_reference' => 1, 'status' => 0]),
            'paygateglobal.com/api/v2/status' => Http::response(['status' => 2]),
        ]);

        $this->commander('mixx', '90123456');
        $paiement = Paiement::sole();

        $this->post(route('paiement.rappel'), ['identifier' => $paiement->identifiant, 'status' => 0, 'payment_reference' => 'FAUX'])->assertOk();

        $this->assertSame('en_attente', $paiement->fresh()->statut);
        $this->assertSame('en_attente', $paiement->commande->statut_paiement);
    }

    public function test_demande_de_paiement_refusee_par_paygate(): void
    {
        config(['services.paygate.mode' => 'live', 'services.paygate.token' => 'cle-api-test']);
        Http::fake(['paygateglobal.com/api/v1/pay' => Http::response(['status' => 4])]);

        $this->commander('mixx', '90123456');
        $commande = Commande::sole();

        $this->assertSame('echoue', $commande->dernierPaiement->statut);
        $this->get(route('paiement.show', $commande->numero))->assertOk()
            ->assertSee('Le numéro de téléphone ou le montant n’est pas accepté par le service de paiement.')
            ->assertSee('Réessayer le paiement');
    }

    public function test_sans_cle_api_en_production_le_mobile_money_n_est_pas_propose(): void
    {
        config(['services.paygate.mode' => 'live', 'services.paygate.token' => null]);

        $produit = Produit::first();
        $this->post(route('panier.ajouter', $produit->slug));
        $this->get(route('commande.create'))->assertOk()->assertDontSee('Moov Money Flooz');

        $this->commander('flooz', '96000000', ajouterAuPanier: false)->assertSessionHasErrors('mode_paiement');
        $this->assertSame(0, Commande::count());
    }

    public function test_la_page_de_paiement_est_reservee_au_client(): void
    {
        $this->commander('mixx', '90123456');
        $numero = Commande::sole()->numero;

        $this->flushSession();
        $this->get(route('paiement.show', $numero))->assertNotFound();
        $this->getJson(route('paiement.statut', $numero))->assertNotFound();
        $this->actingAs(User::factory()->create())->post(route('paiement.simuler', $numero), ['accepte' => '1'])->assertNotFound();
    }

    public function test_l_administration_peut_verifier_un_paiement(): void
    {
        $this->commander('mixx', '90123456');
        $commande = Commande::sole();
        $this->post(route('paiement.simuler', $commande->numero), ['accepte' => '1']);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get(route('admin.commandes.show', $commande))->assertOk()->assertSee('Paiements Mobile Money en ligne');
        $this->actingAs($admin)->post(route('admin.commandes.verifier-paiement', $commande))->assertSessionHas('succes', 'Paiement confirmé par l’opérateur.');

        $this->assertTrue($commande->fresh()->estPayee());
    }

    private function commander(string $operateur, string $telephone, bool $ajouterAuPanier = true)
    {
        if ($ajouterAuPanier) {
            $this->post(route('panier.ajouter', Produit::first()->slug), ['quantite' => 2]);
        }

        return $this->post(route('commande.store'), [
            'nom' => 'Afi Mensah',
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'mode_paiement' => $operateur,
            'telephone_paiement' => $telephone,
        ]);
    }
}
