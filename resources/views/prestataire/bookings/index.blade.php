@extends('layouts.app')

@section('title', 'Mes Demandes')

@section('content')
<div class="bg-blue-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">Mes Demandes</h1>
                <p class="text-lg text-blue-700">Gérez toutes vos demandes de services, équipements et ventes urgentes</p>
            </div>

            <!-- Messages de session -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtres par type -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Filtrer par type</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('prestataire.bookings.index') }}" 
                           class="px-6 py-3 rounded-lg font-semibold {{ !request('type') ? 'bg-gray-600 text-white shadow-lg' : 'bg-gray-50 text-gray-700 hover:bg-gray-100 border border-gray-200' }} transition duration-200">
                            Toutes
                        </a>
                        <a href="{{ route('prestataire.bookings.index', ['type' => 'service']) }}" 
                           class="px-6 py-3 rounded-lg font-semibold {{ request('type') === 'service' ? 'bg-blue-600 text-white shadow-lg' : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200' }} transition duration-200">
                            Services
                        </a>
                        <a href="{{ route('prestataire.bookings.index', ['type' => 'equipment']) }}" 
                           class="px-6 py-3 rounded-lg font-semibold {{ request('type') === 'equipment' ? 'bg-green-600 text-white shadow-lg' : 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200' }} transition duration-200">
                            Équipements
                        </a>
                        <a href="{{ route('prestataire.bookings.index', ['type' => 'urgent_sale']) }}" 
                           class="px-6 py-3 rounded-lg font-semibold {{ request('type') === 'urgent_sale' ? 'bg-red-600 text-white shadow-lg' : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' }} transition duration-200">
                            Ventes Urgentes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Section Toutes les demandes mélangées (affichée uniquement si aucun filtre de type spécifique) -->
            @if(!request('type') || request('type') === 'all')
                @if(isset($allRequests) && $allRequests->count() > 0)
                    <div class="mb-8">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                            <div class="flex items-center justify-between mb-6 border-b-2 border-gray-200 pb-4">
                                 <div class="flex items-center">
                                     <div class="w-4 h-4 bg-gray-600 rounded-full mr-3"></div>
                                     <h2 class="text-2xl font-bold text-gray-800">Toutes les demandes</h2>
                                     <span class="ml-3 bg-gray-100 text-gray-800 text-sm font-bold px-3 py-1 rounded-full">{{ $allRequests->count() }}</span>
                                 </div>
                                 
                                 <!-- Sélecteur de tri -->
                                 <div class="flex items-center space-x-3">
                                     <span class="text-sm text-gray-600 font-medium">Trier par :</span>
                                     <select id="sortOrder" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                         <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>Plus récent au plus ancien</option>
                                         <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Plus ancien au plus récent</option>
                                     </select>
                                 </div>
                             </div>
                             
                             <script>
                                 document.getElementById('sortOrder').addEventListener('change', function() {
                                     const currentUrl = new URL(window.location.href);
                                     currentUrl.searchParams.set('sort', this.value);
                                     window.location.href = currentUrl.toString();
                                 });
                             </script>
                            
                            <div class="space-y-4">
                                @foreach($allRequests as $item)
                                    @if($item->request_type === 'service')
                                        <!-- Réservation de service -->
                                        <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                            <div class="flex flex-col md:flex-row">
                                                <!-- Image à gauche -->
                                                <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                    @if($item->service && $item->service->images && $item->service->images->count() > 0)
                                                        @php
                                                            $firstImage = $item->service->images->first();
                                                            $imagePath = $firstImage->image_path;
                                                            $imageUrl = asset('storage/' . $imagePath);
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" 
                                                             alt="{{ $item->service->title ?? 'Service' }}" 
                                                             class="w-full h-full object-cover object-center" 
                                                             loading="lazy"
                                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\"w-full h-full bg-blue-100 flex items-center justify-center\"><i class=\"fas fa-concierge-bell text-blue-400 text-3xl\"></i></div>
                                                    @else
                                                        <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                                            <i class="fas fa-concierge-bell text-blue-400 text-3xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Détails au centre -->
                                                <div class="flex-1 p-6">
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                        <div class="flex-1">
                                                            <h3 class="text-xl font-bold text-blue-900 mb-2">
                                                                {{ $item->service->title ?? 'Service' }}
                                                            </h3>
                                                            <div class="space-y-1 text-sm mb-3">
                                                                <p class="text-blue-800 font-medium">Client: {{ $item->client->user->name ?? 'N/A' }}</p>
                                                                <p class="text-blue-700">Type: Service</p>
                                                                <p class="text-blue-700">Prix: {{ number_format($item->service->price ?? 0, 2) }}€</p>
                                                                <p class="text-blue-600 text-xs">{{ $item->created_at->format('d/m/Y à H:i') }}</p>
                                                            </div>
                                                            <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @elseif($item->status === 'confirmed') bg-green-100 text-green-800
                                                                @elseif($item->status === 'cancelled') bg-red-100 text-red-800
                                                                @endif">
                                                                @if($item->status === 'pending') En attente
                                                                @elseif($item->status === 'confirmed') Confirmée
                                                                @elseif($item->status === 'cancelled') Annulée
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="mt-4 md:mt-0 md:ml-6">
                                                            <a href="{{ route('prestataire.bookings.show', $item->id) }}" 
                                                               class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded hover:bg-blue-700 transition duration-200 text-center">
                                                                Voir détails
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($item->request_type === 'equipment')
                                        <!-- Demande d'équipement -->
                                        <div class="bg-green-50 border-l-4 border-green-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                            <div class="flex flex-col md:flex-row">
                                                <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                    @if($item->equipment && $item->equipment->images && $item->equipment->images->count() > 0)
                                                        @php
                                                            $firstEquipmentImage = $item->equipment->images->first();
                                                            $equipmentImagePath = $firstEquipmentImage->image_path;
                                                            $equipmentImageUrl = asset('storage/' . $equipmentImagePath);
                                                        @endphp
                                                        <img src="{{ $equipmentImageUrl }}" 
                                                             alt="{{ $item->equipment->name ?? 'Équipement' }}" 
                                                             class="w-full h-full object-cover object-center" 
                                                             loading="lazy"
                                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\"w-full h-full bg-green-100 flex items-center justify-center\"><i class=\"fas fa-tools text-green-400 text-3xl\"></i></div>
                                                    @else
                                                        <div class="w-full h-full bg-green-100 flex items-center justify-center">
                                                            <i class="fas fa-tools text-green-400 text-3xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 p-6">
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                        <div class="flex-1">
                                                            <h3 class="text-xl font-bold text-green-900 mb-2">
                                                                {{ $item->equipment->name ?? 'Équipement' }}
                                                            </h3>
                                                            <div class="space-y-1 text-sm mb-3">
                                                                <p class="text-green-800 font-medium">Client: {{ $item->client->user->name ?? 'N/A' }}</p>
                                                                <p class="text-green-700">Type: Location d'équipement</p>
                                                                @if($item->start_date && $item->end_date)
                                                                    <p class="text-green-700">Période: {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}</p>
                                                                @endif
                                                                <p class="text-green-600 text-xs">{{ $item->created_at->format('d/m/Y à H:i') }}</p>
                                                            </div>
                                                            <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @elseif($item->status === 'accepted') bg-green-100 text-green-800
                                                                @elseif($item->status === 'rejected') bg-red-100 text-red-800
                                                                @endif">
                                                                @if($item->status === 'pending') En attente
                                                                @elseif($item->status === 'accepted') Acceptée
                                                                @elseif($item->status === 'rejected') Refusée
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="mt-4 md:mt-0 md:ml-6">
                                                            <a href="{{ route('prestataire.equipment-rental-requests.show', $item->id) }}" 
                                                               class="w-full md:w-auto px-4 py-2 bg-green-600 text-white text-sm font-bold rounded hover:bg-green-700 transition duration-200 text-center">
                                                                Voir détails
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($item->request_type === 'urgent_sale')
                                        <!-- Vente urgente -->
                                        <div class="bg-red-50 border-l-4 border-red-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                            <div class="flex flex-col md:flex-row">
                                                <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                    @if($item->images && $item->images->count() > 0)
                                                        @php
                                                            $firstUrgentImage = $item->images->first();
                                                            $urgentImagePath = $firstUrgentImage->image_path;
                                                            $urgentImageUrl = asset('storage/' . $urgentImagePath);
                                                        @endphp
                                                        <img src="{{ $urgentImageUrl }}" 
                                                             alt="{{ $item->title ?? 'Vente urgente' }}" 
                                                             class="w-full h-full object-cover object-center" 
                                                             loading="lazy"
                                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\"w-full h-full bg-red-100 flex items-center justify-center\"><i class=\"fas fa-tag text-red-400 text-3xl\"></i></div>
                                                    @else
                                                        <div class="w-full h-full bg-red-100 flex items-center justify-center">
                                                            <i class="fas fa-tag text-red-400 text-3xl"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 p-6">
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                        <div class="flex-1">
                                                            <h3 class="text-xl font-bold text-red-900 mb-2">
                                                                {{ $item->title ?? 'Vente urgente' }}
                                                            </h3>
                                                            <div class="space-y-1 text-sm mb-3">
                                                                <p class="text-red-800 font-medium">Client: {{ $item->client->user->name ?? 'N/A' }}</p>
                                                                <p class="text-red-700">Type: Vente urgente</p>
                                                                @if($item->price)
                                                                    <p class="text-red-700 font-bold">Prix: {{ number_format($item->price, 0, ',', ' ') }}€</p>
                                                                @endif
                                                                <p class="text-red-600 text-xs">{{ $item->created_at->format('d/m/Y à H:i') }}</p>
                                                            </div>
                                                            <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                                @elseif($item->status === 'accepted') bg-green-100 text-green-800
                                                                @elseif($item->status === 'rejected') bg-red-100 text-red-800
                                                                @endif">
                                                                @if($item->status === 'pending') En attente
                                                                @elseif($item->status === 'accepted') Acceptée
                                                                @elseif($item->status === 'rejected') Refusée
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="mt-4 md:mt-0 md:ml-6">
                                                            <a href="{{ route('prestataire.urgent-sales.show', $item->id) }}" 
                                                               class="w-full md:w-auto px-4 py-2 bg-red-600 text-white text-sm font-bold rounded hover:bg-red-700 transition duration-200 text-center">
                                                                Voir détails
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Message si aucune demande -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-inbox text-6xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Aucune demande trouvée</h3>
                        <p class="text-gray-600">Vous n'avez encore reçu aucune demande de réservation.</p>
                    </div>
                @endif
            @endif

    @php
        // Vérifier s'il y a des données à afficher
        $hasItems = ($showServices && isset($serviceBookings) && $serviceBookings->count() > 0) ||
                   ($showEquipments && isset($equipmentRentalRequests) && $equipmentRentalRequests->count() > 0) ||
                   ($showUrgentSales && isset($urgentSales) && $urgentSales->count() > 0);
    @endphp

            @if($hasItems)
                <!-- Section Services -->
                @if($showServices && isset($serviceBookings) && $serviceBookings->count() > 0)
                    <div class="mb-8">
                        <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
                            <div class="flex items-center mb-6 border-b-2 border-blue-200 pb-4">
                                <div class="w-4 h-4 bg-blue-600 rounded-full mr-3"></div>
                                <h2 class="text-2xl font-bold text-blue-800">Services</h2>
                                <span class="ml-3 bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full">{{ $serviceBookings->count() }}</span>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($serviceBookings as $booking)
                                    <div class="bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                        <div class="flex flex-col md:flex-row">
                                            <!-- Image à gauche (format 4:3) -->
                                            <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                @if($booking->service && $booking->service->images && $booking->service->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $booking->service->images->first()->image_path) }}" 
                                                         alt="{{ $booking->service->title ?? 'Service' }}" 
                                                         class="w-full h-full object-cover object-center" loading="lazy">
                                                @else
                                                    <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-concierge-bell text-blue-400 text-3xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Détails au centre -->
                                            <div class="flex-1 p-6">
                                                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                    <div class="flex-1">
                                                        <!-- Titre -->
                                                        <h3 class="text-xl font-bold text-blue-900 mb-2">
                                                            {{ $booking->service->title ?? 'Service' }}
                                                        </h3>
                                                        
                                                        <!-- Métadonnées -->
                                                        <div class="space-y-1 text-sm mb-3">
                                                            <p class="text-blue-800 font-medium">Client: {{ $booking->client->user->name ?? 'N/A' }}</p>
                                                            <p class="text-blue-700">Type: Service</p>
                                                            @if($booking->service && $booking->service->category->first())
                                                                <p class="text-blue-700">Catégorie: {{ $booking->service->category->first()->name }}</p>
                                                            @endif
                                                            <p class="text-blue-600 text-xs">{{ $booking->created_at->format('d/m/Y à H:i') }}</p>
                                                        </div>
                                                        
                                                        <!-- Statut -->
                                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($booking->status === 'accepted') bg-green-100 text-green-800
                                                            @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                                            @endif">
                                                            @if($booking->status === 'pending') En attente
                                                            @elseif($booking->status === 'accepted') Acceptée
                                                            @elseif($booking->status === 'rejected') Refusée
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Bouton à droite -->
                                                    <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                                                        <a href="{{ route('prestataire.bookings.show', $booking->id) }}" 
                                                           class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded hover:bg-blue-700 transition duration-200 text-center">
                                                            Voir détails
                                                        </a>
                                                        @if($booking->status === 'pending')
                                                            <div class="flex space-x-2">
                                                                <form action="{{ route('prestataire.bookings.accept', $booking->id) }}" method="POST" class="flex-1">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="w-full px-3 py-1 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700 transition duration-200">
                                                                        Accepter
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('prestataire.bookings.reject', $booking->id) }}" method="POST" class="flex-1">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="w-full px-3 py-1 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700 transition duration-200">
                                                                        Refuser
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Modals de refus pour chaque demande -->
                    @foreach($equipmentRentalRequests as $request)
                        @if($request->status === 'pending')
                            <x-modal name="reject-request-{{ $request->id }}" :show="false" maxWidth="md">
                                <div class="p-6">
                                    <div class="flex items-center mb-4">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-semibold text-gray-900">Refuser la demande</h3>
                                            <p class="text-sm text-gray-600">Veuillez indiquer la raison du refus</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('prestataire.equipment-rental-requests.reject', $request) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="mb-4">
                                            <label for="rejection_reason_{{ $request->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                Raison du refus *
                                            </label>
                                            <textarea 
                                                id="rejection_reason_{{ $request->id }}"
                                                name="rejection_reason" 
                                                rows="3" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                                                placeholder="Expliquez pourquoi vous refusez cette demande..."
                                                required></textarea>
                                        </div>

                                        <div class="flex justify-end space-x-3">
                                            <button type="button" 
                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                                                    x-on:click="$dispatch('close')">
                                                Annuler
                                            </button>
                                            <button type="submit" 
                                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                Confirmer le refus
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </x-modal>
                        @endif
                    @endforeach
                @endif

                <!-- Section Équipements -->
                @if($showEquipments && isset($equipmentRentalRequests) && $equipmentRentalRequests->count() > 0)
                    <div class="mb-8">
                        <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                            <div class="flex items-center mb-6 border-b-2 border-green-200 pb-4">
                                <div class="w-4 h-4 bg-green-600 rounded-full mr-3"></div>
                                <h2 class="text-2xl font-bold text-green-800">Équipements à louer</h2>
                                <span class="ml-3 bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">{{ $equipmentRentalRequests->count() }}</span>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($equipmentRentalRequests as $request)
                                    <div class="bg-green-50 border-l-4 border-green-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                        <div class="flex flex-col md:flex-row">
                                            <!-- Image à gauche (format 4:3) -->
                                            <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                @if($request->equipment && $request->equipment->images && $request->equipment->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $request->equipment->images->first()->image_path) }}" 
                                                         alt="{{ $request->equipment->name ?? 'Équipement' }}" 
                                                         class="w-full h-full object-cover object-center" loading="lazy">
                                                @else
                                                    <div class="w-full h-full bg-green-100 flex items-center justify-center">
                                                        <i class="fas fa-tools text-green-400 text-3xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Détails au centre -->
                                            <div class="flex-1 p-6">
                                                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                    <div class="flex-1">
                                                        <!-- Titre -->
                                                        <h3 class="text-xl font-bold text-green-900 mb-2">
                                                            {{ $request->equipment->name ?? 'Équipement' }}
                                                        </h3>
                                                        
                                                        <!-- Métadonnées -->
                                                        <div class="space-y-1 text-sm mb-3">
                                                            <p class="text-green-800 font-medium">Client: {{ $request->client->user->name ?? 'N/A' }}</p>
                                                            <p class="text-green-700">Type: Location d'équipement</p>
                                                            @if($request->equipment && $request->equipment->categories->first())
                                                                <p class="text-green-700">Catégorie: {{ $request->equipment->categories->first()->name }}</p>
                                                            @endif
                                                            @if($request->start_date && $request->end_date)
                                                                <p class="text-green-700">Période: {{ \Carbon\Carbon::parse($request->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d/m/Y') }}</p>
                                                            @endif
                                                            <p class="text-green-600 text-xs">{{ $request->created_at->format('d/m/Y à H:i') }}</p>
                                                        </div>
                                                        
                                                        <!-- Statut -->
                                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($request->status === 'accepted') bg-green-100 text-green-800
                                                            @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                                            @endif">
                                                            @if($request->status === 'pending') En attente
                                                            @elseif($request->status === 'accepted') Acceptée
                                                            @elseif($request->status === 'rejected') Refusée
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Bouton à droite -->
                                                    <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                                                        <a href="{{ route('prestataire.equipment-rental-requests.show', $request->id) }}" 
                                                           class="w-full md:w-auto px-4 py-2 bg-green-600 text-white text-sm font-bold rounded hover:bg-green-700 transition duration-200 text-center">
                                                            Voir détails
                                                        </a>
                                                        @if($request->status === 'pending')
                                                            <div class="flex space-x-2">
                                                                <form action="{{ route('prestataire.equipment-rental-requests.accept', $request) }}" method="POST" class="flex-1">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="w-full px-3 py-1 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700 transition duration-200"
                                                                            onclick="return confirm('Accepter cette demande de location ?')">
                                                                        Accepter
                                                                    </button>
                                                                </form>
                                                                <button type="button" 
                                                                        class="flex-1 px-3 py-1 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700 transition duration-200"
                                                                        x-data
                                                                        @click="$dispatch('open-modal', 'reject-request-{{ $request->id }}')">
                                                                    Refuser
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section Ventes Urgentes -->
                @if($showUrgentSales && isset($urgentSales) && $urgentSales->count() > 0)
                    <div class="mb-8">
                        <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6">
                            <div class="flex items-center mb-6 border-b-2 border-red-200 pb-4">
                                <div class="w-4 h-4 bg-red-600 rounded-full mr-3"></div>
                                <h2 class="text-2xl font-bold text-red-800">Ventes urgentes</h2>
                                <span class="ml-3 bg-red-100 text-red-800 text-sm font-bold px-3 py-1 rounded-full">{{ $urgentSales->count() }}</span>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach($urgentSales as $sale)
                                    <div class="bg-red-50 border-l-4 border-red-600 rounded-lg shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden">
                                        <div class="flex flex-col md:flex-row">
                                            <!-- Image à gauche (format 4:3) -->
                                            <div class="w-full md:w-48 h-36 flex-shrink-0">
                                                @if($sale->images && $sale->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $sale->images->first()->image_path) }}" 
                                                         alt="{{ $sale->title ?? 'Vente urgente' }}" 
                                                         class="w-full h-full object-cover object-center" loading="lazy">
                                                @else
                                                    <div class="w-full h-full bg-red-100 flex items-center justify-center">
                                                        <i class="fas fa-tag text-red-400 text-3xl"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Détails au centre -->
                                            <div class="flex-1 p-6">
                                                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-full">
                                                    <div class="flex-1">
                                                        <!-- Titre -->
                                                        <h3 class="text-xl font-bold text-red-900 mb-2">
                                                            {{ $sale->title ?? 'Vente urgente' }}
                                                        </h3>
                                                        
                                                        <!-- Métadonnées -->
                                                        <div class="space-y-1 text-sm mb-3">
                                                            <p class="text-red-800 font-medium">Client: {{ $sale->client->name ?? 'N/A' }}</p>
                                                            <p class="text-red-700">Type: Vente urgente</p>
                                                            @if($sale->category)
                                                                <p class="text-red-700">Catégorie: {{ $sale->category->name }}</p>
                                                            @endif
                                                            @if($sale->price_min && $sale->price_max)
                                                                <p class="text-red-700">Prix: {{ number_format($sale->price_min, 0, ',', ' ') }}€ - {{ number_format($sale->price_max, 0, ',', ' ') }}€</p>
                                                            @elseif($sale->price)
                                                                <p class="text-red-700">Prix: {{ number_format($sale->price, 0, ',', ' ') }}€</p>
                                                            @endif
                                                            <p class="text-red-600 text-xs">{{ $sale->created_at->format('d/m/Y à H:i') }}</p>
                                                        </div>
                                                        
                                                        <!-- Statut -->
                                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full
                                                            @if($sale->status === 'pending') bg-yellow-100 text-yellow-800
                                                            @elseif($sale->status === 'accepted') bg-green-100 text-green-800
                                                            @elseif($sale->status === 'rejected') bg-red-100 text-red-800
                                                            @endif">
                                                            @if($sale->status === 'pending') En attente
                                                            @elseif($sale->status === 'accepted') Acceptée
                                                            @elseif($sale->status === 'rejected') Refusée
                                                            @endif
                                                        </span>
                                                    </div>
                                                    
                                                    <!-- Bouton à droite -->
                                                    <div class="mt-4 md:mt-0 md:ml-6 flex flex-col space-y-2">
                                                        <a href="{{ route('prestataire.urgent-sales.show', $sale->id) }}" 
                                                           class="w-full md:w-auto px-4 py-2 bg-red-600 text-white text-sm font-bold rounded hover:bg-red-700 transition duration-200 text-center">
                                                            Voir détails
                                                        </a>
                                                        @if($sale->status === 'pending')
                                                            <div class="flex space-x-2">
                                                                <form action="{{ route('prestataire.urgent-sales.accept', $sale->id) }}" method="POST" class="flex-1">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="w-full px-3 py-1 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700 transition duration-200">
                                                                        Accepter
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('prestataire.urgent-sales.reject', $sale->id) }}" method="POST" class="flex-1">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" class="w-full px-3 py-1 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700 transition duration-200">
                                                                        Refuser
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @else
        <!-- États vides spécifiques par type -->
        @if($showServices && (!isset($serviceBookings) || $serviceBookings->count() === 0))
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-blue-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-blue-700 mb-2">Aucune demande de service</h3>
                <p class="text-blue-500">Vous n'avez reçu aucune demande de service pour le moment.</p>
            </div>
        @endif

        @if($showEquipments && (!isset($equipmentRentalRequests) || $equipmentRentalRequests->count() === 0))
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tools text-green-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-green-700 mb-2">Aucune demande d'équipement</h3>
                <p class="text-green-500">Vous n'avez reçu aucune demande de location d'équipement pour le moment.</p>
            </div>
        @endif

        @if($showUrgentSales && (!isset($urgentSales) || $urgentSales->count() === 0))
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tag text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-red-700 mb-2">Aucune vente urgente</h3>
                <p class="text-red-500">Vous n'avez reçu aucune demande de vente urgente pour le moment.</p>
            </div>
        @endif

        <!-- État vide général -->
        @if(!$hasItems && !request('type'))
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande</h3>
                <p class="text-gray-500">Vous n'avez aucune demande pour le moment.</p>
            </div>
        @endif
    @endif
</div>

<script>
// Script pour les notifications en temps réel (à implémenter plus tard)
document.addEventListener('DOMContentLoaded', function() {
    // Ici on pourra ajouter la logique pour les notifications en temps réel
    // et la mise à jour automatique des statuts
});
</script>
@endsection