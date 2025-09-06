@extends('layouts.app')

@section('title', 'Détails de la réservation #' . ($booking->id ?? 'N/A'))

@section('content')
@php
    // Ensure variables are defined for backward compatibility
    $isMultiSlotSession = $isMultiSlotSession ?? false;
    $allBookings = $allBookings ?? collect([$booking]);
    $relatedBookings = $relatedBookings ?? collect();
    $totalSessionPrice = $totalSessionPrice ?? ($booking->total_price ?? 0);
    
    // Function to clean session ID from notes for display
    function cleanNotesForDisplay($notes) {
        if (!$notes) return null;
        return trim(preg_replace('/\[SESSION:[^\]]+\]/', '', $notes)) ?: null;
    }
@endphp

<style>
    .bg-blue-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }
    
    .text-blue-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 12px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        border: 2px solid;
        transition: all 0.3s ease;
    }
    
    .status-badge.pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border-color: #f59e0b;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }
    
    .status-badge.confirmed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border-color: #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
    }
    
    .status-badge.completed {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .status-badge.refused {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-color: #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    
    .status-badge.cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border-color: #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    
    .status-badge i {
        margin-right: 8px;
        font-size: 16px;
    }
</style>

<div class="bg-blue-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">Détails de la réservation</h1>
                <p class="text-lg text-blue-700">Réservation #{{ $booking->booking_number ?? 'N/A' }} - {{ $booking->service->name ?? 'Service supprimé' }}</p>
            </div>
            
            <div class="flex justify-center mb-8">
                <a href="{{ route('prestataire.bookings.index') }}" 
                   class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-6 py-3 rounded-lg text-center transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux réservations
                </a>
            </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statut et actions -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-8">
            <h2 class="text-2xl font-bold text-blue-800 mb-5 border-b-2 border-blue-200 pb-3">Statut de la réservation</h2>
            <span class="status-badge
                @if(($booking->status ?? '') === 'pending') pending
                @elseif(($booking->status ?? '') === 'confirmed') confirmed
                @elseif(($booking->status ?? '') === 'completed') completed
                @elseif(($booking->status ?? '') === 'cancelled') cancelled
                @elseif(($booking->status ?? '') === 'refused') refused
                @endif">
                @if(($booking->status ?? '') === 'pending') 
                    <i class="fas fa-clock"></i> En attente de confirmation
                @elseif(($booking->status ?? '') === 'confirmed') 
                    <i class="fas fa-check-circle"></i> Confirmée
                @elseif(($booking->status ?? '') === 'completed') 
                    <i class="fas fa-check-double"></i> Terminée
                @elseif(($booking->status ?? '') === 'cancelled') 
                    <i class="fas fa-times-circle"></i> Annulée
                @elseif(($booking->status ?? '') === 'refused') 
                    <i class="fas fa-ban"></i> Refusée
                @else
                    <i class="fas fa-question-circle"></i> Statut inconnu
                @endif
            </span>

            <!-- Actions selon le statut -->
            @if(($booking->status ?? '') === 'pending')
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('prestataire.bookings.accept', $booking) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                                onclick="return confirm('Accepter cette réservation ?')">
                            <i class="fas fa-check mr-2"></i>Accepter
                        </button>
                    </form>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                            x-data @click="$dispatch('open-modal', 'reject-booking')">
                        <i class="fas fa-times mr-2"></i>Refuser
                    </button>
                </div>
            @elseif(($booking->status ?? '') === 'confirmed')
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('prestataire.bookings.reject', $booking) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                                onclick="return confirm('Marquer cette réservation comme terminée ?')">
                            <i class="fas fa-check-double mr-2"></i>Marquer comme terminée
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Informations du client -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-blue-800 border-b-2 border-blue-200 pb-2">Informations du client</h2>
                    </div>
                    <div class="flex items-start space-x-6">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden mb-2">
                                @if($booking->client && $booking->client->photo)
                                    <img src="{{ asset('storage/' . $booking->client->photo) }}" alt="{{ $booking->client->first_name ?? '' }} {{ $booking->client->last_name ?? '' }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                        <span class="text-blue-700 font-bold text-xl">
                                            {{ substr($booking->client->first_name ?? '', 0, 1) }}{{ substr($booking->client->last_name ?? '', 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-blue-900">{{ $booking->client->first_name ?? '' }} {{ $booking->client->last_name ?? '' }}</h3>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="bg-blue-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Email</div>
                                            <div class="text-sm font-semibold text-blue-900">{{ ($booking->client && $booking->client->user) ? ($booking->client->user->email ?? $booking->client->email) : 'N/A' }}</div>
                                        </div>
                                    </div>
                                    @if($booking->client && $booking->client->phone)
                                    <div class="bg-blue-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Téléphone</div>
                                            <div class="text-sm font-semibold text-blue-900">{{ $booking->client->phone }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="space-y-4">
                                    @if($booking->client && $booking->client->address)
                                    <div class="bg-blue-50 rounded-lg p-3 flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Adresse</div>
                                            <div class="text-sm font-semibold text-blue-900">{{ $booking->client->address }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="bg-blue-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Membre depuis</div>
                                            <div class="text-sm font-semibold text-blue-900">{{ $booking->client && $booking->client->created_at ? $booking->client->created_at->format('F Y') : 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Détails du service -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-blue-800 border-b-2 border-blue-200 pb-2">Détails du service</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Nom du service</div>
                                <div class="text-lg font-bold text-blue-900 mt-1">{{ $booking->service->name ?? 'Service non disponible' }}</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Catégorie</div>
                                <div class="text-sm font-semibold text-blue-900 mt-1">
                                    @if($booking->service && $booking->service->category)
                                        {{ $booking->service->category->first()->name ?? 'Non spécifiée' }}
                                    @else
                                        Non spécifiée
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Prix</div>
                                <div class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($booking->total_price ?? 0, 2, ',', ' ') }} €</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Durée</div>
                                <div class="text-sm font-semibold text-blue-900 mt-1">{{ $booking->service->duration ?? 'N/A' }} minutes</div>
                            </div>
                        </div>
                    </div>
                    @if($booking->service && $booking->service->description)
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-2">Description</div>
                        <div class="text-gray-700">{{ $booking->service->description }}</div>
                    </div>
                    @endif
                </div>
                
                <!-- Détails de la réservation -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-blue-800 border-b-2 border-blue-200 pb-2">Détails de la réservation</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Date de début</div>
                                <div class="text-lg font-bold text-blue-900 mt-1">{{ $booking->start_datetime ? $booking->start_datetime->format('d/m/Y') : 'N/A' }}</div>
                                <div class="text-sm text-blue-700">{{ $booking->start_datetime ? $booking->start_datetime->format('H:i') : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Date de fin</div>
                                <div class="text-lg font-bold text-blue-900 mt-1">{{ $booking->end_datetime ? $booking->end_datetime->format('d/m/Y') : 'N/A' }}</div>
                                <div class="text-sm text-blue-700">{{ $booking->end_datetime ? $booking->end_datetime->format('H:i') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($isMultiSlotSession)
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-2">Session multiple</div>
                        <div class="text-sm text-blue-700">
                            Cette réservation fait partie d'une session de {{ $allBookings->count() }} créneaux.
                            Prix total: <span class="font-bold">{{ number_format($totalSessionPrice, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                    @endif
                    
                    @if(cleanNotesForDisplay($booking->client_notes))
                    <div class="mt-6 bg-blue-50 rounded-lg p-4">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-2">Notes du client</div>
                        <div class="text-gray-700">{{ cleanNotesForDisplay($booking->client_notes) }}</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Résumé -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Résumé
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Numéro:</span>
                            <span class="font-medium">#{{ $booking->booking_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Date de création:</span>
                            <span class="font-medium">{{ $booking->created_at ? $booking->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Statut:</span>
                            <span class="font-medium
                                @if(($booking->status ?? '') === 'pending') text-yellow-600
                                @elseif(($booking->status ?? '') === 'confirmed') text-green-600
                                @elseif(($booking->status ?? '') === 'completed') text-blue-600
                                @elseif(($booking->status ?? '') === 'cancelled') text-red-600
                                @elseif(($booking->status ?? '') === 'refused') text-red-600
                                @endif">
                                @if(($booking->status ?? '') === 'pending') En attente
                                @elseif(($booking->status ?? '') === 'confirmed') Confirmée
                                @elseif(($booking->status ?? '') === 'completed') Terminée
                                @elseif(($booking->status ?? '') === 'cancelled') Annulée
                                @elseif(($booking->status ?? '') === 'refused') Refusée
                                @else Statut inconnu
                                @endif
                            </span>
                        </div>
                        <hr class="border-gray-200 my-2">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">{{ number_format($booking->total_price ?? 0, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
                
                <!-- Communication -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                    <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Communication
                    </h3>
                    <div class="space-y-3">
                        @if($booking->client && $booking->client->user)
                        <a href="{{ route('messaging.conversation', $booking->client->user->id) }}?message=Bonjour {{ $booking->client->user->name ?? '' }}, concernant votre réservation #{{ $booking->booking_number ?? 'N/A' }} du {{ $booking->start_datetime ? $booking->start_datetime->format('d/m/Y à H:i') : 'N/A' }}, je vous contacte pour..." 
                           class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-blue-800 font-medium">Envoyer un message</span>
                        </a>
                        @if($booking->client->user->phone)
                        <a href="tel:{{ $booking->client->user->phone }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                            <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-green-800 font-medium">Appeler (urgence)</span>
                        </a>
                        @endif
                        @else
                        <div class="text-gray-500 italic">Informations de contact non disponibles</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de refus -->
<div x-data="{ open: false }" 
     x-init="$watch('$store.modal', value => open = value === 'reject-booking')"
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     @open-modal.window="if ($event.detail === 'reject-booking') open = true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST" action="{{ route('prestataire.bookings.reject', $booking) }}">
                @csrf
                @method('PATCH')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Refuser la réservation
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Veuillez indiquer la raison du refus de cette réservation.
                                </p>
                                <div class="mt-4">
                                    <textarea name="rejection_reason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2" placeholder="Raison du refus (optionnel)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Refuser
                    </button>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection