<!-- Étape 3: Description et photos -->
<div class="bg-white rounded-xl shadow-lg border border-red-200 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <div class="flex items-center mb-3 sm:mb-4">
        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold mr-2 sm:mr-3">
            3
        </div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-red-900">Description et photos</h2>
    </div>
    
    <div class="space-y-4 sm:space-y-6">
        <!-- Description -->
        <div>
            <label for="description" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Description détaillée *</label>
            <textarea id="description" name="description" required rows="5" maxlength="2000" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('description') border-red-500 @enderror" placeholder="Décrivez votre produit en détail : caractéristiques, raison de la vente, défauts éventuels...">{{ old('description') }}</textarea>
            <div class="mt-1">
                @error('description')
                    <p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>
                @enderror
                <div id="description-error" class="text-red-500 text-xs sm:text-sm hidden">
                    <p class="font-medium">Description trop courte (minimum 50 caractères)</p>
                    <p class="text-xs mt-1">Structure suggérée : Ce que c'est / Pour qui / Ce qui est inclus / Conditions</p>
                </div>
                <div id="description-warning" class="text-yellow-600 text-xs sm:text-sm hidden">
                    <p>Ajoutez bénéfices, état, accessoires, délais, garanties</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 gap-1 sm:gap-2">
                    <p class="text-red-600 text-xs flex-1">Recommandé : 150–600 caractères</p>
                    <p class="text-gray-500 text-xs flex-shrink-0"><span id="description-count">0</span> caractères</p>
                </div>
            </div>
        </div>
        
        <!-- Conseils pour la description -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
            <h4 class="text-xs sm:text-sm font-semibold text-red-800 mb-2">
                <i class="fas fa-lightbulb mr-1 sm:mr-2 text-xs sm:text-sm"></i>Conseils pour une bonne description
            </h4>
            <ul class="text-xs sm:text-sm text-red-700 space-y-1">
                <li>• <strong>Soyez précis :</strong> Marque, modèle, année, dimensions</li>
                <li>• <strong>État réel :</strong> Mentionnez les défauts ou usures</li>
                <li>• <strong>Raison de vente :</strong> Déménagement, changement, etc.</li>
                <li>• <strong>Accessoires inclus :</strong> Boîte, notice, garantie</li>
                <li>• <strong>Urgence :</strong> Précisez pourquoi c'est urgent</li>
            </ul>
        </div>
        
        <!-- Photos -->
        <div>
            <h3 class="text-base sm:text-lg font-semibold text-red-900 mb-2 sm:mb-3">Photos</h3>
            <div class="border-2 border-dashed border-red-300 rounded-lg p-3 sm:p-4 md:p-6 text-center hover:border-red-400 transition-colors">
                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('photos').click()">
                    <i class="fas fa-cloud-upload-alt text-red-400 text-2xl sm:text-3xl md:text-4xl mb-2 sm:mb-3 md:mb-4"></i>
                    <p class="text-red-600 mb-1 sm:mb-2 text-xs sm:text-sm md:text-base">Cliquez pour ajouter des photos ou glissez-déposez</p>
                    <p class="text-red-500 text-xs">Maximum 5 photos, 5MB par photo</p>
                </div>
                
                <!-- Prévisualisation des images -->
                <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-4 mt-3 sm:mt-4 hidden"></div>
            </div>
            @error('photos')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @error('photos.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Conseils pour les photos -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
            <h4 class="text-xs sm:text-sm font-semibold text-red-800 mb-2">
                <i class="fas fa-camera mr-1 sm:mr-2 text-xs sm:text-sm"></i>Conseils pour de bonnes photos
            </h4>
            <ul class="text-xs sm:text-sm text-red-700 space-y-1">
                <li>• <strong>Éclairage :</strong> Prenez les photos en pleine lumière</li>
                <li>• <strong>Angles multiples :</strong> Vue d'ensemble, détails, défauts</li>
                <li>• <strong>Netteté :</strong> Évitez les photos floues</li>
                <li>• <strong>Contexte :</strong> Montrez l'objet en situation d'usage</li>
                <li>• <strong>Honnêteté :</strong> N'hésitez pas à montrer les défauts</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validation en temps réel pour la description
const descriptionInput = document.getElementById('description');
const descriptionCount = document.getElementById('description-count');
const descriptionError = document.getElementById('description-error');
const descriptionWarning = document.getElementById('description-warning');

function validateDescription() {
    const length = descriptionInput.value.length;
    descriptionCount.textContent = length;
    
    // Réinitialiser les styles
    descriptionInput.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
    descriptionError.classList.add('hidden');
    descriptionWarning.classList.add('hidden');
    
    if (length < 50) {
        descriptionInput.classList.add('border-red-500');
        descriptionError.classList.remove('hidden');
    } else if (length < 150) {
        descriptionInput.classList.add('border-yellow-500');
        descriptionWarning.classList.remove('hidden');
    } else {
        descriptionInput.classList.add('border-green-500');
    }
}

descriptionInput.addEventListener('input', validateDescription);
descriptionInput.addEventListener('keyup', validateDescription);

// Prévisualisation des images
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    const uploadArea = document.getElementById('upload-area');
    
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        uploadArea.classList.add('hidden');
        
        // Limiter à 5 images
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
                    preview.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
        
        // Ajouter un bouton pour ajouter plus d'images si moins de 5
        if (files.length < 5) {
            const addMore = document.createElement('div');
            addMore.className = 'flex items-center justify-center h-24 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 transition-colors';
            addMore.innerHTML = '<i class="fas fa-plus text-gray-400 text-xl"></i>';
            addMore.onclick = () => document.getElementById('photos').click();
            preview.appendChild(addMore);
        }
    } else {
        preview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    }
}

function removeImage(index) {
    const input = document.getElementById('photos');
    const dt = new DataTransfer();
    
    for (let i = 0; i < input.files.length; i++) {
        if (i !== index) {
            dt.items.add(input.files[i]);
        }
    }
    
    input.files = dt.files;
    previewImages(input);
}

// Initialiser la validation de la description au chargement
document.addEventListener('DOMContentLoaded', function() {
    validateDescription();
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