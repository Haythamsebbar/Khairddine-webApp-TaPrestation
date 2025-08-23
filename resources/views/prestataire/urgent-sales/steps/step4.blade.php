<!-- Étape 4: Révision et publication -->
<div class="bg-white rounded-xl shadow-lg border border-red-200 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <div class="flex items-center mb-3 sm:mb-4">
        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold mr-2 sm:mr-3">
            4
        </div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-red-900">Révision et publication</h2>
    </div>
    
    <div class="space-y-4 sm:space-y-6">
        <!-- Message de confirmation -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 text-lg sm:text-xl mr-2 sm:mr-3"></i>
                <div>
                    <h3 class="text-green-800 font-semibold text-sm sm:text-base">Votre annonce est prête !</h3>
                    <p class="text-green-700 text-xs sm:text-sm mt-1">Vérifiez les informations ci-dessous avant de publier.</p>
                </div>
            </div>
        </div>
        
        <!-- Résumé des informations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Informations principales -->
            <div class="space-y-3 sm:space-y-4">
                <h3 class="text-base sm:text-lg font-semibold text-red-900 border-b border-red-200 pb-2">
                    <i class="fas fa-info-circle mr-1 sm:mr-2 text-sm sm:text-base"></i>Informations principales
                </h3>
                
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">Titre :</span>
                        <span id="review-title" class="text-xs sm:text-sm text-gray-900 font-medium break-words">-</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">Prix :</span>
                        <span id="review-price" class="text-xs sm:text-sm text-red-600 font-bold">-</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">État :</span>
                        <span id="review-condition" class="text-xs sm:text-sm text-gray-900">-</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">Quantité :</span>
                        <span id="review-quantity" class="text-xs sm:text-sm text-gray-900">-</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">Catégorie :</span>
                        <span id="review-category" class="text-xs sm:text-sm text-gray-900 break-words">-</span>
                    </div>
                    
                    <div id="review-subcategory-container" class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4 hidden">
                        <span class="text-xs sm:text-sm font-medium text-gray-600 flex-shrink-0">Sous-catégorie :</span>
                        <span id="review-subcategory" class="text-xs sm:text-sm text-gray-900 break-words">-</span>
                    </div>
                </div>
            </div>
            
            <!-- Localisation -->
            <div class="space-y-3 sm:space-y-4">
                <h3 class="text-base sm:text-lg font-semibold text-red-900 border-b border-red-200 pb-2">
                    <i class="fas fa-map-marker-alt mr-1 sm:mr-2 text-sm sm:text-base"></i>Localisation
                </h3>
                
                <div class="space-y-2 sm:space-y-3">
                    <div>
                        <span class="text-xs sm:text-sm font-medium text-gray-600 block mb-1">Adresse :</span>
                        <span id="review-location" class="text-xs sm:text-sm text-gray-900 block break-words">-</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="space-y-3 sm:space-y-4">
            <h3 class="text-base sm:text-lg font-semibold text-red-900 border-b border-red-200 pb-2">
                <i class="fas fa-align-left mr-1 sm:mr-2 text-sm sm:text-base"></i>Description
            </h3>
            
            <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                <p id="review-description" class="text-xs sm:text-sm text-gray-900 whitespace-pre-wrap break-words">-</p>
            </div>
        </div>
        
        <!-- Photos -->
        <div class="space-y-3 sm:space-y-4">
            <h3 class="text-base sm:text-lg font-semibold text-red-900 border-b border-red-200 pb-2">
                <i class="fas fa-images mr-1 sm:mr-2 text-sm sm:text-base"></i>Photos
            </h3>
            
            <div id="review-photos" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-4">
                <!-- Les photos seront ajoutées dynamiquement -->
            </div>
            
            <div id="no-photos" class="text-center py-6 sm:py-8 text-gray-500">
                <i class="fas fa-image text-2xl sm:text-3xl mb-2"></i>
                <p class="text-xs sm:text-sm">Aucune photo ajoutée</p>
            </div>
        </div>
        
        <!-- Actions de modification -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
            <h4 class="text-xs sm:text-sm font-semibold text-red-800 mb-2 sm:mb-3">
                <i class="fas fa-edit mr-1 sm:mr-2 text-xs sm:text-sm"></i>Besoin de modifier quelque chose ?
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                <button type="button" onclick="goToStep(1)" class="text-xs bg-white border border-red-300 text-red-700 px-2 sm:px-3 py-2 rounded-md hover:bg-red-50 transition-colors">
                    <i class="fas fa-info-circle mr-1 text-xs"></i><span class="hidden sm:inline">Informations</span><span class="sm:hidden">Info</span>
                </button>
                <button type="button" onclick="goToStep(2)" class="text-xs bg-white border border-red-300 text-red-700 px-2 sm:px-3 py-2 rounded-md hover:bg-red-50 transition-colors">
                    <i class="fas fa-map-marker-alt mr-1 text-xs"></i><span class="hidden sm:inline">Localisation</span><span class="sm:hidden">Lieu</span>
                </button>
                <button type="button" onclick="goToStep(3)" class="text-xs bg-white border border-red-300 text-red-700 px-2 sm:px-3 py-2 rounded-md hover:bg-red-50 transition-colors">
                    <i class="fas fa-align-left mr-1 text-xs"></i><span class="hidden sm:inline">Description</span><span class="sm:hidden">Desc</span>
                </button>
                <button type="button" onclick="goToStep(3)" class="text-xs bg-white border border-red-300 text-red-700 px-2 sm:px-3 py-2 rounded-md hover:bg-red-50 transition-colors">
                    <i class="fas fa-images mr-1 text-xs"></i>Photos
                </button>
            </div>
        </div>
        
        <!-- Conditions et publication -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
            <h4 class="text-xs sm:text-sm font-semibold text-yellow-800 mb-2 sm:mb-3">
                <i class="fas fa-exclamation-triangle mr-1 sm:mr-2 text-xs sm:text-sm"></i>Avant de publier
            </h4>
            <div class="space-y-2 sm:space-y-3">
                <label class="flex items-start space-x-2 sm:space-x-3">
                    <input type="checkbox" id="terms-checkbox" class="mt-0.5 sm:mt-1 rounded border-yellow-300 text-red-600 focus:ring-red-500 flex-shrink-0">
                    <span class="text-xs sm:text-sm text-yellow-800">
                        Je certifie que les informations fournies sont exactes et que je respecte les 
                        <a href="#" class="text-red-600 hover:text-red-800 underline">conditions d'utilisation</a>
                    </span>
                </label>
                
                <label class="flex items-start space-x-2 sm:space-x-3">
                    <input type="checkbox" id="contact-checkbox" class="mt-0.5 sm:mt-1 rounded border-yellow-300 text-red-600 focus:ring-red-500 flex-shrink-0">
                    <span class="text-xs sm:text-sm text-yellow-800">
                        J'accepte d'être contacté par les acheteurs intéressés
                    </span>
                </label>
            </div>
        </div>
        
        
    </div>
