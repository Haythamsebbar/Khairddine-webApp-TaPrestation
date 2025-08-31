<?php

namespace Tests\Feature\Prestataire\UrgentSales;

use App\Models\Category;
use App\Models\Prestataire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $prestataire;
    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with prestataire role
        $this->user = User::factory()->create([
            'role' => 'prestataire'
        ]);

        // Create a prestataire
        $this->prestataire = Prestataire::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Create a category
        $this->category = Category::factory()->create([
            'parent_id' => null,
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_can_display_the_urgent_sale_creation_form()
    {
        // Acting as the prestataire user
        $this->actingAs($this->user);

        // Visit the urgent sale creation page
        $response = $this->get(route('prestataire.urgent-sales.create'));

        // Check if the page loads successfully
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_display_step1_of_urgent_sale_creation()
    {
        // Acting as the prestataire user
        $this->actingAs($this->user);

        // Visit step 1 of urgent sale creation
        $response = $this->get(route('prestataire.urgent-sales.create.step1'));

        // Check if the page loads successfully
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_display_step4_of_urgent_sale_creation()
    {
        // Acting as the prestataire user
        $this->actingAs($this->user);

        // Set up session data to simulate having completed previous steps
        session([
            'urgent_sale_data' => [
                'title' => 'Test Urgent Sale',
                'description' => 'This is a test urgent sale description',
            ]
        ]);

        // Visit step 4 of urgent sale creation
        $response = $this->get(route('prestataire.urgent-sales.create.step4'));

        // Check if the page loads successfully
        $response->assertStatus(200);
    }
}