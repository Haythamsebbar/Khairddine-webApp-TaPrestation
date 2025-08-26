<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$notifications = DB::table('notifications')->get();

echo "Total notifications found: " . $notifications->count() . "\n\n";

if ($notifications->isEmpty()) {
    echo "No notifications found in the database.\n";
} else {
    foreach ($notifications as $index => $notification) {
        echo "Notification #" . ($index + 1) . "\n";
        echo "ID: " . $notification->id . "\n";
        echo "Type: " . $notification->type . "\n";
        echo "Notifiable: " . $notification->notifiable_type . " (ID: " . $notification->notifiable_id . ")\n";
        echo "Created: " . $notification->created_at . "\n";
        echo "Read: " . ($notification->read_at ?: 'No') . "\n";
        
        echo "Data: ";
        $data = json_decode($notification->data, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            echo "\n";
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    echo "  - $key: " . json_encode($value) . "\n";
                } else {
                    echo "  - $key: $value\n";
                }
            }
        } else {
            echo "Invalid JSON: " . $notification->data . "\n";
            echo "JSON Error: " . json_last_error_msg() . "\n";
        }
        echo "\n-------------------------------------------\n\n";
    }
}