@extends('layouts.app')

@section('title', 'Location de matériel')

@section('content')
<div class="bg-green-50">
    <!-- Bannière d'en-tête -->
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-green-900 mb-2 leading-tight">
                    Location de Matériel
                </h1>
                <p class="text-base sm:text-lg text-green-700 max-w-2xl mx-auto">
                    Trouvez l'équipement dont vous avez besoin pour vos projets.
                </p>
            </div>
        </div>
    </div>
    

<div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
    <!-- Section des filtres -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md sm:shadow-lg border border-green-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6 lg:mb-8">
            <div class="mb-3 sm:mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-green-800 mb-1 sm:mb-2">Filtres de recherche</h3>
                    <p class="text-sm sm:text-base lg:text-lg text-green-700">Affinez votre recherche pour trouver l'équipement parfait</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center text-sm sm:text-base">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-1 sm:ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('equipment.index') }}" class="space-y-4 sm:space-y-6" id="filtersForm" style="display: none;">
                <!-- Conserver les paramètres de recherche principaux -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <!-- Première ligne de filtres -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Catégorie -->
                    <div>
                        <label for="category" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Catégorie</label>
                        <div class="relative">
                            <i class="fas fa-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="category" id="category" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm sm:text-base">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Prix maximum -->
                    <div>
                        <label for="price_max" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Prix maximum/jour</label>
                        <div class="relative">
                            <i class="fas fa-euro-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="price_max" id="price_max" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-sm sm:text-base">
                                <option value="">Tous les prix</option>
                                <option value="50" {{ request('price_max') == '50' ? 'selected' : '' }}>Jusqu'à 50€</option>
                                <option value="100" {{ request('price_max') == '100' ? 'selected' : '' }}>Jusqu'à 100€</option>
                                <option value="200" {{ request('price_max') == '200' ? 'selected' : '' }}>Jusqu'à 200€</option>
                                <option value="500" {{ request('price_max') == '500' ? 'selected' : '' }}>Jusqu'à 500€</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Disponibilité -->
                    <div>
                        <label for="availability" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Disponibilité</label>
                        <div class="relative">
                            <i class="fas fa-clock absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs sm:text-sm"></i>
                            <select name="availability" id="availability" class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <option value="">Toutes les disponibilités</option>
                                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Disponible maintenant</option>
                                <option value="delivery" {{ request('availability') == 'delivery' ? 'selected' : '' }}>Avec livraison</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tri par -->
                    <div>
                        <label for="sort" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Trier par</label>
                        <div class="relative">
                            <i class="fas fa-sort absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs sm:text-sm"></i>
                            <select name="sort" id="sort" class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>Pertinence</option>
                                <option value="distance" {{ request('sort') == 'distance' ? 'selected' : '' }}>Distance</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Mieux notés</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Deuxième ligne de filtres -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Localisation -->
                    <div class="md:col-span-2">
                        <label for="city" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Localisation</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <i class="fas fa-map-marker-alt absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs sm:text-sm"></i>
                                <input type="text" name="city" id="city" value="{{ request('city') }}" placeholder="Ville ou code postal" class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <input type="hidden" name="latitude" id="latitude" value="{{ request('latitude') }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ request('longitude') }}">
                            </div>
                            <button type="button" id="getLocationBtn" onclick="getMyLocation()" class="px-2 sm:px-4 py-2 sm:py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[60px] sm:min-w-[120px] text-xs sm:text-sm" title="Utiliser ma position">
                                <i class="fas fa-crosshairs mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Ma position</span>
                                <span class="sm:hidden">GPS</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Rayon de recherche -->
                    <div>
                        <label for="radius" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Rayon (km)</label>
                        <div class="relative">
                            <i class="fas fa-circle-notch absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs sm:text-sm"></i>
                            <select name="radius" id="radius" class="w-full pl-8 sm:pl-10 pr-3 sm:pr-4 py-2 sm:py-3 text-sm sm:text-base rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <option value="" {{ request('radius') == '' ? 'selected' : '' }}>Tous</option>
                                <option value="5" {{ request('radius') == '5' ? 'selected' : '' }}>5 km</option>
                                <option value="10" {{ request('radius') == '10' ? 'selected' : '' }}>10 km</option>
                                <option value="25" {{ request('radius') == '25' ? 'selected' : '' }}>25 km</option>
                                <option value="50" {{ request('radius') == '50' ? 'selected' : '' }}>50 km</option>
                                <option value="100" {{ request('radius') == '100' ? 'selected' : '' }}>100 km</option>
                            </select>
                        </div>
                    </div>
                    
                    
                    
                    <!-- Avec livraison -->
                    <div class="flex items-center justify-center md:justify-start">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="delivery_included" value="1" {{ request('delivery_included') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 w-4 h-4 sm:w-5 sm:h-5">
                            <span class="ml-2 text-xs sm:text-sm text-gray-700">Avec livraison</span>
                        </label>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-green-200">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                        <span class="hidden sm:inline">Appliquer les filtres</span>
                        <span class="sm:hidden">Appliquer</span>
                    </button>
                    
                    <button type="button" onclick="clearFilters()" class="flex-1 bg-green-100 hover:bg-green-200 text-green-800 font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 flex items-center justify-center text-sm sm:text-base">
                        <span class="hidden sm:inline">Effacer tout</span>
                        <span class="sm:hidden">Effacer</span>
                    </button>
                    
                    @if(request()->anyFilled(['search', 'category', 'price_max', 'availability', 'sort', 'city', 'urgent', 'delivery_included']))
                        <a href="{{ route('equipment.index') }}" class="bg-white hover:bg-gray-50 text-green-600 border border-green-200 font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 flex items-center justify-center text-sm sm:text-base">
                            <span class="hidden sm:inline">Réinitialiser</span>
                            <span class="sm:hidden">Reset</span>
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Affichage des résultats -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0 pt-4 border-t-2 border-green-200 mt-4 sm:mt-6">
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm font-semibold text-green-800">Résultats :</span>
                    <span class="px-2 sm:px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-bold">
                        {{ $equipments->total() }} équipement(s)
                    </span>
                </div>
                @if($equipments->total() > 0)
                    <div class="text-xs sm:text-sm font-semibold text-green-700">
                        {{ $equipments->pluck('prestataire_id')->unique()->count() }} prestataires actifs
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Résultats -->
    @if($equipments->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3 sm:gap-4 lg:gap-6">
        @foreach($equipments as $equipment)
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm sm:shadow-md overflow-hidden hover:shadow-lg sm:hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100 h-full flex flex-col">
            <!-- Image -->
            <div class="relative h-40 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                @if($equipment->main_photo)
                <img src="{{ Storage::url($equipment->main_photo) }}" 
                     alt="{{ $equipment->name }}"
                     class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <p class="text-xs text-gray-500">Photo à venir</p>
                    </div>
                </div>
                @endif
                
                <!-- Badges de statut améliorés -->
                <div class="absolute top-2 sm:top-3 left-2 sm:left-3 flex flex-col gap-1 sm:gap-2">
                    @if($equipment->is_available)
                    <span class="inline-flex items-center gap-1 bg-green-500 text-white text-xs font-medium px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-sm">
                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        <span class="hidden sm:inline">Disponible</span>
                        <span class="sm:hidden">Dispo</span>
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 bg-red-500 text-white text-xs font-medium px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-sm">
                        <div class="w-1.5 h-1.5 bg-white rounded-full"></div>
                        <span class="hidden sm:inline">Indisponible</span>
                        <span class="sm:hidden">Indispo</span>
                    </span>
                    @endif
                    
                    @if($equipment->delivery_available)
                    <span class="inline-flex items-center gap-1 bg-blue-500 text-white text-xs font-medium px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-sm">
                        <span class="hidden sm:inline">Livraison</span>
                        <span class="sm:hidden">📦</span>
                    </span>
                    @endif
                </div>
                
                <!-- Badge prix en évidence -->
                <div class="absolute top-2 sm:top-3 right-2 sm:right-3">
                    <div class="bg-white/90 backdrop-blur-sm text-gray-900 text-xs sm:text-sm font-bold px-2 sm:px-3 py-1 sm:py-1.5 rounded-full shadow-sm">
                        {{ number_format($equipment->price_per_day, 0, ',', ' ') }}€/j
                    </div>
                </div>
            </div>
            
            <!-- Contenu amélioré -->
            <div class="p-3 sm:p-4 lg:p-5 flex-1 flex flex-col">
                <!-- En-tête avec nom et note -->
                <div class="flex items-start justify-between mb-2 sm:mb-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 line-clamp-2 leading-tight truncate">{{ $equipment->name }}</h3>
                        @if($equipment->brand || $equipment->model)
                        <p class="text-xs sm:text-sm text-gray-600 font-medium truncate">{{ $equipment->brand }} {{ $equipment->model }}</p>
                        @endif
                    </div>
                    @if($equipment->average_rating > 0)
                    <div class="flex items-center ml-2 bg-yellow-50 px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full flex-shrink-0">
                        <span class="text-yellow-500 text-sm">*</span>
                        <span class="text-xs sm:text-sm text-gray-700 ml-1 font-medium">{{ number_format($equipment->average_rating, 1) }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Prestataire avec design amélioré -->
                @if($equipment->prestataire)
                <div class="flex items-center gap-2 sm:gap-3 mb-3 sm:mb-4 p-2 bg-green-50 rounded-lg">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full flex items-center justify-center flex-shrink-0">
                        @if($equipment->prestataire->photo)
                            <img src="{{ Storage::url($equipment->prestataire->photo) }}" alt="{{ $equipment->prestataire->user->name ?? '' }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover">
                        @elseif($equipment->prestataire->user->avatar)
                            <img src="{{ Storage::url($equipment->prestataire->user->avatar) }}" alt="{{ $equipment->prestataire->user->name ?? '' }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover">
                        @elseif($equipment->prestataire->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $equipment->prestataire->user->profile_photo_path) }}" alt="{{ $equipment->prestataire->user->name ?? '' }}" class="w-6 h-6 sm:w-8 sm:h-8 rounded-full object-cover">
                        @else
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($equipment->prestataire->company_name ?? ($equipment->prestataire->first_name ?? ''), 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">
                                {{ $equipment->prestataire->company_name ?? $equipment->prestataire->first_name . ' ' . $equipment->prestataire->last_name }}
                            </p>
                            <span class="distance-badge hidden text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full ml-2 flex-shrink-0" data-equipment-id="{{ $equipment->id }}"></span>
                        </div>
                        <p class="text-xs text-gray-500">Prestataire vérifié</p>
                    </div>
                </div>
                @endif
                
                <!-- Prix en évidence -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-2 sm:p-3 mb-3 sm:mb-4 border border-green-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ number_format($equipment->price_per_day, 0) }}€</span>
                            <span class="text-xs sm:text-sm text-gray-600">/jour</span>
                        </div>
                        @if($equipment->price_per_week)
                        <div class="text-right">
                            <div class="text-sm sm:text-lg font-semibold text-green-600">{{ number_format($equipment->price_per_week, 0) }}€</div>
                            <div class="text-xs text-gray-500">/semaine</div>
                            <div class="text-xs text-green-600 font-medium">-{{ round((1 - ($equipment->price_per_week / ($equipment->price_per_day * 7))) * 100) }}%</div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Catégories avec style amélioré -->
                @if($equipment->category || $equipment->subcategory)
                <div class="flex flex-wrap gap-1 sm:gap-1.5 mb-3 sm:mb-4">
                    @if($equipment->category)
                    <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                        {{ $equipment->category->name }}
                    </span>
                    @endif
                    @if($equipment->subcategory && $equipment->subcategory->id !== $equipment->category_id)
                    <span class="inline-flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full">
                        {{ $equipment->subcategory->name }}
                    </span>
                    @endif
                </div>
                @endif
                
                <!-- Boutons d'action améliorés -->
                <div class="space-y-1.5 sm:space-y-2 mt-auto">
                    <a href="{{ route('equipment.show', $equipment) }}" 
                       class="block w-full text-center bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2 sm:py-2.5 lg:py-3 px-3 sm:px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg text-xs sm:text-sm lg:text-base">
                        <span class="hidden sm:inline">Voir les détails</span>
                        <span class="sm:hidden">Détails</span>
                    </a>
                    @if($equipment->is_available)
                    <a href="{{ route('equipment.reserve', $equipment) }}" class="block w-full text-center bg-green-100 hover:bg-green-200 text-green-800 font-medium py-1.5 sm:py-2 px-3 sm:px-4 rounded-lg transition-colors duration-200 text-xs sm:text-sm">
                        <span class="hidden sm:inline">Réservation rapide</span>
                        <span class="sm:hidden">Réserver</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($equipments->hasPages())
    <div class="mt-6 sm:mt-8">
        {{ $equipments->links() }}
    </div>
    @endif
    @else
    <!-- État vide amélioré -->
    <div class="text-center py-8 sm:py-12 lg:py-16 bg-white rounded-lg border-2 border-dashed border-gray-200">
        <div class="max-w-sm sm:max-w-md mx-auto px-4">
            <!-- Illustration d'entrepôt vide -->
            <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 mb-4 sm:mb-6">
                <svg viewBox="0 0 100 100" class="w-full h-full text-gray-300">
                    <!-- Entrepôt -->
                    <rect x="10" y="40" width="80" height="50" fill="none" stroke="currentColor" stroke-width="2"/>
                    <polygon points="10,40 50,20 90,40" fill="none" stroke="currentColor" stroke-width="2"/>
                    <!-- Porte -->
                    <rect x="40" y="60" width="20" height="30" fill="none" stroke="currentColor" stroke-width="2"/>
                    <!-- Étagères vides -->
                    <line x1="20" y1="55" x2="35" y2="55" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="65" y1="55" x2="80" y2="55" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="20" y1="70" x2="35" y2="70" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="65" y1="70" x2="80" y2="70" stroke="currentColor" stroke-width="1.5"/>
                    <!-- Points d'interrogation -->
                    <text x="27" y="50" font-size="8" fill="currentColor">?</text>
                    <text x="72" y="50" font-size="8" fill="currentColor">?</text>
                </svg>
            </div>
            
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 sm:mb-3">
                @if(request()->hasAny(['search', 'location', 'category_id', 'max_price', 'availability', 'urgent', 'with_delivery']))
                    Aucun équipement trouvé
                @else
                    Entrepôt en cours de remplissage
                @endif
            </h3>
            
            <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">
                @if(request()->hasAny(['search', 'location', 'category_id', 'max_price', 'availability', 'urgent', 'with_delivery']))
                    Aucun matériel ne correspond à votre recherche.<br class="hidden sm:block">
                    <span class="sm:hidden"> </span>Essayez d'élargir vos critères ou revenez plus tard.
                @else
                    Nos prestataires ajoutent régulièrement de nouveaux équipements.<br class="hidden sm:block">
                    <span class="sm:hidden"> </span>Revenez bientôt pour découvrir notre catalogue !
                @endif
            </p>
            
            <div class="space-y-2 sm:space-y-3">
                @if(request()->hasAny(['search', 'location', 'category_id', 'max_price', 'availability', 'urgent', 'with_delivery']))
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center">
                    <a href="{{ route('equipment.index', request()->only(['search'])) }}" 
                       class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        <span class="hidden sm:inline">Élargir la recherche</span>
                        <span class="sm:hidden">Élargir</span>
                    </a>
                    <a href="{{ route('equipment.index') }}" 
                       class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                        <span class="hidden sm:inline">Voir tout le catalogue</span>
                        <span class="sm:hidden">Tout voir</span>
                    </a>
                </div>
                @else
                <a href="#" 
                   class="inline-flex items-center px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 text-sm sm:text-base">
                    <span class="hidden sm:inline">M'alerter des nouveautés</span>
                    <span class="sm:hidden">Alertes</span>
                </a>
                @endif
            </div>
            
            <!-- Suggestions -->
            @if(request()->hasAny(['search', 'location', 'category_id', 'max_price', 'availability', 'urgent', 'with_delivery']))
            <div class="mt-6 sm:mt-8 p-3 sm:p-4 bg-green-50 rounded-lg">
                <h4 class="text-xs sm:text-sm font-medium text-green-900 mb-2">Suggestions :</h4>
                <ul class="text-xs sm:text-sm text-green-700 space-y-1">
                    <li>• Vérifiez l'orthographe de votre recherche</li>
                    <li>• Utilisez des termes plus généraux</li>
                    <li>• Élargissez votre zone géographique</li>
                    <li>• Augmentez votre budget maximum</li>
                </ul>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Section suggestions/catégories populaires -->
@if(!request()->hasAny(['search', 'location', 'category_id', 'max_price', 'availability']) && $categories && $categories->count() > 0)
<div class="bg-gray-50 py-8 sm:py-12 lg:py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8 sm:mb-10 lg:mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 sm:mb-4">Catégories populaires</h2>
            <p class="text-sm sm:text-base text-gray-600">Découvrez les équipements les plus demandés</p>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-6">
            @foreach($categories->take(6) as $category)
            <a href="{{ route('equipment.index', ['category_id' => $category->id]) }}" 
               class="bg-white rounded-lg p-3 sm:p-4 lg:p-6 text-center hover:shadow-md transition-shadow duration-200">
                <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xs sm:text-sm lg:text-base font-medium text-gray-900 mb-1 truncate">{{ $category->name }}</h3>
                <p class="text-xs sm:text-sm text-gray-500">{{ $category->equipment_count ?? 0 }} équipements</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<script>
// Fonction pour basculer l'affichage des filtres
function toggleFilters() {
    const filtersForm = document.getElementById('filtersForm');
    const buttonText = document.getElementById('filterButtonText');
    const chevron = document.getElementById('filterChevron');
    
    if (filtersForm.style.display === 'none' || filtersForm.style.display === '') {
        filtersForm.style.display = 'block';
        buttonText.textContent = 'Masquer les filtres';
        chevron.classList.remove('fa-chevron-down');
        chevron.classList.add('fa-chevron-up');
    } else {
        filtersForm.style.display = 'none';
        buttonText.textContent = 'Afficher les filtres';
        chevron.classList.remove('fa-chevron-up');
        chevron.classList.add('fa-chevron-down');
    }
}

// Fonction pour effacer tous les filtres
function clearFilters() {
    // Réinitialiser le formulaire
    document.getElementById('filtersForm').reset();
    
    // Rediriger vers la page sans paramètres
    window.location.href = '{{ route("equipment.index") }}';
}

// Variables globales pour la géolocalisation
let userLatitude = null;
let userLongitude = null;

// Fonction pour calculer la distance entre deux points (formule de Haversine)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // Rayon de la Terre en kilomètres
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Fonction pour afficher les distances
function displayDistances() {
    if (!userLatitude || !userLongitude) return;
    
    const distanceBadges = document.querySelectorAll('.distance-badge');
    distanceBadges.forEach(badge => {
        const equipmentId = badge.getAttribute('data-equipment-id');
        // Ici, vous devriez avoir les coordonnées des équipements depuis le backend
        // Pour l'exemple, on simule des coordonnées aléatoires
        const equipmentLat = userLatitude + (Math.random() - 0.5) * 0.1;
        const equipmentLon = userLongitude + (Math.random() - 0.5) * 0.1;
        
        const distance = calculateDistance(userLatitude, userLongitude, equipmentLat, equipmentLon);
        
        if (distance < 100) { // Afficher seulement si moins de 100km
            badge.textContent = distance < 1 ? 
                `${Math.round(distance * 1000)}m` : 
                `${distance.toFixed(1)}km`;
            badge.classList.remove('hidden');
        }
    });
}

// Fonction pour obtenir la géolocalisation
function getMyLocation() {
    const locationInput = document.getElementById('city');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const btn = document.getElementById('getLocationBtn');
    
    if (!navigator.geolocation) {
        alert('La géolocalisation n\'est pas supportée par ce navigateur.');
        return;
    }
    
    // Changer le texte du bouton pendant le chargement
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span class="hidden sm:inline">Localisation...</span>';
    btn.disabled = true;
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Stocker les coordonnées globalement
            userLatitude = lat;
            userLongitude = lng;
            
            // Remplir les champs cachés
            latitudeInput.value = lat;
            longitudeInput.value = lng;
            
            // Utiliser l'API de géocodage inverse gratuite de Nominatim (OpenStreetMap)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=fr`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const address = data.address;
                        const city = address.city || address.town || address.village || address.municipality || '';
                        const postcode = address.postcode || '';
                        
                        if (city) {
                            locationInput.value = postcode ? `${city}, ${postcode}` : city;
                        } else if (data.display_name) {
                            // Extraire les parties pertinentes de l'adresse complète
                            const parts = data.display_name.split(',');
                            locationInput.value = parts.slice(0, 2).join(',').trim();
                        } else {
                            locationInput.value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                        }
                    } else {
                        // Fallback: utiliser les coordonnées
                        locationInput.value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                    }
                    
                    // Afficher les distances
                    displayDistances();
                    
                    // Si le tri par distance est sélectionné, activer le rayon par défaut
                    const sortSelect = document.getElementById('sort');
                    const radiusSelect = document.getElementById('radius');
                    if (sortSelect.value === 'distance' && !radiusSelect.value) {
                        radiusSelect.value = '25'; // 25km par défaut
                    }
                })
                .catch(error => {
                    console.error('Erreur de géocodage:', error);
                    // Fallback: utiliser les coordonnées
                    locationInput.value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                    displayDistances();
                })
                .finally(() => {
                    // Restaurer le bouton
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        },
        function(error) {
            let message = 'Erreur de géolocalisation: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += 'Permission refusée.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += 'Position indisponible.';
                    break;
                case error.TIMEOUT:
                    message += 'Délai d\'attente dépassé.';
                    break;
                default:
                    message += 'Erreur inconnue.';
                    break;
            }
            alert(message);
            
            // Restaurer le bouton
            btn.innerHTML = originalText;
            btn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}

