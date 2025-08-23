@extends('layouts.app')

@section('title', 'Ajouter un équipement - Étape 1')

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-green-900 mb-2">Ajouter un équipement</h1>
                <p class="text-base sm:text-lg text-green-700">Étape 1 : Informations de base</p>
            </div>

            <!-- Barre de progression -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3 sm:mb-4 space-y-2 sm:space-y-0">
                    <h2 class="text-base sm:text-lg font-semibold text-green-900">Processus de création</h2>
                    <span class="text-xs sm:text-sm text-green-600">Étape 1 sur 4</span>
                </div>
                <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 overflow-x-auto">
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            1
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-900 hidden sm:inline">Informations de base</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            2
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Tarifs et conditions</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            3
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Photos</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded min-w-4"></div>
                    <div class="flex items-center flex-shrink-0">
                        <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-medium">
                            4
                        </div>
                        <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Localisation et résumé</span>
                    </div>
                </div>
                <!-- Labels mobiles -->
                <div class="flex justify-between mt-2 sm:hidden text-xs text-gray-600">
                    <span class="text-green-600 font-medium">Info</span>
                    <span>Tarifs</span>
                    <span>Photos</span>
                    <span>Résumé</span>
                </div>
            </div>

            <!-- Formulaire Étape 1 -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('prestataire.equipment.index') }}" class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1">
                            <i class="fas fa-arrow-left text-base sm:text-lg"></i>
                        </a>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-green-900">Informations de base</h2>
                            <p class="text-xs sm:text-sm text-green-700">Décrivez votre équipement et choisissez sa catégorie</p>
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

                <form action="{{ route('prestataire.equipment.store.step1') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Nom de l'équipement -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-green-700 mb-2">Nom de l'équipement *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', session('equipment_step1.name')) }}" required placeholder="Ex: Perceuse sans fil Bosch" class="w-full px-3 py-2 text-sm sm:text-base border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 space-y-1 sm:space-y-0">
                                <div class="flex-1">
                                    @error('name')
                                        <p class="text-red-500 text-xs sm:text-sm">{{ $message }}</p>
                                    @enderror
                                    <p id="name-warning" class="text-yellow-600 text-xs sm:text-sm hidden">Titre trop court, précisez l'usage ou le modèle</p>
                                    <p id="name-tip" class="text-green-600 text-xs sm:text-sm">Idéal : 5–9 mots, sans abréviations</p>
                                </div>
                                <p class="text-gray-500 text-xs sm:text-sm flex-shrink-0 sm:ml-4"><span id="name-count">0</span>/70</p>
                            </div>
                        </div>

                        <!-- Catégorie principale -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-green-700 mb-2">Catégorie principale *</label>
                            <select id="category_id" name="category_id" required class="w-full px-3 py-2 text-sm sm:text-base border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Sélectionnez une catégorie principale</option>
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sous-catégorie -->
                        <div id="subcategory-group" style="display: none;">
                            <label for="subcategory_id" class="block text-sm font-medium text-green-700 mb-2">Sous-catégorie</label>
                            <select id="subcategory_id" name="subcategory_id" class="w-full px-3 py-2 text-sm sm:text-base border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('subcategory_id') border-red-500 @enderror" disabled>
                                <option value="">Veuillez d'abord choisir une catégorie</option>
                            </select>
                            @error('subcategory_id')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-green-700 mb-2">Description courte *</label>
                            <textarea id="description" name="description" rows="4" required placeholder="Décrivez brièvement votre équipement, son état et ses caractéristiques principales" class="w-full px-3 py-2 text-sm sm:text-base border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 resize-none @error('description') border-red-500 @enderror">{{ old('description', session('equipment_step1.description')) }}</textarea>
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
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 space-y-1 sm:space-y-0">
                                    <p class="text-green-600 text-xs sm:text-sm flex-1">Recommandé : 150–600 caractères</p>
                                    <p class="text-gray-500 text-xs sm:text-sm flex-shrink-0 sm:ml-4"><span id="description-count">0</span> caractères</p>
                                </div>
                            </div>
                        </div>

                        <!-- Spécifications techniques -->
                        <div>
                            <label for="technical_specifications" class="block text-sm font-medium text-green-700 mb-2">Spécifications techniques</label>
                            <textarea id="technical_specifications" name="technical_specifications" rows="3" placeholder="Dimensions, poids, puissance, capacité..." class="w-full px-3 py-2 text-sm sm:text-base border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('technical_specifications', session('equipment_step1.technical_specifications')) }}</textarea>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center pt-4 sm:pt-6 border-t border-green-200 gap-3 sm:gap-4 mt-6 sm:mt-8">
                        <a href="{{ route('prestataire.equipment.index') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg transition duration-200 font-medium text-center text-sm sm:text-base">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl text-sm sm:text-base">
                            <span class="hidden sm:inline">Suivant : Tarifs et conditions</span>
                            <span class="sm:hidden">Suivant</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validation du titre
