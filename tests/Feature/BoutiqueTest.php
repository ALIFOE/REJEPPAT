<?php

namespace Tests\Feature;

use App\Models\Commande;
use App\Models\Produit;
use Database\Seeders\ContenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoutiqueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ContenuSeeder::class);
    }

    public function test_commande_complete_avec_livraison_et_mobile_money(): void
    {
        $produit = Produit::where('slug', 'carotte-biologique')->first();
        $produit->update(['stock' => 10]);

        $this->post(route('panier.ajouter', $produit->slug), ['quantite' => 3])->assertRedirect(route('panier.index'));
        $this->get(route('panier.index'))->assertOk()->assertSee($produit->nom);
        $this->get(route('commande.create'))->assertOk();

        $reponse = $this->post(route('commande.store'), [
            'nom' => 'Yao Tchalim',
            'telephone' => '+228 91 00 00 00',
            'mode_livraison' => 'sokode',
            'ville' => 'Sokodé',
            'adresse' => 'Quartier Komah',
            'mode_paiement' => 'tmoney',
            'reference_paiement' => 'TM123456',
        ]);

        $commande = Commande::with('lignes')->sole();
        $reponse->assertRedirect(route('commande.confirmation', $commande->numero));

        $this->assertSame(3 * $produit->prix, $commande->sous_total);
        $this->assertSame($commande->sous_total + config('boutique.livraisons.sokode.frais'), $commande->total);
        $this->assertSame(3, $commande->lignes->first()->quantite);
        $this->assertSame(7, $produit->fresh()->stock);

        $this->get(route('commande.confirmation', $commande->numero))->assertOk()->assertSee($commande->numero);
        $this->get(route('commande.suivi', ['numero' => $commande->numero, 'telephone' => '91000000']))->assertOk()->assertSee('En attente');
        $this->get(route('panier.index'))->assertSee('Votre panier est vide');
    }

    public function test_reference_obligatoire_pour_le_mobile_money(): void
    {
        $produit = Produit::first();
        $this->post(route('panier.ajouter', $produit->slug));

        $this->post(route('commande.store'), [
            'nom' => 'Client',
            'telephone' => '90000000',
            'mode_livraison' => 'retrait',
            'mode_paiement' => 'flooz',
        ])->assertSessionHasErrors('reference_paiement');

        $this->assertSame(0, Commande::count());
    }

    public function test_impossible_de_depasser_le_stock(): void
    {
        $produit = Produit::first();
        $produit->update(['stock' => 2]);

        $this->post(route('panier.ajouter', $produit->slug), ['quantite' => 5])->assertSessionHas('erreur_panier');
        $this->get(route('panier.index'))->assertSee('Votre panier est vide');
    }

    public function test_la_page_de_confirmation_est_reservee_au_client(): void
    {
        $commande = Commande::create([
            'numero' => 'RJ-TEST-0001', 'nom' => 'X', 'telephone' => '1', 'mode_livraison' => 'retrait',
            'sous_total' => 100, 'total' => 100, 'mode_paiement' => 'livraison',
        ]);

        $this->get(route('commande.confirmation', $commande->numero))->assertNotFound();
    }
}
