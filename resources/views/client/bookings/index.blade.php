@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100">
<div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                        <span class="hidden sm:inline">Historique de mes réservations</span>
                        <span class="sm:hidden">Mes réservations</span>
                    </h1>
                    <p class="text-gray-600 text-sm sm:text-base lg:text-lg">
                        <span class="hidden sm:inline">Consultez et gérez l'ensemble de vos réservations de services</span>
                        <span class="sm:hidden">Gérez vos réservations</span>
                    </p>
                </div>
                <div class="flex justify-center sm:justify-end">
                    <a href="{{ route('services.index') }}" class="bg-blue-600 text-white px-4 sm:px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl border border-blue-700 font-semibold text-sm sm:text-base lg:text-lg flex items-center justify-center w-full sm:w-auto" style="min-height: 44px;">
                        <i class="fas fa-plus mr-2 sm:mr-3"></i>
                        <span class="hidden sm:inline">Nouvelle réservation</span>
                        <span class="sm:hidden">Nouveau</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 sm:mb-6 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 sm:mb-6 text-sm sm:text-base">
                {{ session('error') }}
            </div>
        @endif
        
        <!-- Filtres de réservation -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 sm:p-6 mb-6 sm:mb-8">
            <form action="{{ route('client.bookings.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-1 lg:grid-cols-3 sm:gap-4 lg:gap-6 sm:items-end">
                <div class="space-y-2">
                    <label for="status" class="block text-xs sm:text-sm font-semibold text-gray-700">
                        <i class="fas fa-filter mr-1 sm:mr-2 text-blue-600"></i>Statut
                    </label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 py-2.5 sm:py-3 px-3 sm:px-4 text-sm sm:text-base text-gray-700" style="min-height: 44px;">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        <option value="refused" {{ request('status') == 'refused' ? 'selected' : '' }}>Refusée</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="date_range" class="block text-xs sm:text-sm font-semibold text-gray-700">
                        <i class="fas fa-calendar-alt mr-1 sm:mr-2 text-blue-600"></i>Période
                    </label>
                    <select id="date_range" name="date_range" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 py-2.5 sm:py-3 px-3 sm:px-4 text-sm sm:text-base text-gray-700" style="min-height: 44px;">
                        <option value="">Toutes les dates</option>
                        <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                        <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Passées</option>
                        <option value="last_month" {{ request('date_range') == 'last_month' ? 'selected' : '' }}>Dernier mois</option>
                        <option value="last_3months" {{ request('date_range') == 'last_3months' ? 'selected' : '' }}>3 derniers mois</option>
                    </select>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button type="submit" class="bg-blue-600 text-white px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl border border-blue-700 font-semibold text-sm sm:text-base flex items-center justify-center" style="min-height: 44px;">
                        <i class="fas fa-filter mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Filtrer</span>
                        <span class="sm:hidden">OK</span>
                    </button>
                    
                    @if(request('status') || request('date_range'))
                        <a href="{{ route('client.bookings.index') }}" class="text-gray-600 px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 rounded-lg hover:bg-gray-100 transition-all duration-300 border border-gray-300 shadow-sm hover:shadow-md font-medium text-sm sm:text-base flex items-center justify-center" style="min-height: 44px;">
                            <i class="fas fa-times mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Réinitialiser</span>
                            <span class="sm:hidden">Reset</span>
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Indicateur de filtres actifs -->
            @if(request('status') || request('date_range'))
                <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <span class="text-xs sm:text-sm text-gray-600 font-medium">Filtres actifs :</span>
                        @if(request('status'))
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                Statut: {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                        @if(request('date_range'))
                            <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                Période: {{ ucfirst(str_replace('_', ' ', request('date_range'))) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Bookings List -->
        @if($bookings->isEmpty())
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-16 text-center">
                <div class="max-w-lg mx-auto">
                    <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg">
                        <i class="fas fa-calendar-times text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune réservation trouvée</h3>
                    <p class="text-gray-600 text-lg mb-8 leading-relaxed">Vous n'avez pas encore effectué de réservation ou aucune réservation ne correspond à vos critères de recherche.</p>
                    <div class="space-y-4">
                        <a href="{{ route('services.index') }}" class="bg-blue-600 text-white px-8 py-4 rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl border border-blue-700 inline-flex items-center font-semibold text-lg">
                            <i class="fas fa-plus mr-3"></i>
                            Nouvelle réservation
                        </a>
                        @if(request('status') || request('date_range'))
                            <div class="mt-6">
                                <a href="{{ route('client.bookings.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold text-lg hover:underline transition-all duration-200">
                                    Voir toutes mes réservations
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 lg:p-8 mb-6 hover:shadow-xl transition-all duration-300 hover:border-blue-300">
                        <!-- Desktop Layout (≥1024px) -->
                        <div class="hidden lg:flex items-start justify-between">
                            <!-- Section principale avec avatar et infos -->
                            <div class="flex items-start space-x-6 flex-1">
                                <!-- Avatar du prestataire -->
                                <div class="flex-shrink-0">
                                    @if($booking->prestataire && $booking->prestataire->photo)
                                        <img class="h-16 w-16 rounded-full object-cover shadow-lg" src="{{ asset('storage/' . $booking->prestataire->photo) }}" alt="{{ $booking->prestataire->user->name }}">
                                    @elseif($booking->prestataire && $booking->prestataire->user && $booking->prestataire->user->avatar)
                                        <img class="h-16 w-16 rounded-full object-cover shadow-lg" src="{{ asset('storage/' . $booking->prestataire->user->avatar) }}" alt="{{ $booking->prestataire->user->name }}">
                                    @elseif($booking->prestataire && $booking->prestataire->user && $booking->prestataire->user->profile_photo_url)
                                        <img class="h-16 w-16 rounded-full object-cover shadow-lg" src="{{ $booking->prestataire->user->profile_photo_url }}" alt="{{ $booking->prestataire->user->name }}">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                            {{ $booking->prestataire && $booking->prestataire->user ? strtoupper(substr($booking->prestataire->user->name, 0, 1)) : 'P' }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Bloc informations -->
                                <div class="flex-1 space-y-3">
                                    <!-- Titre et prestataire -->
                                    <div>
                                        @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                            <!-- Multi-slot session header -->
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                                                <h3 class="text-xl font-bold text-blue-900 mb-1 flex items-center">
                                                    <i class="fas fa-calendar-alt mr-2"></i>
                                                    {{ $booking->service ? $booking->service->name : 'Service supprimé' }}
                                                    <span class="ml-2 text-sm font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                        {{ $booking->total_slots }} créneaux
                                                    </span>
                                                </h3>
                                                <div class="flex items-center space-x-4 text-sm text-blue-700">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        @php
                                                            $hours = floor($booking->session_duration / 60);
                                                            $minutes = $booking->session_duration % 60;
                                                        @endphp
                                                        @if($hours > 0)
                                                            {{ $hours }}h{{ $minutes > 0 ? sprintf('%02d', $minutes) : '' }}
                                                        @else
                                                            {{ $minutes }} min
                                                        @endif
                                                        durée totale
                                                    </span>
                                                    <span class="flex items-center font-semibold">
                                                        <i class="fas fa-euro-sign mr-1"></i>
                                                        {{ number_format($booking->total_session_price, 2) }} € total
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $booking->service ? $booking->service->name : 'Service supprimé' }}</h3>
                                        @endif
                                        <p class="text-gray-700 font-medium text-lg">avec {{ $booking->prestataire && $booking->prestataire->user ? $booking->prestataire->user->name : 'Prestataire supprimé' }}</p>
                                        <p class="text-gray-500 text-sm mt-1">Réservation #{{ $booking->id }}</p>
                                    </div>
                                    
                                    <!-- Méta temporelles -->
                                    <div class="flex items-center space-x-8 text-gray-600">
                                        @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                            <!-- Multi-slot session time range -->
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-week mr-3 text-blue-500 text-lg"></i>
                                                <span class="font-medium">
                                                    Du {{ $booking->session_bookings->first()->start_datetime->format('d/m/Y à H:i') }}
                                                    au {{ $booking->session_bookings->last()->end_datetime->format('d/m/Y à H:i') }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <i class="fas fa-calendar-alt mr-3 text-blue-500 text-lg"></i>
                                                <span class="font-medium">{{ $booking->start_datetime->format('d/m/Y à H:i') }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-3 text-blue-500 text-lg"></i>
                                                <span class="font-medium">{{ $booking->start_datetime->diffInMinutes($booking->end_datetime) }} minutes</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($booking->client_notes)
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-500 mb-1">Notes</p>
                                            <p class="text-gray-700">{{ $booking->client_notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Badge de statut en haut à droite -->
                            <div class="ml-6">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm border
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-200
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800 border-green-200
                                    @elseif($booking->status === 'completed') bg-gray-100 text-gray-700 border-gray-200
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border-red-200
                                    @elseif($booking->status === 'refused') bg-red-100 text-red-800 border-red-200
                                    @else bg-gray-100 text-gray-800 border-gray-200
                                    @endif">
                                    @if($booking->status === 'pending')
                                        <i class="fas fa-clock mr-2"></i> En attente
                                    @elseif($booking->status === 'confirmed')
                                        <i class="fas fa-check-circle mr-2"></i> Confirmée
                                    @elseif($booking->status === 'completed')
                                        <i class="fas fa-flag-checkered mr-2"></i> Terminée
                                    @elseif($booking->status === 'cancelled')
                                        <i class="fas fa-times-circle mr-2"></i> Annulée
                                    @elseif($booking->status === 'refused')
                                        <i class="fas fa-ban mr-2"></i> Refusée
                                    @else
                                        {{ ucfirst($booking->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <!-- Tablet Layout (768-1023px) -->
                        <div class="hidden md:block lg:hidden">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        @if($booking->prestataire && $booking->prestataire->photo)
                                            <img class="h-14 w-14 rounded-full object-cover shadow-lg" src="{{ asset('storage/' . $booking->prestataire->photo) }}" alt="{{ $booking->prestataire->user->name }}">
                                        @elseif($booking->prestataire && $booking->prestataire->user && $booking->prestataire->user->avatar)
                                            <img class="h-14 w-14 rounded-full object-cover shadow-lg" src="{{ asset('storage/' . $booking->prestataire->user->avatar) }}" alt="{{ $booking->prestataire->user->name }}">
                                        @elseif($booking->prestataire && $booking->prestataire->user && $booking->prestataire->user->profile_photo_url)
                                            <img class="h-14 w-14 rounded-full object-cover shadow-lg" src="{{ $booking->prestataire->user->profile_photo_url }}" alt="{{ $booking->prestataire->user->name }}">
                                        @else
                                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                {{ $booking->prestataire && $booking->prestataire->user ? strtoupper(substr($booking->prestataire->user->name, 0, 1)) : 'P' }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Titre et prestataire -->
                                    <div class="flex-1">
                                        @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                            <!-- Multi-slot session header -->
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2">
                                                <h3 class="text-lg font-bold text-blue-900 mb-1 flex items-center">
                                                    <i class="fas fa-calendar-alt mr-2"></i>
                                                    {{ $booking->service ? $booking->service->name : 'Service supprimé' }}
                                                    <span class="ml-2 text-xs font-medium bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                        {{ $booking->total_slots }} créneaux
                                                    </span>
                                                </h3>
                                                <div class="text-xs text-blue-700">
                                                    @php
                                                        $hours = floor($booking->session_duration / 60);
                                                        $minutes = $booking->session_duration % 60;
                                                    @endphp
                                                    <span class="mr-3">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        @if($hours > 0)
                                                            {{ $hours }}h{{ $minutes > 0 ? sprintf('%02d', $minutes) : '' }}
                                                        @else
                                                            {{ $minutes }} min
                                                        @endif
                                                    </span>
                                                    <span class="font-semibold">
                                                        <i class="fas fa-euro-sign mr-1"></i>
                                                        {{ number_format($booking->total_session_price, 2) }} €
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">{{ $booking->service ? $booking->service->name : 'Service supprimé' }}</h3>
                                        @endif
                                        <p class="text-gray-700 font-medium">avec {{ $booking->prestataire && $booking->prestataire->user ? $booking->prestataire->user->name : 'Prestataire supprimé' }}</p>
                                        <p class="text-gray-500 text-sm mt-1">Réservation #{{ $booking->id }}</p>
                                    </div>
                                </div>
                                
                                <!-- Badge de statut -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm border
                                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-200
                                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800 border-green-200
                                    @elseif($booking->status === 'completed') bg-gray-100 text-gray-700 border-gray-200
                                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border-red-200
                                    @elseif($booking->status === 'refused') bg-red-100 text-red-800 border-red-200
                                    @else bg-gray-100 text-gray-800 border-gray-200
                                    @endif">
                                    @if($booking->status === 'pending')
                                        <i class="fas fa-clock mr-1"></i> En attente
                                    @elseif($booking->status === 'confirmed')
                                        <i class="fas fa-check-circle mr-1"></i> Confirmée
                                    @elseif($booking->status === 'completed')
                                        <i class="fas fa-flag-checkered mr-1"></i> Terminée
                                    @elseif($booking->status === 'cancelled')
                                        <i class="fas fa-times-circle mr-1"></i> Annulée
                                    @elseif($booking->status === 'refused')
                                        <i class="fas fa-ban mr-1"></i> Refusée
                                    @else
                                        {{ ucfirst($booking->status) }}
                                    @endif
                                </span>
                            </div>
                            
                            <!-- Méta temporelles sous le titre -->
                            <div class="flex items-center space-x-6 text-gray-600 mb-4">
                                @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                    <!-- Multi-slot session time range -->
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-week mr-2 text-blue-500"></i>
                                        <span class="font-medium">
                                            Du {{ $booking->session_bookings->first()->start_datetime->format('d/m/Y à H:i') }}
                                            au {{ $booking->session_bookings->last()->end_datetime->format('d/m/Y à H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        <span class="font-medium">{{ $booking->start_datetime->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                                        <span class="font-medium">{{ $booking->start_datetime->diffInMinutes($booking->end_datetime) }} minutes</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if($booking->client_notes)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 mb-1">Notes</p>
                                    <p class="text-gray-700">{{ $booking->client_notes }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Mobile Layout (<768px) -->
                        <div class="block md:hidden">
                            <div class="space-y-4">
                                <!-- Titre -->
                                <div>
                                    @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                        <!-- Multi-slot session header -->
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-2 mb-2">
                                            <h3 class="text-lg font-bold text-blue-900 mb-1 flex items-center">
                                                <i class="fas fa-calendar-alt mr-2"></i>
                                                {{ $booking->service ? $booking->service->name : 'Service supprimé' }}
                                            </h3>
                                            <div class="flex flex-col space-y-1 text-xs text-blue-700">
                                                <span class="flex items-center">
                                                    <i class="fas fa-list mr-1"></i>
                                                    {{ $booking->total_slots }} créneaux réservés
                                                </span>
                                                @php
                                                    $hours = floor($booking->session_duration / 60);
                                                    $minutes = $booking->session_duration % 60;
                                                @endphp
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    @if($hours > 0)
                                                        {{ $hours }}h{{ $minutes > 0 ? sprintf('%02d', $minutes) : '' }}
                                                    @else
                                                        {{ $minutes }} min
                                                    @endif
                                                    durée totale
                                                </span>
                                                <span class="flex items-center font-semibold">
                                                    <i class="fas fa-euro-sign mr-1"></i>
                                                    {{ number_format($booking->total_session_price, 2) }} € total
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight">{{ $booking->service ? $booking->service->name : 'Service supprimé' }}</h3>
                                    @endif
                                    <p class="text-gray-700 font-medium">avec {{ $booking->prestataire && $booking->prestataire->user ? $booking->prestataire->user->name : 'Prestataire supprimé' }}</p>
                                    <p class="text-gray-500 text-sm mt-1">Réservation #{{ $booking->id }}</p>
                                </div>
                                
                                <!-- Badge de statut -->
                                <div class="flex justify-center">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm border
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-200
                                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-800 border-green-200
                                        @elseif($booking->status === 'completed') bg-gray-100 text-gray-700 border-gray-200
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 border-red-200
                                        @elseif($booking->status === 'refused') bg-red-100 text-red-800 border-red-200
                                        @else bg-gray-100 text-gray-800 border-gray-200
                                        @endif">
                                        @if($booking->status === 'pending')
                                            <i class="fas fa-clock mr-2"></i> En attente
                                        @elseif($booking->status === 'confirmed')
                                            <i class="fas fa-check-circle mr-2"></i> Confirmée
                                        @elseif($booking->status === 'completed')
                                            <i class="fas fa-flag-checkered mr-2"></i> Terminée
                                        @elseif($booking->status === 'cancelled')
                                            <i class="fas fa-times-circle mr-2"></i> Annulée
                                        @elseif($booking->status === 'refused')
                                            <i class="fas fa-ban mr-2"></i> Refusée
                                        @else
                                            {{ ucfirst($booking->status) }}
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Méta temporelles -->
                                <div class="space-y-2 text-gray-600">
                                    @if(isset($booking->is_multi_slot) && $booking->is_multi_slot)
                                        <!-- Multi-slot session time range -->
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-calendar-week mr-2 text-blue-500"></i>
                                            <span class="font-medium text-center">
                                                Du {{ $booking->session_bookings->first()->start_datetime->format('d/m/Y à H:i') }}<br>
                                                au {{ $booking->session_bookings->last()->end_datetime->format('d/m/Y à H:i') }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                            <span class="font-medium">{{ $booking->start_datetime->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                                            <span class="font-medium">{{ $booking->start_datetime->diffInMinutes($booking->end_datetime) }} minutes</span>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($booking->client_notes)
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Notes</p>
                                        <p class="text-gray-700">{{ $booking->client_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions - Responsive -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <!-- Desktop & Tablet Actions -->
                            <div class="hidden md:flex items-center justify-end space-x-4">
                                <a href="{{ route('bookings.show', $booking) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl border border-blue-700 font-semibold flex items-center">
                                    <i class="fas fa-eye mr-2"></i> Voir détails
                                </a>
                                
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="cancellation_reason" value="Annulée par le client">
                                        <button type="submit" class="bg-red-50 text-red-700 px-6 py-3 rounded-lg hover:bg-red-100 transition-all duration-300 shadow-md hover:shadow-lg border border-red-200 font-semibold flex items-center">
                                            <i class="fas fa-times mr-2"></i> Annuler
                                        </button>
                                    </form>
                                @elseif($booking->status === 'completed')
                                    <a href="#" class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-6 py-3 rounded-lg font-semibold flex items-center border border-purple-200 shadow-md hover:shadow-lg transition-all duration-300">
                                        <i class="fas fa-star mr-2"></i> Évaluer
                                    </a>
                                    <a href="#" class="bg-gray-50 hover:bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold flex items-center border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300">
                                        <i class="fas fa-redo mr-2"></i> Réserver à nouveau
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Mobile Actions -->
                            <div class="block md:hidden space-y-3">
                                <a href="{{ route('bookings.show', $booking) }}" class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl border border-blue-700 font-semibold flex items-center justify-center text-lg" style="min-height: 44px;">
                                    <i class="fas fa-eye mr-2"></i> Voir détails
                                </a>
                                
                                @if($booking->status === 'pending' || $booking->status === 'confirmed')
                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="cancellation_reason" value="Annulée par le client">
                                        <button type="submit" class="w-full bg-red-50 text-red-700 px-6 py-4 rounded-lg hover:bg-red-100 transition-all duration-300 shadow-md hover:shadow-lg border border-red-200 font-semibold flex items-center justify-center text-lg" style="min-height: 44px;">
                                            <i class="fas fa-times mr-2"></i> Annuler
                                        </button>
                                    </form>
                                @elseif($booking->status === 'completed')
                                    <a href="#" class="w-full bg-purple-50 hover:bg-purple-100 text-purple-700 px-6 py-4 rounded-lg font-semibold flex items-center justify-center border border-purple-200 shadow-md hover:shadow-lg transition-all duration-300 text-lg" style="min-height: 44px;">
                                        <i class="fas fa-star mr-2"></i> Évaluer
                                    </a>
                                    <a href="#" class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 px-6 py-4 rounded-lg font-semibold flex items-center justify-center border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 text-lg" style="min-height: 44px;">
                                        <i class="fas fa-redo mr-2"></i> Réserver à nouveau
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
</div>
@endsection