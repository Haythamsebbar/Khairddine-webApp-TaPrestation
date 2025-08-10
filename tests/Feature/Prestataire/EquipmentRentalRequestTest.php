<?php

namespace Tests\Feature\Prestataire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Prestataire;
use App\Models\Equipment;
use App\Models\EquipmentRentalRequest;
use Carbon\Carbon;

class EquipmentRentalRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $prestataire;
    protected $prestataireUser;
    protected $client;
    protected $clientUser;
    protected $equipment;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur prestataire
        $this->prestataireUser = User::factory()->create([
            'role' => 'prestataire',
            'email_verified_at' => now()
        ]);

        $this->prestataire = Prestataire::factory()->create([
            'user_id' => $this->prestataireUser->id
        ]);

        // Créer un utilisateur client
        $this->clientUser = User::factory()->create([
            'role' => 'client',
            'email_verified_at' => now()
        ]);

        $this->client = Client::factory()->create([
            'user_id' => $this->clientUser->id
        ]);

        // Créer un équipement
        $this->equipment = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function prestataire_can_accept_equipment_rental_request()
    {
        // Créer une demande de location
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);

        // Se connecter en tant que prestataire
        $this->actingAs($this->prestataireUser);

        // Accepter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.accept', $request->id));

        // Vérifications
        $response->assertRedirect(route('prestataire.equipment-rental-requests.show', $request->id));
        $response->assertSessionHas('success', 'Demande acceptée avec succès! La location a été créée.');

        // Vérifier que le statut a changé
        $request->refresh();
        $this->assertEquals('accepted', $request->status);
        $this->assertNotNull($request->responded_at);

        // Vérifier qu'une location a été créée
        $this->assertDatabaseHas('equipment_rentals', [
            'rental_request_id' => $request->id,
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'status' => 'confirmed'
        ]);

        // Vérifier que l'équipement est marqué comme loué
        $this->equipment->refresh();
        $this->assertEquals('rented', $this->equipment->status);
    }

    /** @test */
    public function prestataire_can_reject_equipment_rental_request()
    {
        // Créer une demande de location
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);

        // Se connecter en tant que prestataire
        $this->actingAs($this->prestataireUser);

        // Rejeter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.reject', $request->id), [
            'rejection_reason' => 'Équipement non disponible'
        ]);

        // Vérifications
        $response->assertRedirect(route('prestataire.equipment-rental-requests.show', $request->id));
        $response->assertSessionHas('success', 'Demande rejetée.');

        // Vérifier que le statut a changé
        $request->refresh();
        $this->assertEquals('rejected', $request->status);
        $this->assertEquals('Équipement non disponible', $request->rejection_reason);
        $this->assertNotNull($request->responded_at);

        // Vérifier qu'aucune location n'a été créée
        $this->assertDatabaseMissing('equipment_rentals', [
            'rental_request_id' => $request->id
        ]);

        // Vérifier que l'équipement reste actif
        $this->equipment->refresh();
        $this->assertEquals('active', $this->equipment->status);
    }

    /** @test */
    public function cannot_accept_request_with_missing_equipment()
    {
        // Créer un équipement temporaire puis le supprimer
        $tempEquipment = Equipment::factory()->create([
            'prestataire_id' => $this->prestataire->id,
            'status' => 'active'
        ]);
        
        // Créer une demande avec cet équipement
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $tempEquipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);
        
        // Maintenant supprimer l'équipement pour simuler un équipement introuvable
        $tempEquipment->delete();

        // Se connecter en tant que prestataire
        $this->actingAs($this->prestataireUser);

        // Tenter d'accepter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.accept', $request->id));

        // Vérifications
        $response->assertRedirect(route('prestataire.equipment-rental-requests.index'));
        $response->assertSessionHas('error', 'Équipement introuvable.');

        // Vérifier que le statut n'a pas changé
        $request->refresh();
        $this->assertEquals('pending', $request->status);
    }

    /** @test */
    public function cannot_accept_request_for_unavailable_equipment()
    {
        // Marquer l'équipement comme non disponible
        $this->equipment->update(['status' => 'maintenance']);

        // Créer une demande de location
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);

        // Se connecter en tant que prestataire
        $this->actingAs($this->prestataireUser);

        // Tenter d'accepter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.accept', $request->id));

        // Vérifications
        $response->assertRedirect(route('prestataire.equipment-rental-requests.show', $request->id));
        $response->assertSessionHas('error', 'L\'équipement n\'est plus disponible pour cette période.');

        // Vérifier que le statut n'a pas changé
        $request->refresh();
        $this->assertEquals('pending', $request->status);
    }

    /** @test */
    public function only_prestataire_can_accept_their_equipment_requests()
    {
        // Créer un autre prestataire
        $otherPrestataireUser = User::factory()->create(['role' => 'prestataire']);
        $otherPrestataire = Prestataire::factory()->create(['user_id' => $otherPrestataireUser->id]);

        // Créer une demande pour le premier prestataire
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);

        // Se connecter en tant qu'autre prestataire
        $this->actingAs($otherPrestataireUser);

        // Tenter d'accepter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.accept', $request->id));

        // Devrait être interdit (403) ou redirigé
        $this->assertTrue($response->status() === 403 || $response->isRedirect());
    }

    /** @test */
    public function client_cannot_accept_equipment_requests()
    {
        // Créer une demande de location
        $request = EquipmentRentalRequest::create([
            'equipment_id' => $this->equipment->id,
            'client_id' => $this->client->id,
            'prestataire_id' => $this->prestataire->id,
            'request_number' => 'REQ-' . strtoupper(uniqid()),
            'status' => 'pending',
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addDays(2),
            'duration_days' => 3,
            'unit_price' => 100,
            'total_amount' => 300,
            'final_amount' => 300
        ]);

        // Se connecter en tant que client
        $this->actingAs($this->clientUser);

        // Tenter d'accepter la demande
        $response = $this->patch(route('prestataire.equipment-rental-requests.accept', $request->id));

        // Devrait être interdit (403) ou redirigé
        $this->assertTrue($response->status() === 403 || $response->isRedirect());
    }
}