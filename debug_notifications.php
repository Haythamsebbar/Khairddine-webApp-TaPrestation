<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Notification;

// Get a few notifications
$notifications = Notification::limit(5)->get();

foreach ($notifications as $index => $notification) {
    echo "========== Notification #" . ($index + 1) . " ==========\n";
    echo "ID: " . $notification->id . "\n";
    echo "Type: " . $notification->type . "\n";
    
    // Raw data inspection
    echo "\nRaw Data:\n";
    echo "Data Type: " . gettype($notification->data) . "\n";
    echo "Value: " . json_encode($notification->data, JSON_PRETTY_PRINT) . "\n";
    
    // Accessor methods
    echo "\nAccessor Methods:\n";
    echo "Title: \"" . $notification->title . "\"\n";
    echo "Message: \"" . $notification->message . "\"\n";
    echo "Action URL: " . ($notification->action_url ?? 'null') . "\n";
    echo "Action Text: " . ($notification->action_text ?? 'null') . "\n";
    
    // getDecodedData method
    echo "\ngetDecodedData Method:\n";
    $decodedData = $notification->getDecodedData();
    echo "Result Type: " . gettype($decodedData) . "\n";
    echo "Result: " . json_encode($decodedData, JSON_PRETTY_PRINT) . "\n";
    
    echo "\n";
}