<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DebugNotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/debug-notifications', function () {
    // Get the authenticated user
    $user = Auth::user();
    
    if (!$user) {
        return 'Please log in first';
    }
    
    // Get notifications using the relationship
    $notifications = $user->notifications()
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
    
    echo "<h1>Debugging Notifications</h1>";
    echo "<h2>User: {$user->name} (ID: {$user->id})</h2>";
    echo "<h3>Found " . $notifications->count() . " notifications</h3>";
    
    if ($notifications->count() == 0) {
        echo "<p>No notifications found.</p>";
        
        // Check if the notifications table exists and has records
        $count = DB::table('notifications')->count();
        echo "<p>Total notifications in database: {$count}</p>";
        
        // Show the class being used by the User model's notifications() method
        echo "<p>User notifications relationship class: " . get_class($user->notifications()) . "</p>";
        
        return;
    }
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th>ID</th>
            <th>Type</th>
            <th>Raw Data</th>
            <th>Title (from accessor)</th>
            <th>Message (from accessor)</th>
            <th>Created At</th>
          </tr>";
    
    foreach ($notifications as $notification) {
        $data = json_encode($notification->data);
        echo "<tr>
                <td>{$notification->id}</td>
                <td>{$notification->type}</td>
                <td><pre>{$data}</pre></td>
                <td>{$notification->title}</td>
                <td>{$notification->message}</td>
                <td>{$notification->created_at}</td>
              </tr>";
    }
    
    echo "</table>";
    
    // Check if a specific notification has the right properties
    if ($notifications->first()) {
        $firstNotification = $notifications->first();
        echo "<h3>First Notification Details:</h3>";
        echo "<pre>";
        var_dump([
            'Class' => get_class($firstNotification),
            'Has title method' => method_exists($firstNotification, 'getTitleAttribute'),
            'Has message method' => method_exists($firstNotification, 'getMessageAttribute'),
            'Has getDecodedData method' => method_exists($firstNotification, 'getDecodedData'),
            'Raw data type' => gettype($firstNotification->data),
            'Decoded data' => $firstNotification instanceof App\Models\Notification ? $firstNotification->getDecodedData() : 'N/A'
        ]);
        echo "</pre>";
    }
});

Route::get('/debug-notifications-view', [DebugNotificationController::class, 'debug'])->middleware('auth');