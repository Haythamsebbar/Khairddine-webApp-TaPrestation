@extends('layouts.app')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin="" />
@endpush

@section('content')
<div class="bg-blue-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">Modifier un service</h1>
                <p class="text-lg text-blue-700">Modifiez votre annonce de service professionnel</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('prestataire.services.index') }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-xl font-bold text-blue-900">Modifier le service</h2>
                            <p class="text-blue-700">Mettez à jour les informations de votre service</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg" role="alert">
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

            <form method="POST" action="{{ route('prestataire.services.update', $service->id) }}" enctype="multipart/form-data" id="serviceForm">
                @csrf
                @method('PUT')

                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Informations de base</h2>
                    
                    <div class="space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-blue-700 mb-2">Titre du service *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $service->title) }}" required maxlength="255" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                            <div class="flex justify-between items-center mt-1">
                                <div>
                                    @error('title')
                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                    <p id="title-warning" class="text-yellow-600 text-sm hidden">Titre trop court, précisez l'usage ou le modèle</p>
                                    <p id="title-tip" class="text-blue-600 text-sm">Idéal : 5–9 mots, sans abréviations</p>
                                </div>
                                <p class="text-gray-500 text-sm"><span id="title-count">0</span>/70</p>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-blue-700 mb-2">Description détaillée *</label>
                            <textarea id="description" name="description" required rows="6" maxlength="2000" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" placeholder="Décrivez en détail votre service, vos compétences et ce qui vous différencie...">{{ old('description', $service->description) }}</textarea>
                            <div class="mt-1">
                                @error('description')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <div id="description-error" class="text-red-500 text-sm hidden">
                                    <p class="font-medium">Description trop courte (minimum 50 caractères)</p>
                                    <p class="text-xs mt-1">Structure suggérée : Ce que c'est / Pour qui / Ce qui est inclus / Conditions</p>
                                </div>
                                <div id="description-warning" class="text-yellow-600 text-sm hidden">
                                    <p>Ajoutez bénéfices, état, accessoires, délais, garanties</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-blue-600 text-sm">Recommandé : 150–600 caractères</p>
                                    <p class="text-gray-500 text-sm"><span id="description-count">0</span> caractères</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reservable -->
                        <div>
                            <label for="reservable" class="inline-flex items-center">
                                <input id="reservable" type="checkbox" class="rounded border-blue-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50" name="reservable" {{ old('reservable', $service->reservable) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-blue-600">Activer la réservation directe pour ce service</span>
                            </label>
                        </div>

                        <!-- Delivery time -->
                        <div>
                            <label for="delivery_time" class="block text-sm font-medium text-blue-700 mb-2">Délai de livraison (en jours)</label>
                            <input type="number" id="delivery_time" name="delivery_time" value="{{ old('delivery_time', $service->delivery_time) }}" min="1" max="365" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('delivery_time') border-red-500 @enderror">
                            @error('delivery_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Photos</h2>
                    
                    <!-- Images existantes -->
                    @if($service->images->count() > 0)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-blue-700 mb-3">Images actuelles</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="existing-images">
                            @foreach($service->images as $image)
                                <div class="relative group" id="image-container-{{ $image->id }}">
                                    <img src="{{ Storage::url($image->path) }}" alt="Service Image" class="rounded-lg object-cover h-32 w-full border border-blue-200">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                        <button type="button" data-image-id="{{ $image->id }}" class="delete-image-btn text-white p-2 rounded-full bg-red-500 hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Zone d'upload -->
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center bg-blue-50 hover:border-blue-400 transition-colors">
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('images').click()">
                            <i class="fas fa-cloud-upload-alt text-blue-400 text-4xl mb-4"></i>
                            <p class="text-blue-600 mb-2">Cliquez pour ajouter des photos ou glissez-déposez</p>
                            <p class="text-blue-500 text-sm">Maximum 5 photos, 5MB par photo</p>
                        </div>
                        <div id="image-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4 hidden"></div>
                    </div>
                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Catégorie du service</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-blue-700 mb-2">Catégorie principale *</label>
                            <select id="category_id" name="category_id" required class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Sélectionnez une catégorie principale</option>
                                @if(isset($categories))
                                    @foreach($categories->whereNull('parent_id') as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $service->categories->first()->parent_id ? $service->categories->first()->parent_id : $service->categories->first()->id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="subcategory-group" style="display: none;">
                            <label for="subcategory_id" class="block text-sm font-medium text-blue-700 mb-2">Sous-catégorie</label>
                            <select id="subcategory_id" name="subcategory_id" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" disabled>
                                <option value="">Veuillez d'abord choisir une catégorie</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Prix -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Prix du service</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-blue-700 mb-2">Prix (€)</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $service->price) }}" min="0" step="0.01" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price_type" class="block text-sm font-medium text-blue-700 mb-2">Type de tarification</label>
                            <select id="price_type" name="price_type" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('price_type') border-red-500 @enderror">
                                <option value="">Sélectionnez un type</option>
                                <option value="fixe" {{ old('price_type', $service->price_type) == 'fixe' ? 'selected' : '' }}>Prix fixe</option>
                                <option value="heure" {{ old('price_type', $service->price_type) == 'heure' ? 'selected' : '' }}>Par heure</option>
                                <option value="jour" {{ old('price_type', $service->price_type) == 'jour' ? 'selected' : '' }}>Par jour</option>
                                <option value="projet" {{ old('price_type', $service->price_type) == 'projet' ? 'selected' : '' }}>Par projet</option>
                                <option value="devis" {{ old('price_type', $service->price_type) == 'devis' ? 'selected' : '' }}>Sur devis</option>
                            </select>
                            @error('price_type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Localisation</h2>
                    <div class="map-container">
                        <div id="serviceMap" class="h-64 rounded-lg border border-blue-300"></div>
                        <div class="mt-3">
                            <input type="text" id="selectedAddress" name="address" value="{{ old('address', $service->address) }}" class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" placeholder="Cliquez sur la carte pour sélectionner une localisation" readonly>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $service->latitude) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $service->longitude) }}">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex gap-3 mt-3">
                                <button type="button" id="getCurrentLocationBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200"><i class="fas fa-location-arrow mr-2"></i>Ma position actuelle</button>
                                <button type="button" id="clearLocationBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200"><i class="fas fa-times mr-2"></i>Effacer la localisation</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-8 border-t border-blue-200">
                    <a href="{{ route('prestataire.services.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-6 py-3 rounded-lg transition duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Annuler
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl">
                        <i class="fas fa-check mr-2"></i>Mettre à jour le service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    const existingLat = document.getElementById('latitude').value;
    const existingLng = document.getElementById('longitude').value;

    function initializeMap() {
        const mapElement = document.getElementById('serviceMap');
        if (!mapElement) return;

        // Use existing coordinates if available, otherwise use default
        const initialLat = existingLat ? parseFloat(existingLat) : defaultLat;
        const initialLng = existingLng ? parseFloat(existingLng) : defaultLng;
        const initialZoom = (existingLat && existingLng) ? 13 : 6;

        map = L.map('serviceMap').setView([initialLat, initialLng], initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add existing marker if coordinates exist
        if (existingLat && existingLng) {
            marker = L.marker([parseFloat(existingLat), parseFloat(existingLng)]).addTo(map);
        }

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            updateMarker(lat, lng);
            reverseGeocode(lat, lng);
        });
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
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1&accept-language=fr`);
            const data = await response.json();
            document.getElementById('selectedAddress').value = data.display_name || `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        } catch (error) {
            console.error('Error during reverse geocoding:', error);
            document.getElementById('selectedAddress').value = `Coordonnées: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }
    }

    document.getElementById('getCurrentLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 13);
                updateMarker(lat, lng);
                reverseGeocode(lat, lng);
            }, function(error) {
                alert('Erreur de géolocalisation: ' + error.message);
            });
        } else {
            alert('La géolocalisation n\'est pas supportée par votre navigateur.');
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

    // Image Preview
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    const uploadArea = document.getElementById('upload-area');

    window.previewImages = function(input) {
        previewContainer.innerHTML = '';
        if (input.files && input.files.length > 0) {
            previewContainer.classList.remove('hidden');
            uploadArea.classList.add('hidden');
            
            const files = Array.from(input.files).slice(0, 5);
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                            <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (files.length < 5) {
                const addMore = document.createElement('div');
                addMore.className = 'flex items-center justify-center h-24 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 transition-colors';
                addMore.innerHTML = '<i class="fas fa-plus text-gray-400 text-xl"></i>';
                addMore.onclick = () => imageInput.click();
                previewContainer.appendChild(addMore);
            }
        } else {
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        }
    }

    window.removeImage = function(index) {
        const dt = new DataTransfer();
        const files = imageInput.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        imageInput.files = dt.files;
        previewImages(imageInput);
    }

    // Delete existing images
    const deleteButtons = document.querySelectorAll('.delete-image-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const imageId = this.dataset.imageId;
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                fetch(`/prestataire/services/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || 'Une erreur est survenue lors de la communication avec le serveur.') });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const imageElement = document.getElementById(`image-container-${imageId}`);
                        if (imageElement) {
                            imageElement.remove();
                        }
                    } else {
                        alert(data.message || 'Erreur lors de la suppression de l\'image.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur: ' + error.message);
                });
            }
        });
    });

    // Validation en temps réel pour le titre
    const titleInput = document.getElementById('title');
    const titleCount = document.getElementById('title-count');
    const titleWarning = document.getElementById('title-warning');
    const titleTip = document.getElementById('title-tip');

    function updateTitleValidation() {
        const length = titleInput.value.length;
        titleCount.textContent = length;
        
        // Réinitialiser les styles
        titleInput.classList.remove('border-yellow-400', 'border-green-500', 'border-red-500');
        titleInput.classList.add('border-blue-300');
        
        if (length < 10) {
            titleInput.classList.remove('border-blue-300');
            titleInput.classList.add('border-yellow-400');
            titleWarning.classList.remove('hidden');
            titleTip.classList.add('hidden');
        } else if (length >= 10 && length <= 70) {
            titleInput.classList.remove('border-blue-300');
            titleInput.classList.add('border-green-500');
            titleWarning.classList.add('hidden');
            titleTip.classList.remove('hidden');
        } else {
            titleInput.classList.remove('border-blue-300');
            titleInput.classList.add('border-red-500');
            titleWarning.classList.add('hidden');
            titleTip.classList.add('hidden');
        }
    }

    // Validation en temps réel pour la description
    const descriptionInput = document.getElementById('description');
    const descriptionCount = document.getElementById('description-count');
    const descriptionError = document.getElementById('description-error');
    const descriptionWarning = document.getElementById('description-warning');

    function updateDescriptionValidation() {
        const length = descriptionInput.value.length;
        descriptionCount.textContent = length;
        
        // Réinitialiser les styles
        descriptionInput.classList.remove('border-red-500', 'border-yellow-400', 'border-green-500');
        descriptionInput.classList.add('border-blue-300');
        
        if (length < 50) {
            descriptionInput.classList.remove('border-blue-300');
            descriptionInput.classList.add('border-red-500');
            descriptionError.classList.remove('hidden');
            descriptionWarning.classList.add('hidden');
        } else if (length >= 50 && length <= 150) {
            descriptionInput.classList.remove('border-blue-300');
            descriptionInput.classList.add('border-yellow-400');
            descriptionError.classList.add('hidden');
            descriptionWarning.classList.remove('hidden');
        } else {
            descriptionInput.classList.remove('border-blue-300');
            descriptionInput.classList.add('border-green-500');
            descriptionError.classList.add('hidden');
            descriptionWarning.classList.add('hidden');
        }
    }

    // Événements
    titleInput.addEventListener('input', updateTitleValidation);
    descriptionInput.addEventListener('input', updateDescriptionValidation);

    // Initialisation
    updateTitleValidation();
    updateDescriptionValidation();

    // Gestion des catégories
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    const subcategoryGroup = document.getElementById('subcategory-group');
    
    // Variables pour stocker les valeurs actuelles du service
    const currentCategoryId = '{{ $service->categories->first()->parent_id ? $service->categories->first()->parent_id : $service->categories->first()->id ?? "" }}';
    const currentSubcategoryId = '{{ $service->categories->first()->parent_id ? $service->categories->first()->id : "" }}';
    
    // Fonctions pour charger les catégories
    function loadMainCategories() {
        fetch('/categories/main')
            .then(response => response.json())
            .then(categories => {
                categorySelect.innerHTML = '<option value="">Sélectionnez une catégorie principale</option>';
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (category.id == currentCategoryId) {
                        option.selected = true;
                    }
                    categorySelect.appendChild(option);
                });
                
                // Charger les sous-catégories si une catégorie est sélectionnée
                if (currentCategoryId) {
                    loadSubcategories(currentCategoryId, currentSubcategoryId);
                }
            })
            .catch(error => {
                console.error('Erreur lors du chargement des catégories:', error);
            });
    }
    
    function loadSubcategories(categoryId, selectedSubcategoryId = null) {
        if (!categoryId) {
            subcategoryGroup.style.display = 'none';
            subcategorySelect.disabled = true;
            subcategorySelect.innerHTML = '<option value="">Veuillez d\'abord choisir une catégorie</option>';
            return;
        }
        
        fetch(`/api/categories/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(subcategories => {
                subcategorySelect.innerHTML = '';
                
                if (subcategories.length === 0) {
                    subcategorySelect.innerHTML = '<option value="">Pas de sous-catégorie disponible</option>';
                    subcategorySelect.disabled = true;
                } else {
                    subcategorySelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie (optionnel)</option>';
                    subcategories.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        if (selectedSubcategoryId && subcategory.id == selectedSubcategoryId) {
                            option.selected = true;
                        }
                        subcategorySelect.appendChild(option);
                    });
                    subcategorySelect.disabled = false;
                }
                
                subcategoryGroup.style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur lors du chargement des sous-catégories:', error);
                subcategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
                subcategorySelect.disabled = true;
                subcategoryGroup.style.display = 'block';
            });
    }
    
    // Charger les catégories principales au chargement de la page
    if (categorySelect) {
        loadMainCategories();
        
        // Gérer le changement de catégorie principale
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            loadSubcategories(categoryId);
        });
    }

    // Validation lors de la soumission
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        if (descriptionInput.value.length < 50) {
            e.preventDefault();
            alert('La description doit contenir au moins 50 caractères.');
            descriptionInput.focus();
            return false;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.group:hover .group-hover\:opacity-100 {
    opacity: 1;
}
</style>
@endpush

@endsection