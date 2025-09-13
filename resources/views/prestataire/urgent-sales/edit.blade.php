@extends('layouts.app')

@section('title', 'Modifier la vente - ' . $urgentSale->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center mb-6">
            <a href="{{ route('prestataire.urgent-sales.show', $urgentSale) }}" class="text-red-600 hover:text-red-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Modifier l'annonce</h1>
                <p class="text-gray-600 mt-1">{{ $urgentSale->title }}</p>
            </div>
        </div>
        
        <!-- Formulaire -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('prestataire.urgent-sales.update', $urgentSale) }}" method="POST" enctype="multipart/form-data" id="urgent-sale-form">
                @csrf
                @method('PUT')
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Titre -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre de la vente <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $urgentSale->title) }}" required maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('title') border-red-500 @enderror">
                            <div class="flex justify-between items-center mt-1">
                                <div>
                                    @error('title')
                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                    <p id="title-warning" class="text-yellow-600 text-sm hidden">Titre trop court, précisez la marque ou le modèle</p>
                                    <p id="title-tip" class="text-red-600 text-sm">Idéal : 5–9 mots, incluez marque et état</p>
                                </div>
                                <p class="text-gray-500 text-sm"><span id="title-count">0</span>/70</p>
                            </div>
                        </div>
                        
                        <!-- Prix -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Prix (€) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" name="price" value="{{ old('price', $urgentSale->price) }}" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('price') border-red-500 @enderror">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- État -->
                        <div>
                            <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                                État <span class="text-red-500">*</span>
                            </label>
                            <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('condition') border-red-500 @enderror">
                                <option value="">Sélectionner un état</option>
                                <option value="new" {{ old('condition', $urgentSale->condition) === 'new' ? 'selected' : '' }}>Neuf</option>
                                <option value="like_new" {{ old('condition', $urgentSale->condition) === 'like_new' ? 'selected' : '' }}>Comme neuf</option>
                                <option value="very_good" {{ old('condition', $urgentSale->condition) === 'very_good' ? 'selected' : '' }}>Très bon état</option>
                                <option value="good" {{ old('condition', $urgentSale->condition) === 'good' ? 'selected' : '' }}>Bon état</option>
                                <option value="fair" {{ old('condition', $urgentSale->condition) === 'fair' ? 'selected' : '' }}>État correct</option>
                                <option value="poor" {{ old('condition', $urgentSale->condition) === 'poor' ? 'selected' : '' }}>Mauvais état</option>
                            </select>
                            @error('condition')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Quantité -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantité <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $urgentSale->quantity) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('quantity') border-red-500 @enderror">
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Localisation -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                Localisation <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="location" name="location" value="{{ old('location', $urgentSale->location) }}" required maxlength="255" placeholder="Ville, département" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('location') border-red-500 @enderror">
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" required rows="6" maxlength="2000" placeholder="Décrivez votre article en détail..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $urgentSale->description) }}</textarea>
                            <div class="mt-1">
                                @error('description')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <div id="description-error" class="text-red-500 text-sm hidden">
                                    <p class="font-medium">Description trop courte (minimum 50 caractères)</p>
                                    <p class="text-xs mt-1">Structure suggérée : État / Marque-modèle / Raison de vente / Défauts éventuels</p>
                                </div>
                                <div id="description-warning" class="text-yellow-600 text-sm hidden">
                                    <p>Ajoutez détails sur l'état, accessoires, historique d'achat</p>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-red-600 text-sm">Recommandé : 150–500 caractères</p>
                                    <p class="text-gray-500 text-sm"><span id="description-count">0</span> caractères</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photos -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Photos
                            </label>
                            
                            <!-- Photos actuelles -->
                            @if(is_array($urgentSale->photos) && count($urgentSale->photos) > 0)
                            <div class="mb-4">
                                <h3 class="text-sm font-medium text-gray-700 mb-3">Images actuelles</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="existing-photos">
                                    @foreach($urgentSale->photos as $index => $photo)
                                        <div class="relative group" id="photo-container-{{ $index }}">
                                            <img src="{{ Storage::url($photo) }}" alt="Photo annonce" class="rounded-lg object-cover h-32 w-full border border-gray-200">
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
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:border-gray-400 transition-colors">
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden">
                                <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('photos').click()">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-600 mb-2">Cliquez pour ajouter des photos ou glissez-déposez</p>
                                    <p class="text-gray-500 text-sm">Maximum 5 photos, 5MB par photo</p>
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
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center rounded-b-lg">
                    <a href="{{ route('prestataire.urgent-sales.show', $urgentSale) }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        Annuler
                    </a>
                    
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-check mr-2"></i>Mettre à jour et publier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

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
                            <img src="${e.target.result}" alt="Preview" class="rounded-lg object-cover h-32 w-full border border-gray-200">
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
                // Note: For urgent sales, we would need a specific route for photo deletion
                // This is a simplified implementation - in a real application, you would 
                // implement a proper photo deletion endpoint
                const photoElement = document.getElementById(`photo-container-${photoIndex}`);
                if (photoElement) {
                    photoElement.remove();
                }
            }
        });
    });

    // Compteur de caractères pour la description
    const descriptionTextarea = document.getElementById('description');
    const descriptionCount = document.getElementById('description-count');

    function updateDescriptionCount() {
        const count = descriptionTextarea.value.length;
        descriptionCount.textContent = `${count}/2000`;
        
        if (count > 1800) {
            descriptionCount.classList.add('text-red-500');
            descriptionCount.classList.remove('text-gray-500');
        } else {
            descriptionCount.classList.add('text-gray-500');
            descriptionCount.classList.remove('text-red-500');
        }
    }

    descriptionTextarea.addEventListener('input', updateDescriptionCount);

    // Initialiser le compteur
    updateDescriptionCount();

    // Validation du formulaire
    document.getElementById('urgent-sale-form').addEventListener('submit', function(e) {
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
            titleInput.classList.add('border-gray-300');
            
            if (length < 10) {
                titleInput.classList.remove('border-gray-300');
                titleInput.classList.add('border-yellow-400');
                titleWarning.classList.remove('hidden');
                titleTip.classList.add('hidden');
            } else if (length >= 10 && length <= 70) {
                titleInput.classList.remove('border-gray-300');
                titleInput.classList.add('border-green-500');
                titleWarning.classList.add('hidden');
                titleTip.classList.remove('hidden');
            } else {
                titleInput.classList.remove('border-gray-300');
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
            descriptionInput.classList.add('border-gray-300');
            
            if (length < 50) {
                descriptionInput.classList.remove('border-gray-300');
                descriptionInput.classList.add('border-red-500');
                descriptionError.classList.remove('hidden');
                descriptionWarning.classList.add('hidden');
            } else if (length >= 50 && length <= 150) {
                descriptionInput.classList.remove('border-gray-300');
                descriptionInput.classList.add('border-yellow-400');
                descriptionError.classList.add('hidden');
                descriptionWarning.classList.remove('hidden');
            } else {
                descriptionInput.classList.remove('border-gray-300');
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
    });

    // Validation supplémentaire lors de la soumission pour la description
    document.getElementById('urgent-sale-form').addEventListener('submit', function(e) {
        const descriptionInput = document.getElementById('description');
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