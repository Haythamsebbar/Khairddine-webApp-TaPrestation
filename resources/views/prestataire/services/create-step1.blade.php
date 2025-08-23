@extends('layouts.app')

@section('content')
<div class="bg-blue-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-blue-900 mb-2">Créer un nouveau service</h1>
                <p class="text-base sm:text-lg text-blue-700">Étape 1 : Informations de base</p>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-4 sm:p-6 mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <a href="{{ route('prestataire.services.create') }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-lg sm:text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-blue-900">Étape 1 sur 4</h2>
                            <p class="text-sm sm:text-base text-blue-700 hidden sm:block">Informations de base</p>
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 w-full overflow-x-auto">
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                1
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-blue-600 hidden sm:inline">Informations</span>
                            <span class="ml-1 text-xs font-medium text-blue-600 sm:hidden">Info</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-blue-600 rounded" style="width: 25%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                2
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Prix & Catégorie</span>
                            <span class="ml-1 text-xs font-medium text-gray-500 sm:hidden">Prix</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-gray-200 rounded" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                3
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Photos</span>
                            <span class="ml-1 text-xs font-medium text-gray-500 sm:hidden">Photo</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-gray-200 rounded" style="width: 0%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                4
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-500 hidden sm:inline">Localisation</span>
                            <span class="ml-1 text-xs font-medium text-gray-500 sm:hidden">Lieu</span>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg" role="alert">
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

            <form method="POST" action="{{ route('prestataire.services.create.step1.store') }}" id="step1Form">
                @csrf

                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-blue-900 mb-3 sm:mb-4 border-b border-blue-200 pb-2">
                        <i class="fas fa-info-circle text-blue-600 mr-1 sm:mr-2 text-sm sm:text-base"></i>Informations de base
                    </h2>
                    
                    <div class="space-y-3 sm:space-y-4 lg:space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1 sm:mb-2">Titre du service *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', session('service_data.title')) }}" required maxlength="255" class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 space-y-1 sm:space-y-0">
                                <div class="flex-1">
                                    @error('title')
                                        <p class="text-red-500 text-xs">{{ $message }}</p>
                                    @enderror
                                    <p id="title-warning" class="text-yellow-600 text-xs hidden">Titre trop court, précisez l'usage ou le modèle</p>
                                    <p id="title-tip" class="text-blue-600 text-xs">Idéal : 5–9 mots, sans abréviations</p>
                                </div>
                                <p class="text-gray-500 text-xs flex-shrink-0 sm:ml-2"><span id="title-count">0</span>/70</p>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1 sm:mb-2">Description détaillée *</label>
                            <textarea id="description" name="description" required rows="3" sm:rows="4" lg:rows="6" maxlength="2000" class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" placeholder="Décrivez en détail votre service, vos compétences et ce qui vous différencie...">{{ old('description', session('service_data.description')) }}</textarea>
                            <div class="mt-1">
                                @error('description')
                                    <p class="text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                                <div id="description-error" class="text-red-500 text-xs hidden">
                                    <p class="font-medium">Description trop courte (minimum 50 caractères)</p>
                                    <p class="text-xs mt-1 hidden sm:block">Structure suggérée : Ce que c'est / Pour qui / Ce qui est inclus / Conditions</p>
                                </div>
                                <div id="description-warning" class="text-yellow-600 text-xs hidden">
                                    <p>Ajoutez bénéfices, état, accessoires, délais, garanties</p>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mt-1 space-y-1 sm:space-y-0">
                                    <p class="text-blue-600 text-xs flex-1">Recommandé : 150–600 caractères</p>
                                    <p class="text-gray-500 text-xs flex-shrink-0 sm:ml-2"><span id="description-count">0</span> caractères</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reservable -->
                        <div>
                            <label for="reservable" class="inline-flex items-start">
                                <input id="reservable" type="checkbox" class="mt-0.5 rounded border-blue-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50 flex-shrink-0" name="reservable" {{ old('reservable', session('service_data.reservable')) ? 'checked' : '' }}>
                                <span class="ml-2 text-xs sm:text-sm text-blue-600">Activer la réservation directe pour ce service</span>
                            </label>
                        </div>

                        <!-- Delivery time -->
                        <div>
                            <label for="delivery_time" class="block text-xs sm:text-sm font-medium text-blue-700 mb-1 sm:mb-2">Délai de livraison (en jours)</label>
                            <input type="number" id="delivery_time" name="delivery_time" value="{{ old('delivery_time', session('service_data.delivery_time')) }}" min="1" max="365" class="w-full px-3 py-2 sm:py-2.5 text-sm sm:text-base border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('delivery_time') border-red-500 @enderror">
                            @error('delivery_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center pt-4 sm:pt-6 lg:pt-8 border-t border-blue-200 space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('prestataire.services.create') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg transition duration-200 font-medium text-center text-sm sm:text-base">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>Retour
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl text-sm sm:text-base">
                        <span class="hidden sm:inline">Suivant : Prix & Catégorie</span>
                        <span class="sm:hidden">Suivant</span>
                        <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
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

    // Initialiser les compteurs au chargement
    validateTitle();
    validateDescription();

    // Validation du formulaire
    document.getElementById('step1Form').addEventListener('submit', function(e) {
        const description = document.getElementById('description').value.trim();
        
        // Vérifier la longueur minimale de la description (bloquant)
        if (description.length < 50) {
            e.preventDefault();
            alert('La description doit contenir au moins 50 caractères.');
            descriptionInput.focus();
            return;
        }
    });
});
</script>
@endpush