// Fonction pour gérer l'affichage du rayon selon le tri sélectionné
function handleSortChange() {
    const sortSelect = document.getElementById('sort');
    const radiusContainer = document.getElementById('radius').closest('div');
    
    if (sortSelect.value === 'distance') {
        radiusContainer.style.display = 'block';
        // Demander la géolocalisation si pas encore obtenue
        if (!userLatitude || !userLongitude) {
            const locationInput = document.getElementById('city');
            if (!locationInput.value) {
                // Suggérer d'utiliser la géolocalisation
                const getLocationBtn = document.getElementById('getLocationBtn');
                if (getLocationBtn) {
                    getLocationBtn.classList.add('animate-pulse', 'ring-2', 'ring-blue-300');
                    setTimeout(() => {
                        getLocationBtn.classList.remove('animate-pulse', 'ring-2', 'ring-blue-300');
                    }, 3000);
                }
            }
        }
    } else {
        // Ne pas masquer le rayon, le laisser visible
    }
}

// Fonction pour gérer le changement de rayon
function handleRadiusChange() {
    const radiusSelect = document.getElementById('radius');
    const sortSelect = document.getElementById('sort');
    
    // Si un rayon est sélectionné, forcer le tri par distance
    if (radiusSelect.value && sortSelect.value !== 'distance') {
        sortSelect.value = 'distance';
    }
}

// Ajouter l'événement au bouton de basculement des filtres
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    if (toggleButton) {
        toggleButton.addEventListener('click', toggleFilters);
    }
    
    // Gestionnaire pour le changement de tri
    const sortSelect = document.getElementById('sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', handleSortChange);
        // Vérifier l'état initial
        handleSortChange();
    }
    
    // Gestionnaire pour le changement de rayon
    const radiusSelect = document.getElementById('radius');
    if (radiusSelect) {
        radiusSelect.addEventListener('change', handleRadiusChange);
    }
    
    // Si des coordonnées sont déjà présentes (par exemple depuis une session précédente)
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    if (latitudeInput && longitudeInput && latitudeInput.value && longitudeInput.value) {
        userLatitude = parseFloat(latitudeInput.value);
        userLongitude = parseFloat(longitudeInput.value);
        displayDistances();
    }
    
    // Afficher les filtres si des paramètres sont présents
    const hasFilters = {{ request()->anyFilled(['search', 'category', 'price_max', 'availability', 'sort', 'city', 'urgent', 'delivery_included']) ? 'true' : 'false' }};
    if (hasFilters) {
        toggleFilters();
    }
});
</script>

@endsection