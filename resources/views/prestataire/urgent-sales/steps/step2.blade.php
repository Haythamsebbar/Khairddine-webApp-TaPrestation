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
        <div class="mt-3 sm:mt-4 relative z-50">
            <label for="selectedAddress" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Adresse sélectionnée *</label>
            <input type="text" id="selectedAddress" name="location" value="{{ old('location') }}" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('location') border-red-500 @enderror" placeholder="Tapez pour rechercher ou cliquez sur la carte">
            <div id="address-suggestions" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-xl mt-1 hidden max-h-60 overflow-y-auto" style="z-index: 999999 !important; position: absolute !important;">
                <!-- Suggestions will be populated here -->
            </div>
            <input type="hidden" id="selectedLatitude" name="latitude" value="{{ old('latitude') }}" required>
            <input type="hidden" id="selectedLongitude" name="longitude" value="{{ old('longitude') }}" required>
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
// Autocomplete functionality
let autocompleteTimeout;

// Utility function to hide suggestions
function hideSuggestions() {
    const suggestionsContainer = document.getElementById('address-suggestions');
    if (suggestionsContainer) {
        suggestionsContainer.classList.add('hidden');
        suggestionsContainer.style.display = 'none';
        console.log('Suggestions hidden');
    }
}

function fetchLocationSuggestions(query) {
    if (query.length < 2) {
        hideSuggestions();
        return;
    }

    console.log('Fetching suggestions for:', query);
    
    // Try the public geolocation API first
    fetch(`/api/public/geolocation/cities?search=${encodeURIComponent(query)}&limit=10`)
        .then(response => {
            console.log('API response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API response data:', data);
            if (data.success && data.data && data.data.length > 0) {
                displaySuggestions(data.data, query);
            } else {
                // Fallback to simple geocoding API
                console.log('No results from cities API, trying fallback');
                tryFallbackSuggestions(query);
            }
        })
        .catch(error => {
            console.error('Error fetching suggestions:', error);
            // Fallback to simple geocoding API
            tryFallbackSuggestions(query);
        });
}

function tryFallbackSuggestions(query) {
    // Simple fallback with common French cities
    const commonCities = [
        { display_name: 'Paris, France', lat: 48.8566, lon: 2.3522, country: 'France' },
        { display_name: 'Marseille, France', lat: 43.2965, lon: 5.3698, country: 'France' },
        { display_name: 'Lyon, France', lat: 45.7640, lon: 4.8357, country: 'France' },
        { display_name: 'Toulouse, France', lat: 43.6047, lon: 1.4442, country: 'France' },
        { display_name: 'Nice, France', lat: 43.7102, lon: 7.2620, country: 'France' },
        { display_name: 'Nantes, France', lat: 47.2184, lon: -1.5536, country: 'France' },
        { display_name: 'Montpellier, France', lat: 43.6110, lon: 3.8767, country: 'France' },
        { display_name: 'Strasbourg, France', lat: 48.5734, lon: 7.7521, country: 'France' },
        { display_name: 'Bordeaux, France', lat: 44.8378, lon: -0.5792, country: 'France' },
        { display_name: 'Lille, France', lat: 50.6292, lon: 3.0573, country: 'France' }
    ];
    
    const filteredCities = commonCities.filter(city => 
        city.display_name.toLowerCase().includes(query.toLowerCase())
    );
    
    if (filteredCities.length > 0) {
        console.log('Using fallback suggestions:', filteredCities);
        displaySuggestions(filteredCities, query);
    } else {
        hideSuggestions();
    }
}

function displaySuggestions(suggestions, query) {
    const container = document.getElementById('address-suggestions');
    if (!container) {
        console.error('Suggestions container not found');
        return;
    }
    
    console.log('Displaying suggestions:', suggestions);
    container.innerHTML = '';

    suggestions.forEach((suggestion, index) => {
        const div = document.createElement('div');
        div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0 transition-colors';
        
        const highlightedText = suggestion.display_name.replace(
            new RegExp(`(${query})`, 'gi'),
            '<strong class="text-red-600">$1</strong>'
        );
        
        div.innerHTML = `
            <div class="font-medium text-gray-800">${highlightedText}</div>
            <div class="text-sm text-gray-600 mt-1">${suggestion.country || 'France'}</div>
        `;
        
        div.setAttribute('data-lat', suggestion.lat);
        div.setAttribute('data-lon', suggestion.lon);
        div.setAttribute('data-display-name', suggestion.display_name);
        
        div.addEventListener('click', () => selectLocationFromData(div));
        
        container.appendChild(div);
    });

    // Force show the container
    container.classList.remove('hidden');
    container.style.display = 'block';
    container.style.zIndex = '999999';
    
    console.log('Suggestions container is now visible:', !container.classList.contains('hidden'));
}

