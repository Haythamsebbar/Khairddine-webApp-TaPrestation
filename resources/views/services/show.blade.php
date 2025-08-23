@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('title', $service->title . ' - Service - TaPrestation')

@section('content')
<div class="bg-blue-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 lg:py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-4 sm:mb-6 lg:mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-2 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        <span class="hidden sm:inline">Accueil</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('services.index') }}" class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-700 hover:text-blue-600">Services</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500">{{ Str::limit($service->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <!-- Galerie d'images -->
            <div class="lg:col-span-2">
                <!-- Titre et prix au-dessus de l'image -->
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-4 sm:mb-6 border border-blue-100">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-2 sm:mb-3 leading-tight break-words">{{ $service->title }}</h1>
                    <div class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">
                        {{ number_format($service->price, 2) }}€ 
                        <span class="text-sm sm:text-lg font-normal text-gray-500">/ {{ $service->price_type }}</span>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    @if($service->images && count($service->images) > 0)
                        <div class="relative">
                            <!-- Image principale -->
                            <div class="aspect-w-16 aspect-h-12">
                                <img id="mainImage" src="{{ Storage::url($service->images[0]->image_path) }}" alt="{{ $service->title }}" class="w-full h-64 sm:h-80 lg:h-96 object-cover">
                            </div>
                        </div>
                        
                        <!-- Miniatures -->
                        @if(count($service->images) > 1)
                            <div class="p-3 sm:p-4 border-t">
                                <div class="flex space-x-2 overflow-x-auto pb-2">
                                    @foreach($service->images as $index => $image)
                                        <button onclick="changeMainImage('{{ Storage::url($image->image_path) }}')"
                                                class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-blue-500' : 'border-gray-200' }} hover:border-blue-500 transition duration-200">
                                            <img src="{{ Storage::url($image->image_path) }}" alt="Photo {{ $index + 1 }}" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="h-64 sm:h-80 lg:h-96 bg-gray-200 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm sm:text-base text-gray-500">Aucune photo disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Description détaillée -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 mt-4 sm:mt-6">
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-800 mb-3 sm:mb-4">Description</h2>
                    <div class="prose max-w-none text-gray-700 text-sm sm:text-base leading-relaxed">
                        {!! nl2br(e($service->description)) !!}
                    </div>
                </div>
            </div>
            
            <!-- Sidebar d'informations -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 lg:sticky lg:top-4">
                    
                    <!-- Informations du vendeur -->
                    <div class="border-t border-gray-200 pt-4 sm:pt-6 mb-4 sm:mb-6">
                        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-blue-800 mb-3 sm:mb-4">Prestataire</h3>
                        <a href="{{ route('prestataires.show', $service->prestataire) }}" class="block">
                            <div class="flex items-center mb-4 cursor-pointer hover:bg-blue-50 p-2 sm:p-3 rounded-lg transition-colors duration-200">
                                <div class="relative w-10 h-10 sm:w-12 sm:h-12 mr-2 sm:mr-3 flex-shrink-0">
                                    @if($service->prestataire->photo)
                                        <img src="{{ Storage::url($service->prestataire->photo) }}" alt="{{ $service->prestataire->user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                    @elseif($service->prestataire->user->avatar)
                                        <img src="{{ Storage::url($service->prestataire->user->avatar) }}" alt="{{ $service->prestataire->user->name }}" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    @if($service->prestataire->isVerified())
                                        <div class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                                            <svg class="w-2 h-2 sm:w-3 sm:h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center">
                                        <span class="font-medium text-sm sm:text-base text-gray-900 hover:text-blue-600 transition-colors duration-200 truncate">{{ $service->prestataire->user->name }}</span>
                                        @if($service->prestataire->isVerified())
                                            <span class="mt-1 sm:mt-0 sm:ml-2 inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 self-start">
                                                <svg class="w-2 h-2 sm:w-3 sm:h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Vérifié</span><span class="sm:hidden">✓</span>
                                            </span>
                                        @endif
                                    </div>
                                    @if($service->prestataire->company_name)
                                        <div class="text-xs sm:text-sm text-gray-600 truncate">{{ $service->prestataire->company_name }}</div>
                                    @endif
                                    
                                    <!-- Évaluations avec étoiles -->
                                    @php
                                        $averageRating = $service->prestataire->reviews()->avg('rating') ?? 0;
                                        $reviewCount = $service->prestataire->reviews()->count();
                                    @endphp
                                    @if($reviewCount > 0)
                                        <div class="flex items-center mt-2">
                                            <div class="flex items-center mr-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($averageRating))
                                                        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @elseif($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                                                            <defs>
                                                                <linearGradient id="half-fill-{{ $i }}">
                                                                    <stop offset="50%" stop-color="currentColor"/>
                                                                    <stop offset="50%" stop-color="#e5e7eb"/>
                                                                </linearGradient>
                                                            </defs>
                                                            <path fill="url(#half-fill-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">{{ number_format($averageRating, 1) }} ({{ $reviewCount }} avis)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center mt-2">
                                            <span class="text-sm text-gray-500">Aucun avis pour le moment</span>
                                        </div>
                                    @endif
                                    
                                    <div class="text-xs text-blue-600 mt-1">Cliquez pour voir le profil</div>
                                </div>
                            </div>
                        </a>
                        
                        <div class="space-y-2 text-xs sm:text-sm text-gray-600">
                            @if($service->city)
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $service->city }}</span>
                                </div>
                            @endif
                            
                            <div class="flex items-center">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="truncate">Publié {{ $service->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if ($service->latitude && $service->longitude)
                            <div class="border-t border-gray-200 pt-4 sm:pt-6">
                                <h3 class="text-base sm:text-lg font-semibold text-blue-800 mb-3">Localisation sur carte</h3>
                                <div id="map" style="height: 200px;" class="sm:h-64 rounded-lg z-10"></div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Détails du produit -->
                    <div class="border-t border-gray-200 pt-4 sm:pt-6 mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-semibold text-blue-800 mb-3">Détails</h3>
                        <div class="space-y-3 text-xs sm:text-sm">
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Catégories:</span>
                                <div class="font-medium text-left sm:text-right">
                                    @foreach($service->categories as $category)
                                        <a href="{{ route('services.index', ['category' => $category->id]) }}" class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mr-1 sm:mr-2 mb-1 px-2 sm:px-2.5 py-0.5 rounded-full hover:bg-blue-200 transition-colors duration-200">{{ $category->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Référence:</span>
                                <span class="font-medium text-gray-800">#{{ $service->id }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Temps de livraison:</span>
                                <span class="font-medium text-gray-800 break-words">{{ $service->delivery_time }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Status:</span>
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} self-start sm:self-auto">
                                    {{ $service->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Réservable:</span>
                                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->reservable ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} self-start sm:self-auto">
                                    {{ $service->reservable ? 'Oui' : 'Non' }}
                                </span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                <span class="text-gray-600 font-medium sm:font-normal">Vues:</span>
                                <span class="font-medium text-gray-800">{{ $service->views }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 sm:mt-6">
                        @auth
                            @if(auth()->user()->role === 'client' && auth()->user()->id !== $service->prestataire->user_id)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3">
                                    <a href="{{ route('bookings.create', $service) }}" class="w-full bg-blue-600 text-white px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-bold text-center text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                         <span class="truncate">Réserver</span>
                                     </a>
                                     <a href="#" class="w-full bg-blue-100 text-blue-800 px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg hover:bg-blue-200 transition duration-200 font-bold text-center text-sm sm:text-base shadow-lg">
                                         <span class="truncate">Contacter</span>
                                     </a>
                                </div>
                                
                                <!-- Bouton de signalement -->
                                <button onclick="openReportModal()" class="w-full bg-red-50 text-red-600 border border-red-200 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg hover:bg-red-100 hover:border-red-300 transition duration-200 text-center text-xs sm:text-sm flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    <span>Signaler ce service</span>
                                </button>
                            @elseif(auth()->user()->id === $service->prestataire->user_id)
                                <a href="{{ route('prestataire.services.edit', $service) }}" class="w-full bg-blue-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-bold text-center text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                     <span class="truncate">Modifier mon service</span>
                                 </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full bg-blue-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-bold text-center text-sm sm:text-base shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                 <span class="truncate">Se connecter pour réserver</span>
                             </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de signalement -->
    @auth
        @if(auth()->user()->role === 'client' && auth()->user()->id !== $service->prestataire->user_id)
            <div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Signaler ce service</h3>
                            <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="reportForm" action="{{ route('services.report', $service) }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie du signalement *</label>
                                <select name="category" id="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="inappropriate_content">Contenu inapproprié</option>
                                    <option value="false_information">Informations fausses</option>
                                    <option value="spam">Spam ou publicité</option>
                                    <option value="fraud">Fraude ou arnaque</option>
                                    <option value="copyright">Violation de droits d'auteur</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priorité *</label>
                                <select name="priority" id="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="">Sélectionnez une priorité</option>
                                    <option value="low">Faible</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Élevée</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Raison du signalement *</label>
                                <textarea name="reason" id="reason" rows="3" required placeholder="Décrivez brièvement le problème..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                            </div>
                            
                            <div class="mb-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description détaillée</label>
                                <textarea name="description" id="description" rows="4" placeholder="Fournissez plus de détails si nécessaire..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="button" onclick="closeReportModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                                    Annuler
                                </button>
                                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200">
                                    Envoyer le signalement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

        <!-- Services similaires -->
        <div class="mt-8 sm:mt-12">
            <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-4 sm:mb-6 px-4 sm:px-0">Services similaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @forelse($similarServices as $similarService)
                    @include('components.service-card', ['service' => $similarService])
                @empty
                    <div class="col-span-full text-center text-gray-500 px-4 sm:px-0">
                        Aucun service similaire trouvé.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @if ($service->latitude && $service->longitude)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            var map = L.map('map').setView([{{ $service->latitude }}, {{ $service->longitude }}], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([{{ $service->latitude }}, {{ $service->longitude }}]).addTo(map)
                .bindPopup('{{ $service->title }}')
                .openPopup();
        </script>
    @endif
    <script>
        function changeMainImage(url) {
            document.getElementById('mainImage').src = url;
            // Update border on thumbnails
            const buttons = document.querySelectorAll('.flex-shrink-0');
            buttons.forEach(button => {
                const img = button.querySelector('img');
                if (img.src === url) {
                    button.classList.add('border-blue-500');
                    button.classList.remove('border-gray-200');
                } else {
                    button.classList.remove('border-blue-500');
                    button.classList.add('border-gray-200');
                }
            });
        }

        // Fonctions pour le modal de signalement
        function openReportModal() {
            document.getElementById('reportModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Réinitialiser le formulaire
            document.getElementById('reportForm').reset();
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });

        // Gérer la soumission du formulaire
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = 'Envoi en cours...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Votre signalement a été envoyé avec succès. Nous examinerons votre demande dans les plus brefs délais.');
                    closeReportModal();
                } else {
                    alert('Une erreur est survenue. Veuillez réessayer.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    </script>
@endpush