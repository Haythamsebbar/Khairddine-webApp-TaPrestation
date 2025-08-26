<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Notification;

echo "Starting notification repair script...\n";
$count = 0;

// Fix for NewBookingNotification
$newBookingNotifications = Notification::where('type', 'App\\Notifications\\NewBookingNotification')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $newBookingNotifications->count() . " NewBookingNotification records to fix.\n";

foreach ($newBookingNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = 'Nouvelle réservation';
        $data['message'] = 'Vous avez reçu une nouvelle réservation pour ' . ($data['service_title'] ?? 'un service');
        if (!isset($data['url']) && isset($data['booking_id'])) {
            $data['url'] = route('bookings.show', $data['booking_id']);
        }
        $data['type'] = 'new_booking';
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

// Fix for EquipmentRentalRequestConfirmationNotification
$rentalConfirmationNotifications = Notification::where('type', 'App\\Notifications\\EquipmentRentalRequestConfirmationNotification')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $rentalConfirmationNotifications->count() . " EquipmentRentalRequestConfirmationNotification records to fix.\n";

foreach ($rentalConfirmationNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = 'Confirmation de demande de location';
        if (!isset($data['type'])) {
            $data['type'] = 'equipment_rental_request';
        }
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

// Fix for MissionCompletedNotification
$missionCompletedNotifications = Notification::where('type', 'App\\Notifications\\MissionCompletedNotification')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $missionCompletedNotifications->count() . " MissionCompletedNotification records to fix.\n";

foreach ($missionCompletedNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = 'Mission terminée';
        if (!isset($data['type'])) {
            $data['type'] = 'mission_completed';
        }
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

// Fix for BookingCancelledNotification
$bookingCancelledNotifications = Notification::where('type', 'App\\Notifications\\BookingCancelledNotification')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $bookingCancelledNotifications->count() . " BookingCancelledNotification records to fix.\n";

foreach ($bookingCancelledNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = 'Réservation annulée';
        if (!isset($data['type'])) {
            $data['type'] = 'booking_cancelled';
        }
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

// Fix for NewEquipmentRentalRequestNotification
$newRentalRequestNotifications = Notification::where('type', 'App\\Notifications\\NewEquipmentRentalRequestNotification')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $newRentalRequestNotifications->count() . " NewEquipmentRentalRequestNotification records to fix.\n";

foreach ($newRentalRequestNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = 'Nouvelle demande de location';
        if (!isset($data['type'])) {
            $data['type'] = 'equipment_rental_request';
        }
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

// Fix for EquipmentReportCreated
$equipmentReportNotifications = Notification::where('type', 'App\\Notifications\\EquipmentReportCreated')
    ->whereRaw("JSON_EXTRACT(data, '$.title') IS NULL")
    ->get();

echo "Found " . $equipmentReportNotifications->count() . " EquipmentReportCreated records to fix.\n";

foreach ($equipmentReportNotifications as $notification) {
    $data = $notification->getDecodedData();
    
    if (!isset($data['title'])) {
        $data['title'] = "Nouveau signalement d'équipement";
        $data['message'] = "Un nouveau signalement d'équipement a été créé";
        if (!isset($data['type'])) {
            $data['type'] = 'equipment_report';
        }
        
        $notification->data = $data;
        $notification->save();
        $count++;
    }
}

echo "Repair completed. Fixed $count notification records.\n";