<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the authenticated user (you'll need to simulate this)
// For testing purposes, let's just get the first prestataire
$prestataire = \App\Models\Prestataire::first();

if (!$prestataire) {
    echo "No prestataire found in database\n";
    exit;
}

echo "Testing agenda events for prestataire ID: " . $prestataire->id . "\n";

// Check if there are any bookings
$bookings = \App\Models\Booking::where('prestataire_id', $prestataire->id)->get();
echo "Total bookings: " . $bookings->count() . "\n";

foreach ($bookings as $booking) {
    echo "Booking ID: " . $booking->id . " - Start: " . $booking->start_datetime . " - Service: " . ($booking->service->title ?? 'N/A') . "\n";
}

// Check if there are any equipment rentals
$equipmentRentals = \App\Models\EquipmentRental::where('prestataire_id', $prestataire->id)->get();
echo "Total equipment rentals: " . $equipmentRentals->count() . "\n";

foreach ($equipmentRentals as $rental) {
    echo "Rental ID: " . $rental->id . " - Start: " . $rental->start_date . " - Equipment: " . ($rental->equipment->name ?? 'N/A') . "\n";
}

// Test date filtering
$start = \Carbon\Carbon::now()->startOfMonth();
$end = \Carbon\Carbon::now()->endOfMonth();

echo "\nTesting date filtering from " . $start . " to " . $end . "\n";

$bookingsInRange = \App\Models\Booking::where('prestataire_id', $prestataire->id)
    ->whereBetween('start_datetime', [$start, $end])
    ->get();
    
echo "Bookings in range: " . $bookingsInRange->count() . "\n";

$equipmentRentalsInRange = \App\Models\EquipmentRental::where('prestataire_id', $prestataire->id)
    ->where(function($query) use ($start, $end) {
        $query->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
              ->orWhereBetween('end_date', [$start->toDateString(), $end->toDateString()])
              ->orWhere(function($q) use ($start, $end) {
                  $q->where('start_date', '<=', $start->toDateString())
                    ->where('end_date', '>=', $end->toDateString());
              });
    })
    ->get();
    
echo "Equipment rentals in range: " . $equipmentRentalsInRange->count() . "\n";