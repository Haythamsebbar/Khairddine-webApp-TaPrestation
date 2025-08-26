<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Prestataire;
use App\Models\Service;
use App\Models\UrgentSale;
use App\Models\Equipment;
use App\Models\Category;
use App\Models\EquipmentCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;

class SearchFiltersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $prestataire;
    protected $prestataireUser;
    protected $categories;
    protected $equipmentCategories;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur prestataire
        $this->prestataireUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'prestataire@test.com',
            'role' => 'prestataire',
            'email_verified_at' => now()
        ]);
        
        // Créer un prestataire
        $this->prestataire = Prestataire::factory()->create([
            'user_id' => $this->prestataireUser->id,
            'secteur_activite' => 'Développement Web',
            'description' => 'Expert en développement web',
            'is_approved' => true,
            'city' => 'Paris',
            'latitude' => 48.8566,
            'longitude' => 2.3522
        ]);
        
        // Créer des catégories pour les services
        $this->categories = Category::factory()->count(3)->create();
        
        // Créer des catégories pour les équipements (utiliser le même modèle Category)
        $this->equipmentCategories = Category::factory()->count(3)->create();
    }

    /** @test */
    public function it_can_filter_services_by_search_term()
    {
        // Créer des services avec différents titres
        $service1 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Développement Web',
            'price' => 100,
            'reservable' => true
        ]);
        
        $service2 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Design Graphique',
            'price' => 80,
            'reservable' => true
        ]);
        
        // Associer les catégories aux services
        $service1->categories()->attach($this->categories->first()->id);
        $service2->categories()->attach($this->categories->last()->id);
        
        // Test de recherche par mot-clé
        $response = $this->get(route('services.index', ['search' => 'Développement']));
        
        $response->assertStatus(200);
        $response->assertSee('Développement Web');
        $response->assertDontSee('Design Graphique');
    }

    /** @test */
    public function it_can_filter_services_by_category()
    {
        $service1 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service 1',
            'price' => 100
        ]);
        
        $service2 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service 2',
            'price' => 150
        ]);
        
        $service1->categories()->attach($this->categories->first()->id);
        $service2->categories()->attach($this->categories->last()->id);
        
        // Test de filtrage par catégorie
        $response = $this->get(route('services.index', ['category' => $this->categories->first()->id]));
        
        $response->assertStatus(200);
        $response->assertSee('Service 1');
        $response->assertDontSee('Service 2');
    }

    /** @test */
    public function it_can_filter_services_by_price_range()
    {
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service Pas Cher',
            'price' => 50
        ]);
        
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service Cher',
            'price' => 200
        ]);
        
        // Test de filtrage par prix maximum
        $response = $this->get(route('services.index', ['price_max' => 100]));
        
        $response->assertStatus(200);
        $response->assertSee('Service Pas Cher');
        $response->assertDontSee('Service Cher');
        
        // Test de filtrage par prix minimum
        $response = $this->get(route('services.index', ['price_min' => 100]));
        
        $response->assertStatus(200);
        $response->assertSee('Service Cher');
        $response->assertDontSee('Service Pas Cher');
    }

    /** @test */
    public function it_can_sort_services_by_price()
    {
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service A',
            'price' => 200
        ]);
        
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service B',
            'price' => 100
        ]);
        
        // Test tri par prix croissant
        $response = $this->get(route('services.index', ['sort' => 'price_asc']));
        $response->assertStatus(200);
        
        // Test tri par prix décroissant
        $response = $this->get(route('services.index', ['sort' => 'price_desc']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_urgent_sales_by_search_term()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Ordinateur Portable',
            'description' => 'Excellent état',
            'price' => 500,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Téléphone Mobile',
            'description' => 'Comme neuf',
            'price' => 300,
            'status' => 'active'
        ]);
        
        // Test de recherche par mot-clé
        $response = $this->get(route('urgent-sales.index', ['search' => 'Ordinateur']));
        
        $response->assertStatus(200);
        $response->assertSee('Ordinateur Portable');
        $response->assertDontSee('Téléphone Mobile');
    }

    /** @test */
    public function it_can_filter_urgent_sales_by_condition()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article Neuf',
            'condition' => 'new',
            'price' => 500,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article Usagé',
            'condition' => 'used',
            'price' => 300,
            'status' => 'active'
        ]);
        
        // Test de filtrage par état
        $response = $this->get(route('urgent-sales.index', ['condition' => 'new']));
        
        $response->assertStatus(200);
        $response->assertSee('Article Neuf');
        $response->assertDontSee('Article Usagé');
    }

    /** @test */
    public function it_can_filter_urgent_sales_by_price_max()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article Pas Cher',
            'price' => 100,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article Cher',
            'price' => 500,
            'status' => 'active'
        ]);
        
        // Test de filtrage par prix maximum
        $response = $this->get(route('urgent-sales.index', ['price_max' => 200]));
        
        $response->assertStatus(200);
        $response->assertSee('Article Pas Cher');
        $response->assertDontSee('Article Cher');
    }

    /** @test */
    public function it_can_sort_urgent_sales_by_price()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article A',
            'price' => 500,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article B',
            'price' => 200,
            'status' => 'active'
        ]);
        
        // Test tri par prix croissant
        $response = $this->get(route('urgent-sales.index', ['sort' => 'price_asc']));
        $response->assertStatus(200);
        
        // Test tri par prix décroissant
        $response = $this->get(route('urgent-sales.index', ['sort' => 'price_desc']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_urgent_sales_by_location()
    {
        // Créer un autre prestataire dans une autre ville
        $otherUser = User::factory()->create(['role' => 'prestataire']);
        $otherPrestataire = Prestataire::factory()->create([
            'user_id' => $otherUser->id,
            'city' => 'Lyon',
            'is_approved' => true
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Article Paris',
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $otherPrestataire->id,
            'title' => 'Article Lyon',
            'status' => 'active'
        ]);
        
        // Test de filtrage par ville
        $response = $this->get(route('urgent-sales.index', ['city' => 'Paris']));
        
        $response->assertStatus(200);
        $response->assertSee('Article Paris');
        $response->assertDontSee('Article Lyon');
    }

    /** @test */
    public function it_can_filter_equipment_by_search_term()
    {
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Perceuse Électrique',
            'description' => 'Outil professionnel',
            'price_per_day' => 25,
            'status' => 'active',
            'is_available' => true
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Scie Circulaire',
            'description' => 'Pour découpe bois',
            'price_per_day' => 30,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Test de recherche par mot-clé
        $response = $this->get(route('equipment.index', ['search' => 'Perceuse']));
        
        $response->assertStatus(200);
        $response->assertSee('Perceuse Électrique');
        $response->assertDontSee('Scie Circulaire');
    }

    /** @test */
    public function it_can_filter_equipment_by_category()
    {
        $equipment1 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement 1',
            'category_id' => $this->equipmentCategories->first()->id,
            'price_per_day' => 25,
            'status' => 'active',
            'is_available' => true
        ]);
        
        $equipment2 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement 2',
            'category_id' => $this->equipmentCategories->last()->id,
            'price_per_day' => 35,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Test de filtrage par catégorie
        $response = $this->get(route('equipment.index', ['category' => $this->equipmentCategories->first()->id]));
        
        $response->assertStatus(200);
        $response->assertSee('Équipement 1');
        $response->assertDontSee('Équipement 2');
    }

    /** @test */
    public function it_can_filter_equipment_by_price_max()
    {
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Pas Cher',
            'price_per_day' => 20,
            'status' => 'active',
            'is_available' => true
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Cher',
            'price_per_day' => 100,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Test de filtrage par prix maximum
        $response = $this->get(route('equipment.index', ['price_max' => '50']));
        
        $response->assertStatus(200);
        $response->assertSee('Équipement Pas Cher');
        $response->assertDontSee('Équipement Cher');
    }

    /** @test */
    public function it_can_filter_equipment_by_availability()
    {
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Disponible',
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Indisponible',
            'status' => 'active',
            'is_available' => false,
            'price_per_day' => 25
        ]);
        
        // Test de filtrage par disponibilité
        $response = $this->get(route('equipment.index', ['availability' => 'available']));
        
        $response->assertStatus(200);
        $response->assertSee('Équipement Disponible');
        $response->assertDontSee('Équipement Indisponible');
    }

    /** @test */
    public function it_can_sort_equipment_by_price()
    {
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement A',
            'price_per_day' => 50,
            'status' => 'active',
            'is_available' => true
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement B',
            'price_per_day' => 25,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Test tri par prix croissant
        $response = $this->get(route('equipment.index', ['sort' => 'price_asc']));
        $response->assertStatus(200);
        
        // Test tri par prix décroissant
        $response = $this->get(route('equipment.index', ['sort' => 'price_desc']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_combine_multiple_filters_for_services()
    {
        $service1 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Développement Web Premium',
            'price' => 150,
            'reservable' => true
        ]);
        
        $service2 = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Design Graphique',
            'price' => 80,
            'reservable' => true
        ]);
        
        $service1->categories()->attach($this->categories->first()->id);
        $service2->categories()->attach($this->categories->last()->id);
        
        // Test combinaison de filtres : recherche + catégorie + prix
        $response = $this->get(route('services.index', [
            'search' => 'Développement',
            'category' => $this->categories->first()->id,
            'price_max' => 200
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Développement Web Premium');
        $response->assertDontSee('Design Graphique');
    }

    /** @test */
    public function it_can_combine_multiple_filters_for_urgent_sales()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Ordinateur Portable Neuf',
            'condition' => 'new',
            'price' => 400,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Téléphone Usagé',
            'condition' => 'used',
            'price' => 200,
            'status' => 'active'
        ]);
        
        // Test combinaison de filtres : recherche + état + prix
        $response = $this->get(route('urgent-sales.index', [
            'search' => 'Ordinateur',
            'condition' => 'new',
            'price_max' => 500
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Ordinateur Portable Neuf');
        $response->assertDontSee('Téléphone Usagé');
    }

    /** @test */
    public function it_can_combine_multiple_filters_for_equipment()
    {
        $equipment1 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Perceuse Professionnelle',
            'category_id' => $this->equipmentCategories->first()->id,
            'price_per_day' => 30,
            'status' => 'active',
            'is_available' => true
        ]);
        
        $equipment2 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Scie Électrique',
            'category_id' => $this->equipmentCategories->last()->id,
            'price_per_day' => 60,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Test combinaison de filtres : recherche + catégorie + prix + disponibilité
        $response = $this->get(route('equipment.index', [
            'search' => 'Perceuse',
            'category' => $this->equipmentCategories->first()->id,
            'price_max' => '50',
            'availability' => 'available'
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Perceuse Professionnelle');
        $response->assertDontSee('Scie Électrique');
    }

    /** @test */
    public function it_returns_empty_results_when_no_matches_found()
    {
        // Test avec des termes de recherche qui ne correspondent à rien
        $response = $this->get(route('services.index', ['search' => 'TermeInexistant123']));
        $response->assertStatus(200);
        $response->assertSee('0 service(s)');
        
        $response = $this->get(route('urgent-sales.index', ['search' => 'TermeInexistant123']));
        $response->assertStatus(200);
        $response->assertSee('0 vente(s)');
        
        $response = $this->get(route('equipment.index', ['search' => 'TermeInexistant123']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_invalid_filter_parameters_gracefully()
    {
        // Test avec des paramètres invalides
        $response = $this->get(route('services.index', [
            'category' => 'invalid_id',
            'price_min' => 'not_a_number',
            'price_max' => 'not_a_number'
        ]));
        $response->assertStatus(200);
        
        $response = $this->get(route('urgent-sales.index', [
            'condition' => 'invalid_condition',
            'price_max' => 'not_a_number'
        ]));
        $response->assertStatus(200);
        
        $response = $this->get(route('equipment.index', [
            'category' => 'invalid_id',
            'price_max' => 'invalid_price'
        ]));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_services_by_location()
    {
        // Créer un autre prestataire dans une autre ville
        $otherUser = User::factory()->create(['role' => 'prestataire', 'email_verified_at' => now()]);
        $otherPrestataire = Prestataire::factory()->create([
            'user_id' => $otherUser->id,
            'city' => 'Lyon',
            'latitude' => 45.7640,
            'longitude' => 4.8357,
            'is_approved' => true
        ]);
        
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service Paris',
            'price' => 100
        ]);
        
        Service::factory()->create([
            'prestataire_id' => $otherPrestataire->id,
            'title' => 'Service Lyon',
            'price' => 100
        ]);
        
        // Test de filtrage par localisation
        $response = $this->get(route('services.index', ['location' => 'Paris']));
        
        $response->assertStatus(200);
        $response->assertSee('Service Paris');
        $response->assertDontSee('Service Lyon');
    }

    /** @test */
    public function it_can_filter_services_by_verified_prestataires_only()
    {
        // Créer un prestataire non certifié
        $unverifiedUser = User::factory()->create(['role' => 'prestataire', 'email_verified_at' => now()]);
        $unverifiedPrestataire = Prestataire::factory()->create([
            'user_id' => $unverifiedUser->id,
            'is_approved' => true,
            'is_verified' => false
        ]);
        
        // Marquer notre prestataire principal comme certifié
        $this->prestataire->update(['is_verified' => true]);
        
        Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Service Certifié',
            'price' => 100
        ]);
        
        Service::factory()->create([
            'prestataire_id' => $unverifiedPrestataire->id,
            'title' => 'Service Non Certifié',
            'price' => 100
        ]);
        
        // Test de filtrage par prestataires certifiés uniquement
        $response = $this->get(route('services.index', ['verified_only' => '1']));
        
        $response->assertStatus(200);
        $response->assertSee('Service Certifié');
        $response->assertDontSee('Service Non Certifié');
    }

    /** @test */
    public function it_can_filter_urgent_sales_by_geolocation()
    {
        // Créer un prestataire à Lyon
        $lyonUser = User::factory()->create(['role' => 'prestataire', 'email_verified_at' => now()]);
        $lyonPrestataire = Prestataire::factory()->create([
            'user_id' => $lyonUser->id,
            'city' => 'Lyon',
            'latitude' => 45.7640,
            'longitude' => 4.8357,
            'is_approved' => true
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Vente Paris',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $lyonPrestataire->id,
            'title' => 'Vente Lyon',
            'latitude' => 45.7640,
            'longitude' => 4.8357,
            'status' => 'active'
        ]);
        
        // Test de filtrage par géolocalisation avec rayon
        $response = $this->get(route('urgent-sales.index', [
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'radius' => 50
        ]));
        
        $response->assertStatus(200);
        $response->assertSee('Vente Paris');
        $response->assertDontSee('Vente Lyon');
    }

    /** @test */
    public function it_can_filter_urgent_sales_with_delivery()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Avec Livraison',
            'delivery_available' => true,
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Sans Livraison',
            'delivery_available' => false,
            'status' => 'active'
        ]);
        
        // Test de filtrage avec livraison
        $response = $this->get(route('urgent-sales.index', ['with_delivery' => '1']));
        
        $response->assertStatus(200);
        $response->assertSee('Avec Livraison');
        $response->assertDontSee('Sans Livraison');
    }

    /** @test */
    public function it_can_sort_urgent_sales_by_urgency()
    {
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Vente Normale',
            'created_at' => now()->subDays(5),
            'status' => 'active'
        ]);
        
        UrgentSale::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Vente Urgente',
            'created_at' => now()->subHours(2),
            'status' => 'active'
        ]);
        
        // Test tri par urgence
        $response = $this->get(route('urgent-sales.index', ['sort' => 'urgent']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_filter_equipment_with_delivery()
    {
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement avec Livraison',
            'delivery_available' => true,
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement sans Livraison',
            'delivery_available' => false,
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25
        ]);
        
        // Test de filtrage avec livraison
        $response = $this->get(route('equipment.index', ['delivery_included' => '1']));
        
        $response->assertStatus(200);
        $response->assertSee('Équipement avec Livraison');
        $response->assertDontSee('Équipement sans Livraison');
    }

    /** @test */
    public function it_can_filter_equipment_by_location_and_radius()
    {
        // Créer un prestataire à Marseille
        $marseilleUser = User::factory()->create(['role' => 'prestataire', 'email_verified_at' => now()]);
        $marseillePrestataire = Prestataire::factory()->create([
            'user_id' => $marseilleUser->id,
            'city' => 'Marseille',
            'latitude' => 43.2965,
            'longitude' => 5.3698,
            'is_approved' => true
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Paris',
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25
        ]);
        
        Equipment::factory()->create([
            'prestataire_id' => $marseillePrestataire->id,
            'name' => 'Équipement Marseille',
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25
        ]);
        
        // Test de filtrage par ville
        $response = $this->get(route('equipment.index', ['city' => 'Paris']));
        
        $response->assertStatus(200);
        $response->assertSee('Équipement Paris');
        $response->assertDontSee('Équipement Marseille');
    }

    /** @test */
    public function it_can_sort_equipment_by_rating()
    {
        $equipment1 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Bien Noté',
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25,
            'average_rating' => 4.5
        ]);
        
        $equipment2 = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'name' => 'Équipement Mal Noté',
            'status' => 'active',
            'is_available' => true,
            'price_per_day' => 25,
            'average_rating' => 2.0
        ]);
        
        // Test tri par note
        $response = $this->get(route('equipment.index', ['sort' => 'rating']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_sort_by_recent_for_all_sections()
    {
        // Test tri par récent pour les services
        $response = $this->get(route('services.index', ['sort' => 'recent']));
        $response->assertStatus(200);
        
        // Test tri par récent pour les ventes urgentes
        $response = $this->get(route('urgent-sales.index', ['sort' => 'recent']));
        $response->assertStatus(200);
        
        // Test tri par récent pour les équipements
        $response = $this->get(route('equipment.index', ['sort' => 'recent']));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_displays_correct_result_counts()
    {
        // Créer quelques services
        Service::factory()->count(3)->create([
            'prestataire_id' => $this->prestataire->id,
            'price' => 100
        ]);
        
        // Créer quelques ventes urgentes
        UrgentSale::factory()->count(2)->create([
            'prestataire_id' => $this->prestataire->id,
            'status' => 'active'
        ]);
        
        // Créer quelques équipements
        Equipment::factory()->count(4)->create([
            'prestataire_id' => $this->prestataire->id,
            'status' => 'active',
            'is_available' => true
        ]);
        
        // Vérifier les compteurs de résultats
        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
        $response->assertSee('3 service(s)');
        
        $response = $this->get(route('urgent-sales.index'));
        $response->assertStatus(200);
        $response->assertSee('2 vente(s)');
        
        $response = $this->get(route('equipment.index'));
        $response->assertStatus(200);
        $response->assertSee('4 équipement(s)');
    }

    /** @test */
    public function it_can_clear_and_reset_filters()
    {
        // Test que les pages se chargent correctement sans filtres
        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
        
        $response = $this->get(route('urgent-sales.index'));
        $response->assertStatus(200);
        
        $response = $this->get(route('equipment.index'));
        $response->assertStatus(200);
        
        // Test avec des filtres puis réinitialisation
        $response = $this->get(route('services.index', [
            'search' => 'test',
            'category' => $this->categories->first()->id,
            'price_max' => 200
        ]));
        $response->assertStatus(200);
        
        // Réinitialisation
        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
    }
}