<?php

namespace App\Http\Controllers\Prestataire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\EquipmentRentalRequest;
use App\Models\UrgentSale;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:prestataire');
    }

    /**
     * Display a listing of bookings for the prestataire
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $prestataire = $user->prestataire;
        
        if (!$prestataire) {
            abort(403, 'Accès non autorisé.');
        }

        $type = $request->get('type', 'all');
        $status = $request->get('status');
        $dateRange = $request->get('date_range');
        $serviceId = $request->get('service_id');

        // Déterminer quelles sections afficher
        $showServices = in_array($type, ['all', 'service']);
        $showEquipments = in_array($type, ['all', 'equipment']);
        $showUrgentSales = in_array($type, ['all', 'urgent_sale']);

        $serviceBookings = collect();
        $equipmentRentalRequests = collect();
        $urgentSales = collect();

        // Récupérer les réservations de services
        if ($showServices) {
            $query = $prestataire->bookings()->with(['client.user', 'service']);
            
            if ($status) {
                $query->where('status', $status);
            }
            
            if ($serviceId) {
                $query->where('service_id', $serviceId);
            }
            
            if ($dateRange) {
                switch ($dateRange) {
                    case 'today':
                        $query->whereDate('start_datetime', today());
                        break;
                    case 'week':
                        $query->whereBetween('start_datetime', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('start_datetime', now()->month)
                              ->whereYear('start_datetime', now()->year);
                        break;
                }
            }
            
            $serviceBookings = $query->orderBy('start_datetime', 'desc')->get();
        }

        // Récupérer les demandes de location d'équipements
        if ($showEquipments) {
            $query = $prestataire->equipmentRentalRequests()->with(['client.user', 'equipment']);
            
            if ($status) {
                $query->where('status', $status);
            }
            
            if ($dateRange) {
                switch ($dateRange) {
                    case 'today':
                        $query->whereDate('start_date', today());
                        break;
                    case 'week':
                        $query->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('start_date', now()->month)
                              ->whereYear('start_date', now()->year);
                        break;
                }
            }
            
            $equipmentRentalRequests = $query->orderBy('created_at', 'desc')->get();
        }

        // Récupérer les ventes urgentes
        if ($showUrgentSales) {
            $query = $prestataire->urgentSales();
            
            if ($status) {
                $query->where('status', $status);
            }
            
            if ($dateRange) {
                switch ($dateRange) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                        break;
                }
            }
            
            $urgentSales = $query->orderBy('created_at', 'desc')->get();
        }

        // Récupérer les services du prestataire pour le filtre
        $services = $prestataire->services()->get();

        // Créer une collection unifiée de toutes les demandes avec tri chronologique
        $allRequests = collect();
        
        // Ajouter les réservations de services
        foreach ($serviceBookings as $booking) {
            $booking->request_type = 'service';
            $allRequests->push($booking);
        }
        
        // Ajouter les demandes d'équipement
        foreach ($equipmentRentalRequests as $request) {
            $request->request_type = 'equipment';
            $allRequests->push($request);
        }
        
        // Ajouter les ventes urgentes
        foreach ($urgentSales as $sale) {
            $sale->request_type = 'urgent_sale';
            $allRequests->push($sale);
        }
        
        // Trier par date de création selon le paramètre de tri
        $sortOrder = $request->get('sort', 'desc'); // Par défaut : du plus récent au plus ancien
        if ($sortOrder === 'asc') {
            $allRequests = $allRequests->sortBy('created_at');
        } else {
            $allRequests = $allRequests->sortByDesc('created_at');
        }

        return view('prestataire.bookings.index', compact(
            'serviceBookings',
            'equipmentRentalRequests', 
            'urgentSales',
            'allRequests',
            'services',
            'showServices',
            'showEquipments',
            'showUrgentSales',
            'type',
            'status',
            'dateRange',
            'serviceId'
        ));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient au prestataire connecté
        if ($booking->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        $booking->load(['service', 'client.user', 'timeSlot']);
        
        return view('prestataire.bookings.show', compact('booking'));
    }

    /**
     * Accept a booking
     */
    public function accept(Booking $booking)
    {
        $user = Auth::user();
        
        if ($booking->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être acceptée.');
        }
        
        $booking->update(['status' => 'confirmed']);
        
        return redirect()->back()->with('success', 'Réservation acceptée avec succès.');
    }

    /**
     * Reject a booking
     */
    public function reject(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        if ($booking->prestataire_id !== $user->prestataire->id) {
            abort(403);
        }
        
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Cette réservation ne peut pas être refusée.');
        }
        
        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->get('rejection_reason')
        ]);
        
        return redirect()->back()->with('success', 'Réservation refusée.');
    }
}