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
                            <label for="name" class="block text-sm font-medium text-green-700 mb-2">Nom de l'équipement *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $equipment->name) }}" required 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-green-700 mb-2">Description détaillée *</label>
                            <textarea id="description" name="description" required rows="6" 
                                      class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-500 @enderror" 
                                      placeholder="Décrivez en détail votre équipement, ses caractéristiques et ce qui le rend unique...">{{ old('description', $equipment->description) }}</textarea>
                            <div class="mt-1">
                                @error('description')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-green-600 text-sm">Recommandé : 150–600 caractères</p>
                                </div>
                            </div>
                        </div>

                        <!-- Spécifications techniques -->
                        <div>
                            <label for="technical_specifications" class="block text-sm font-medium text-green-700 mb-2">Spécifications techniques</label>
                            <textarea id="technical_specifications" name="technical_specifications" rows="3" 
                                      placeholder="Dimensions, poids, puissance, capacité..." 
                                      class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('technical_specifications') border-red-500 @enderror">{{ old('technical_specifications', $equipment->technical_specifications) }}</textarea>
                            @error('technical_specifications')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Catégorie de l'équipement</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-green-700 mb-2">Catégorie principale *</label>
                            <select id="category_id" name="category_id" required class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Sélectionnez une catégorie principale</option>
                                @if(isset($categories))
                                    @foreach($categories->whereNull('parent_id') as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $equipment->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div id="subcategory-group" style="display: none;">
                            <label for="subcategory_id" class="block text-sm font-medium text-green-700 mb-2">Sous-catégorie</label>
                            <select id="subcategory_id" name="subcategory_id" class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" disabled>
                                <option value="">Veuillez d'abord choisir une catégorie</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Livraison -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Livraison</h2>
                    <div class="space-y-6">
                        <!-- Livraison disponible -->
                        <div>
                            <label for="delivery_available" class="inline-flex items-center">
                                <input id="delivery_available" name="delivery_available" type="checkbox" value="1" {{ old('delivery_available', $equipment->delivery_available) ? 'checked' : '' }} 
                                       class="rounded border-green-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-green-600">Livraison disponible pour cet équipement</span>
                            </label>
                        </div>

                        <!-- Rayon de livraison -->
                        <div id="delivery-radius-section" style="{{ old('delivery_available', $equipment->delivery_available) ? '' : 'display: none;' }}">
                            <label for="delivery_radius" class="block text-sm font-medium text-green-700 mb-2">Rayon de livraison (km)</label>
                            <input type="number" name="delivery_radius" id="delivery_radius" min="0" 
                                   value="{{ old('delivery_radius', $equipment->delivery_radius) }}" 
                                   placeholder="Ex: 20" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('delivery_radius') border-red-500 @enderror">
                            @error('delivery_radius')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Coût de livraison -->
                        <div>
                            <label for="delivery_cost" class="block text-sm font-medium text-green-700 mb-2">Coût de livraison (€)</label>
                            <input type="number" name="delivery_cost" id="delivery_cost" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('delivery_cost') border-red-500 @enderror"
                                   placeholder="0.00" value="{{ old('delivery_cost', $equipment->delivery_cost) }}">
                            @error('delivery_cost')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Photos</h2>
                        
                    <!-- Photos actuelles -->
                    @if(is_array($equipment->photos) && count($equipment->photos) > 0)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-green-700 mb-3">Images actuelles</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="existing-photos">
                            @foreach($equipment->photos as $index => $photo)
                                <div class="relative group" id="photo-container-{{ $index }}">
                                    <img src="{{ Storage::url($photo) }}" alt="Photo équipement" class="rounded-lg object-cover h-32 w-full border border-green-200">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
                                        <button type="button" data-photo-index="{{ $index }}" class="delete-photo-btn text-white p-2 rounded-full bg-red-500 hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                        
                    <!-- Zone d'upload -->
                    <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center bg-green-50 hover:border-green-400 transition-colors">
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden">
                        <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('photos').click()">
                            <i class="fas fa-cloud-upload-alt text-green-400 text-4xl mb-4"></i>
                            <p class="text-green-600 mb-2">Cliquez pour ajouter des photos ou glissez-déposez</p>
                            <p class="text-green-500 text-sm">Maximum 5 photos, 5MB par photo</p>
                        </div>
                        <div id="photo-preview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-4 hidden"></div>
                    </div>
                    @error('photos')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarification -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Tarification</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="daily_rate" class="block text-sm font-medium text-green-700 mb-2">Prix par jour (€) *</label>
                            <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', $equipment->daily_rate) }}" required 
                                   min="0" step="0.01" placeholder="50" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('daily_rate') border-red-500 @enderror">
                            @error('daily_rate')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="deposit_amount" class="block text-sm font-medium text-green-700 mb-2">Caution (€) *</label>
                            <input type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount', $equipment->deposit_amount) }}" required 
                                   min="0" step="0.01" placeholder="100" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('deposit_amount') border-red-500 @enderror">
                            @error('deposit_amount')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Autres prix -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div>
                            <label for="price_per_hour" class="block text-sm font-medium text-green-700 mb-2">Prix par heure (€)</label>
                            <input type="number" name="price_per_hour" id="price_per_hour" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('price_per_hour') border-red-500 @enderror"
                                   placeholder="10.00" value="{{ old('price_per_hour', $equipment->price_per_hour) }}">
                            @error('price_per_hour')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price_per_week" class="block text-sm font-medium text-green-700 mb-2">Prix par semaine (€)</label>
                            <input type="number" name="price_per_week" id="price_per_week" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('price_per_week') border-red-500 @enderror"
                                   placeholder="300.00" value="{{ old('price_per_week', $equipment->price_per_week) }}">
                            @error('price_per_week')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price_per_month" class="block text-sm font-medium text-green-700 mb-2">Prix par mois (€)</label>
                            <input type="number" name="price_per_month" id="price_per_month" step="0.01" min="0" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('price_per_month') border-red-500 @enderror"
                                   placeholder="1000.00" value="{{ old('price_per_month', $equipment->price_per_month) }}">
                            @error('price_per_month')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Disponibilité -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Disponibilité</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="available_from" class="block text-sm font-medium text-green-700 mb-2">
                                Disponible à partir de
                            </label>
                            <input type="date" name="available_from" id="available_from" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('available_from') border-red-500 @enderror"
                                   value="{{ old('available_from', $equipment->available_from ? $equipment->available_from->format('Y-m-d') : '') }}">
                            @error('available_from')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="available_until" class="block text-sm font-medium text-green-700 mb-2">
                                Disponible jusqu'au
                            </label>
                            <input type="date" name="available_until" id="available_until" 
                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('available_until') border-red-500 @enderror"
                                   value="{{ old('available_until', $equipment->available_until ? $equipment->available_until->format('Y-m-d') : '') }}">
                            @error('available_until')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Accessoires et conditions -->
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                    <h2 class="text-xl font-bold text-green-900 mb-4 border-b border-green-200 pb-2">Accessoires et conditions</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="accessories" class="block text-sm font-medium text-green-700 mb-2">
                                Accessoires inclus
                            </label>
                            <textarea name="accessories" id="accessories" rows="2" 
                                    class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('accessories') border-red-500 @enderror"
                                    placeholder="Casques, gants, manuel d'utilisation...">{{ old('accessories', $equipment->accessories) }}</textarea>
                            @error('accessories')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rental_conditions" class="block text-sm font-medium text-green-700 mb-2">
                                Conditions de location
                            </label>
                            <textarea name="rental_conditions" id="rental_conditions" rows="3" 
                                    class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('rental_conditions') border-red-500 @enderror"
                                    placeholder="Conditions particulières, restrictions d'usage...">{{ old('rental_conditions', $equipment->rental_conditions) }}</textarea>
                            @error('rental_conditions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
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
document.addEventListener('DOMContentLoaded', function () {
    // Photo preview functionality
    const photoInput = document.getElementById('photos');
    const previewContainer = document.getElementById('photo-preview');
    const uploadArea = document.getElementById('upload-area');
    let existingFiles = [];
    let isAddingMore = false;

    // Preview photos
    window.previewPhotos = function(input) {
        // Combine existing files with new ones when adding more
        if (isAddingMore && existingFiles.length > 0) {
            const newFiles = Array.from(input.files);
            const combinedFiles = new DataTransfer();
            
            // Add existing files first
            existingFiles.forEach(file => {
                if (combinedFiles.files.length < 5) {
                    combinedFiles.items.add(file);
                }
            });
            
            // Add new files
            newFiles.forEach(file => {
                if (combinedFiles.files.length < 5) {
                    combinedFiles.items.add(file);
                }
            });
            
            input.files = combinedFiles.files;
            isAddingMore = false;
        }
        
        // Store current files
        existingFiles = Array.from(input.files);
        
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
                            <img src="${e.target.result}" alt="Preview" class="rounded-lg object-cover h-32 w-full border border-green-200">
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="removePhoto(${index})" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            // Add "Add more" button if under limit
            if (files.length < 5) {
                const addMore = document.createElement('div');
                addMore.className = 'flex items-center justify-center h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 transition-colors';
                addMore.innerHTML = '<i class="fas fa-plus text-gray-400 text-xl"></i>';
                addMore.onclick = () => {
                    isAddingMore = true;
                    photoInput.click();
                };
                previewContainer.appendChild(addMore);
            }
        } else {
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        }
    }

    window.removePhoto = function(index) {
        const dt = new DataTransfer();
        const files = photoInput.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        photoInput.files = dt.files;
        existingFiles = Array.from(photoInput.files);
        previewPhotos(photoInput);
    }

    // Single event listener for file input changes
    if (photoInput) {
        photoInput.addEventListener('change', function() {
            previewPhotos(this);
        });
    }

    // Delete existing photos
    const deletePhotoButtons = document.querySelectorAll('.delete-photo-btn');
    deletePhotoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const photoIndex = this.dataset.photoIndex;
            if (confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')) {
                fetch(`/prestataire/equipment/{{ $equipment->id }}/photos/${photoIndex}`, {
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
                        const photoElement = document.getElementById(`photo-container-${photoIndex}`);
                        if (photoElement) {
                            photoElement.remove();
                        }
                    } else {
                        alert(data.message || 'Erreur lors de la suppression de la photo.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur: ' + error.message);
                });
            }
        });
    });

    // Validation lors de la soumission
    const form = document.querySelector('form');
    const descriptionInput = document.getElementById('description');
    
    if (form && descriptionInput) {
        form.addEventListener('submit', function(e) {
            if (descriptionInput.value.length < 50) {
                e.preventDefault();
                alert('La description doit contenir au moins 50 caractères.');
                descriptionInput.focus();
                return false;
            }
        });
    }
    
    // Gestion des catégories et sous-catégories
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
</script>
@endpush