</div>

@push('scripts')
<script>
// Fonction pour aller à une étape spécifique
function goToStep(step) {
    currentStep = step;
    showStep(currentStep);
}

// Mettre à jour l'aperçu des photos
function updatePhotoReview() {
    const photosInput = document.getElementById('photos');
    const reviewPhotos = document.getElementById('review-photos');
    const noPhotos = document.getElementById('no-photos');
    
    reviewPhotos.innerHTML = '';
    
    if (photosInput && photosInput.files && photosInput.files.length > 0) {
        noPhotos.classList.add('hidden');
        reviewPhotos.classList.remove('hidden');
        
        Array.from(photosInput.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-gray-200">
                        <div class="absolute top-1 right-1 bg-red-600 text-white text-xs px-1 rounded">${index + 1}</div>
                    `;
                    reviewPhotos.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        noPhotos.classList.remove('hidden');
        reviewPhotos.classList.add('hidden');
    }
}

// Validation des cases à cocher
function validateCheckboxes() {
    const termsCheckbox = document.getElementById('terms-checkbox');
    const contactCheckbox = document.getElementById('contact-checkbox');
    const publishBtn = document.getElementById('final-publish-btn');
    
    if (termsCheckbox && contactCheckbox && publishBtn) {
        const isValid = termsCheckbox.checked && contactCheckbox.checked;
        publishBtn.disabled = !isValid;
    }
}

// Événements pour les cases à cocher
document.addEventListener('DOMContentLoaded', function() {
    const termsCheckbox = document.getElementById('terms-checkbox');
    const contactCheckbox = document.getElementById('contact-checkbox');
    const finalPublishBtn = document.getElementById('final-publish-btn');
    
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', validateCheckboxes);
    }
    
    if (contactCheckbox) {
        contactCheckbox.addEventListener('change', validateCheckboxes);
    }
    
    // Événement pour le bouton de publication final
    if (finalPublishBtn) {
        finalPublishBtn.addEventListener('click', function() {
            if (!this.disabled) {
                // Soumettre le formulaire
                document.getElementById('urgent-sale-form').submit();
            }
        });
    }
});

// Fonction appelée quand on arrive sur l'étape 4
function updateReviewStep() {
    // Mettre à jour les informations de base
    const titleEl = document.getElementById('title');
    const priceEl = document.getElementById('price');
    const conditionEl = document.getElementById('condition');
    const parentCategoryEl = document.getElementById('parent_category_id');
    const categoryEl = document.getElementById('category_id');
    const quantityEl = document.getElementById('quantity');
    const locationEl = document.getElementById('selectedAddress');
    const descriptionEl = document.getElementById('description');
    
    if (titleEl) document.getElementById('review-title').textContent = titleEl.value || '-';
    if (priceEl) document.getElementById('review-price').textContent = priceEl.value ? priceEl.value + ' €' : '-';
    if (conditionEl && conditionEl.selectedOptions[0]) {
        document.getElementById('review-condition').textContent = conditionEl.selectedOptions[0].text;
    }
    if (parentCategoryEl && parentCategoryEl.selectedOptions[0]) {
        document.getElementById('review-category').textContent = parentCategoryEl.selectedOptions[0].text;
    }
    if (quantityEl) document.getElementById('review-quantity').textContent = quantityEl.value || '-';
    if (locationEl) document.getElementById('review-location').textContent = locationEl.value || '-';
    if (descriptionEl) document.getElementById('review-description').textContent = descriptionEl.value || '-';
    
    // Sous-catégorie (optionnelle)
    if (categoryEl && categoryEl.value && categoryEl.selectedOptions[0]) {
        document.getElementById('review-subcategory').textContent = categoryEl.selectedOptions[0].text;
        document.getElementById('review-subcategory-container').classList.remove('hidden');
    } else {
        document.getElementById('review-subcategory-container').classList.add('hidden');
    }
    
    // Mettre à jour l'aperçu des photos
    updatePhotoReview();
    
    // Réinitialiser les cases à cocher
    const termsCheckbox = document.getElementById('terms-checkbox');
    const contactCheckbox = document.getElementById('contact-checkbox');
    if (termsCheckbox) termsCheckbox.checked = false;
    if (contactCheckbox) contactCheckbox.checked = false;
    validateCheckboxes();
}
</script>
@endpush