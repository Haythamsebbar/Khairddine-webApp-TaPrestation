<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestataire;
use App\Models\Client;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\EquipmentRentalRequest;

class AgendaReservationTest extends TestCase
{
    use RefreshDatabase;

    protected $prestataire;
    protected $client;
    protected $service;
    protected $equipment;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a prestataire user
        $prestataireUser = User::factory()->create(['role' => 'prestataire']);
        $this->prestataire = Prestataire::factory()->create(['user_id' => $prestataireUser->id]);

        // Create a client user
        $clientUser = User::factory()->create(['role' => 'client']);
        $this->client = Client::factory()->create(['user_id' => $clientUser->id]);

        // Create a service
        $this->service = Service::factory()->create(['prestataire_id' => $this->prestataire->id]);

        // Create an equipment
        $this->equipment = Equipment::factory()->create(['prestataire_id' => $this->prestataire->id]);
    }

    /** @test */
    public function it_can_accept_a_service_booking()
    {
        // Create a pending booking
        $booking = Booking::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'service_id' => $this->service->id,
            'status' => 'pending'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.booking.update-status', $booking), [
                'status' => 'confirmed'
            ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert the booking status was updated
        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    /** @test */
    public function it_can_reject_a_service_booking()
    {
        // Create a pending booking
        $booking = Booking::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'service_id' => $this->service->id,
            'status' => 'pending'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.booking.update-status', $booking), [
                'status' => 'cancelled',
                'reason' => 'Not available at that time'
            ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert the booking status was updated
        $booking = $booking->fresh();
        $this->assertEquals('cancelled', $booking->status);
        $this->assertEquals('Not available at that time', $booking->cancellation_reason);
    }

    /** @test */
    public function it_can_accept_an_equipment_rental_request()
    {
        // Create a pending equipment rental request
        $rentalRequest = EquipmentRentalRequest::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'equipment_id' => $this->equipment->id,
            'status' => 'pending'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.equipment-request.accept', $rentalRequest), [
                'response' => 'Request accepted'
            ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert the rental request status was updated
        $this->assertEquals('accepted', $rentalRequest->fresh()->status);
    }

    /** @test */
    public function it_can_reject_an_equipment_rental_request()
    {
        // Create a pending equipment rental request
        $rentalRequest = EquipmentRentalRequest::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'equipment_id' => $this->equipment->id,
            'status' => 'pending'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.equipment-request.reject', $rentalRequest), [
                'reason' => 'Equipment not available'
            ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert the rental request status was updated
        $rentalRequest = $rentalRequest->fresh();
        $this->assertEquals('rejected', $rentalRequest->status);
        $this->assertEquals('Equipment not available', $rentalRequest->rejection_reason);
    }

    /** @test */
    public function it_cannot_accept_a_non_pending_booking()
    {
        // Create a confirmed booking
        $booking = Booking::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'service_id' => $this->service->id,
            'status' => 'confirmed'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.booking.update-status', $booking), [
                'status' => 'confirmed'
            ]);

        // Assert the response
        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function it_cannot_reject_a_non_pending_rental_request()
    {
        // Create an accepted rental request
        $rentalRequest = EquipmentRentalRequest::factory()->create([
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'equipment_id' => $this->equipment->id,
            'status' => 'accepted'
        ]);

        // Act as the prestataire
        $response = $this->actingAs($this->prestataire->user)
            ->put(route('prestataire.agenda.equipment-request.reject', $rentalRequest), [
                'reason' => 'Changed my mind'
            ]);

        // Assert the response
        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }
}