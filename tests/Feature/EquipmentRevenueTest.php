<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestataire;
use App\Models\EquipmentRental;

class EquipmentRevenueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_monthly_equipment_revenue_correctly()
    {
        // Create a prestataire
        $user = User::factory()->create(['role' => 'prestataire']);
        $prestataire = Prestataire::factory()->create(['user_id' => $user->id]);

        // Create some equipment rentals
        $completedRental1 = EquipmentRental::factory()->create([
            'prestataire_id' => $prestataire->id,
            'status' => 'completed',
            'total_amount' => 100.00,
            'completed_at' => now()->startOfMonth()
        ]);

        $completedRental2 = EquipmentRental::factory()->create([
            'prestataire_id' => $prestataire->id,
            'status' => 'completed',
            'total_amount' => 150.00,
            'completed_at' => now()->startOfMonth()->addDays(15)
        ]);

        // Create a rental from a different month
        $oldRental = EquipmentRental::factory()->create([
            'prestataire_id' => $prestataire->id,
            'status' => 'completed',
            'total_amount' => 200.00,
            'completed_at' => now()->subMonth()
        ]);

        // Create a rental with a different status
        $activeRental = EquipmentRental::factory()->create([
            'prestataire_id' => $prestataire->id,
            'status' => 'active',
            'total_amount' => 75.00,
            'completed_at' => null
        ]);

        // Calculate monthly revenue
        $monthlyRevenue = $prestataire->equipmentRentals()
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->sum('total_amount');

        // Should only include the two completed rentals from this month
        $this->assertEquals(250.00, $monthlyRevenue);
    }
}