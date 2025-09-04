@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/client-dashboard.css') }}">
    <style>
        .dashboard-stat-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .dashboard-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-primary-card {
            background-color: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }
        
        .dashboard-primary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-left: 0.75rem;
        }
        
        /* Mobile responsive improvements */
        @media (max-width: 640px) {
            .dashboard-stat-card {
                padding: 1rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .section-title {
                margin-left: 0;
                margin-top: 0.5rem;
            }
            
            .stat-icon {
                width: 2rem;
                height: 2rem;
            }
            
            .dashboard-primary-card {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Message -->
        <div class="mb-6 sm:mb-8">
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2 flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-0"> 
                    <span>{{ $welcomeMessage }}</span>
                    @if(auth()->user()->isClient())
                        <span class="sm:ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mt-2 sm:mt-0">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Client
                        </span>
                    @endif
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 px-4">Heureux de vous revoir, {{ $client->user->name ?? 'Client' }} ! Gérez toutes vos activités depuis votre espace personnel</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex justify-center mb-6 sm:mb-8">
                <div class="grid grid-cols-3 gap-2 sm:gap-6 max-w-4xl w-full">
                    <!-- Messages -->
                    <a href="{{ route('messaging.index') }}" class="dashboard-stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-6 text-center hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 bg-blue-50 rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="text-xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $unreadMessages ?? 0 }}</div>
                        <div class="text-xs sm:text-sm font-medium text-gray-600">Messages non lus</div>
                    </a>

                    <!-- Abonnements -->
                    <a href="{{ route('client.prestataire-follows.index') }}" class="dashboard-stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-6 text-center hover:shadow-lg hover:border-purple-200 transition-all duration-300">
                        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 bg-purple-50 rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div class="text-xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $recentFollowedPrestataires->count() ?? 0 }}</div>
                        <div class="text-xs sm:text-sm font-medium text-gray-600">Abonnements</div>
                    </a>

                    <!-- Profil -->
                    <a href="{{ route('client.profile.edit') }}" class="dashboard-stat-card bg-white rounded-xl shadow-sm border border-gray-100 p-3 sm:p-6 text-center hover:shadow-lg hover:border-orange-200 transition-all duration-300">
                        <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 bg-orange-50 rounded-xl">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="text-xl sm:text-3xl font-bold text-gray-900 mb-1">
                            @if($client->avatar)
                                <span class="text-sm">Profil</span>
                            @else
                                <span class="text-sm">Profil</span>
                            @endif
                        </div>
                        <div class="text-xs sm:text-sm font-medium text-gray-600">Gérer mon profil</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Shortcuts -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
            @foreach($shortcuts as $shortcut)
                <a href="{{ $shortcut['url'] }}" class="dashboard-primary-card p-3 sm:p-6 hover:border-blue-200 flex items-center gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="{{ $shortcut['icon'] }} text-sm sm:text-base lg:text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $shortcut['name'] }}</h3>
                        <p class="text-gray-500 text-xs sm:text-sm mt-1">{{ $shortcut['description'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6 lg:gap-8">
            <!-- Unified Recent Requests -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mes demandes récentes -->
                <div class="dashboard-primary-card p-3 sm:p-6">
                    <div class="section-header">
                        <div class="stat-icon bg-blue-50">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-gray-900">Mes demandes récentes</h3>
                            <p class="text-sm text-gray-600">Historique de vos dernières demandes</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        @if($unifiedRequests->isEmpty())
                            <div class="empty-state p-4 sm:p-6 text-center">
                                <div class="w-12 h-12 bg-blue-50 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune demande pour le moment</h3>
                                <p class="text-sm text-gray-500 mb-4">Commencez votre parcours en explorant nos services disponibles.</p>
                                <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                    <a href="{{ route('prestataires.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <span>Rechercher des services</span>
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
                </div>
                
                <!-- Mes abonnements -->
                <div class="dashboard-primary-card p-3 sm:p-6">
                    <div class="section-header">
                        <div class="stat-icon bg-purple-50">
                            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-gray-900">Mes abonnements</h3>
                            <p class="text-sm text-gray-600">Prestataires que vous suivez</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        @if($recentFollowedPrestataires->isEmpty())
                            <div class="empty-state p-4 sm:p-6 text-center">
                                <div class="w-12 h-12 bg-purple-50 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun abonnement pour le moment</h3>
                                <p class="text-sm text-gray-500 mb-4">Découvrez et suivez des prestataires préférés pour rester informé de leurs dernières activités.</p>
                                <a href="{{ route('prestataires.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <span>Découvrir des prestataires</span>
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
            
            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Unread Messages -->
                <div class="dashboard-primary-card p-3 sm:p-6">
                    <div class="section-header">
                        <div class="stat-icon bg-blue-50">
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-gray-900">Messages</h3>
                            <p class="text-sm text-gray-600">Vos conversations récentes</p>
                        </div>
                    </div>
                    
                    <div class="text-center py-6">
                        <div class="relative inline-block">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @if($unreadMessages > 0)
                                <div class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold animate-pulse">
                                    {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                                </div>
                            @endif
                        </div>
                        
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ $unreadMessages }}</p>
                        <p class="text-sm text-gray-600 mb-4">
                            @if($unreadMessages == 0)
                                Aucun nouveau message
                            @elseif($unreadMessages == 1)
                                nouveau message
                            @else
                                nouveaux messages
                            @endif
                        </p>
                        
                        <a href="{{ route('messaging.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span>Voir mes messages</span>
                        </a>
                    </div>
                </div>

                <!-- Dernières activités -->
                <div class="dashboard-primary-card p-3 sm:p-6">
                    <div class="section-header">
                        <div class="stat-icon bg-purple-50">
                            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-gray-900">Dernières activités</h3>
                            <p class="text-sm text-gray-600">Nouvelles des prestataires suivis</p>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        @if($recentServicesFromFollowed->isEmpty())
                            <div class="empty-state p-4 sm:p-6 text-center">
                                <div class="w-12 h-12 bg-purple-50 rounded-full mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune activité récente</h3>
                                <p class="text-sm text-gray-500 mb-4">Suivez des prestataires pour voir leurs dernières activités et nouveaux services ici.</p>
                                <a href="{{ route('prestataires.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <span>Découvrir des prestataires</span>
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection