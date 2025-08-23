@extends('layouts.app')

@section('title', 'Demande de location #' . $request->id)

@section('content')
<style>
    .bg-green-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .text-green-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
    
    .status-badge.refused {
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
<div class="bg-green-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-green-900 mb-2">Détails de la demande de location</h1>
                <p class="text-lg text-green-700">Demande #{{ $request->id }} - {{ $request->equipment->name ?? 'Équipement supprimé' }}</p>
            </div>
            
            <div class="flex justify-center mb-8">
                <a href="{{ route('prestataire.equipment-rental-requests.index') }}" 
                   class="bg-green-100 hover:bg-green-200 text-green-800 font-bold px-6 py-3 rounded-lg text-center transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux demandes
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
        <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-8">
            <h2 class="text-2xl font-bold text-green-800 mb-5 border-b-2 border-green-200 pb-3">Statut de la demande</h2>
            <span class="status-badge
                @if($request->status === 'pending') pending
                @elseif($request->status === 'accepted') confirmed
                @elseif($request->status === 'rejected') refused
                @endif">
                @if($request->status === 'pending') 
                    <i class="fas fa-clock"></i> En attente de confirmation
                @elseif($request->status === 'accepted') 
                    <i class="fas fa-check-circle"></i> Acceptée
                @elseif($request->status === 'rejected') 
                    <i class="fas fa-ban"></i> Refusée
                @endif
            </span>

            <!-- Actions selon le statut -->
            @if($request->status === 'pending')
                <div class="mt-6 flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('prestataire.equipment-rental-requests.accept', $request) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                                onclick="return confirm('Accepter cette demande de location ?')">
                            <i class="fas fa-check mr-2"></i>Accepter
                        </button>
                    </form>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-200"
                            x-data @click="$dispatch('open-modal', 'reject-request')">
                        <i class="fas fa-times mr-2"></i>Refuser
                    </button>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
            <!-- Colonne principale -->
            <div class="xl:col-span-3 space-y-6">
                <!-- Informations du client -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Informations du client</h2>
                    </div>
                    <div class="flex items-start space-x-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-green-700 font-bold text-xl">
                                {{ substr($request->client->first_name, 0, 1) }}{{ substr($request->client->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-green-900 mb-4">{{ $request->client->first_name }} {{ $request->client->last_name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="bg-green-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Email</div>
                                            <div class="text-sm font-semibold text-green-900">{{ $request->client->email }}</div>
                                        </div>
                                    </div>
                                    @if($request->client->phone)
                                    <div class="bg-green-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Téléphone</div>
                                            <div class="text-sm font-semibold text-green-900">{{ $request->client->phone }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="space-y-4">
                                    @if($request->client->address)
                                    <div class="bg-green-50 rounded-lg p-3 flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Adresse</div>
                                            <div class="text-sm font-semibold text-green-900">{{ $request->client->address }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="bg-green-50 rounded-lg p-3 flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6"></path>
                                        </svg>
                                        <div>
                                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Membre depuis</div>
                                            <div class="text-sm font-semibold text-green-900">{{ $request->client->created_at->format('F Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Détails de l'équipement -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Équipement demandé</h2>
                    </div>
                    @if($request->equipment)
                    <div class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-6">
                        @if($request->equipment->main_photo)
                        <div class="w-full md:w-32 h-32 bg-green-50 border border-green-200 rounded-xl overflow-hidden flex-shrink-0">
                            <img src="{{ Storage::url($request->equipment->main_photo) }}" 
                                 alt="{{ $request->equipment->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-green-900 mb-2">{{ $request->equipment->name }}</h3>
                            <p class="text-green-700 mb-4">{{ $request->equipment->brand }} {{ $request->equipment->model }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="text-xs font-medium text-green-600 uppercase tracking-wide">État</div>
                                    <div class="text-sm font-semibold text-green-900 mt-1">{{ $request->equipment->formatted_condition }}</div>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Prix/jour</div>
                                    <div class="text-sm font-semibold text-green-900 mt-1">{{ number_format($request->equipment->daily_rate, 2) }}€</div>
                                </div>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Disponibilité</div>
                                    <div class="text-sm font-semibold text-green-600 mt-1">Disponible</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('prestataire.equipment.show', $request->equipment) }}" 
                                   class="inline-flex items-center text-green-600 hover:text-green-800 text-sm font-medium">
                                    <span>Voir la fiche complète</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="text-green-400 mb-2">
                            <i class="fas fa-exclamation-triangle text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-green-900 mb-2">Équipement supprimé</h3>
                        <p class="text-green-700">L'équipement associé à cette demande n'existe plus.</p>
                    </div>
                    @endif
                </div>
                
                <!-- Détails de la demande -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Détails de la demande</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-semibold text-green-900 mb-4 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0h6m-6 0l-2 2m8-2l2 2m-2-2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6"></path>
                                </svg>
                                <span>Période de location</span>
                            </h3>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-green-700 font-medium">Date de début</span>
                                    <span class="font-semibold text-green-900">{{ $request->start_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-green-700 font-medium">Date de fin</span>
                                    <span class="font-semibold text-green-900">{{ $request->end_date->format('d/m/Y') }}</span>
                                </div>
                                <div class="border-t border-green-200 pt-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-green-700 font-medium">Durée totale</span>
                                        <span class="font-bold text-green-600">{{ $request->start_date->diffInDays($request->end_date) + 1 }} jour(s)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-green-900 mb-4 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                                <span>Options de livraison</span>
                            </h3>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-3">
                                <div class="flex items-center space-x-3">
                                    @if($request->delivery_required)
                                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-green-900">Livraison demandée</span>
                                    @else
                                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </div>
                                        <span class="text-green-700">Récupération sur place</span>
                                    @endif
                                </div>
                                
                                @if($request->delivery_required && $request->delivery_address)
                                <div class="border-t border-green-200 pt-3">
                                    <div class="text-sm text-green-700 font-medium mb-1">Adresse de livraison</div>
                                    <div class="text-green-900">{{ $request->delivery_address }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($request->message)
                    <div class="mt-8">
                        <h3 class="font-semibold text-green-900 mb-4 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>Message du client</span>
                        </h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-green-900 leading-relaxed">{{ $request->message }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Historique -->
                @if($request->status !== 'pending')
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Historique</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-green-900">Demande créée</p>
                                <p class="text-sm text-green-600">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($request->status === 'accepted')
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-green-900">Demande acceptée</p>
                                <p class="text-sm text-green-600">{{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        @elseif($request->status === 'rejected')
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-green-900">Demande refusée</p>
                                <p class="text-sm text-green-600">{{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                                @if($request->rejection_reason)
                                <p class="text-sm text-green-700 mt-1">Raison: {{ $request->rejection_reason }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Récapitulatif financier -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Récapitulatif</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-green-600">Location ({{ $request->start_date->diffInDays($request->end_date) + 1 }} jours)</span>
                                <span class="font-semibold text-green-900">{{ number_format($request->rental_amount ?? $request->total_amount, 2) }}€</span>
                            </div>
                            
                            @if($request->delivery_required && isset($request->delivery_cost) && $request->delivery_cost > 0)
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-green-600">Livraison</span>
                                <span class="font-semibold text-green-900">{{ number_format($request->delivery_cost, 2) }}€</span>
                            </div>
                            @endif
                            
                            <div class="border-t border-green-300 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-green-900">Total</span>
                                    <span class="font-bold text-xl text-green-600">{{ number_format($request->total_amount, 2) }}€</span>
                                </div>
                            </div>
                        </div>
                        
                        @if(isset($request->deposit_amount) && $request->deposit_amount > 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-yellow-800 font-medium">Caution demandée</span>
                                <span class="font-bold text-yellow-900">{{ number_format($request->deposit_amount, 2) }}€</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Informations complémentaires -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-800 border-b-2 border-green-200 pb-2">Informations</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Demande créée</div>
                            <div class="text-sm font-semibold text-green-900">{{ $request->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        
                        @if($request->status !== 'pending')
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Dernière mise à jour</div>
                            <div class="text-sm font-semibold text-green-900">{{ $request->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @endif
                        
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Référence</div>
                            <div class="text-sm font-semibold text-green-900 font-mono">#{{ $request->id }}</div>
                        </div>
                        
                        @if($request->request_number)
                        <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Numéro de demande</div>
                            <div class="text-sm font-semibold text-green-900 font-mono">{{ $request->request_number }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal name="reject-request" :show="$errors->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('prestataire.equipment-rental-requests.reject', $request) }}" class="p-6">
            @csrf
            @method('PATCH')

            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">
                    Refuser la demande de location
                </h2>
            </div>

            <p class="text-gray-600 mb-6">
                Veuillez indiquer la raison du refus de cette demande. Cette information sera communiquée au client.
            </p>

            <div class="mb-6">
                <x-input-label for="rejection_reason" value="Raison du refus" class="text-sm font-medium text-gray-700 mb-2" />
                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500 resize-none"
                    rows="4"
                    placeholder="Expliquez pourquoi vous refusez cette demande (équipement non disponible, problème technique, etc.)..."
                    required
                >{{ old('rejection_reason') }}</textarea>
                <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
            </div>

            <div class="flex justify-end space-x-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="px-6 py-2">
                    Annuler
                </x-secondary-button>

                <x-danger-button class="px-6 py-2 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Refuser la demande</span>
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</div>
@endsection