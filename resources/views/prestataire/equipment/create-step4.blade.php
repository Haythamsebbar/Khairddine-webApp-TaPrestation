@extends('layouts.app')

@section('title', 'Ajouter un équipement - Étape 4')

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-green-900 mb-2">Ajouter un équipement</h1>
                <p class="text-base sm:text-lg text-green-700">Étape 4 : Localisation et résumé</p>
            </div>

            <!-- Barre de progression -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 sm:mb-4 space-y-2 sm:space-y-0">
                    <h2 class="text-base sm:text-lg font-semibold text-green-900">Processus de création</h2>
                    <span class="text-xs sm:text-sm text-green-600">Étape 4 sur 4</span>
                </div>
                <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 overflow-x-auto">
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-900 hidden sm:inline">Informations de base</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-600 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-900 hidden sm:inline">Tarifs et conditions</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-600 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-900 hidden sm:inline">Photos</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-600 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            4
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-900 hidden sm:inline">Localisation et résumé</span>
                    </div>
                </div>
                <!-- Labels mobiles -->
                <div class="flex justify-between mt-2 sm:hidden text-xs text-gray-600">
                    <span class="text-green-600 font-medium">Info</span>
                    <span class="text-green-600 font-medium">Tarifs</span>
                    <span class="text-green-600 font-medium">Photos</span>
                    <span class="text-green-600 font-medium">Résumé</span>
                </div>
            </div>

            <!-- Formulaire Étape 4 -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('prestataire.equipment.create.step3') }}" class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1">
                            <i class="fas fa-arrow-left text-base sm:text-lg"></i>
                        </a>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-green-900">Localisation et résumé</h2>
                            <p class="text-xs sm:text-sm text-green-700">Définissez la localisation et vérifiez les informations</p>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">Oups!</strong>
                        <span class="block sm:inline">Quelque chose s'est mal passé.</span>
                        <ul class="mt-2">
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('prestataire.equipment.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4 sm:space-y-6 lg:space-y-8">
                        <!-- Localisation -->
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-green-900 mb-3 sm:mb-4 border-b border-green-200 pb-2">Localisation</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                <div class="sm:col-span-2 lg:col-span-1">
                                    <label for="address" class="block text-sm font-medium text-green-700 mb-2">Adresse</label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}" class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base" placeholder="Ex: 123 Rue de Paris">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-green-700 mb-2">Ville *</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" required class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('city') border-red-500 @enderror text-sm sm:text-base" placeholder="Ex: Paris">
                                    @error('city')
                                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="country" class="block text-sm font-medium text-green-700 mb-2">Pays *</label>
                                    <input type="text" name="country" id="country" value="{{ old('country') }}" required class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('country') border-red-500 @enderror text-sm sm:text-base" placeholder="Ex: France">
                                    @error('country')
                                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div id="map" class="h-48 sm:h-64 lg:h-80 rounded-lg border border-gray-300"></div>
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                @error('latitude')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                                @error('longitude')
                                    <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-3">
                                    <button type="button" id="getCurrentLocationBtn" class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-sm sm:text-base">
                                        <i class="fas fa-location-arrow mr-2"></i><span class="hidden sm:inline">Ma position actuelle</span><span class="sm:hidden">Ma position</span>
                                    </button>
                                    <button type="button" id="clearLocationBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-sm sm:text-base">
                                        <i class="fas fa-times mr-2"></i><span class="hidden sm:inline">Effacer la localisation</span><span class="sm:hidden">Effacer</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Résumé complet -->
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-green-900 mb-3 sm:mb-4 border-b border-green-200 pb-2">Résumé de votre équipement</h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Informations de base -->
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Informations de base</h4>
                                    <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Nom :</span>
                                            <span class="font-medium text-gray-900 break-words">{{ session('equipment_step1.name', 'Non défini') }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Catégorie :</span>
                                            <span class="font-medium text-gray-900 break-words">{{ $categoryName ?? 'Non définie' }}</span>
                                        </div>
                                        @if($subcategoryName)
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Sous-catégorie :</span>
                                            <span class="font-medium text-gray-900 break-words">{{ $subcategoryName }}</span>
                                        </div>
                                        @endif
                                        <div class="pt-1 sm:pt-2">
                                            <span class="text-gray-600 font-medium sm:font-normal">Description :</span>
                                            <p class="text-gray-900 text-xs mt-1 break-words">{{ Str::limit(session('equipment_step1.description', 'Non définie'), 100) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tarifs et conditions -->
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                                    <h4 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Tarifs et conditions</h4>
                                    <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Prix par jour :</span>
                                            <span class="font-medium text-green-600">{{ session('equipment_step2.price_per_day', 'Non défini') }}€</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Caution :</span>
                                            <span class="font-medium text-gray-900">{{ session('equipment_step2.security_deposit', 'Non défini') }}€</span>
                                        </div>
                                        @if(session('equipment_step2.price_per_hour'))
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">Prix par heure :</span>
                                            <span class="font-medium text-gray-900">{{ session('equipment_step2.price_per_hour') }}€</span>
                                        </div>
                                        @endif
                                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                                            <span class="text-gray-600 font-medium sm:font-normal">État :</span>
                                            <span class="font-medium text-gray-900">
                                                @php
                                                    $conditions = [
                                                        'excellent' => 'Excellent',
                                                        'very_good' => 'Très bon',
                                                        'good' => 'Bon',
                                                        'fair' => 'Correct',
                                                        'poor' => 'Mauvais'
                                                    ];
                                                    $condition = session('equipment_step2.condition');
                                                @endphp
                                                {{ $conditions[$condition] ?? 'Non défini' }}
                                            </span>
                                        </div>
                                        <div class="pt-1 sm:pt-2">
                                            <div class="flex flex-wrap gap-1 sm:gap-2">
                                                @if(session('equipment_step2.delivery_included'))
                                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Livraison incluse</span>
                                                @endif
                                                @if(session('equipment_step2.license_required'))
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Permis requis</span>
                                                @endif
                                                @if(session('equipment_step2.is_available'))
                                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Disponible immédiatement</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Photos -->
                            @if(session('equipment_step3.temp_image_paths') && count(session('equipment_step3.temp_image_paths')) > 0)
                            <div class="mt-4 sm:mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Photos de l'équipement ({{ count(session('equipment_step3.temp_image_paths')) }})</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach(session('equipment_step3.temp_image_paths') as $imagePath)
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Photo de l'équipement" class="w-full h-24 sm:h-32 object-cover rounded-lg border-2 border-green-300">
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center pt-4 sm:pt-6 border-t border-green-200 gap-3 sm:gap-4 mt-6 sm:mt-8">
                        <a href="{{ route('prestataire.equipment.create.step3') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg transition duration-200 font-medium text-center text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>Précédent
                        </a>
                        
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl text-sm sm:text-base">
                            <i class="fas fa-check mr-2"></i>
                            <span class="hidden sm:inline">Publier l'équipement</span>
                            <span class="sm:hidden">Publier</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
let map, marker;

function initMap() {
    const lat = parseFloat(document.getElementById('latitude').value) || 48.8566;
    const lon = parseFloat(document.getElementById('longitude').value) || 2.3522;

    map = L.map('map').setView([lat, lon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    marker = L.marker([lat, lon], { draggable: true }).addTo(map);

    if (document.getElementById('latitude').value && document.getElementById('longitude').value) {
        fetchAddress(lat, lon);
    }

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        const latlng = marker.getLatLng();
        updateLatLng(latlng.lat, latlng.lng);
    });
}

function updateLatLng(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
    fetchAddress(lat, lng);
}

function fetchAddress(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
            if (data.address) {
                document.getElementById('address').value = data.address.road || '';
                document.getElementById('city').value = data.address.city || data.address.town || data.address.village || '';
                document.getElementById('country').value = data.address.country || '';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération de l\'adresse:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    initMap();

    document.getElementById('getCurrentLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 13);
                updateLatLng(lat, lng);
            }, function() {
                alert('Impossible de récupérer votre position.');
            });
        } else {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
        }
    });

    document.getElementById('clearLocationBtn').addEventListener('click', function() {
        const defaultLat = 48.8566;
        const defaultLon = 2.3522;
        const defaultLatLng = new L.LatLng(defaultLat, defaultLon);
        
        marker.setLatLng(defaultLatLng);
        map.setView(defaultLatLng, 5);
        
        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
        document.getElementById('address').value = '';
        document.getElementById('city').value = '';
        document.getElementById('country').value = '';
    });
});
</script>
@endsection