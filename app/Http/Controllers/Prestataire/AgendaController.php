<?php

namespace App\Http\Controllers\Prestataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\EquipmentRental;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Affiche l'agenda du prestataire
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        // Paramètres de vue
        $view = $request->get('view', 'month');
        $search = $request->get('search', '');
        $serviceFilter = $request->get('service', '');
        $statusFilter = $request->get('status', '');
        
        // Récupérer les services du prestataire
        $services = $prestataire->services;
        
        // Statistiques
        $stats = [
            'total' => $prestataire->bookings()->count(),
            'confirmed' => $prestataire->bookings()->where('status', 'confirmed')->count(),
            'pending' => $prestataire->bookings()->where('status', 'pending')->count(),
            'completed' => $prestataire->bookings()->where('status', 'completed')->count(),
        ];
        
        // Réservations récentes pour la liste des demandes
        $recentBookings = $prestataire->bookings()
            ->with(['service', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Réservations pour la vue liste (avec filtres)
        $bookingsQuery = $prestataire->bookings()->with(['service', 'client.user']);
        
        if ($search) {
            $bookingsQuery->where(function($q) use ($search) {
                $q->whereHas('client.user', function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('service', function($query) use ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                })
                ->orWhere('booking_number', 'like', '%' . $search . '%');
            });
        }
        
        if ($serviceFilter) {
            $bookingsQuery->where('service_id', $serviceFilter);
        }
        
        if ($statusFilter) {
            $bookingsQuery->where('status', $statusFilter);
        }
        
        $bookings = $bookingsQuery->orderBy('start_datetime', 'desc')->paginate(10);
        
        return view('prestataire.agenda.index', compact(
            'view', 'search', 'serviceFilter', 'statusFilter', 'services', 
            'stats', 'recentBookings', 'bookings'
        ));
    }
    
    /**
     * API pour récupérer les événements du calendrier
     */
    public function events(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));
        
        // Récupérer les réservations de services
        $bookings = Booking::where('prestataire_id', $prestataire->id)
            ->whereBetween('start_datetime', [$start, $end])
            ->with(['service', 'client.user'])
            ->get();
        
        // Récupérer les locations d'équipements
        $equipmentRentals = EquipmentRental::where('prestataire_id', $prestataire->id)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                      ->orWhereBetween('end_date', [$start->toDateString(), $end->toDateString()])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start->toDateString())
                            ->where('end_date', '>=', $end->toDateString());
                      });
            })
            ->with(['equipment', 'client.user'])
            ->get();
        
        // Mapper les réservations de services
        $serviceEvents = $bookings->map(function ($booking) {
            $eventType = 'service'; // Par défaut service
            $icon = '🛠️'; // Icône service
            $color = '#3b82f6'; // Bleu pour services
            
            // Déterminer le type et la couleur selon le contexte
            if (str_contains(strtolower($booking->service->title ?? ''), 'équipement') || 
                str_contains(strtolower($booking->service->title ?? ''), 'location')) {
                $eventType = 'equipment';
                $icon = '⚙️';
                $color = '#10b981'; // Vert pour équipements
            } elseif (str_contains(strtolower($booking->service->title ?? ''), 'urgent') || 
                     str_contains(strtolower($booking->service->title ?? ''), 'vente')) {
                $eventType = 'urgent_sale';
                $icon = '⚡';
                $color = '#ef4444'; // Rouge pour annonces
            }
            
            return [
                'id' => 'booking_' . $booking->id,
                'title' => $icon . ' ' . ($booking->service->title ?? 'Réservation'),
                'start' => $booking->start_datetime->toISOString(),
                'end' => $booking->end_datetime->toISOString(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'clientName' => $booking->client->user->name ?? 'N/A',
                    'serviceName' => $booking->service->title ?? 'N/A',
                    'status' => ucfirst($booking->status),
                    'bookingUrl' => route('prestataire.bookings.show', $booking->id),
                    'startTime' => $booking->start_datetime->format('H:i'),
                    'type' => $eventType,
                    'icon' => $icon,
                    'itemType' => 'booking'
                ]
            ];
        });
        
        // Mapper les locations d'équipements
        $equipmentEvents = $equipmentRentals->map(function ($rental) {
            $statusColors = [
                'pending' => '#f59e0b',    // Orange
                'confirmed' => '#10b981',  // Vert
                'active' => '#3b82f6',     // Bleu
                'completed' => '#6b7280',  // Gris
                'cancelled' => '#ef4444'   // Rouge
            ];
            
            $color = $statusColors[$rental->status] ?? '#10b981';
            
            return [
                'id' => 'equipment_' . $rental->id,
                'title' => '⚙️ ' . ($rental->equipment->name ?? 'Location équipement'),
                'start' => $rental->start_date . 'T00:00:00',
                'end' => Carbon::parse($rental->end_date)->addDay()->format('Y-m-d') . 'T00:00:00',
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'allDay' => true,
                'extendedProps' => [
                    'clientName' => $rental->client->user->name ?? 'N/A',
                    'equipmentName' => $rental->equipment->name ?? 'N/A',
                    'status' => ucfirst($rental->status),
                    'rentalUrl' => route('prestataire.equipment-rentals.show', $rental->id),
                    'startDate' => Carbon::parse($rental->start_date)->format('d/m/Y'),
                    'endDate' => Carbon::parse($rental->end_date)->format('d/m/Y'),
                    'type' => 'equipment',
                    'icon' => '⚙️',
                    'itemType' => 'equipment_rental'
                ]
            ];
        });
        
        // Combiner tous les événements
        $allEvents = $serviceEvents->concat($equipmentEvents);
        
        return response()->json($allEvents);
    }
    
    /**
     * Affiche les détails d'une réservation
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient au prestataire connecté
        if ($booking->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $booking->load(['service', 'client.user', 'timeSlot']);
        
        return response()->json([
            'booking' => $booking,
            'canConfirm' => $booking->canBeConfirmed(),
            'canCancel' => $booking->canBeCancelled(),
            'canComplete' => $booking->canBeCompleted()
        ]);
    }
    
    /**
     * Met à jour le statut d'une réservation
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient au prestataire connecté
        if ($booking->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
            'reason' => 'nullable|string|max:500'
        ]);
        
        $status = $request->get('status');
        $reason = $request->get('reason');
        
        switch ($status) {
            case 'confirmed':
                if ($booking->confirm()) {
                    return response()->json(['success' => true, 'message' => 'Réservation confirmée']);
                }
                break;
                
            case 'cancelled':
                if ($booking->cancel($reason)) {
                    return response()->json(['success' => true, 'message' => 'Réservation annulée']);
                }
                break;
                
            case 'completed':
                if ($booking->canBeCompleted()) {
                    $booking->update([
                        'status' => 'completed',
                        'completed_at' => now()
                    ]);
                    return response()->json(['success' => true, 'message' => 'Réservation marquée comme terminée']);
                }
                break;
        }
        
        return response()->json(['success' => false, 'message' => 'Action non autorisée'], 400);
    }
    

    
    /**
     * Retourne la couleur selon le statut
     */
    public function recentBookings(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;

        $bookings = Booking::where('prestataire_id', $prestataire->id)
            ->with(['service', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($bookings);
    }

    /**
     * Retourne la couleur selon le statut
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => '#ff9f43',
            'confirmed' => '#28c76f',
            'cancelled' => '#82868b',
            'completed' => '#1e90ff',
            'refused' => '#dc3545' // Red
        ];
        
        return $colors[$status] ?? '#82868b';
    }
}