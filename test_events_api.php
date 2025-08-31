<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Prestataire\AgendaController;

// Get a user who has a prestataire relationship
$user = \App\Models\User::whereHas('prestataire')->first();

if (!$user) {
    echo "No user with prestataire relationship found\n";
    
    // Let's try to find any prestataire and their user
    $prestataire = \App\Models\Prestataire::with('user')->first();
    if ($prestataire && $prestataire->user) {
        $user = $prestataire->user;
        echo "Found prestataire with user ID: " . $user->id . "\n";
    } else {
        echo "No prestataire with user found\n";
        exit;
    }
}

// Authenticate the user
Auth::login($user);

// Create a mock request
$request = Request::create('/prestataire/agenda/events', 'GET', [
    'start' => '2025-08-01',
    'end' => '2025-08-31'
]);

// Create controller instance and call the events method
$controller = new AgendaController();
$response = $controller->events($request);

// Output the response
echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Content:\n";
echo $response->content() . "\n";