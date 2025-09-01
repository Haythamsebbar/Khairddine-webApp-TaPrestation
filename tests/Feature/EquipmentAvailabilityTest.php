<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestataire;
use App\Models\Client;
use App\Models\Equipment;
use App\Models\EquipmentRentalRequest;
use App\Models\EquipmentRental;

class EquipmentAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected $prestataire;
    protected $client;
    protected $equipment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $prestataireUser = User::factory()->create();
        $clientUser = User::factory()->create();

        // Create prestataire and client
        $this->prestataire = Prestataire::factory()->create([
            'user_id' => $prestataireUser->id
        ]);

        $this->client = Client::factory()->create([
            'user_id' => $clientUser->id
        ]);

        // Create equipment
        $this->equipment = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'status' => 'active',
            'is_available' => true
        ]);
    }

    /** @test */
    public function it_prevents_accepting_request_when_equipment_is_already_rented()
    {
        // Create an existing rental
        $existingRental = EquipmentRental::factory()->create([
            'equipment_id' => $this->equipment->id,
            'prestataire_id' => $this->prestataire->id,
            'client_id' => $this->client->id,
            'start_date' => '2025-09-10',
            'end_date' => '2025-09-15',
            'status' => 'confirmed'
        ]);

        // Create a conflicting rental request
        $rentalRequest = EquipmentRentalRequest::factory()->create([
            'equipment_id' => $this->equipment->id,
            'prestataire_id' => $this->prestataire->id,
            'client_id' => $this->client->id,
            'start_date' => '2025-09-12',
            'end_date' => '2025-09-14',
            'status' => 'pending'
        ]);

        // Acting as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->patch(route('prestataire.equipment-rental-requests.accept', $rentalRequest));

        // Assert that the request was not accepted due to conflict
        $response->assertSessionHas('error');
        $this->assertStringContainsString('déjà réservé', session('error'));
        
        // Verify the request status is still pending
        $rentalRequest->refresh();
        $this->assertEquals('pending', $rentalRequest->status);
    }

    /** @test */
    public function it_allows_accepting_request_when_no_conflicts_exist()
    {
        // Create a rental request
        $rentalRequest = EquipmentRentalRequest::factory()->create([
            'equipment_id' => $this->equipment->id,
            'prestataire_id' => $this->prestataire->id,
            'client_id' => $this->client->id,
            'start_date' => '2025-09-10',
            'end_date' => '2025-09-15',
            'status' => 'pending'
        ]);

        // Acting as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->patch(route('prestataire.equipment-rental-requests.accept', $rentalRequest));

        // Assert that the request was accepted
        $response->assertSessionHas('success');
        
        // Verify the request status is now accepted
        $rentalRequest->refresh();
        $this->assertEquals('accepted', $rentalRequest->status);
    }
}