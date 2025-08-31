@extends('layouts.app')

@section('title', 'Tous les services - TaPrestation')

@php
    // Récupérer les filtres de session s'ils existent, sinon utiliser les paramètres de requête
    $sessionFilters = session('services_filters', []);
    $currentSearch = request('search', $sessionFilters['search'] ?? '');
    $currentCategory = request('category', $sessionFilters['category'] ?? '');
    $currentMainCategory = request('main_category', $sessionFilters['main_category'] ?? '');
    $currentPriceMin = request('price_min', $sessionFilters['price_min'] ?? '');
    $currentPriceMax = request('price_max', $sessionFilters['price_max'] ?? '');
    $currentLocation = request('location', $sessionFilters['location'] ?? '');
    $currentVerifiedOnly = request('verified_only', $sessionFilters['verified_only'] ?? false);
    $currentSort = request('sort', $sessionFilters['sort'] ?? '');
@endphp

@section('content')
<div class="bg-blue-50">
    <!-- Bannière d'en-tête -->
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-blue-900 mb-2 leading-tight">
                    Services Professionnels
                </h1>
                <p class="text-base sm:text-lg text-blue-700 max-w-2xl mx-auto">
                    Découvrez l'expertise de nos prestataires qualifiés pour tous vos besoins.
                </p>
            </div>
        </div>
    </div>
                        
        <!-- Section des filtres -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 mb-6 sm:mb-8">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-blue-800 mb-1 sm:mb-2">Filtres de recherche</h3>
                    <p class="text-sm sm:text-base lg:text-lg text-blue-700">Affinez votre recherche pour trouver le service parfait</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('services.index') }}" class="space-y-4 sm:space-y-6" id="filtersForm" style="display: none;">
                <!-- Première ligne de filtres -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                    <!-- Recherche par mot-clé -->
                    <div>
                        <label for="search" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Recherche</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search" id="search" value="{{ $currentSearch }}" placeholder="Services, prestataires, mots-clés..." class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                        </div>
                    </div>
                    
                    <!-- Catégorie principale -->
                    <div>
                        <label for="main_category" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Catégorie principale</label>
                        <div class="relative">
                            <i class="fas fa-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="main_category" id="main_category" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories->whereNull('parent_id') as $category)
                                    <option value="{{ $category->id }}" {{ request('main_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Sous-catégorie -->
                    <div>
                        <label for="category" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Sous-catégorie</label>
                        <div class="relative">
                            <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="category" id="category" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base" disabled>
                                <option value="">Sélectionnez d'abord une catégorie principale</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Prix minimum -->
                    <div>
                        <label for="price_min" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Prix minimum</label>
                        <div class="relative">
                            <i class="fas fa-euro-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="number" name="price_min" id="price_min" value="{{ $currentPriceMin }}" placeholder="Prix min" min="0" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                        </div>
                    </div>
                    
                    <!-- Prix maximum -->
                    <div>
                        <label for="price_max" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Prix maximum</label>
                        <div class="relative">
                            <i class="fas fa-euro-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="number" name="price_max" id="price_max" value="{{ $currentPriceMax }}" placeholder="Prix max" min="0" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                        </div>
                    </div>
                    
                    <!-- Tri par -->
                    <div>
                        <label for="sort" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Trier par</label>
                        <div class="relative">
                            <i class="fas fa-sort absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="sort" id="sort" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                                <option value="">Pertinence</option>
                                <option value="price_asc" {{ $currentSort == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ $currentSort == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="recent" {{ $currentSort == 'recent' ? 'selected' : '' }}>Plus récents</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Deuxième ligne de filtres -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <!-- Localisation -->
                    <div>
                        <label for="location" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Localisation</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="relative flex-1">
                                <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                                <input type="text" name="location" id="location" value="{{ $currentLocation }}" placeholder="Ville ou code postal" class="w-full pl-10 pr-4 py-2.5 sm:py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm sm:text-base">
                            </div>
                            <button type="button" id="getLocationBtn" onclick="getMyLocation()" class="px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center justify-center min-w-[100px] sm:min-w-[120px] text-sm sm:text-base" title="Utiliser ma position">
                                <i class="fas fa-crosshairs mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Ma position</span><span class="sm:hidden">Position</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Prestataire certifié -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="verified_only" value="1" {{ $currentVerifiedOnly ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-xs sm:text-sm text-gray-700">Prestataires certifiés uniquement</span>
                        </label>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-4 sm:pt-6 border-t-2 border-blue-200">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-search mr-2"></i>Appliquer les filtres
                    </button>
                    
                    <button type="button" onclick="clearFilters()" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 flex items-center justify-center text-sm sm:text-base">
                        <i class="fas fa-times mr-2"></i>Effacer tout
                    </button>
                    
                    @if($currentSearch || $currentCategory || $currentMainCategory || $currentPriceMin || $currentPriceMax || $currentLocation || $currentVerifiedOnly || $currentSort)
                        <a href="{{ route('services.index') }}" class="bg-white hover:bg-gray-50 text-blue-600 border border-blue-200 font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 flex items-center justify-center text-sm sm:text-base">
                            <i class="fas fa-undo mr-2"></i>Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Affichage des résultats -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-4 border-t-2 border-blue-200 mt-6 space-y-2 sm:space-y-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs sm:text-sm font-semibold text-blue-800">Résultats :</span>
                    <span class="px-2 sm:px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs sm:text-sm font-bold">
                        {{ $services->total() }} service(s)
                    </span>
                </div>
                @if($services->total() > 0)
                    <div class="text-xs sm:text-sm font-semibold text-blue-700">
                        {{ $services->pluck('prestataire_id')->unique()->count() }} prestataires actifs
                    </div>
                @endif
            </div>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    const filtersForm = document.getElementById('filtersForm');
    const buttonText = document.getElementById('filterButtonText');
    const chevron = document.getElementById('filterChevron');
    
    toggleButton.addEventListener('click', function() {
        if (filtersForm.style.display === 'none') {
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
    });
    
    // Gestion des catégories hiérarchiques
    const mainCategorySelect = document.getElementById('main_category');
    const subcategorySelect = document.getElementById('category');
    
    // Données des catégories (passées depuis le contrôleur)
    const categoriesData = @json($categories->mapWithKeys(function($category) {
        return [$category->id => $category->children];
    }));
    
    // Fonction pour charger les sous-catégories
    function loadSubcategories(mainCategoryId) {
        subcategorySelect.innerHTML = '<option value="">Toutes les sous-catégories</option>';
        
        if (mainCategoryId && categoriesData[mainCategoryId]) {
            const subcategories = categoriesData[mainCategoryId];
            
            if (subcategories.length > 0) {
                subcategorySelect.disabled = false;
                subcategories.forEach(function(subcategory) {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    if ('{{ $currentCategory }}' == subcategory.id) {
                        option.selected = true;
                    }
                    subcategorySelect.appendChild(option);
                });
            } else {
                subcategorySelect.disabled = true;
                subcategorySelect.innerHTML = '<option value="">Aucune sous-catégorie disponible</option>';
            }
        } else {
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie principale</option>';
        }
    }
    
    // Écouter les changements de catégorie principale
    mainCategorySelect.addEventListener('change', function() {
        loadSubcategories(this.value);
    });
    
    // Charger les sous-catégories si une catégorie principale est déjà sélectionnée
    const selectedMainCategory = mainCategorySelect.value;
    if (selectedMainCategory) {
        loadSubcategories(selectedMainCategory);
    }
});

function clearFilters() {
    const form = document.getElementById('filtersForm');
    form.reset();
    
    // Clear search input
    document.getElementById('search').value = '';
    
    // Reset subcategory dropdown
    const subcategorySelect = document.getElementById('category');
    subcategorySelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie principale</option>';
    subcategorySelect.disabled = true;
    
    window.location.href = '{{ route('services.index') }}';
}

// Fonction pour obtenir la géolocalisation
function getMyLocation() {
    const btn = document.getElementById('getLocationBtn');
    const locationInput = document.getElementById('location');
    
    if (!navigator.geolocation) {
        alert('La géolocalisation n\'est pas supportée par ce navigateur.');
        return;
    }
    
    // Changer l'état du bouton pendant le chargement
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span class="hidden sm:inline">Localisation...</span>';
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
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
                })
                .catch(error => {
                    console.error('Erreur de géocodage:', error);
                    // Fallback: utiliser les coordonnées
                    locationInput.value = `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                })
                .finally(() => {
                    // Restaurer l'état du bouton
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-crosshairs mr-2"></i><span class="hidden sm:inline">Ma position</span>';
                });
        },
        function(error) {
            let errorMessage = 'Erreur de géolocalisation: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Permission refusée.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Position indisponible.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Délai d\'attente dépassé.';
                    break;
                default:
                    errorMessage += 'Erreur inconnue.';
                    break;
            }
            alert(errorMessage);
            
            // Restaurer l'état du bouton
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-crosshairs mr-2"></i><span class="hidden sm:inline">Ma position</span>';
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        }
    );
}
</script>

        <!-- Section des résultats -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-8">
        @if($services->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 sm:gap-6">
                @foreach($services as $service)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100 service-card flex flex-col h-full">
                        <!-- Images du service -->
                        @if($service->images && $service->images->count() > 0)
                            <div class="relative h-48 sm:h-56 lg:h-64 overflow-hidden">
                                <img src="{{ asset('storage/' . $service->images->first()->image_path) }}" 
                                     alt="{{ $service->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                @if($service->images->count() > 1)
                                    <div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-images mr-1"></i>
                                        {{ $service->images->count() }}
                                    </div>
                                @endif
                                @if($service->price)
                                    <div class="absolute bottom-3 right-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-lg shadow-lg">
                                            <span class="text-lg font-bold">{{ number_format($service->price, 0, ',', ' ') }}€</span>
                                            @if($service->price_type)
                                                <div class="text-xs text-white opacity-90">/ {{ $service->price_type }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="relative h-48 sm:h-56 lg:h-64 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-blue-400 mb-2"></i>
                                    <p class="text-blue-600 font-medium">Aucune image</p>
                                </div>
                                @if($service->price)
                                    <div class="absolute bottom-3 right-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-lg shadow-lg">
                                            <span class="text-lg font-bold">{{ number_format($service->price, 0, ',', ' ') }}€</span>
                                            @if($service->price_type)
                                                <div class="text-xs text-white opacity-90">/ {{ $service->price_type }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Contenu de la carte -->
                        <div class="p-4 sm:p-6 flex flex-col flex-grow">
                            <!-- En-tête avec titre et prestataire -->
                            <div class="mb-4">
                                <h3 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 line-clamp-2">
                                {{ $service->title }}
                            </h3>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <div class="relative mr-2">
                                        @if($service->prestataire->photo)
                                            <img src="{{ asset('storage/' . $service->prestataire->photo) }}" alt="{{ $service->prestataire->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600 text-xs"></i>
                                            </div>
                                        @endif
                                        @if($service->prestataire->isVerified())
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-white" style="font-size: 8px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $service->prestataire->user->name }}</span>
                                        @if($service->prestataire->isVerified())
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1" style="font-size: 10px;"></i>
                                                Vérifié
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Contenu flexible -->
                            <div class="flex-grow">
                                <p class="text-gray-700 mb-4 line-clamp-3 leading-relaxed text-sm sm:text-base">{{ $service->description }}</p>
                                
                                @if($service->categories->count() > 0)
                                    <div class="mb-4">
                                        <div class="flex flex-wrap gap-1 sm:gap-2">
                                            @foreach($service->categories->take(2) as $category)
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 sm:px-3 py-1 rounded-full">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                            @if($service->categories->count() > 2)
                                                <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2 sm:px-3 py-1 rounded-full">
                                                    +{{ $service->categories->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Informations complémentaires -->
                                <div class="flex flex-col text-xs sm:text-sm text-gray-600 mb-4 pt-3 border-t border-gray-100 gap-2">
                                    <span class="flex items-center font-medium text-gray-500">
                                        <i class="fas fa-clock mr-1 text-gray-400"></i>
                                        {{ $service->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center font-medium truncate">
                                        <i class="fas fa-map-marker-alt mr-1 text-gray-400 flex-shrink-0"></i>
                                        <span class="truncate">
                                            @if($service->city)
                                                {{ $service->city }}
                                                @if($service->postal_code)
                                                    ({{ $service->postal_code }})
                                                @endif
                                            @elseif($service->address)
                                                {{ $service->address }}
                                            @elseif($service->prestataire->city)
                                                {{ $service->prestataire->city }}
                                                @if($service->prestataire->postal_code)
                                                    ({{ $service->prestataire->postal_code }})
                                                @endif
                                            @else
                                                Non spécifié
                                            @endif
                                        </span>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Actions - Toujours en bas -->
                            <div class="flex flex-col space-y-2 sm:space-y-3 mt-auto pt-4">
                                <a href="{{ route('services.show', $service) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center flex items-center justify-center text-sm sm:text-base">
                                    <i class="fas fa-eye mr-2"></i>
                                    Voir les détails
                                </a>
                                
                                @auth
                                    @if(auth()->user()->role === 'client')
                                        <a href="{{ route('bookings.create', $service) }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition duration-200 text-center flex items-center justify-center text-sm sm:text-base">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Réserver maintenant
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 sm:py-3 px-3 sm:px-4 rounded-lg transition duration-200 text-center flex items-center justify-center text-sm sm:text-base">
                                        <i class="fas fa-sign-in-alt mr-2"></i>
                                        <span class="hidden sm:inline">Se connecter pour </span>Réserver
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Message d'état vide harmonisé -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-blue-100">
                <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                    <div class="text-3xl text-blue-600">Recherche</div>
                </div>
                <h3 class="text-xl font-bold text-blue-900 mb-3">Aucun service trouvé</h3>
                <p class="text-blue-700 mb-2">Nous n'avons trouvé aucun service correspondant à vos critères de recherche.</p>
                <p class="text-blue-600 mb-6">Essayez de modifier vos filtres ou explorez tous nos services.</p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(request()->anyFilled(['search', 'category', 'price_min', 'price_max', 'location', 'premium', 'with_portfolio']))
                        <a href="{{ route('services.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Réinitialiser les filtres
                        </a>
                    @else
                        <a href="{{ route('services.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Voir tous les services
                        </a>
                    @endif
                    
                    <a href="{{ route('home') }}" 
                       class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-3 px-6 rounded-lg transition duration-200">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        @endif
    
        <!-- Pagination -->
        @if($services->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $services->appends(request()->query())->links() }}
            </div>
        @endif
        </div>
    </div>
</div>
@endsection