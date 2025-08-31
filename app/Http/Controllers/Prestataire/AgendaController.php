<?php

namespace App\Http\Controllers\Prestataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\EquipmentRental;
use App\Models\EquipmentRentalRequest;
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
        
        // Réservations récentes pour la liste des demandes (services)
        $recentServiceBookings = $prestataire->bookings()
            ->with(['service', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->service->title ?? 'Service',
                    'client_name' => $booking->client->user->name ?? 'N/A',
                    'start_date' => $booking->start_datetime,
                    'status' => $booking->status,
                    'can_confirm' => $booking->canBeConfirmed(),
                    'can_cancel' => $booking->canBeCancelled(),
                    'can_complete' => $booking->canBeCompleted(),
                    'url' => route('prestataire.bookings.show', $booking->id),
                    'type' => 'service'
                ];
            });
        
        // Demandes de location d'équipements récentes
        $recentRentalRequests = EquipmentRentalRequest::where('prestataire_id', $prestataire->id)
            ->with(['equipment', 'client.user'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'title' => $request->equipment->name ?? 'Location équipement',
                    'client_name' => $request->client->user->name ?? 'N/A',
                    'start_date' => $request->start_date,
                    'status' => $request->status,
                    'can_confirm' => $request->status === 'pending',
                    'can_cancel' => $request->status === 'pending',
                    'can_complete' => false,
                    'url' => route('prestataire.agenda.equipment-request.show', $request->id),
                    'type' => 'equipment'
                ];
            });
            
        // Combiner les deux types de demandes et trier par date de création (du plus récent au plus ancien)
        $recentDemands = $recentServiceBookings->concat($recentRentalRequests)
            ->sortByDesc('start_date')
            ->values() // Re-index the collection
            ->take(31);
        
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
            'stats', 'recentDemands', 'bookings'
        ));
    }
    
    /**
     * API pour récupérer les événements du calendrier
     */
    public function events(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        // Log the request parameters for debugging
        \Log::info('Agenda events request', [
            'start' => $request->get('start'),
            'end' => $request->get('end'),
            'filter' => $request->get('filter', 'all')
        ]);
        
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));
        $filter = $request->get('filter', 'all'); // Get the filter parameter
        
        // Log the parsed dates
        \Log::info('Parsed dates', [
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString()
        ]);
        
        // Récupérer les réservations de services
        $bookingsQuery = Booking::where('prestataire_id', $prestataire->id)
            ->whereBetween('start_datetime', [$start, $end])
            ->with(['service', 'client.user']);
            
        // Récupérer les locations d'équipements
        $equipmentRentalsQuery = EquipmentRental::where('prestataire_id', $prestataire->id)
            ->where(function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    // Equipment that starts during the period
                    $q->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                      // OR equipment that ends during the period
                      ->orWhereBetween('end_date', [$start->toDateString(), $end->toDateString()])
                      // OR equipment that spans the entire period
                      ->orWhere(function($q2) use ($start, $end) {
                          $q2->where('start_date', '<=', $start->toDateString())
                            ->where('end_date', '>=', $end->toDateString());
                      });
                });
            })
            ->with(['equipment', 'client.user']);
        
        // Apply filtering based on the filter parameter
        if ($filter === 'service') {
            // Only show service events
            $equipmentRentalsQuery->whereRaw('1=0'); // This will return no equipment rentals
        } elseif ($filter === 'equipment') {
            // Only show equipment events
            $bookingsQuery->whereRaw('1=0'); // This will return no service bookings
        }
        
        $bookings = $bookingsQuery->get();
        $equipmentRentals = $equipmentRentalsQuery->get();
        
        // Log the number of records found
        \Log::info('Records found', [
            'bookings' => $bookings->count(),
            'equipment_rentals' => $equipmentRentals->count()
        ]);
        
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
                'title' => ($booking->service->title ?? 'Réservation'),
                'start' => $booking->start_datetime->toISOString(),
                'end' => $booking->end_datetime->toISOString(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'id' => $booking->id,
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
            // Toujours utiliser le vert pour les équipements, peu importe le statut
            $color = '#10b981'; // Vert pour équipements
            $icon = '🔧'; // Icône pour équipements
            
            // Format dates properly for calendar display
            $startDate = Carbon::parse($rental->start_date)->startOfDay();
            $endDate = Carbon::parse($rental->end_date)->endOfDay();
            
            return [
                'id' => 'equipment_' . $rental->id,
                'title' => ($rental->equipment->name ?? 'Location équipement'),
                'start' => $startDate->toISOString(),
                'end' => $endDate->toISOString(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'allDay' => false, // Equipment rentals can be time-specific
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
        
        // Log the final events count
        \Log::info('Final events count', [
            'total_events' => $allEvents->count()
        ]);
        
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
     * Affiche les détails d'une location d'équipement
     */
    public function showEquipmentRental(EquipmentRental $rental)
    {
        $user = Auth::user();
        
        // Vérifier que la location appartient au prestataire connecté
        if ($rental->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $rental->load(['equipment', 'client.user']);
        
        return response()->json([
            'rental' => $rental,
            'canStart' => $rental->status === 'confirmed',
            'canComplete' => $rental->status === 'active'
        ]);
    }
    
    /**
     * Affiche les détails d'une demande de location d'équipement
     */
    public function showEquipmentRequest(EquipmentRentalRequest $rentalRequest)
    {
        $user = Auth::user();
        
        // Vérifier que la demande appartient au prestataire connecté
        if ($rentalRequest->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $rentalRequest->load(['equipment', 'client.user']);
        
        return response()->json([
            'rentalRequest' => $rentalRequest,
            'canAccept' => $rentalRequest->status === 'pending',
            'canReject' => $rentalRequest->status === 'pending'
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
     * Accepte une demande de location d'équipement
     */
    public function acceptEquipmentRequest(Request $request, EquipmentRentalRequest $rentalRequest)
    {
        $user = Auth::user();
        
        // Vérifier que la demande appartient au prestataire connecté
        if ($rentalRequest->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $response = $request->get('response');
        
        if ($rentalRequest->accept($response)) {
            return response()->json(['success' => true, 'message' => 'Demande de location acceptée']);
        }
        
        return response()->json(['success' => false, 'message' => 'Action non autorisée'], 400);
    }
    
    /**
     * Rejette une demande de location d'équipement
     */
    public function rejectEquipmentRequest(Request $request, EquipmentRentalRequest $rentalRequest)
    {
        $user = Auth::user();
        
        // Vérifier que la demande appartient au prestataire connecté
        if ($rentalRequest->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);
        
        $reason = $request->get('reason');
        
        if ($rentalRequest->reject($reason)) {
            return response()->json(['success' => true, 'message' => 'Demande de location refusée']);
        }
        
        return response()->json(['success' => false, 'message' => 'Action non autorisée'], 400);
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
            'refused' => '#dc3545', // Red
            'rejected' => '#dc3545',
            'accepted' => '#28c76f'
        ];
        
        return $colors[$status] ?? '#82868b';
    }
}