<?php
/**
 * Notification Debug and Fix Script
 * Run this script to analyze and fix notifications in the database.
 * 
 * Usage: php notification-debug-fix.php
 */

// Setup autoloading using Laravel's bootstrap
require __DIR__ . '/vendor/autoload.php';

// Create a custom Laravel application to use facades
$app = require __DIR__ . '/bootstrap/app.php';

// Initialize the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get access to the DB facade
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

echo "===== Notification Debug and Fix Tool =====\n\n";

// 1. Check if notifications table exists
$tableExists = Schema::hasTable('notifications');
echo "Notifications table exists: " . ($tableExists ? 'Yes' : 'No') . "\n";

if (!$tableExists) {
    echo "Error: Notifications table doesn't exist. Cannot continue.\n";
    exit(1);
}

// 2. Count notifications in the database
$count = DB::table('notifications')->count();
echo "Total notifications in database: {$count}\n\n";

if ($count === 0) {
    echo "No notifications found in the database.\n";
    exit(0);
}

// 3. Sample the first few notifications
echo "Sampling first 5 notifications:\n";
$notifications = DB::table('notifications')->orderBy('created_at', 'desc')->limit(5)->get();

foreach ($notifications as $index => $notification) {
    $data = json_decode($notification->data);
    
    echo "\n--- Notification #{$index} ---\n";
    echo "ID: {$notification->id}\n";
    echo "Type: {$notification->type}\n";
    echo "Created: {$notification->created_at}\n";
    echo "Data structure:\n";
    
    // Check if data is valid JSON
    if ($data) {
        $hasTitle = isset($data->title);
        $hasMessage = isset($data->message);
        
        echo "- Contains 'title' field: " . ($hasTitle ? 'Yes' : 'No') . "\n";
        echo "- Contains 'message' field: " . ($hasMessage ? 'Yes' : 'No') . "\n";
        
        if ($hasTitle) {
            echo "- Title: {$data->title}\n";
        }
        
        if ($hasMessage) {
            echo "- Message: {$data->message}\n";
        }
        
        // Show all fields in the data
        echo "- All fields: " . implode(', ', array_keys((array)$data)) . "\n";
    } else {
        echo "- WARNING: Invalid JSON data or empty data\n";
        echo "- Raw data: {$notification->data}\n";
    }
}

// 4. Find notifications with missing title or message
echo "\n\nChecking for notifications with missing title or message...\n";

$notificationsWithoutTitle = 0;
$notificationsWithoutMessage = 0;
$totalChecked = 0;

// Process in batches to avoid memory issues
$batchSize = 100;
$offset = 0;
$typesToFix = [];
$typeStats = [];

while (true) {
    $batch = DB::table('notifications')
        ->orderBy('id')
        ->offset($offset)
        ->limit($batchSize)
        ->get();
    
    if ($batch->isEmpty()) {
        break;
    }
    
    foreach ($batch as $notification) {
        $totalChecked++;
        $data = json_decode($notification->data);
        
        // Record statistics by notification type
        if (!isset($typeStats[$notification->type])) {
            $typeStats[$notification->type] = ['count' => 0, 'missing_title' => 0, 'missing_message' => 0];
        }
        $typeStats[$notification->type]['count']++;
        
        if (!$data || !isset($data->title)) {
            $notificationsWithoutTitle++;
            $typeStats[$notification->type]['missing_title']++;
            
            // Add this type to the list to fix if not already there
            if (!in_array($notification->type, $typesToFix)) {
                $typesToFix[] = $notification->type;
            }
        }
        
        if (!$data || !isset($data->message)) {
            $notificationsWithoutMessage++;
            $typeStats[$notification->type]['missing_message']++;
            
            // Add this type to the list to fix if not already there
            if (!in_array($notification->type, $typesToFix)) {
                $typesToFix[] = $notification->type;
            }
        }
    }
    
    $offset += $batchSize;
    
    // Progress indicator
    echo ".";
    if ($offset % 1000 === 0) {
        echo " {$offset} records checked\n";
    }
}

echo "\n\n";
echo "Total notifications checked: {$totalChecked}\n";
echo "Notifications without title: {$notificationsWithoutTitle}\n";
echo "Notifications without message: {$notificationsWithoutMessage}\n\n";

// 5. Show statistics by notification type
echo "Statistics by notification type:\n";
echo str_repeat('-', 80) . "\n";
echo sprintf("%-50s | %-8s | %-12s | %-14s\n", "Type", "Count", "Missing Title", "Missing Message");
echo str_repeat('-', 80) . "\n";

foreach ($typeStats as $type => $stats) {
    $shortType = substr($type, strrpos($type, '\\') + 1);
    echo sprintf("%-50s | %-8d | %-12d | %-14d\n", 
        $shortType, 
        $stats['count'], 
        $stats['missing_title'], 
        $stats['missing_message']
    );
}

// 6. Ask if user wants to fix the notifications
echo "\n\nThe following notification types need fixes:\n";
foreach ($typesToFix as $index => $type) {
    $shortType = substr($type, strrpos($type, '\\') + 1);
    echo ($index + 1) . ". {$shortType}\n";
}

echo "\nDo you want to add default title and message to notifications? (yes/no): ";
$input = trim(readline());

if (strtolower($input) === 'yes' || strtolower($input) === 'y') {
    echo "\nFixing notifications...\n";
    
    // Process each type that needs fixing
    foreach ($typesToFix as $type) {
        $shortType = substr($type, strrpos($type, '\\') + 1);
        echo "Processing {$shortType}...\n";
        
        // Generate default title and message based on the notification type
        $defaultTitle = preg_replace('/([a-z])([A-Z])/', '$1 $2', $shortType);
        $defaultTitle = str_replace('Notification', '', $defaultTitle);
        $defaultMessage = "Vous avez reçu une notification de type {$defaultTitle}.";
        
        // Update in batches
        $updated = 0;
        $offset = 0;
        
        while (true) {
            $batch = DB::table('notifications')
                ->where('type', $type)
                ->orderBy('id')
                ->offset($offset)
                ->limit($batchSize)
                ->get();
            
            if ($batch->isEmpty()) {
                break;
            }
            
            foreach ($batch as $notification) {
                $data = json_decode($notification->data, true) ?: [];
                $needsUpdate = false;
                
                if (!isset($data['title'])) {
                    $data['title'] = $defaultTitle;
                    $needsUpdate = true;
                }
                
                if (!isset($data['message'])) {
                    $data['message'] = $defaultMessage;
                    $needsUpdate = true;
                }
                
                if ($needsUpdate) {
                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['data' => json_encode($data)]);
                    
                    $updated++;
                }
            }
            
            $offset += $batchSize;
            echo ".";
            
            if ($offset % 1000 === 0) {
                echo " {$offset} records processed\n";
            }
        }
        
        echo "\nUpdated {$updated} records for {$shortType}\n";
    }
    
    echo "\nFix completed!\n";
} else {
    echo "No changes made.\n";
}

echo "\nDone!\n";