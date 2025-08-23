<!-- Étape 1: Informations de base -->
<div class="bg-white rounded-xl shadow-lg border border-red-200 p-3 sm:p-4 md:p-6 mb-4 sm:mb-6">
    <div class="flex items-center mb-3 sm:mb-4">
        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-xs sm:text-sm font-bold mr-2 sm:mr-3">
            1
        </div>
        <h2 class="text-base sm:text-lg md:text-xl font-bold text-red-900">Informations de base</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6 lg:gap-8">
        <!-- Titre -->
        <div class="md:col-span-2">
            <label for="title" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Titre de la vente *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="255" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('title') border-red-500 @enderror">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 gap-1 sm:gap-2">
                <div class="flex-1">
                    @error('title')
                        <p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                    <p id="title-warning" class="text-yellow-600 text-xs sm:text-sm hidden">Titre trop court, précisez l'usage ou le modèle</p>
                    <p id="title-tip" class="text-red-600 text-xs">Idéal : 5–9 mots, sans abréviations</p>
                </div>
                <p class="text-gray-500 text-xs sm:text-sm flex-shrink-0"><span id="title-count">0</span>/70</p>
            </div>
        </div>
        
        <!-- Prix -->
        <div>
            <label for="price" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Prix (€) *</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('price') border-red-500 @enderror">
            @error('price')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- État -->
        <div>
            <label for="condition" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">État *</label>
            <select id="condition" name="condition" required class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('condition') border-red-500 @enderror">
                <option value="">Sélectionner l'état</option>
                <option value="excellent" {{ old('condition') === 'excellent' ? 'selected' : '' }}>Excellent</option>
                <option value="very_good" {{ old('condition') === 'very_good' ? 'selected' : '' }}>Très bon</option>
                <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>Bon état</option>
                <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>État correct</option>
                <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>Mauvais état</option>
            </select>
            @error('condition')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Catégorie principale -->
        <div>
            <label for="parent_category_id" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Catégorie principale *</label>
            <select id="parent_category_id" name="parent_category_id" required class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('parent_category_id') border-red-500 @enderror">
                <option value="">Choisissez une catégorie principale</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('parent_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('parent_category_id')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Sous-catégorie -->
        <div>
            <label for="category_id" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Sous-catégorie</label>
            <select id="category_id" name="category_id" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('category_id') border-red-500 @enderror" disabled>
                <option value="">Sélectionnez d'abord une catégorie principale</option>
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Quantité -->
        <div class="md:col-span-2">
            <label for="quantity" class="block text-xs sm:text-sm font-medium text-red-700 mb-1 sm:mb-2">Quantité *</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required min="1" class="w-full px-2 sm:px-3 py-2 text-sm sm:text-base border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('quantity') border-red-500 @enderror">
            @error('quantity')
                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
// Validation en temps réel pour le titre
const titleInput = document.getElementById('title');
const titleCount = document.getElementById('title-count');
const titleWarning = document.getElementById('title-warning');

function validateTitle() {
    const length = titleInput.value.length;
    titleCount.textContent = length;
    
    // Réinitialiser les styles
    titleInput.classList.remove('border-red-500', 'border-yellow-500', 'border-green-500');
    
    if (length < 10) {
        titleInput.classList.add('border-yellow-500');
        titleWarning.classList.remove('hidden');
    } else if (length <= 70) {
        titleInput.classList.add('border-green-500');
        titleWarning.classList.add('hidden');
    } else {
        titleInput.classList.add('border-red-500');
        titleWarning.classList.add('hidden');
    }
}

titleInput.addEventListener('input', validateTitle);
titleInput.addEventListener('keyup', validateTitle);

// Gestion des catégories et sous-catégories
const categoriesData = @json($categories->mapWithKeys(function($category) {
    return [$category->id => $category->children];
}));

document.getElementById('parent_category_id').addEventListener('change', function() {
    const parentCategoryId = this.value;
    const subcategorySelect = document.getElementById('category_id');
    
    // Réinitialiser le select des sous-catégories
    subcategorySelect.innerHTML = '<option value="">Choisissez une sous-catégorie</option>';
    
    if (parentCategoryId && categoriesData[parentCategoryId]) {
        const subcategories = categoriesData[parentCategoryId];
        
        if (subcategories.length > 0) {
            subcategorySelect.disabled = false;
            subcategories.forEach(function(subcategory) {
                const option = document.createElement('option');
                option.value = subcategory.id;
                option.textContent = subcategory.name;
                if ('{{ old("category_id") }}' == subcategory.id) {
                    option.selected = true;
                }
                subcategorySelect.appendChild(option);
            });
        } else {
            subcategorySelect.innerHTML = '<option value="">Aucune sous-catégorie disponible</option>';
            subcategorySelect.disabled = true;
        }
    } else {
        subcategorySelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie principale</option>';
        subcategorySelect.disabled = true;
    }
});

// Initialiser les sous-catégories si une catégorie principale est déjà sélectionnée
if (document.getElementById('parent_category_id').value) {
    document.getElementById('parent_category_id').dispatchEvent(new Event('change'));
}

// Initialiser le compteur de titre au chargement
document.addEventListener('DOMContentLoaded', function() {
    validateTitle();
});
</script>
@endpush