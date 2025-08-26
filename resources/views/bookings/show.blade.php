@extends('layouts.app')

@section('content')
@php
    // Ensure variables are defined for backward compatibility
    $isMultiSlotSession = $isMultiSlotSession ?? false;
    $allBookings = $allBookings ?? collect([$booking]);
    $relatedBookings = $relatedBookings ?? collect();
    $totalSessionPrice = $totalSessionPrice ?? $booking->total_price;
    
    // Function to clean session ID from notes for display
    function cleanNotesForDisplay($notes) {
        if (!$notes) return null;
        return trim(preg_replace('/\[SESSION:[^\]]+\]/', '', $notes)) ?: null;
    }
@endphp

<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-4">
        <div class="max-w-5xl mx-auto">
            <!-- Compact Header -->
            <div class="bg-white rounded-lg shadow-sm border p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('bookings.index') }}" 
                           class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> 
                            Retour
                        </a>
                        <div class="border-l border-gray-300 pl-4">
                            @if($isMultiSlotSession)
                                <h1 class="text-xl font-bold text-gray-900">
                                    Réservations multiples #{{ $booking->booking_number }}
                                    <span class="text-sm font-normal text-gray-600">({{ $allBookings->count() }} créneaux)</span>
                                </h1>
                            @else
                                <h1 class="text-xl font-bold text-gray-900">Réservation {{ $booking->booking_number }}</h1>
                            @endif
                            <p class="text-sm text-gray-600">{{ $booking->service->name }}</p>
                            @if($isMultiSlotSession)
                                <p class="text-xs text-blue-600 font-medium mt-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Du {{ $allBookings->first()->start_datetime->format('d/m/Y à H:i') }} 
                                    au {{ $allBookings->last()->end_datetime->format('d/m/Y à H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @if($isMultiSlotSession)
                            <!-- Show session status summary -->
                            @php
                                $statusCounts = $allBookings->groupBy('status')->map->count();
                                $primaryStatus = $allBookings->first()->status;
                            @endphp
                            <div class="flex flex-col items-end space-y-1">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($primaryStatus === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($primaryStatus === 'confirmed') bg-green-100 text-green-800
                                    @elseif($primaryStatus === 'completed') bg-blue-100 text-blue-800
                                    @elseif($primaryStatus === 'cancelled') bg-red-100 text-red-800
                                    @elseif($primaryStatus === 'refused') bg-gray-100 text-gray-800
                                    @endif">
                                    @if($primaryStatus === 'pending') Session en attente
                                    @elseif($primaryStatus === 'confirmed') Session confirmée
                                    @elseif($primaryStatus === 'completed') Session terminée
                                    @elseif($primaryStatus === 'cancelled') Session annulée
                                    @elseif($primaryStatus === 'refused') Session refusée
                                    @endif
                                </span>
                                @if($statusCounts->count() > 1)
                                    <div class="text-xs text-gray-500">
                                        @foreach($statusCounts as $status => $count)
                                            @if($count > 0)
                                                <span class="mr-2">
                                                    {{ $count }} 
                                                    @if($status === 'pending') en attente
                                                    @elseif($status === 'confirmed') confirmée(s)
                                                    @elseif($status === 'completed') terminée(s)
                                                    @elseif($status === 'cancelled') annulée(s)
                                                    @elseif($status === 'refused') refusée(s)
                                                    @endif
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                @elseif($booking->status === 'refused') bg-gray-100 text-gray-800
                                @endif">
                                @if($booking->status === 'pending') En attente
                                @elseif($booking->status === 'confirmed') Confirmée
                                @elseif($booking->status === 'completed') Terminée
                                @elseif($booking->status === 'cancelled') Annulée
                                @elseif($booking->status === 'refused') Refusée
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Flash Messages -->
            @include('bookings.partials.flash-messages')

            <!-- Multi-Slot Booking Overview -->
            @if($isMultiSlotSession)
                <div class="bg-white rounded-lg shadow-sm border p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-calendar-check text-blue-500 mr-2"></i>
                            Créneaux réservés ({{ $allBookings->count() }})
                        </h2>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Prix total</div>
                            <div class="text-xl font-bold text-blue-600">{{ number_format($totalSessionPrice, 2) }} €</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($allBookings as $sessionBooking)
                            <div class="border rounded-lg p-3 @if($sessionBooking->id === $booking->id) bg-blue-50 border-blue-300 @else bg-gray-50 border-gray-200 @endif">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-900">
                                        #{{ $sessionBooking->booking_number }}
                                    </div>
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($sessionBooking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($sessionBooking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($sessionBooking->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($sessionBooking->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($sessionBooking->status === 'refused') bg-gray-100 text-gray-800
                                        @endif">
                                        @if($sessionBooking->status === 'pending') En attente
                                        @elseif($sessionBooking->status === 'confirmed') Confirmée
                                        @elseif($sessionBooking->status === 'completed') Terminée
                                        @elseif($sessionBooking->status === 'cancelled') Annulée
                                        @elseif($sessionBooking->status === 'refused') Refusée
                                        @endif
                                    </span>
                                </div>
                                <div class="space-y-1 text-xs text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar text-blue-500 mr-1 w-3"></i>
                                        <span>{{ $sessionBooking->start_datetime->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-green-500 mr-1 w-3"></i>
                                        <span>{{ $sessionBooking->start_datetime->format('H:i') }} - {{ $sessionBooking->end_datetime->format('H:i') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-euro-sign text-yellow-500 mr-1 w-3"></i>
                                        <span class="font-medium">{{ number_format($sessionBooking->total_price, 2) }} €</span>
                                    </div>
                                </div>
                                @if($sessionBooking->id === $booking->id)
                                    <div class="mt-2 text-xs text-blue-700 font-medium">
                                        <i class="fas fa-eye mr-1"></i> Créneau actuel
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Quick navigation to other bookings -->
                    @if($relatedBookings->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-600 mb-2">Navigation rapide :</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($allBookings as $sessionBooking)
                                    @if($sessionBooking->id !== $booking->id)
                                        <a href="{{ route('bookings.show', $sessionBooking) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded transition-colors">
                                            <i class="fas fa-external-link-alt mr-1"></i>
                                            #{{ $sessionBooking->booking_number }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="flex justify-center">
                <div class="w-full max-w-4xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Booking Details Card -->
                        <div class="flex justify-center">
                            @if($isMultiSlotSession)
                                @include('bookings.partials.booking-details-multi', ['currentBooking' => $booking, 'allBookings' => $allBookings, 'totalSessionPrice' => $totalSessionPrice])
                            @else
                                @include('bookings.partials.booking-details')
                            @endif
                        </div>
                        
                        <!-- User Profile Card -->
                        <div class="flex justify-center">
                            @include('bookings.partials.user-profile')
                        </div>
                    </div>
                    
                    <!-- Status and Actions - Centered -->
                    <div class="flex justify-center mb-4">
                        <div class="w-full max-w-2xl">
                            @include('bookings.partials.status-actions', [
                                'booking' => $booking, 
                                'isMultiSlotSession' => $isMultiSlotSession,
                                'allBookings' => $allBookings ?? collect(),
                                'relatedBookings' => $relatedBookings ?? collect()
                            ])
                        </div>
                    </div>
                    
                    <!-- Status Notifications - Centered -->
                    @if($booking->status === 'confirmed' && $booking->confirmed_at)
                        <div class="flex justify-center mb-4">
                            <div class="w-full max-w-2xl">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-check-circle text-blue-500 mr-2"></i>
                                        <span class="text-blue-800 text-sm font-medium">Confirmée le {{ $booking->confirmed_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($booking->status === 'completed' && $booking->completed_at)
                        <div class="flex justify-center mb-4">
                            <div class="w-full max-w-2xl">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span class="text-green-800 text-sm font-medium">Terminé le {{ $booking->completed_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($booking->status === 'cancelled' || $booking->status === 'refused')
                        <div class="flex justify-center mb-4">
                            <div class="w-full max-w-2xl">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <div class="text-center">
                                        <div class="flex items-center justify-center mb-1">
                                            <i class="fas fa-times-circle text-red-500 mr-2"></i>
                                            <span class="text-red-800 text-sm font-medium">
                                                @if($booking->status === 'cancelled')
                                                    Annulée le {{ $booking->cancelled_at->format('d/m/Y à H:i') }}
                                                @else
                                                    Refusée le {{ $booking->cancelled_at->format('d/m/Y à H:i') }}
                                                @endif
                                            </span>
                                        </div>
                                        @if($booking->cancellation_reason)
                                            <p class="text-red-700 text-xs"><strong>Raison:</strong> {{ $booking->cancellation_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Notes - Centered -->
                    @php
                        $cleanClientNotes = cleanNotesForDisplay($booking->client_notes);
                        $cleanPrestataireNotes = cleanNotesForDisplay($booking->prestataire_notes);
                    @endphp
                    @if($cleanClientNotes || $cleanPrestataireNotes)
                        <div class="flex justify-center">
                            <div class="w-full max-w-3xl">
                                <div class="bg-white rounded-lg shadow-sm border p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3 text-center">Notes</h3>
                                    
                                    <div class="space-y-3">
                                        @if($cleanClientNotes)
                                            <div class="text-center">
                                                <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                                    Client
                                                </h4>
                                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mx-auto max-w-md">{{ $cleanClientNotes }}</p>
                                            </div>
                                        @endif
                                        
                                        @if($cleanPrestataireNotes)
                                            <div class="text-center">
                                                <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center justify-center">
                                                    <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                                                    Prestataire
                                                </h4>
                                                <p class="text-sm text-gray-600 bg-blue-50 p-3 rounded mx-auto max-w-md">{{ $cleanPrestataireNotes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('bookings.partials.modals')

@push('scripts')
<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }

    function openRefuseModal() {
        document.getElementById('refuseModal').classList.remove('hidden');
    }

    function closeRefuseModal() {
        document.getElementById('refuseModal').classList.add('hidden');
    }
</script>
@endpush

@endsection