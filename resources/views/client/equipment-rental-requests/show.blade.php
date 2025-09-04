@extends('layouts.app')

@section('title', 'Détails de la demande de location')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-4 sm:py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6">
        <!-- Messages de session -->
        @if(session('success'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-400 text-green-700 px-3 sm:px-4 py-3 rounded-lg shadow" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                    <span class="font-medium text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-400 text-red-700 px-3 sm:px-4 py-3 rounded-lg shadow" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    <span class="font-medium text-sm sm:text-base">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 text-yellow-700 px-3 sm:px-4 py-3 rounded-lg shadow" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>
                    <span class="font-medium text-sm sm:text-base">{{ session('warning') }}</span>
                </div>
            </div>
        @endif

        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-xs sm:text-sm bg-white rounded-lg shadow px-2 sm:px-3 py-2 border border-green-200">
                <li class="inline-flex items-center">
                    <a href="{{ route('client.dashboard') }}" class="inline-flex items-center font-medium text-gray-700 hover:text-green-600">
                        <i class="fas fa-home mr-1 text-green-600"></i>
                        <span class="hidden xs:inline">Accueil</span>
                        <span class="xs:hidden">Accueil</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                        <a href="{{ route('client.equipment-rental-requests.index') }}" class="font-medium text-gray-700 hover:text-green-600">
                            <span class="hidden xs:inline">Mes demandes</span>
                            <span class="xs:hidden">Demandes</span>
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                        <span class="font-medium text-gray-500">
                            <span class="hidden xs:inline">Demande #{{ $request->id }}</span>
                            <span class="xs:hidden">#{{ $request->id }}</span>
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow border border-green-200 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-clipboard-list text-lg sm:text-xl text-green-600"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-green-900">Demande #{{ $request->id }}</h1>
                            <p class="text-xs sm:text-sm text-green-700 font-medium">Demande de location d'équipement</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-calendar-plus mr-1.5 text-green-600"></i>
                            <span class="font-medium">Créée le {{ $request->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="flex items-center text-green-700">
                            <i class="fas fa-clock mr-1.5 text-green-600"></i>
                            <span class="font-medium">Mise à jour le {{ $request->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2">
                    @if($request->status === 'pending')
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-bold bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 shadow">
                            <i class="fas fa-clock mr-1"></i>
                            En attente
                        </span>
                    @elseif($request->status === 'accepted')
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow">
                            <i class="fas fa-check-circle mr-1"></i>
                            Acceptée
                        </span>
                    @elseif($request->status === 'rejected')
                        <span class="inline-flex items-center px-3 py-2 rounded-full text-xs font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 shadow">
                            <i class="fas fa-times-circle mr-1"></i>
                            Refusée
                        </span>
                    @endif
                    
                    @if($request->status === 'pending')
                        <form method="POST" 
                              action="{{ route('client.equipment-rental-requests.destroy', $request) }}" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-xs font-medium rounded-lg shadow hover:shadow-md transition-all">
                                <i class="fas fa-times mr-1"></i>
                                <span class="hidden xs:inline">Annuler</span>
                                <span class="xs:inline">Annuler</span>
                            </button>
                        </form>
                    @endif
                    
                    @if($request->status === 'accepted')
                        <a href="{{ route('client.equipment-rental-requests.index') }}" 
                           class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-xs font-medium rounded-lg shadow hover:shadow-md transition-all">
                            <i class="fas fa-eye mr-1"></i>
                            <span class="hidden xs:inline">Voir mes locations</span>
                            <span class="xs:inline">Locations</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Détails de l'équipement demandé -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-tools text-lg text-green-600"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Équipement demandé</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Photos de l'équipement -->
                        <div>
                            @if($request->equipment->photos && count($request->equipment->photos) > 0)
                                <div class="space-y-3">
                                    <div class="relative group">
                                        <img src="{{ Storage::url($request->equipment->photos[0]) }}" 
                                             alt="{{ $request->equipment->name }}" 
                                             class="w-full h-48 sm:h-64 object-cover rounded-lg shadow cursor-pointer transition-transform group-hover:scale-105"
                                             onclick="showPhotoModal('{{ Storage::url($request->equipment->photos[0]) }}', '{{ $request->equipment->name }}')">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-lg opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        </div>
                                    </div>
                                    
                                    @if(count($request->equipment->photos) > 1)
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach(array_slice($request->equipment->photos, 1, 4) as $photo)
                                                <div class="relative group">
                                                    <img src="{{ Storage::url($photo) }}" 
                                                         alt="{{ $request->equipment->name }}" 
                                                         class="w-full h-16 object-cover rounded shadow cursor-pointer transition-transform group-hover:scale-105"
                                                         onclick="showPhotoModal('{{ Storage::url($photo) }}', '{{ $request->equipment->name }}')">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded flex items-center justify-center">
                                                        <i class="fas fa-search-plus text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="w-full h-48 sm:h-64 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center shadow">
                                    <div class="text-center">
                                        <i class="fas fa-image text-2xl sm:text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500 text-xs sm:text-sm font-medium">Aucune photo disponible</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Informations de l'équipement -->
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-green-900 mb-3">
                                    <a href="{{ route('equipment.show', $request->equipment) }}" 
                                       class="hover:text-green-600 transition-colors">
                                        {{ $request->equipment->name }}
                                    </a>
                                </h3>
                                <div class="grid grid-cols-1 gap-3">
                                    @if($request->equipment->brand || $request->equipment->model)
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center mb-1.5">
                                            <i class="fas fa-tag text-green-600 mr-1.5"></i>
                                            <span class="text-xs font-semibold text-green-700 uppercase tracking-wide">Marque/Modèle</span>
                                        </div>
                                        <p class="text-sm font-semibold text-green-900">{{ $request->equipment->brand }} {{ $request->equipment->model }}</p>
                                    </div>
                                    @endif
                                    
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center mb-1.5">
                                            <i class="fas fa-star text-green-600 mr-1.5"></i>
                                            <span class="text-xs font-semibold text-green-600 uppercase tracking-wide">État</span>
                                        </div>
                                        <p class="text-sm font-semibold text-green-900">{{ $request->equipment->formatted_condition }}</p>
                                    </div>
                                    
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                        <div class="flex items-center mb-1.5">
                                            <i class="fas fa-euro-sign text-green-600 mr-1.5"></i>
                                            <span class="text-xs font-semibold text-green-700 uppercase tracking-wide">Tarif journalier</span>
                                        </div>
                                        <p class="text-lg font-bold text-green-600">{{ number_format($request->equipment->daily_rate, 0) }}€</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($request->equipment->description)
                                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-info-circle text-green-600 mr-1.5"></i>
                                        <h4 class="font-semibold text-green-900 text-sm">Description</h4>
                                    </div>
                                    <p class="text-green-700 text-xs sm:text-sm leading-relaxed">{{ $request->equipment->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Période de location -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-calendar-alt text-lg text-green-600"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Période de location</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-play text-green-600 mr-1.5"></i>
                                <label class="text-xs font-semibold text-green-700 uppercase tracking-wide">Date de début</label>
                            </div>
                            <p class="text-lg font-bold text-gray-900 mb-1">{{ $request->start_date->format('d/m/Y') }}</p>
                            <p class="text-xs text-green-600 font-medium">{{ $request->start_date->translatedFormat('l') }}</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-stop text-red-600 mr-1.5"></i>
                                <label class="text-xs font-semibold text-red-700 uppercase tracking-wide">Date de fin</label>
                            </div>
                            <p class="text-lg font-bold text-gray-900 mb-1">{{ $request->end_date->format('d/m/Y') }}</p>
                            <p class="text-xs text-red-600 font-medium">{{ $request->end_date->translatedFormat('l') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-green-600 mr-2"></i>
                                <div>
                                    <p class="text-xs font-semibold text-green-700 uppercase tracking-wide mb-1">Durée totale</p>
                                    <p class="text-base font-bold text-green-600">{{ $request->duration_days }} jour{{ $request->duration_days > 1 ? 's' : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options de livraison -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-truck text-lg text-green-600"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Options de livraison</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center mb-2">
                                @if($request->delivery_required)
                                    <i class="fas fa-shipping-fast text-green-600 mr-1.5"></i>
                                @else
                                    <i class="fas fa-store text-green-600 mr-1.5"></i>
                                @endif
                                <label class="text-xs font-semibold text-green-700 uppercase tracking-wide">Mode de livraison</label>
                            </div>
                            <p class="text-base font-bold text-green-900">
                                @if($request->delivery_required)
                                    Livraison demandée
                                @else
                                    Retrait sur place
                                @endif
                            </p>
                        </div>
                        
                        @if($request->delivery_required && $request->delivery_address)
                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-map-marker-alt text-green-600 mr-1.5"></i>
                                <label class="text-xs font-semibold text-green-700 uppercase tracking-wide">Adresse de livraison</label>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $request->delivery_address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($request->message)
                <!-- Message -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-comment text-lg text-green-600"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Message</h2>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $request->message }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Réponse du prestataire -->
                @if($request->status !== 'pending')
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-{{ $request->status === 'accepted' ? 'green' : 'red' }}-100 to-{{ $request->status === 'accepted' ? 'green' : 'red' }}-200 rounded-full mr-3 shadow">
                            @if($request->status === 'accepted')
                                <i class="fas fa-check text-lg text-green-600"></i>
                            @else
                                <i class="fas fa-times text-lg text-red-600"></i>
                            @endif
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Réponse du prestataire</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-{{ $request->status === 'accepted' ? 'green' : 'red' }}-50 to-{{ $request->status === 'accepted' ? 'green' : 'red' }}-100 rounded-lg p-4 border border-{{ $request->status === 'accepted' ? 'green' : 'red' }}-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($request->status === 'accepted')
                                        <i class="fas fa-check-circle text-lg text-green-600 mr-2"></i>
                                    @else
                                        <i class="fas fa-times-circle text-lg text-red-600 mr-2"></i>
                                    @endif
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm sm:text-base">
                                            @if($request->status === 'accepted')
                                                Demande acceptée
                                            @else
                                                Demande refusée
                                            @endif
                                        </p>
                                        <p class="text-xs text-{{ $request->status === 'accepted' ? 'green' : 'red' }}-600 font-medium">
                                            Répondu le {{ $request->updated_at->format('d/m/Y à H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($request->provider_message)
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-quote-left text-blue-600 mr-1.5"></i>
                                    <h4 class="font-semibold text-gray-900 text-sm">Message du prestataire</h4>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed italic">{{ $request->provider_message }}</p>
                            </div>
                        @endif
                        
                        @if($request->status === 'accepted')
                        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-check-circle text-green-600 mr-2 text-base"></i>
                                <span class="text-green-800 font-bold text-sm">Votre demande a été acceptée !</span>
                            </div>
                            <p class="text-green-700 text-xs leading-relaxed">
                                Une location a été créée automatiquement. Vous pouvez la consulter dans votre espace "Mes locations".
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Historique -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-history text-lg text-green-600"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-green-900">Historique</h2>
                    </div>
                    
                    <div class="relative">
                        <!-- Timeline line -->
                        <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-green-400 to-gray-300"></div>
                        
                        <div class="space-y-5">
                            <!-- Création de la demande -->
                            <div class="relative flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center shadow border-2 border-white">
                                    <i class="fas fa-plus text-green-600 text-sm"></i>
                                </div>
                                <div class="ml-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">Demande créée</p>
                                            <p class="text-xs text-green-600 font-medium">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                        <i class="fas fa-file-plus text-green-600"></i>
                                    </div>
                                </div>
                            </div>
                            
                            @if($request->status !== 'pending')
                            <!-- Réponse du prestataire -->
                            <div class="relative flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-{{ $request->status === 'accepted' ? 'green' : 'red' }}-100 to-{{ $request->status === 'accepted' ? 'green' : 'red' }}-200 rounded-full flex items-center justify-center shadow border-2 border-white">
                                    @if($request->status === 'accepted')
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    @else
                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                    @endif
                                </div>
                                <div class="ml-4 bg-gradient-to-r from-{{ $request->status === 'accepted' ? 'green' : 'red' }}-50 to-{{ $request->status === 'accepted' ? 'green' : 'red' }}-100 rounded-lg p-3 border border-{{ $request->status === 'accepted' ? 'green' : 'red' }}-200 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">
                                                Demande {{ $request->status === 'accepted' ? 'acceptée' : 'refusée' }}
                                            </p>
                                            <p class="text-xs text-{{ $request->status === 'accepted' ? 'green' : 'red' }}-600 font-medium">{{ $request->updated_at->format('d/m/Y à H:i') }}</p>
                                        </div>
                                        @if($request->status === 'accepted')
                                            <i class="fas fa-thumbs-up text-green-600"></i>
                                        @else
                                            <i class="fas fa-thumbs-down text-red-600"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Récapitulatif financier -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-calculator text-lg text-green-600"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-green-900">Récapitulatif</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-green-600 mr-1.5"></i>
                                    <span class="text-xs font-semibold text-green-700">Location ({{ $request->duration_days }} jour{{ $request->duration_days > 1 ? 's' : '' }})</span>
                                </div>
                                <span class="font-bold text-green-600 text-sm">{{ number_format($request->rental_amount, 0) }}€</span>
                            </div>
                        </div>
                        
                        @if($request->delivery_required && $request->delivery_cost > 0)
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-truck text-green-600 mr-1.5"></i>
                                    <span class="text-xs font-semibold text-green-700">Livraison</span>
                                </div>
                                <span class="font-bold text-green-600 text-sm">{{ number_format($request->delivery_cost, 0) }}€</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->equipment->deposit_amount > 0)
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-3 border border-orange-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt text-orange-600 mr-1.5"></i>
                                    <span class="text-xs font-semibold text-orange-700">Caution</span>
                                </div>
                                <span class="font-bold text-orange-600 text-sm">{{ number_format($request->equipment->deposit_amount, 0) }}€</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border-2 border-green-300 shadow">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-euro-sign text-green-600 mr-2 text-base"></i>
                                    <span class="text-sm font-bold text-green-700">Total</span>
                                </div>
                                <span class="text-lg font-bold text-green-600">{{ number_format($request->total_amount, 0) }}€</span>
                            </div>
                        </div>
                        
                        @if($request->equipment->deposit_amount > 0)
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-2 border border-yellow-200">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-yellow-600 mr-1.5"></i>
                                <p class="text-xs text-yellow-700 font-medium">
                                    La caution sera restituée après retour de l'équipement en bon état
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Informations du prestataire -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-user-tie text-lg text-green-600"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-green-900">Prestataire</h3>
                    </div>
                    
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center shadow border-2 border-white">
                                <span class="text-green-600 font-bold text-lg">
                                    {{ substr($request->equipment->prestataire->company_name ?? $request->equipment->prestataire->first_name, 0, 1) }}
                                </span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-gradient-to-br from-green-400 to-green-500 rounded-full flex items-center justify-center shadow">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        
                        <h4 class="text-base font-bold text-gray-900 mb-2">
                            {{ $request->equipment->prestataire->company_name ?? $request->equipment->prestataire->first_name . ' ' . $request->equipment->prestataire->last_name }}
                        </h4>
                        
                        @if($request->equipment->prestataire->address)
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-2 mb-4 border border-gray-200">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-gray-600 mr-1.5"></i>
                                <p class="text-xs text-gray-700 font-medium">{{ $request->equipment->prestataire->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        
                    </div>
                </div>
                
                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow border border-green-200 p-4 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-green-100 to-green-200 rounded-full mr-3 shadow">
                            <i class="fas fa-bolt text-lg text-green-600"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-green-900">Actions rapides</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('equipment.show', $request->equipment) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-lg shadow hover:from-green-600 hover:to-green-700 transition-all">
                            <i class="fas fa-eye mr-2"></i>
                            Voir l'équipement
                        </a>
                        
                        <a href="{{ route('equipment.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-lg shadow hover:from-green-600 hover:to-green-700 transition-all">
                            <i class="fas fa-search mr-2"></i>
                            Parcourir le matériel
                        </a>
                        
                        @if($request->status === 'rejected')
                        <a href="{{ route('equipment.show', $request->equipment) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium rounded-lg shadow hover:from-green-600 hover:to-green-700 transition-all">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle demande
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les photos -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50 p-2">
    <div class="max-w-full max-h-full">
        <img id="modalPhoto" src="" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closePhotoModal()" 
                class="absolute top-2 right-2 text-white text-xl hover:text-gray-300">
            ×
        </button>
    </div>
</div>

<script>
// Gestion des photos
function showPhotoModal(photoUrl, altText) {
    const modal = document.getElementById('photoModal');
    const modalPhoto = document.getElementById('modalPhoto');
    
    modalPhoto.src = photoUrl;
    modalPhoto.alt = altText;
    modal.classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Fermer la modal avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('photoModal').classList.add('hidden');
    }
});
</script>
@endsection