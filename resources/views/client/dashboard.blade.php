@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/client-dashboard.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-blue-50">
<div class="container mx-auto py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8 fade-in-up">
    <!-- Welcome Message -->
    <div class="welcome-card card-hover mb-6 sm:mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 lg:p-8">
        <div class="flex-1 mb-4 sm:mb-0">
            <div class="flex items-center mb-2 sm:mb-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-full flex items-center justify-center mr-2 sm:mr-3 lg:mr-4">
                    <i class="fas fa-home text-white text-sm sm:text-base lg:text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-blue-900">{{ $welcomeMessage }}</h1>
                    <p class="text-sm sm:text-base lg:text-lg text-blue-700">Heureux de vous revoir, {{ $client->user->name ?? 'Client' }} !</p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center text-xs sm:text-sm text-gray-500 space-y-1 sm:space-y-0">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt mr-1 sm:mr-2"></i>
                    <span>{{ now()->format('l d F Y') }}</span>
                </div>
                <span class="hidden sm:inline mx-2">•</span>
                <div class="flex items-center">
                    <i class="fas fa-clock mr-1 sm:mr-2"></i>
                    <span>{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="sm:ml-4 lg:ml-6 flex-shrink-0">
            @if($client->avatar)
                <div class="relative">
                    <img src="{{ asset('storage/' . $client->avatar) }}" alt="Photo de profil" class="h-12 w-12 sm:h-16 sm:w-16 lg:h-20 lg:w-20 rounded-full object-cover border-2 sm:border-3 lg:border-4 border-white shadow-lg">
                    <div class="absolute -bottom-0.5 -right-0.5 sm:-bottom-1 sm:-right-1 h-4 w-4 sm:h-5 sm:w-5 lg:h-6 lg:w-6 bg-green-500 rounded-full border-1 sm:border-2 border-white"></div>
                </div>
            @else
                <div class="relative">
                    <div class="h-12 w-12 sm:h-16 sm:w-16 lg:h-20 lg:w-20 rounded-full bg-blue-600 flex items-center justify-center border-2 sm:border-3 lg:border-4 border-white shadow-lg">
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold text-white">{{ $client->user->name ? strtoupper(substr($client->user->name, 0, 1)) : 'C' }}</span>
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 sm:-bottom-1 sm:-right-1 h-4 w-4 sm:h-5 sm:w-5 lg:h-6 lg:w-6 bg-green-500 rounded-full border-1 sm:border-2 border-white"></div>
                </div>
            @endif
        </div>
    </div>

    <!-- Shortcuts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        @foreach($shortcuts as $shortcut)
            <a href="{{ $shortcut['url'] }}" class="group bg-white p-4 sm:p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-3 sm:space-x-4 border border-blue-200 card-hover">
                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                    <i class="{{ $shortcut['icon'] }} text-sm sm:text-base lg:text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-blue-800 group-hover:text-blue-600 transition-colors duration-300 truncate">{{ $shortcut['name'] }}</h3>
                    <p class="text-gray-500 text-xs sm:text-sm mt-1 line-clamp-2">{{ $shortcut['description'] }}</p>
                    <div class="flex items-center mt-1 sm:mt-2 text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="text-xs font-medium">Accéder</span>
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
        <!-- Unified Recent Requests -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6 lg:space-y-8">
            <!-- Mes demandes récentes -->
            <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                        <i class="fas fa-clipboard-list text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-800">Mes demandes récentes</h2>
                </div>
                <a href="{{ route('client.equipment-rental-requests.index') }}" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm transition duration-200 flex items-center border border-blue-200 self-start sm:self-auto">
                    <span class="hidden sm:inline">Voir plus</span>
                    <span class="sm:hidden">Plus</span>
                    <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                </a>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                @if($unifiedRequests->isEmpty())
                    <div class="empty-state p-4 sm:p-6 lg:p-8">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-clipboard-list text-lg sm:text-xl lg:text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2 sm:mb-3">Aucune demande pour le moment</h3>
                        <p class="text-xs sm:text-sm lg:text-base text-gray-500 mb-3 sm:mb-4 lg:mb-6 max-w-md mx-auto px-2">Commencez votre parcours en explorant nos services disponibles et en créant vos premières demandes.</p>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center">
                            <a href="{{ route('client.prestataires.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                                <i class="fas fa-search mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Rechercher des services</span>
                                <span class="sm:hidden">Services</span>
                         </a>
                            <a href="{{ route('client.equipment-rentals.index') }}" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base transition duration-200 flex items-center justify-center">
                                <i class="fas fa-tools mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Louer du matériel</span>
                                <span class="sm:hidden">Matériel</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($unifiedRequests as $request)
                            <a href="@if($request['type'] === 'service'){{ route('client.bookings.show', $request['id']) }}@elseif($request['type'] === 'equipment'){{ route('client.equipment-rental-requests.show', $request['id']) }}@else{{ route('urgent-sales.show', $request['id']) }}@endif" class="block py-3 sm:py-4 px-4 sm:px-6 hover:bg-gray-50 transition-colors duration-200 cursor-pointer">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <!-- Image du service/équipement -->
                                    <div class="flex-shrink-0">
                                        @if($request['type'] === 'service' && isset($request['image']) && $request['image'])
                                            <img src="{{ asset('storage/' . $request['image']) }}" alt="{{ $request['title'] }}" class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 rounded-lg object-cover">
                                        @elseif($request['type'] === 'equipment' && isset($request['image']) && $request['image'])
                                            <img src="{{ asset('storage/' . $request['image']) }}" alt="{{ $request['title'] }}" class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 rounded-lg object-cover">
                                        @else
                                            <div class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 rounded-lg flex items-center justify-center
                                                @if($request['type'] === 'service') bg-blue-100 text-blue-600
                                                @else bg-green-100 text-green-600 @endif">
                                                @if($request['type'] === 'service')
                                                    <i class="fas fa-cogs text-lg sm:text-xl lg:text-2xl"></i>
                                                @else
                                                    <i class="fas fa-tools text-lg sm:text-xl lg:text-2xl"></i>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Contenu principal -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                                            <span class="px-2 py-1 rounded text-xs font-medium self-start
                                                @if($request['type'] === 'service') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                @if($request['type'] === 'service') Service @else Matériel @endif
                                            </span>
                                            <h4 class="font-semibold text-gray-900 text-sm sm:text-base truncate">{{ $request['title'] }}</h4>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-600">
                                            <span class="truncate">{{ $request['prestataire'] }}</span>
                                            <span class="text-xs sm:text-sm">
                                                @if($request['type'] === 'service')
                                                    {{ $request['date']->format('d/m/Y à H:i') }}
                                                @else
                                                    {{ $request['date']->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Statut -->
                                    <div class="flex-shrink-0">
                                        <span class="px-2 sm:px-3 py-1 rounded-full text-xs font-medium
                                            @if($request['status'] === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif(in_array($request['status'], ['confirmed', 'approved', 'responded'])) bg-green-100 text-green-800
                                            @elseif(in_array($request['status'], ['completed', 'active'])) bg-blue-100 text-blue-800
                                            @elseif(in_array($request['status'], ['cancelled', 'rejected'])) bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @switch($request['status'])
                                                @case('pending')
                                                    <span class="hidden sm:inline">En attente</span>
                                                    <span class="sm:hidden">Attente</span>
                                                    @break
                                                @case('confirmed')
                                                    Confirmé
                                                    @break
                                                @case('approved')
                                                    <span class="hidden sm:inline">Approuvé</span>
                                                    <span class="sm:hidden">OK</span>
                                                    @break
                                                @case('completed')
                                                    <span class="hidden sm:inline">Terminé</span>
                                                    <span class="sm:hidden">Fini</span>
                                                    @break
                                                @case('active')
                                                    <span class="hidden sm:inline">En cours</span>
                                                    <span class="sm:hidden">Actif</span>
                                                    @break
                                                @case('cancelled')
                                                    Annulé
                                                    @break
                                                @case('rejected')
                                                    <span class="hidden sm:inline">Rejeté</span>
                                                    <span class="sm:hidden">Non</span>
                                                    @break
                                                @case('responded')
                                                    <span class="hidden sm:inline">Répondu</span>
                                                    <span class="sm:hidden">Rép.</span>
                                                    @break
                                                @default
                                                    {{ ucfirst($request['status']) }}
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <br>
            <br>
            <!-- Mes abonnements -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                    <div class="flex items-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                            <i class="fas fa-heart text-sm sm:text-base lg:text-lg"></i>
                        </div>
                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-800">Mes abonnements</h2>
                    </div>
                    <a href="{{ route('client.prestataire-follows.index') }}" class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm transition duration-200 flex items-center self-start sm:self-auto">
                        <span class="hidden sm:inline">Voir tous</span>
                        <span class="sm:hidden">Tous</span>
                        <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                    @if($recentFollowedPrestataires->isEmpty())
                        <div class="empty-state p-4 sm:p-6 lg:p-8">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 lg:mb-6">
                                <i class="fas fa-heart text-lg sm:text-xl lg:text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2 sm:mb-3">Aucun abonnement pour le moment</h3>
                            <p class="text-xs sm:text-sm lg:text-base text-gray-500 mb-3 sm:mb-4 lg:mb-6 max-w-md mx-auto px-2">Découvrez et suivez vos prestataires préférés pour rester informé de leurs dernières activités.</p>
                            <a href="{{ route('client.prestataires.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                                <i class="fas fa-search mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Découvrir des prestataires</span>
                                <span class="sm:hidden">Découvrir</span>
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($recentFollowedPrestataires as $prestataire)
                                <div class="py-3 sm:py-4 px-4 sm:px-6 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between space-y-3 sm:space-y-0">
                                        <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                            <div class="flex-shrink-0">
                                                @if($prestataire->photo)
                                                    <img src="{{ asset('storage/' . $prestataire->photo) }}" alt="{{ $prestataire->user->name ?? 'Prestataire' }}" class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover">
                                                @elseif($prestataire->user && $prestataire->user->avatar)
                                                    <img src="{{ asset('storage/' . $prestataire->user->avatar) }}" alt="{{ $prestataire->user->name ?? 'Prestataire' }}" class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover">
                                                @else
                                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                                        <span class="text-sm sm:text-base font-bold text-white">{{ $prestataire->user ? strtoupper(substr($prestataire->user->name, 0, 1)) : 'P' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                                                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base lg:text-lg truncate">{{ $prestataire->user->name ?? 'Prestataire' }}</h4>
                                                    @if($prestataire->is_approved)
                                                        <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 self-start">
                                                            <span class="hidden sm:inline">Vérifié</span>
                                                            <span class="sm:hidden">✓</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs sm:text-sm lg:text-base text-gray-600 mb-1 sm:mb-2 truncate">{{ $prestataire->company_name }}</p>
                                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                                    <div class="flex items-center gap-2 sm:gap-4">
                                                        @if($prestataire->services->count() > 0)
                                                            <span>{{ $prestataire->services->count() }} service(s)</span>
                                                        @endif
                                                        @if($prestataire->rating_average > 0)
                                                            <span class="flex items-center gap-1">
                                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                                {{ number_format($prestataire->rating_average, 1) }}/5
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="hidden lg:block text-xs">Suivi depuis {{ $prestataire->pivot->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 sm:gap-3 self-start sm:self-auto">
                                            <a href="{{ route('prestataires.show', $prestataire->id) }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium transition-colors duration-200">
                                                <span class="hidden sm:inline">Voir profil</span>
                                                <span class="sm:hidden">Profil</span>
                                            </a>
                                            <form action="{{ route('client.prestataire-follows.unfollow', $prestataire->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium transition-colors duration-200" onclick="return confirm('Êtes-vous sûr de vouloir vous désabonner ?')">
                                                    <span class="hidden sm:inline">Se désabonner</span>
                                                    <span class="sm:hidden">✗</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif
                </div>
            </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                        <i class="fas fa-envelope text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">Messages</h2>
                </div>
            </div>
            <div class="stat-card bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-3 sm:p-4 lg:p-6 rounded-xl shadow-lg text-center border border-blue-200">
                <div class="relative mb-3 sm:mb-4 lg:mb-6">
                    <div class="icon-circle bg-gradient-to-r from-blue-500 to-indigo-600 text-white mx-auto w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope-open-text text-lg sm:text-xl lg:text-2xl"></i>
                    </div>
                    @if($unreadMessages > 0)
                        <div class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-red-500 text-white rounded-full w-5 h-5 sm:w-6 sm:h-6 lg:w-8 lg:h-8 flex items-center justify-center text-xs sm:text-sm font-bold animate-pulse">
                            {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                        </div>
                    @endif
                </div>
                <div class="mb-3 sm:mb-4 lg:mb-6">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-indigo-800 mb-1 sm:mb-2">{{ $unreadMessages }}</p>
                    <p class="text-indigo-600 text-xs sm:text-sm lg:text-base">
                        @if($unreadMessages == 0)
                            <span class="hidden sm:inline">Aucun nouveau message</span>
                            <span class="sm:hidden">Aucun message</span>
                        @elseif($unreadMessages == 1)
                            nouveau message
                        @else
                            <span class="hidden sm:inline">nouveaux messages</span>
                            <span class="sm:hidden">messages</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('messaging.index') }}" class="action-button bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-3 sm:px-4 lg:px-6 py-2 sm:py-2.5 lg:py-3 rounded-full hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl inline-flex items-center text-xs sm:text-sm lg:text-base">
                    <i class="fas fa-comments mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Voir mes messages</span>
                    <span class="sm:hidden">Messages</span>
                </a>
            </div>
            <br>
            <br>
            <!-- Dernières activités -->
        <div>
            <div class="flex items-center mb-4 sm:mb-6">
                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-purple-600 rounded-full flex items-center justify-center text-white mr-2 sm:mr-3">
                    <i class="fas fa-bell text-sm sm:text-base lg:text-lg"></i>
                </div>
                <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-purple-800">Dernières activités</h2>
            </div>
            <div class="bg-white rounded-xl shadow-lg border border-purple-200">
                @if($recentServicesFromFollowed->isEmpty())
                    <div class="empty-state p-4 sm:p-6 lg:p-8">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4 lg:mb-6">
                            <i class="fas fa-bell text-lg sm:text-xl lg:text-2xl text-purple-600"></i>
                        </div>
                        <h3 class="text-base sm:text-lg lg:text-xl font-semibold text-gray-700 mb-2 sm:mb-3">Aucune activité récente</h3>
                        <p class="text-xs sm:text-sm lg:text-base text-gray-500 mb-3 sm:mb-4 lg:mb-6 max-w-md mx-auto px-2">Suivez des prestataires pour voir leurs dernières activités et nouveaux services ici.</p>
                        <a href="{{ route('client.prestataires.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-4 sm:px-6 py-2 sm:py-3 rounded-lg text-sm sm:text-base transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                            <i class="fas fa-search mr-1 sm:mr-2"></i>
                            <span class="hidden sm:inline">Découvrir des prestataires</span>
                            <span class="sm:hidden">Découvrir</span>
                        </a>
                    </div>
                @else
                <div class="divide-y divide-gray-200">
                    @foreach($recentServicesFromFollowed as $service)
                        <div class="p-2 sm:p-3 lg:p-4 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-start space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-3">
                                <div class="flex items-start space-x-2 sm:space-x-0">
                                    <div class="flex-shrink-0">
                                        @if($service->prestataire->photo)
                                            <img src="{{ asset('storage/' . $service->prestataire->photo) }}" alt="{{ $service->prestataire->user->name ?? 'Prestataire' }}" class="h-6 w-6 sm:h-8 sm:w-8 lg:h-10 lg:w-10 rounded-full object-cover">
                                        @elseif($service->prestataire->user && $service->prestataire->user->avatar)
                                            <img src="{{ asset('storage/' . $service->prestataire->user->avatar) }}" alt="{{ $service->prestataire->user->name ?? 'Prestataire' }}" class="h-6 w-6 sm:h-8 sm:w-8 lg:h-10 lg:w-10 rounded-full object-cover">
                                        @else
                                            <div class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10 bg-purple-600 rounded-full flex items-center justify-center text-white text-xs font-medium">
                                                {{ ($service->prestataire->user && $service->prestataire->user->name) ? substr($service->prestataire->user->name, 0, 1) : 'P' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0 sm:hidden">
                                        <div class="flex flex-col gap-0.5 mb-1">
                                            <span class="font-medium text-gray-900 text-xs truncate">{{ ($service->prestataire->user && $service->prestataire->user->name) ? $service->prestataire->user->name : 'Prestataire' }}</span>
                                            <span class="text-xs text-gray-500">a ajouté un nouveau service</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="hidden sm:flex items-center gap-1 lg:gap-2 mb-1">
                                        <span class="font-medium text-gray-900 text-sm lg:text-base">{{ ($service->prestataire->user && $service->prestataire->user->name) ? $service->prestataire->user->name : 'Prestataire' }}</span>
                                        <span class="text-xs lg:text-sm text-gray-500">a ajouté un nouveau service</span>
                                    </div>
                                    <h4 class="font-semibold text-purple-800 mb-1 text-xs sm:text-sm lg:text-base truncate">{{ $service->title }}</h4>
                                    <p class="text-xs text-gray-600 mb-1 sm:mb-2 line-clamp-2">{{ Str::limit($service->description, 60) }}</p>
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-2">
                                        <span class="text-xs text-gray-500">{{ $service->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('services.show', $service->id) }}" class="text-purple-600 hover:text-purple-800 text-xs font-medium transition-colors duration-200 self-start">
                                            <span class="hidden sm:inline">Voir le service</span>
                                            <span class="sm:hidden">Voir</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        </div>

        
    </div>
</div>
</div>
@endsection