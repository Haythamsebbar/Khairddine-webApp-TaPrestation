@extends('layouts.app')

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-green-900 mb-2">Modifier l'équipement</h1>
                <p class="text-lg text-green-700">Modifiez les informations de votre équipement</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('prestataire.equipment.show', $equipment) }}" class="text-green-600 hover:text-green-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-xl font-bold text-green-900">Modification de l'équipement</h2>
                            <p class="text-green-700">Mettez à jour les informations de votre équipement</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('prestataire.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Informations de base</h2>
                    <div class="space-y-6">
                        <!-- Nom de l'équipement -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-green-700">Nom de l'équipement *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}" required 
                                   placeholder="Ex: Perceuse sans fil Bosch" 
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            <div class="flex justify-between items-center mt-1">
                                <div>
                                    <p id="name-warning" class="text-yellow-600 text-sm hidden">Nom trop court, précisez la marque ou le modèle</p>
                                    <p id="name-tip" class="text-green-600 text-sm">Idéal : 5–9 mots, incluez marque et modèle</p>
                                </div>
                                <p class="text-gray-500 text-sm"><span id="name-count">0</span>/70</p>
                            </div>
                        </div>

                    <!-- Catégorie principale -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-green-700 mb-2">Catégorie principale *</label>
                        <select id="category_id" name="category_id" required class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Sélectionnez une catégorie principale</option>
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sous-catégorie -->
                    <div id="subcategory-group" style="display: none;">
                        <label for="subcategory_id" class="block text-sm font-medium text-green-700 mb-2">Sous-catégorie</label>
                        <select id="subcategory_id" name="subcategory_id" class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500 @error('subcategory_id') border-red-500 @enderror" disabled>
                            <option value="">Veuillez d'abord choisir une catégorie</option>
                        </select>
                        @error('subcategory_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-green-700">Description courte *</label>
                        <textarea id="description" name="description" rows="3" required 
                                  placeholder="Décrivez brièvement votre équipement, son état et ses caractéristiques principales" 
                                  class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">{{ old('description', $equipment->description) }}</textarea>
                        <div class="mt-1">
                            <div id="description-error" class="text-red-500 text-sm hidden">
                                <p class="font-medium">Description trop courte (minimum 50 caractères)</p>
                                <p class="text-xs mt-1">Structure suggérée : État / Marque-modèle / Usage / Accessoires inclus</p>
                            </div>
                            <div id="description-warning" class="text-yellow-600 text-sm hidden">
                                <p>Ajoutez état, année, accessoires, conditions d'utilisation</p>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-green-600 text-sm">Recommandé : 150–400 caractères</p>
                                <p class="text-gray-500 text-sm"><span id="description-count">0</span> caractères</p>
                            </div>
                        </div>
                    </div>

                    <!-- Spécifications techniques -->
                    <div>
                        <label for="technical_specifications" class="block text-sm font-medium text-gray-700">Spécifications techniques</label>
                        <textarea id="technical_specifications" name="technical_specifications" rows="3" 
                                  placeholder="Dimensions, poids, puissance, capacité..." 
                                  class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">{{ old('technical_specifications', $equipment->technical_specifications) }}</textarea>
                    </div>

                    <!-- Photo principale actuelle -->
                    @if($equipment->main_photo || (is_array($equipment->photos) && count($equipment->photos) > 0))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photo principale actuelle</label>
                            <div class="flex items-center space-x-4">
                                @if($equipment->main_photo)
                                    <img src="{{ Storage::url($equipment->main_photo) }}" alt="Photo principale" class="w-20 h-20 object-cover rounded-lg">
                                @elseif(is_array($equipment->photos) && count($equipment->photos) > 0)
                                    <img src="{{ Storage::url($equipment->photos[0]) }}" alt="Photo principale" class="w-20 h-20 object-cover rounded-lg">
                                @endif
                                <span class="text-sm text-gray-500">Photo actuelle</span>
                            </div>
                        </div>
                    @endif

                    <!-- Nouvelle photo principale -->
                    <div>
                        <label for="main_photo" class="block text-sm font-medium text-gray-700">Nouvelle photo principale</label>
                        <input type="file" name="main_photo" id="main_photo" accept="image/*" 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-600 hover:file:bg-green-100">
                        <p class="mt-1 text-xs text-gray-500">Laissez vide pour conserver la photo actuelle</p>
                    </div>

                    <!-- Photos supplémentaires actuelles -->
                    @if(is_array($equipment->photos) && count($equipment->photos) > 1)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Photos supplémentaires actuelles</label>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach(array_slice($equipment->photos, 1) as $photo)
                                    <img src="{{ Storage::url($photo) }}" alt="Photo supplémentaire" class="w-20 h-20 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Nouvelles photos supplémentaires -->
                    <div>
                        <label for="photos" class="block text-sm font-medium text-gray-700">Nouvelles photos supplémentaires</label>
                        <input type="file" name="photos[]" id="photos" accept="image/*" multiple 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-600 hover:file:bg-green-100">
                        <p class="mt-1 text-xs text-gray-500">Ajoutez de nouvelles photos (les anciennes seront conservées)</p>
                    </div>

                    </div>
                </div>

                <!-- Tarification -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Tarification</h2>
                    <div class="space-y-6">
                        <!-- Prix par jour -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="daily_rate" class="block text-sm font-medium text-green-700">Prix par jour (€) *</label>
                                <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', $equipment->daily_rate) }}" required 
                                       min="0" step="0.01" placeholder="50" 
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label for="deposit_amount" class="block text-sm font-medium text-green-700">Caution (€) *</label>
                                <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount', $equipment->deposit_amount) }}" required 
                                       min="0" step="0.01" placeholder="100" 
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Autres prix -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="price_per_hour" class="block text-sm font-medium text-green-700">Prix par heure (€)</label>
                                <input type="number" name="price_per_hour" id="price_per_hour" step="0.01" min="0" 
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                       placeholder="10.00" value="{{ old('price_per_hour', $equipment->price_per_hour) }}">
                            </div>
                            <div>
                                <label for="price_per_week" class="block text-sm font-medium text-green-700">Prix par semaine (€)</label>
                                <input type="number" name="price_per_week" id="price_per_week" step="0.01" min="0" 
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                       placeholder="300.00" value="{{ old('price_per_week', $equipment->price_per_week) }}">
                            </div>
                            <div>
                                <label for="price_per_month" class="block text-sm font-medium text-green-700">Prix par mois (€)</label>
                                <input type="number" name="price_per_month" id="price_per_month" step="0.01" min="0" 
                                       class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                       placeholder="1000.00" value="{{ old('price_per_month', $equipment->price_per_month) }}">
                            </div>
                        </div>

                        <!-- Livraison disponible -->
                        <div>
                            <div class="flex items-center">
                                <input id="delivery_available" name="delivery_available" type="checkbox" value="1" {{ old('delivery_available', $equipment->delivery_available) ? 'checked' : '' }} 
                                       class="h-4 w-4 text-green-600 border-green-300 rounded focus:ring-green-500">
                                <label for="delivery_available" class="ml-2 block text-sm text-green-900">Livraison disponible</label>
                            </div>
                        </div>

                        <!-- Rayon de livraison -->
                        <div id="delivery-radius-section" style="{{ old('delivery_available', $equipment->delivery_available) ? '' : 'display: none;' }}">
                            <label for="delivery_radius" class="block text-sm font-medium text-green-700">Rayon de livraison (km)</label>
                            <input type="number" name="delivery_radius" id="delivery_radius" min="0" 
                                   value="{{ old('delivery_radius', $equipment->delivery_radius) }}" 
                                   placeholder="Ex: 20" 
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        </div>

                        <!-- Coût de livraison -->
                        <div>
                            <label for="delivery_cost" class="block text-sm font-medium text-green-700">Coût de livraison (€)</label>
                            <input type="number" name="delivery_cost" id="delivery_cost" step="0.01" min="0" 
                                   class="mt-1 block w-full shadow-sm sm:text-sm border-green-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                   placeholder="0.00" value="{{ old('delivery_cost', $equipment->delivery_cost) }}">
                        </div>

                    <!-- Disponibilité -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="available_from" class="block text-sm font-medium text-gray-700 mb-2">
                                Disponible à partir de
                            </label>
                            <input type="date" name="available_from" id="available_from" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   value="{{ old('available_from', $equipment->available_from ? $equipment->available_from->format('Y-m-d') : '') }}">
                            @error('available_from')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="available_until" class="block text-sm font-medium text-gray-700 mb-2">
                                Disponible jusqu'au
                            </label>
                            <input type="date" name="available_until" id="available_until" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   value="{{ old('available_until', $equipment->available_until ? $equipment->available_until->format('Y-m-d') : '') }}">
                            @error('available_until')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Accessoires et conditions -->
                    <div class="mb-4">
                        <label for="accessories" class="block text-sm font-medium text-gray-700 mb-2">
                            Accessoires inclus
                        </label>
                        <textarea name="accessories" id="accessories" rows="2" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Casques, gants, manuel d'utilisation...">{{ old('accessories', $equipment->accessories) }}</textarea>
                        @error('accessories')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="rental_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                            Conditions de location
                        </label>
                        <textarea name="rental_conditions" id="rental_conditions" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Conditions particulières, restrictions d'usage...">{{ old('rental_conditions', $equipment->rental_conditions) }}</textarea>
                        @error('rental_conditions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exigences -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="license_required" id="license_required" value="1" 
                                   {{ old('license_required', $equipment->license_required) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="license_required" class="ml-2 block text-sm text-gray-900">
                                Permis ou certification requis
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_available" id="is_available" value="1" 
                                   {{ old('is_available', $equipment->is_available) ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="is_available" class="ml-2 block text-sm text-gray-900">
                                Équipement disponible immédiatement
                            </label>
                        </div>
                    </div>

                <!-- Détails techniques -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Détails techniques</h2>
                    <div class="space-y-6">
                        <!-- État et statut -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="condition" class="block text-sm font-medium text-green-700 mb-2">
                                    État de l'équipement
                                </label>
                                <select name="condition" id="condition" 
                                        class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Sélectionner l'état</option>
                                    <option value="excellent" {{ old('condition', $equipment->condition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                    <option value="very_good" {{ old('condition', $equipment->condition) == 'very_good' ? 'selected' : '' }}>Très bon</option>
                                    <option value="good" {{ old('condition', $equipment->condition) == 'good' ? 'selected' : '' }}>Bon</option>
                                    <option value="fair" {{ old('condition', $equipment->condition) == 'fair' ? 'selected' : '' }}>Correct</option>
                                    <option value="poor" {{ old('condition', $equipment->condition) == 'poor' ? 'selected' : '' }}>Mauvais</option>
                                </select>
                                @error('condition')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-green-700 mb-2">
                                    Statut
                                </label>
                                <select name="status" id="status" 
                                        class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="active" {{ old('status', $equipment->status) == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status', $equipment->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                                    <option value="rented" {{ old('status', $equipment->status) == 'rented' ? 'selected' : '' }}>Loué</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Localisation détaillée -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-green-700 mb-2">
                                Adresse complète
                            </label>
                            <input type="text" name="address" id="address" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="123 Rue de la République" value="{{ old('address', $equipment->address) }}">
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('prestataire.equipment.show', $equipment) }}" 
                       class="bg-white py-2 px-4 border border-green-300 rounded-md shadow-sm text-sm font-medium text-green-700 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation en temps réel pour le nom
    const nameInput = document.getElementById('name');
    const nameCount = document.getElementById('name-count');
    const nameWarning = document.getElementById('name-warning');
    const nameTip = document.getElementById('name-tip');

    function updateNameValidation() {
        const length = nameInput.value.length;
        nameCount.textContent = length;
        
        // Réinitialiser les styles
        nameInput.classList.remove('border-yellow-400', 'border-green-500', 'border-red-500');
        nameInput.classList.add('border-green-300');
        
        if (length < 10) {
            nameInput.classList.remove('border-green-300');
            nameInput.classList.add('border-yellow-400');
            nameWarning.classList.remove('hidden');
            nameTip.classList.add('hidden');
        } else if (length >= 10 && length <= 70) {
            nameInput.classList.remove('border-green-300');
            nameInput.classList.add('border-green-500');
            nameWarning.classList.add('hidden');
            nameTip.classList.remove('hidden');
        } else {
            nameInput.classList.remove('border-green-300');
            nameInput.classList.add('border-red-500');
            nameWarning.classList.add('hidden');
            nameTip.classList.add('hidden');
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
        descriptionInput.classList.add('border-green-300');
        
        if (length < 50) {
            descriptionInput.classList.remove('border-green-300');
            descriptionInput.classList.add('border-red-500');
            descriptionError.classList.remove('hidden');
            descriptionWarning.classList.add('hidden');
        } else if (length >= 50 && length <= 150) {
            descriptionInput.classList.remove('border-green-300');
            descriptionInput.classList.add('border-yellow-400');
            descriptionError.classList.add('hidden');
            descriptionWarning.classList.remove('hidden');
        } else {
            descriptionInput.classList.remove('border-green-300');
            descriptionInput.classList.add('border-green-500');
            descriptionError.classList.add('hidden');
            descriptionWarning.classList.add('hidden');
        }
    }

    // Événements
    nameInput.addEventListener('input', updateNameValidation);
    descriptionInput.addEventListener('input', updateDescriptionValidation);

    // Initialisation
    updateNameValidation();
    updateDescriptionValidation();

    // Validation lors de la soumission
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
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

@push('scripts')
<script>
// Gestion des catégories et sous-catégories
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    
    // Valeurs existantes de l'équipement
    const currentCategoryId = '{{ old("category_id", $equipment->category_id ?? "") }}';
    const currentSubcategoryId = '{{ old("subcategory_id", $equipment->subcategory_id ?? "") }}';
    
    if (categorySelect) {
        loadMainCategories();
        
        // Gérer le changement de catégorie principale
        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            loadSubcategories(categoryId);
        });
    }
});

// Fonctions pour la gestion des catégories
function loadMainCategories() {
    fetch('/categories/main')
        .then(response => response.json())
        .then(categories => {
            const categorySelect = document.getElementById('category_id');
            const currentCategoryId = '{{ old("category_id", $equipment->category_id ?? "") }}';
            
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
            
            // Charger les sous-catégories si une catégorie est pré-sélectionnée
            if (currentCategoryId) {
                loadSubcategories(currentCategoryId);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des catégories:', error);
        });
}

function loadSubcategories(categoryId) {
    const subcategorySelect = document.getElementById('subcategory_id');
    const subcategoryGroup = document.getElementById('subcategory-group');
    const currentSubcategoryId = '{{ old("subcategory_id", $equipment->subcategory_id ?? "") }}';
    
    if (!categoryId) {
        subcategoryGroup.style.display = 'none';
        subcategorySelect.disabled = true;
        subcategorySelect.innerHTML = '<option value="">Veuillez d\'abord choisir une catégorie</option>';
        return;
    }
    
    fetch(`/api/categories/${categoryId}/subcategories`)
        .then(response => response.json())
        .then(subcategories => {
            subcategorySelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>';
            
            if (subcategories.length > 0) {
                subcategories.forEach(subcategory => {
                    const option = document.createElement('option');
                    option.value = subcategory.id;
                    option.textContent = subcategory.name;
                    if (subcategory.id == currentSubcategoryId) {
                        option.selected = true;
                    }
                    subcategorySelect.appendChild(option);
                });
                subcategoryGroup.style.display = 'block';
                subcategorySelect.disabled = false;
            } else {
                subcategorySelect.innerHTML = '<option value="">Pas de sous-catégorie disponible</option>';
                subcategoryGroup.style.display = 'block';
                subcategorySelect.disabled = true;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des sous-catégories:', error);
            subcategorySelect.innerHTML = '<option value="">Erreur de chargement</option>';
            subcategoryGroup.style.display = 'block';
            subcategorySelect.disabled = true;
        });
}
</script>
@endpush

@endsection