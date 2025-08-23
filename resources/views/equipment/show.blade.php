@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Animations et transitions */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pulse-green {
        animation: pulseGreen 2s infinite;
    }
    
    @keyframes pulseGreen {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
        50% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
    }
    
    /* Amélioration des cartes */
    .card-hover {
        transition: all 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Badge de statut */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-available {
        background-color: #dcfce7;
        color: #166534;
    }
</style>
@endpush

@section('title', $equipment->name . ' - Location de matériel - TaPrestation')

@section('content')
<div class="min-h-screen bg-green-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6 lg:mb-8 overflow-x-auto" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2 md:space-x-3 whitespace-nowrap">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-green-700 hover:text-green-600">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">Accueil</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('equipment.index') }}" class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-700 hover:text-green-600 truncate">Matériel à louer</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-500 truncate max-w-32 sm:max-w-none">{{ Str::limit($equipment->name, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Titre et prix par jour au-dessus de l'image -->
        <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6 border border-green-100">
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2 sm:mb-3 leading-tight break-words">{{ $equipment->name }}</h1>
            
            <!-- Prix par jour uniquement -->
            @if($equipment->price_per_day)
                <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">{{ number_format($equipment->price_per_day, 2) }}€ / jour</div>
            @endif
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <!-- Image et description -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-green-100 card-hover fade-in">
                    @if($equipment->main_photo)
                        <div class="relative">
                            <div class="aspect-w-16 aspect-h-12">
                                <img id="mainImage" src="{{ Storage::url($equipment->main_photo) }}" alt="{{ $equipment->name }}" class="w-full h-64 sm:h-80 lg:h-96 object-cover">
                            </div>
                            <div class="absolute top-2 sm:top-4 left-2 sm:left-4 status-badge status-available pulse-green text-xs sm:text-sm">
                                Disponible
                            </div>
                        </div>
                    @else
                        <div class="h-64 sm:h-80 lg:h-96 bg-gray-200 flex items-center justify-center">
                            <div class="text-center px-4">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm sm:text-base text-gray-500">Aucune photo disponible</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-6 border border-green-100 card-hover fade-in">
                    <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Description</h2>
                    <div class="prose max-w-none text-sm sm:text-base text-green-700 leading-relaxed">
                        {!! nl2br(e($equipment->description)) !!}
                    </div>
                </div>


                
                <!-- Spécifications techniques -->
                @if($equipment->technical_specifications)
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-6 border border-green-100 card-hover fade-in">
                        <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Spécifications techniques</h2>
                        <div class="prose max-w-none text-sm sm:text-base text-green-700 leading-relaxed">
                            {!! nl2br(e($equipment->technical_specifications)) !!}
                        </div>
                    </div>
                @endif
                 
                 <!-- Accessoires -->
                 @if($equipment->included_accessories || $equipment->optional_accessories)
                     <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-6 border border-green-100 card-hover fade-in">
                         <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Accessoires</h2>
                         
                         @if($equipment->included_accessories && count($equipment->included_accessories) > 0)
                             <div class="mb-3 sm:mb-4">
                                 <h3 class="font-medium text-sm sm:text-base text-green-900 mb-2">Inclus dans la location</h3>
                                 <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm text-green-700 ml-2">
                                     @foreach($equipment->included_accessories as $accessory)
                                         <li class="break-words">{{ $accessory }}</li>
                                     @endforeach
                                 </ul>
                             </div>
                         @endif
                         
                         @if($equipment->optional_accessories && count($equipment->optional_accessories) > 0)
                             <div>
                                 <h3 class="font-medium text-sm sm:text-base text-green-900 mb-2">Accessoires optionnels</h3>
                                 <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm text-green-700 ml-2">
                                     @foreach($equipment->optional_accessories as $accessory)
                                         <li class="break-words">{{ $accessory }}</li>
                                     @endforeach
                                 </ul>
                             </div>
                         @endif
                     </div>
                 @endif
                 
                 <!-- Conditions de location -->
                 @if($equipment->rental_conditions)
                     <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mt-4 sm:mt-6 border border-green-100 card-hover fade-in">
                         <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Conditions de location</h2>
                         <div class="prose max-w-none text-sm sm:text-base text-green-700 leading-relaxed">
                             {!! nl2br(e($equipment->rental_conditions)) !!}
                         </div>
                     </div>
                 @endif
                 
                 <!-- Instructions d'utilisation -->
                 @if($equipment->usage_instructions)
                     <div class="bg-white rounded-lg shadow-sm p-6 mt-6 card-hover fade-in">
                         <h2 class="text-xl font-semibold text-gray-900 mb-4">Instructions d'utilisation</h2>
                         <div class="prose max-w-none text-gray-700">
                             {!! nl2br(e($equipment->usage_instructions)) !!}
                         </div>
                     </div>
                 @endif
                 
                 <!-- Instructions de sécurité -->
                 @if($equipment->safety_instructions)
                     <div class="bg-white rounded-lg shadow-sm p-6 mt-6 card-hover fade-in">
                         <h2 class="text-xl font-semibold text-gray-900 mb-4">Instructions de sécurité</h2>
                         <div class="prose max-w-none text-gray-700 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                             <div class="flex items-start">
                                 <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                     <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                 </svg>
                                 <div>{!! nl2br(e($equipment->safety_instructions)) !!}</div>
                             </div>
                         </div>
                     </div>
                 @endif
             </div>

            <!-- Sidebar -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky top-4 card-hover fade-in">
                    <!-- Propriétaire -->
                    <div class="mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Propriétaire</h2>
                        <a href="{{ route('prestataires.show', $equipment->prestataire) }}" class="block hover:bg-green-50 p-2 rounded-lg transition-colors duration-200">
                            <div class="flex items-center space-x-3 sm:space-x-4">
                                @if($equipment->prestataire && $equipment->prestataire->photo)
                                    <img src="{{ Storage::url($equipment->prestataire->photo) }}" alt="{{ $equipment->prestataire->user->name ?? '' }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-green-200">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center border-2 border-green-200">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-semibold text-green-900 truncate hover:text-green-700">{{ $equipment->prestataire->user->name ?? '' }}</h3>
                                    
                                    <!-- Évaluations avec étoiles -->
                                    @php
                                        $averageRating = $equipment->prestataire->reviews()->avg('rating') ?? 0;
                                        $reviewCount = $equipment->prestataire->reviews()->count();
                                    @endphp
                                    @if($reviewCount > 0)
                                        <div class="flex items-center mt-1 mb-2">
                                            <div class="flex items-center mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($averageRating))
                                                        <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @elseif($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                                        <svg class="w-3 h-3 text-yellow-400" viewBox="0 0 20 20">
                                                            <defs>
                                                                <linearGradient id="half-fill-equipment-{{ $i }}">
                                                                    <stop offset="50%" stop-color="currentColor"/>
                                                                    <stop offset="50%" stop-color="#e5e7eb"/>
                                                                </linearGradient>
                                                            </defs>
                                                            <path fill="url(#half-fill-equipment-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-3 h-3 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-600">{{ number_format($averageRating, 1) }} ({{ $reviewCount }} avis)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center mt-1 mb-2">
                                            <span class="text-xs text-gray-500">Aucun avis pour le moment</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center text-xs sm:text-sm text-green-600 mt-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">
                                            @if($equipment->city)
                                                {{ $equipment->city }}
                                                @if($equipment->postal_code), {{ $equipment->postal_code }}@endif
                                            @else
                                                {{ $equipment->address ?? 'Localisation non spécifiée' }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center text-xs sm:text-sm text-green-600 mt-1">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Publié {{ $equipment->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($equipment->view_count)
                                        <div class="flex items-center text-xs sm:text-sm text-green-600 mt-1">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>{{ $equipment->view_count }} vue(s)</span>
                                        </div>
                                    @endif
                                    @if($equipment->total_rentals)
                                        <div class="flex items-center text-xs sm:text-sm text-green-600 mt-1">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ $equipment->total_rentals }} location(s) réalisée(s)</span>
                                        </div>
                                    @endif
                                    <div class="text-xs text-green-600 mt-1">Cliquez pour voir le profil</div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Informations détaillées -->
                    <div class="mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-green-900 mb-3 sm:mb-4">Informations détaillées</h2>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <!-- Autres prix -->
                            @if($equipment->price_per_hour)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Prix horaire :</span>
                                    <span class="text-green-700">{{ number_format($equipment->price_per_hour, 2) }}€ / heure</span>
                                </div>
                            @endif
                            
                            @if($equipment->price_per_week)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Prix semaine :</span>
                                    <span class="text-green-700">{{ number_format($equipment->price_per_week, 2) }}€ / semaine</span>
                                </div>
                            @endif
                            
                            @if($equipment->price_per_month)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Prix mensuel :</span>
                                    <span class="text-green-700">{{ number_format($equipment->price_per_month, 2) }}€ / mois</span>
                                </div>
                            @endif
                            
                            <!-- Caution et frais -->
                            @if($equipment->security_deposit)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Caution :</span>
                                    <span class="text-green-700">{{ number_format($equipment->security_deposit, 2) }}€</span>
                                </div>
                            @endif
                            
                            @if($equipment->delivery_fee && !$equipment->delivery_included)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Frais de livraison :</span>
                                    <span class="text-green-700">{{ number_format($equipment->delivery_fee, 2) }}€</span>
                                </div>
                            @elseif($equipment->delivery_included)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Livraison :</span>
                                    <span class="text-green-600">Incluse</span>
                                </div>
                            @endif
                            
                            @if($equipment->brand)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Marque :</span>
                                    <span class="text-green-700 break-words">{{ $equipment->brand }}</span>
                                </div>
                            @endif
                            
                            @if($equipment->model)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Modèle :</span>
                                    <span class="text-green-700 break-words">{{ $equipment->model }}</span>
                                </div>
                            @endif
                            
                            @if($equipment->condition)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">État :</span>
                                    <span class="text-green-700">{{ $equipment->formatted_condition }}</span>
                                </div>
                            @endif
                            
                            @if($equipment->weight)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Poids :</span>
                                    <span class="text-green-700">{{ $equipment->weight }} kg</span>
                                </div>
                            @endif
                            
                            @if($equipment->dimensions)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Dimensions :</span>
                                    <span class="text-green-700 break-words">{{ $equipment->dimensions }}</span>
                                </div>
                            @endif
                            
                            @if($equipment->power_requirements)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Alimentation :</span>
                                    <span class="text-green-700 break-words">{{ $equipment->power_requirements }}</span>
                                </div>
                            @endif
                            
                            @if($equipment->minimum_age)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Âge minimum :</span>
                                    <span class="text-green-700">{{ $equipment->minimum_age }} ans</span>
                                </div>
                            @endif
                            
                            @if($equipment->requires_license)
                                <div class="text-sm sm:text-base">
                                    <span class="font-medium text-green-900">Permis requis :</span>
                                    <span class="text-red-600">{{ $equipment->required_license_type ?? 'Oui' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($equipment->minimum_rental_duration || $equipment->maximum_rental_duration)
                            <div class="border-t border-green-200 pt-3 sm:pt-4 mt-4">
                                <h3 class="font-medium text-sm sm:text-base text-green-900 mb-2">Durée de location</h3>
                                <div class="space-y-1 text-xs sm:text-sm text-green-700">
                                    @if($equipment->minimum_rental_duration)
                                        <div>Durée minimum : {{ $equipment->minimum_rental_duration }} jour(s)</div>
                                    @endif
                                    @if($equipment->maximum_rental_duration)
                                        <div>Durée maximum : {{ $equipment->maximum_rental_duration }} jour(s)</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="border-t border-green-200 pt-4 sm:pt-6 mt-4 sm:mt-6">
                        <div class="space-y-3">
                            <!-- Bouton principal de réservation -->
                            <a href="{{ route('equipment.reserve', $equipment) }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl flex items-center justify-center text-center text-sm">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h6m-6 0l-.5 3.5A2 2 0 003.5 13H20.5a2 2 0 002-2l-.5-3.5m-15 0h15"></path>
                                </svg>
                                <span>Réserver cet équipement</span>
                            </a>
                            
                            <!-- Bouton secondaire de contact -->
                             <a href="#" 
                                class="w-full bg-white hover:bg-gray-50 text-green-600 border border-green-600 px-4 py-3 rounded-lg transition duration-200 font-semibold flex items-center justify-center text-center text-sm">
                                 <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                 </svg>
                                 <span>Contacter le propriétaire</span>
                             </a>
                             
                             <!-- Bouton de signalement -->
                             <button onclick="openReportModal()" 
                                     class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 py-3 rounded-lg transition duration-200 font-medium flex items-center justify-center text-center text-sm">
                                 <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                 </svg>
                                 <span>Signaler cet équipement</span>
                             </button>
                         </div>
                     </div>

                    <!-- Location Map -->
                    @if ($equipment->latitude && $equipment->longitude)
                        <div class="border-t border-green-200 pt-4 sm:pt-6 mt-4 sm:mt-6">
                            <h3 class="text-base sm:text-lg font-semibold text-green-900 mb-2 sm:mb-3">Localisation sur carte</h3>
                            <div id="map" style="height: 200px;" class="sm:h-64 rounded-lg z-10"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Similar Equipment -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Équipements similaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @if(isset($similarEquipment) && $similarEquipment->count() > 0)
                    @foreach($similarEquipment as $item)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <a href="{{ route('equipment.show', $item) }}">
                                <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="h-48 w-full object-cover">
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2"><a href="{{ route('equipment.show', $item) }}">{{ $item->name }}</a></h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($item->description, 75) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-green-600">{{ number_format($item->daily_rate, 2) }}€/jour</span>
                                    <a href="{{ route('equipment.show', $item) }}" class="text-green-600 hover:text-green-800 font-semibold">Voir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun équipement similaire trouvé.</p>
                @endif
            </div>
        </div>

        <!-- Similar Equipment -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Équipements similaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @if(isset($similarEquipment) && $similarEquipment->count() > 0)
                    @foreach($similarEquipment as $item)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <a href="{{ route('equipment.show', $item) }}">
                                <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="h-48 w-full object-cover">
                            </a>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2"><a href="{{ route('equipment.show', $item) }}">{{ $item->name }}</a></h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($item->description, 75) }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-green-600">{{ number_format($item->daily_rate, 2) }}€/jour</span>
                                    <a href="{{ route('equipment.show', $item) }}" class="text-green-600 hover:text-green-800 font-semibold">Voir</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">Aucun équipement similaire trouvé.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de signalement -->
<div id="reportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full border border-green-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Signaler cet équipement</h3>
                    <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('equipment.report', $equipment) }}" method="POST">
                    @csrf
                    <input type="hidden" name="reason" id="reason" value="">
                    <div class="mb-4">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie du signalement</label>
                        <select id="category" name="category" required onchange="updateReason()" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="inappropriate">Contenu inapproprié</option>
                            <option value="fraud">Annonce frauduleuse</option>
                            <option value="safety">Problème de sécurité</option>
                            <option value="condition">État de l'équipement</option>
                            <option value="pricing">Prix incorrect</option>
                            <option value="availability">Disponibilité incorrecte</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description détaillée</label>
                        <textarea id="description" name="description" rows="4" required minlength="20" maxlength="1000"
                                  placeholder="Décrivez le problème en détail (minimum 20 caractères)..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"></textarea>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeReportModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-6 py-3 rounded-lg transition duration-200 font-medium">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl">
                            Signaler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
@endsection

@push('scripts')
@if ($equipment->latitude && $equipment->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var map = L.map('map').setView([{{ $equipment->latitude }}, {{ $equipment->longitude }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([{{ $equipment->latitude }}, {{ $equipment->longitude }}]).addTo(map)
        .bindPopup('Localisation approximative de l\'équipement.')
        .openPopup();
</script>
@endif

<script>
    function openReportModal() {
        document.getElementById('reportModal').classList.remove('hidden');
    }

    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
    }

    function updateReason() {
        const category = document.getElementById('category').value;
        const reasonField = document.getElementById('reason');
        
        const reasonMap = {
            'inappropriate': 'Contenu inapproprié',
            'fraud': 'Annonce frauduleuse',
            'safety': 'Problème de sécurité',
            'condition': 'État de l\'équipement',
            'pricing': 'Prix incorrect',
            'availability': 'Disponibilité incorrecte',
            'other': 'Autre'
        };
        
        reasonField.value = reasonMap[category] || '';
    }

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });
</script>
@endpush