const nameInput = document.getElementById('name');
const nameCount = document.getElementById('name-count');
const nameWarning = document.getElementById('name-warning');
const nameTip = document.getElementById('name-tip');

function validateName() {
    const length = nameInput.value.length;
    nameCount.textContent = length;
    
    if (length < 10) {
        nameWarning.classList.remove('hidden');
        nameTip.classList.add('hidden');
        nameInput.classList.add('border-yellow-400');
        nameInput.classList.remove('border-green-300');
    } else {
        nameWarning.classList.add('hidden');
        nameTip.classList.remove('hidden');
        nameInput.classList.remove('border-yellow-400');
        nameInput.classList.add('border-green-300');
    }
    
    if (length > 70) {
        nameInput.classList.add('border-red-500');
    } else {
        nameInput.classList.remove('border-red-500');
    }
}

nameInput.addEventListener('input', validateName);
validateName(); // Initial validation

// Validation de la description
const descriptionInput = document.getElementById('description');
const descriptionCount = document.getElementById('description-count');
const descriptionError = document.getElementById('description-error');
const descriptionWarning = document.getElementById('description-warning');

function validateDescription() {
    const length = descriptionInput.value.length;
    descriptionCount.textContent = length;
    
    if (length < 50) {
        descriptionError.classList.remove('hidden');
        descriptionWarning.classList.add('hidden');
        descriptionInput.classList.add('border-red-500');
        descriptionInput.classList.remove('border-yellow-400', 'border-green-300');
    } else if (length < 150) {
        descriptionError.classList.add('hidden');
        descriptionWarning.classList.remove('hidden');
        descriptionInput.classList.add('border-yellow-400');
        descriptionInput.classList.remove('border-red-500', 'border-green-300');
    } else {
        descriptionError.classList.add('hidden');
        descriptionWarning.classList.add('hidden');
        descriptionInput.classList.add('border-green-300');
        descriptionInput.classList.remove('border-red-500', 'border-yellow-400');
    }
}

descriptionInput.addEventListener('input', validateDescription);
validateDescription(); // Initial validation

// Validation du formulaire avant soumission
document.querySelector('form').addEventListener('submit', function(e) {
    const nameLength = nameInput.value.length;
    const descriptionLength = descriptionInput.value.length;
    
    if (descriptionLength < 50) {
        e.preventDefault();
        descriptionInput.focus();
        alert('La description doit contenir au moins 50 caractères.');
        return false;
    }
});

// Gestion des catégories et sous-catégories
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');
    
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
            categorySelect.innerHTML = '<option value="">Sélectionnez une catégorie principale</option>';
            
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                if (category.id == '{{ old("category_id", session("equipment_step1.category_id")) }}') {
                    option.selected = true;
                }
                categorySelect.appendChild(option);
            });
            
            // Si une catégorie est sélectionnée, charger les sous-catégories
            if (categorySelect.value) {
                loadSubcategories(categorySelect.value);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des catégories:', error);
        });
}

function loadSubcategories(categoryId) {
    const subcategorySelect = document.getElementById('subcategory_id');
    const subcategoryGroup = document.getElementById('subcategory-group');
    
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
                    if (subcategory.id == '{{ old("subcategory_id", session("equipment_step1.subcategory_id")) }}') {
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
@endsection