function selectLocationFromData(element) {
    const lat = parseFloat(element.getAttribute('data-lat'));
    const lon = parseFloat(element.getAttribute('data-lon'));
    const displayName = element.getAttribute('data-display-name');
    
    console.log('Selecting location:', displayName, lat, lon);
    
    document.getElementById('selectedAddress').value = displayName;
    document.getElementById('selectedLatitude').value = lat.toFixed(6);
    document.getElementById('selectedLongitude').value = lon.toFixed(6);
    
    // Hide the suggestions dropdown
    hideSuggestions();
    
    // Update map
    if (urgentSaleMap) {
        urgentSaleMap.setView([lat, lon], 15);
        if (currentMarker) urgentSaleMap.removeLayer(currentMarker);
        currentMarker = L.marker([lat, lon]).addTo(urgentSaleMap);
        console.log('Map updated with selected location');
    }
}

// Add event listeners for autocomplete
document.addEventListener('DOMContentLoaded', function() {
    const addressInput = document.getElementById('selectedAddress');
    
    if (addressInput) {
        console.log('Address input found, setting up autocomplete');
        
        addressInput.addEventListener('input', function() {
            const query = this.value;
            console.log('Input changed:', query);
            
            clearTimeout(autocompleteTimeout);
            autocompleteTimeout = setTimeout(() => {
                console.log('Triggering autocomplete for:', query);
                fetchLocationSuggestions(query);
            }, 300);
        });
        
        addressInput.addEventListener('focus', function() {
            console.log('Input focused, current value:', this.value);
            if (this.value.length >= 2) {
                fetchLocationSuggestions(this.value);
            }
        });
    } else {
        console.error('Address input not found!');
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('#selectedAddress') && !event.target.closest('#address-suggestions')) {
            hideSuggestions();
        }
    });
    
    // Also hide on blur (when input loses focus)
    if (addressInput) {
        addressInput.addEventListener('blur', function() {
            // Add a small delay to allow click on suggestions to register first
            setTimeout(() => {
                const suggestionsContainer = document.getElementById('address-suggestions');
                if (suggestionsContainer && !suggestionsContainer.matches(':hover')) {
                    hideSuggestions();
                }
            }, 150);
        });
    }
    
    // Handle keyboard navigation
    addressInput.addEventListener('keydown', function(e) {
        const suggestions = document.querySelectorAll('#address-suggestions > div');
        const currentActive = document.querySelector('#address-suggestions > div.bg-gray-200');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentActive) {
                currentActive.classList.remove('bg-gray-200');
                const next = currentActive.nextElementSibling;
                if (next) {
                    next.classList.add('bg-gray-200');
                } else {
                    suggestions[0]?.classList.add('bg-gray-200');
                }
            } else {
                suggestions[0]?.classList.add('bg-gray-200');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentActive) {
                currentActive.classList.remove('bg-gray-200');
                const prev = currentActive.previousElementSibling;
                if (prev) {
                    prev.classList.add('bg-gray-200');
                } else {
                    suggestions[suggestions.length - 1]?.classList.add('bg-gray-200');
                }
            } else {
                suggestions[suggestions.length - 1]?.classList.add('bg-gray-200');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentActive) {
                selectLocationFromData(currentActive);
            }
        } else if (e.key === 'Escape') {
            document.getElementById('address-suggestions').classList.add('hidden');
        }
    });
});

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

    // Restore marker from old values if they exist
    restoreLocationFromOldValues();

    setTimeout(() => urgentSaleMap.invalidateSize(), 250);
}

// Function to restore location from old form values
function restoreLocationFromOldValues() {
    const latInput = document.getElementById('selectedLatitude');
    const lngInput = document.getElementById('selectedLongitude');
    const addressInput = document.getElementById('selectedAddress');
    
    if (latInput && lngInput && latInput.value && lngInput.value) {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        
        if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            console.log('Restoring location from old values:', lat, lng);
            
            if (urgentSaleMap) {
                urgentSaleMap.setView([lat, lng], 15);
                if (currentMarker) urgentSaleMap.removeLayer(currentMarker);
                currentMarker = L.marker([lat, lng]).addTo(urgentSaleMap);
            }
        }
    }
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