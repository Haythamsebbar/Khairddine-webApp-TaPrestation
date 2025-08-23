<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Récupérer une vente urgente
$urgentSale = \App\Models\UrgentSale::first();

if ($urgentSale) {
    // Récupérer un utilisateur différent du prestataire
    $user = \App\Models\User::where('id', '!=', $urgentSale->prestataire->user_id)->first();
    
    if ($user) {
        // Créer un message de test
        \App\Models\Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $urgentSale->prestataire->user_id,
            'content' => "Concernant votre vente urgente '{$urgentSale->title}': Je suis intéressé par cet équipement. Pouvez-vous me donner plus d'informations?",
            'status' => 'approved'
        ]);
        
        echo "Message créé avec succès pour la vente: {$urgentSale->title}\n";
    } else {
        echo "Aucun utilisateur trouvé\n";
    }
} else {
    echo "Aucune vente urgente trouvée\n";
}