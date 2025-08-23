<!-- Étape 2: Localisation -->
<div class="bg-white rounded-xl shadow-lg border border-red-200 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <div class="flex items-center mb-3 sm:mb-4">
        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold mr-2 sm:mr-3">
            2
        </div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-red-900">Localisation</h2>
    </div>
    
    <div class="space-y-3 sm:space-y-4">
        <p class="text-xs sm:text-sm text-red-700 mb-3 sm:mb-4">
            <i class="fas fa-info-circle mr-1 sm:mr-2"></i>
            Cliquez sur la carte pour sélectionner l'emplacement de votre vente urgente
        </p>
        
        <!-- Carte -->
        <div class="map-container">
            <div id="urgentSaleMap" class="h-64 sm:h-80 rounded-lg border border-gray-300 shadow-sm"></div>
        </div>
        
        <!-- Adresse sélectionnée -->
        <div class="mt-3 sm:mt-4">
            <label for="selectedAddress" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Adresse sélectionnée *</label>
            <input type="text" id="selectedAddress" name="location" value="{{ old('location') }}" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('location') border-red-500 @enderror" placeholder="Cliquez sur la carte pour sélectionner une localisation" readonly>
            <input type="hidden" id="selectedLatitude" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="selectedLongitude" name="longitude" value="{{ old('longitude') }}">
            @error('location')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
            @error('latitude')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
            @error('longitude')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Boutons d'action -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-3 sm:mt-4">
            <button type="button" id="getCurrentLocationBtn" class="bg-red-600 hover:bg-red-700 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-xs sm:text-sm md:text-base flex items-center justify-center">
                <i class="fas fa-location-arrow mr-1 sm:mr-2 text-xs sm:text-sm"></i>Ma position actuelle
            </button>
            <button type="button" id="clearLocationBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-md transition duration-200 text-xs sm:text-sm md:text-base flex items-center justify-center">
                <i class="fas fa-times mr-1 sm:mr-2 text-xs sm:text-sm"></i>Effacer la localisation
            </button>
        </div>
        
        <!-- Conseils -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4 mt-3 sm:mt-4">
            <h4 class="text-xs sm:text-sm font-semibold text-red-800 mb-2">
                <i class="fas fa-lightbulb mr-1 sm:mr-2 text-xs sm:text-sm"></i>Conseils pour la localisation
            </h4>
            <ul class="text-xs sm:text-sm text-red-700 space-y-1">
                <li>• Choisissez un lieu facilement accessible pour les acheteurs</li>
                <li>• Privilégiez les lieux publics et sécurisés pour les rencontres</li>
                <li>• Vous pourrez ajuster la localisation précise lors du contact avec l'acheteur</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialisation de la carte Leaflet pour urgent sale
function initializeUrgentSaleMap() {
    const mapElement = document.getElementById('urgentSaleMap');
    if (!mapElement || urgentSaleMap) return;

    const defaultLat = 33.5731;
    const defaultLng = -7.5898;

    urgentSaleMap = L.map('urgentSaleMap', {
        center: [defaultLat, defaultLng],
        zoom: 6
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(urgentSaleMap);

    urgentSaleMap.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (currentMarker) urgentSaleMap.removeLayer(currentMarker);

        currentMarker = L.marker([lat, lng]).addTo(urgentSaleMap);

        document.getElementById('selectedLatitude').value = lat.toFixed(6);
        document.getElementById('selectedLongitude').value = lng.toFixed(6);

        reverseGeocode(lat, lng);
    });

    setTimeout(() => urgentSaleMap.invalidateSize(), 250);
}

async function reverseGeocode(lat, lng) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=fr`);
        const data = await response.json();
        document.getElementById('selectedAddress').value = data.display_name || `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    } catch (error) {
        document.getElementById('selectedAddress').value = `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
}

function clearLocation() {
    if (currentMarker && urgentSaleMap) urgentSaleMap.removeLayer(currentMarker);
    currentMarker = null;
    document.getElementById('selectedLatitude').value = '';
    document.getElementById('selectedLongitude').value = '';
    document.getElementById('selectedAddress').value = '';
}

function getCurrentLocation() {
    if (!navigator.geolocation) {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            if (urgentSaleMap) {
                urgentSaleMap.setView([lat, lng], 15);
                if (currentMarker) urgentSaleMap.removeLayer(currentMarker);
                currentMarker = L.marker([lat, lng]).addTo(urgentSaleMap);

                document.getElementById('selectedLatitude').value = lat.toFixed(6);
                document.getElementById('selectedLongitude').value = lng.toFixed(6);

                reverseGeocode(lat, lng);
            }
        },
        error => {
            let errorMessage = 'Erreur de géolocalisation: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage += 'Permission refusée par l\'utilisateur.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage += 'Position non disponible.';
                    break;
                case error.TIMEOUT:
                    errorMessage += 'Délai d\'attente dépassé.';
                    break;
                default:
                    errorMessage += 'Erreur inconnue.';
                    break;
            }
            alert(errorMessage);
        }
    );
}

// Événements pour les boutons
document.addEventListener('DOMContentLoaded', function() {
    const getCurrentBtn = document.getElementById('getCurrentLocationBtn');
    const clearBtn = document.getElementById('clearLocationBtn');
    
    if (getCurrentBtn) {
        getCurrentBtn.addEventListener('click', getCurrentLocation);
    }
    
    if (clearBtn) {
        clearBtn.addEventListener('click', clearLocation);
    }
});
</script>
@endpush