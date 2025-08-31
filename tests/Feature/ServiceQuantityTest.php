<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestataire;
use App\Models\Service;
use App\Models\Category;

class ServiceQuantityTest extends TestCase
{
    use RefreshDatabase;

    protected $prestataire;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a prestataire user
        $user = User::factory()->create(['role' => 'prestataire']);
        $this->prestataire = Prestataire::factory()->create(['user_id' => $user->id]);
        
        // Create a category
        $this->category = Category::factory()->create(['name' => 'Test Category']);
    }

    /** @test */
    public function it_displays_calculated_price_for_hourly_service_with_quantity()
    {
        // Create a service with hourly pricing and quantity
        $service = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Test Service',
            'price' => 10.50,
            'price_type' => 'heure',
            'quantity' => 3,
            'category_id' => $this->category->id,
        ]);

        // Visit the service page
        $response = $this->get(route('services.show', $service));
        
        // Assert that the calculated price is displayed
        $response->assertSee('10,50€');
        $response->assertSee('3');
        $response->assertSee('31,50€'); // 10.50 * 3 = 31.50
    }

    /** @test */
    public function it_displays_calculated_price_for_daily_service_with_quantity()
    {
        // Create a service with daily pricing and quantity
        $service = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Test Service',
            'price' => 50.00,
            'price_type' => 'jour',
            'quantity' => 2,
            'category_id' => $this->category->id,
        ]);

        // Visit the service page
        $response = $this->get(route('services.show', $service));
        
        // Assert that the calculated price is displayed
        $response->assertSee('50,00€');
        $response->assertSee('2');
        $response->assertSee('100,00€'); // 50.00 * 2 = 100.00
    }

    /** @test */
    public function it_displays_example_for_hourly_service_without_quantity()
    {
        // Create a service with hourly pricing but no quantity
        $service = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Test Service',
            'price' => 15.00,
            'price_type' => 'heure',
            'quantity' => null,
            'category_id' => $this->category->id,
        ]);

        // Visit the service page
        $response = $this->get(route('services.show', $service));
        
        // Assert that the example is displayed
        $response->assertSee('15,00€');
        $response->assertSee('Pour 3 heures: 45,00€');
    }

    /** @test */
    public function it_displays_example_for_daily_service_without_quantity()
    {
        // Create a service with daily pricing but no quantity
        $service = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Test Service',
            'price' => 75.00,
            'price_type' => 'jour',
            'quantity' => null,
            'category_id' => $this->category->id,
        ]);

        // Visit the service page
        $response = $this->get(route('services.show', $service));
        
        // Assert that the example is displayed
        $response->assertSee('75,00€');
        $response->assertSee('Pour 3 jours: 225,00€');
    }

    /** @test */
    public function it_displays_fixed_price_for_non_hourly_daily_services()
    {
        // Create a service with fixed pricing
        $service = Service::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'title' => 'Test Service',
            'price' => 100.00,
            'price_type' => 'fixe',
            'quantity' => null,
            'category_id' => $this->category->id,
        ]);

        // Visit the service page
        $response = $this->get(route('services.show', $service));
        
        // Assert that only the fixed price is displayed
        $response->assertSee('100,00€ / fixe');
        $response->assertDontSee('heures');
        $response->assertDontSee('jours');
    }
}