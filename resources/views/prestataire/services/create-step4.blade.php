@extends('layouts.app')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
@endpush

@section('content')
<div class="bg-blue-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-4 sm:mb-6 lg:mb-8 text-center">
                <h1 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-extrabold text-blue-900 mb-1 sm:mb-2">Créer un nouveau service</h1>
                <p class="text-sm sm:text-base lg:text-lg text-blue-700">Étape 4 : Localisation</p>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                        <a href="{{ route('prestataire.services.create.step3') }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-base sm:text-lg lg:text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-blue-900">Étape 4 sur 4</h2>
                            <p class="text-xs sm:text-sm lg:text-base text-blue-700 hidden sm:block">Localisation</p>
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 w-full overflow-x-auto">
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">Informations</span>
                            <span class="ml-1 text-xs font-medium text-green-600 sm:hidden">Info</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-green-600 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">Prix & Catégorie</span>
                            <span class="ml-1 text-xs font-medium text-green-600 sm:hidden">Prix</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-green-600 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">Photos</span>
                            <span class="ml-1 text-xs font-medium text-green-600 sm:hidden">Photo</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-blue-600 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                4
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-blue-600 hidden sm:inline">Localisation</span>
                            <span class="ml-1 text-xs font-medium text-blue-600 sm:hidden">Lieu</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('prestataire.services.store') }}" id="step4Form">
                @csrf

                <!-- Localisation -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-blue-900 mb-3 sm:mb-4 border-b border-blue-200 pb-2">
                        <i class="fas fa-map-marker-alt text-orange-600 mr-1 sm:mr-2 text-sm sm:text-base"></i>Localisation de votre service
                    </h2>
                    
                    <div class="mb-3 sm:mb-4">
                        <p class="text-xs sm:text-sm text-blue-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-xs sm:text-sm"></i>
                            Indiquez où vous proposez ce service. Cela aidera les clients à vous trouver plus facilement.
                        </p>
                        <div class="bg-orange-50 p-2 sm:p-3 rounded-lg">
                            <h4 class="font-semibold text-orange-800 mb-1 sm:mb-2 text-xs sm:text-sm">Conseils pour la localisation :</h4>
                            <ul class="text-xs sm:text-sm text-orange-700 space-y-0.5 sm:space-y-1">
                                <li>• Sélectionnez l'emplacement principal où vous exercez</li>
                                <li>• Vous pouvez proposer vos services dans un rayon autour de ce point</li>
                                <li>• Une localisation précise améliore votre visibilité</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="map-container">
                        <div id="serviceMap" class="h-40 sm:h-48 lg:h-64 rounded-lg border border-blue-300 shadow-inner"></div>
                        <div class="mt-2 sm:mt-3">
                            <input type="text" id="selectedAddress" name="address" value="{{ old('address', session('service_data.address')) }}" class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" placeholder="Cliquez sur la carte pour sélectionner une localisation" readonly>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', session('service_data.latitude')) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', session('service_data.longitude')) }}">
                            @error('address')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-2 sm:mt-3">
                                <button type="button" id="getCurrentLocationBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-md transition duration-200 text-xs sm:text-sm lg:text-base flex items-center justify-center">
                                    <i class="fas fa-location-arrow mr-1 sm:mr-2 text-xs sm:text-sm"></i><span class="hidden xs:inline">Ma position actuelle</span><span class="xs:hidden">Ma position</span>
                                </button>
                                <button type="button" id="clearLocationBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 sm:py-2.5 rounded-md transition duration-200 text-xs sm:text-sm lg:text-base flex items-center justify-center">
                                    <i class="fas fa-times mr-1 sm:mr-2 text-xs sm:text-sm"></i><span class="hidden xs:inline">Effacer la localisation</span><span class="xs:hidden">Effacer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-green-900 mb-3 sm:mb-4 border-b border-green-200 pb-2">
                        <i class="fas fa-check-circle text-green-600 mr-1 sm:mr-2 text-sm sm:text-base"></i>Récapitulatif de votre service
                    </h2>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <!-- Titre -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Titre :</span>
                            <p class="text-blue-900 font-semibold text-sm sm:text-base sm:text-right sm:max-w-xs" id="recap-title">{{ session('service_data.title', 'Non défini') }}</p>
                        </div>
                        
                        <!-- Prix -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Prix :</span>
                            <p class="text-green-600 font-semibold text-sm sm:text-base sm:text-right" id="recap-price">
                                {{ session('service_data.price') ? session('service_data.price') . '€' : 'Non défini' }}
                                @if(session('service_data.price_type'))
                                    <span class="text-xs sm:text-sm text-gray-600">({{ session('service_data.price_type') }})</span>
                                @endif
                            </p>
                        </div>
                        
                        <!-- Réservable -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Réservable :</span>
                            <p class="text-blue-900 font-semibold text-sm sm:text-base" id="recap-reservable">
                                {{ session('service_data.reservable') ? 'Oui' : 'Non' }}
                            </p>
                        </div>
                        
                        <!-- Délai de livraison -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-1 sm:space-y-0">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Délai de livraison :</span>
                            <p class="text-blue-900 font-semibold text-sm sm:text-base sm:text-right sm:max-w-xs" id="recap-delivery">
                                {{ session('service_data.delivery_time') ? session('service_data.delivery_time') . ' jours' : 'Non défini' }}
                            </p>
                        </div>
                        
                        <!-- Description -->
                        <div class="flex flex-col space-y-2 pt-3 sm:pt-4 border-t border-green-200">
                            <span class="text-xs sm:text-sm font-medium text-gray-600">Description :</span>
                            <p class="text-gray-800 text-xs sm:text-sm leading-relaxed" id="recap-description">{{ Str::limit(session('service_data.description', 'Non définie'), 150) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-6 sm:pt-8 border-t border-blue-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('prestataire.services.create.step3') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-3 rounded-lg transition duration-200 font-medium text-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl text-sm sm:text-base">
                        <i class="fas fa-check mr-2"></i>Créer le service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Map Initialization
    let map = null;
    let marker = null;
    const defaultLat = 33.5731; // Casablanca
    const defaultLng = -7.5898;

    function initializeMap() {
        const mapElement = document.getElementById('serviceMap');
        if (!mapElement) return;

        map = L.map('serviceMap').setView([defaultLat, defaultLng], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            updateMarker(lat, lng);
            reverseGeocode(lat, lng);
        });
        
        // Si des coordonnées existent déjà, les afficher
        const existingLat = document.getElementById('latitude').value;
        const existingLng = document.getElementById('longitude').value;
        if (existingLat && existingLng) {
            updateMarker(parseFloat(existingLat), parseFloat(existingLng));
            map.setView([parseFloat(existingLat), parseFloat(existingLng)], 13);
        }
    }

    function updateMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng]).addTo(map);
        }
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    }

    async function reverseGeocode(lat, lng) {
        try {
            // Ajouter un délai pour éviter les limitations de taux
            await new Promise(resolve => setTimeout(resolve, 100));
            
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=fr`, {
                method: 'GET',
                headers: {
                    'User-Agent': 'TaPrestation-App/1.0',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            document.getElementById('selectedAddress').value = data.display_name || `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        } catch (error) {
            console.error('Error during reverse geocoding:', error);
            // Fallback vers les coordonnées si l'API échoue
            document.getElementById('selectedAddress').value = `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }
    }

    document.getElementById('getCurrentLocationBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Localisation...';
        btn.disabled = true;
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 13);
                updateMarker(lat, lng);
                reverseGeocode(lat, lng);
                
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, function(error) {
                alert('Erreur de géolocalisation: ' + error.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        } else {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    document.getElementById('clearLocationBtn').addEventListener('click', function() {
        if (marker) {
            map.removeLayer(marker);
            marker = null;
        }
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('selectedAddress').value = '';
        map.setView([defaultLat, defaultLng], 6);
    });

    initializeMap();
});
</script>
@endpush