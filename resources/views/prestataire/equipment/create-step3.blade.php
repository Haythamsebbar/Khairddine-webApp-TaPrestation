@extends('layouts.app')

@section('title', 'Ajouter un équipement - Étape 3')

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-green-900 mb-2">Ajouter un équipement</h1>
                <p class="text-base sm:text-lg text-green-700">Étape 3 : Photos</p>
            </div>

            <!-- Barre de progression -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-4 sm:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-green-900">Processus de création</h2>
                    <span class="text-sm text-green-600">Étape 3 sur 4</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-900">Informations de base</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-600 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            ✓
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-900">Tarifs et conditions</span>
                    </div>
                    <div class="flex-1 h-1 bg-green-600 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-900">Photos</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            4
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Localisation et résumé</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire Étape 3 -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('prestataire.equipment.create.step2') }}" class="text-green-600 hover:text-green-900 transition-colors duration-200 p-1">
                            <i class="fas fa-arrow-left text-base sm:text-lg"></i>
                        </a>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-green-900">Photos de l'équipement</h2>
                            <p class="text-xs sm:text-sm text-green-700">Ajoutez une photo principale de votre équipement</p>
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

                <form action="{{ route('prestataire.equipment.store.step3') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Photo principale -->
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-green-900 mb-3 sm:mb-4">Photo principale *</h3>
                            <div class="border-2 border-dashed border-green-300 rounded-lg p-4 sm:p-6 text-center hover:border-green-400 transition-colors">
                                <input type="file" name="main_photo" id="main_photo" required accept="image/*" class="hidden" onchange="previewMainImage(this)">
                                <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('main_photo').click()">
                                    <i class="fas fa-cloud-upload-alt text-green-400 text-2xl sm:text-4xl mb-2 sm:mb-4"></i>
                                    <p class="text-green-600 mb-1 sm:mb-2 text-sm sm:text-base">Cliquez pour ajouter une photo ou glissez-déposez</p>
                                    <p class="text-green-500 text-xs sm:text-sm">Ajoutez une photo claire de votre équipement</p>
                                    <p class="text-gray-500 text-xs mt-1 sm:mt-2">Formats acceptés : JPG, PNG, GIF (max 5MB)</p>
                                </div>
                                <div id="image-preview" class="mt-3 sm:mt-4 hidden"></div>
                            </div>
                            @error('main_photo')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conseils pour les photos -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-xs sm:text-sm text-blue-700">
                                    <p class="font-medium mb-1 sm:mb-2">Conseils pour de meilleures photos :</p>
                                    <ul class="list-disc list-inside space-y-0.5 sm:space-y-1">
                                        <li>Utilisez un bon éclairage naturel</li>
                                        <li>Montrez l'équipement sous différents angles</li>
                                        <li>Assurez-vous que l'équipement soit propre et bien visible</li>
                                        <li>Évitez les arrière-plans encombrés</li>
                                        <li>Incluez les accessoires importants dans la photo</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des informations précédentes -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">Récapitulatif de votre équipement</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                                <div>
                                    <p class="text-gray-600">Nom :</p>
                                    <p class="font-medium text-gray-900">{{ session('equipment_step1.name', 'Non défini') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Prix par jour :</p>
                                    <p class="font-medium text-gray-900">{{ session('equipment_step2.price_per_day', 'Non défini') }}€</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Caution :</p>
                                    <p class="font-medium text-gray-900">{{ session('equipment_step2.security_deposit', 'Non défini') }}€</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">État :</p>
                                    <p class="font-medium text-gray-900">
                                        @php
                                            $conditions = [
                                                'excellent' => 'Excellent',
                                                'very_good' => 'Très bon',
                                                'good' => 'Bon',
                                                'fair' => 'Correct',
                                                'poor' => 'Mauvais'
                                            ];
                                            $condition = session('equipment_step2.condition');
                                        @endphp
                                        {{ $conditions[$condition] ?? 'Non défini' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-green-200 gap-3 sm:gap-0 mt-8">
                        <a href="{{ route('prestataire.equipment.create.step2') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-6 py-3 rounded-lg transition duration-200 font-medium text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Précédent
                        </a>
                        
                        <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl">
                            Suivant : Localisation et résumé
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewMainImage(input) {
    const preview = document.getElementById('image-preview');
    const uploadArea = document.getElementById('upload-area');
    
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        preview.classList.remove('hidden');
        uploadArea.classList.add('hidden');
        
        const file = input.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group w-48 h-48 mx-auto';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg border-2 border-green-300">
                    <button type="button" onclick="removeMainImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-50 text-white text-xs p-2 rounded">
                        ${file.name}
                    </div>
                `;
                preview.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        }
    } else {
        preview.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    }
}

function removeMainImage() {
    const input = document.getElementById('main_photo');
    input.value = '';
    previewMainImage(input);
}

// Drag and drop functionality
const uploadArea = document.getElementById('upload-area');
const mainPhotoInput = document.getElementById('main_photo');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('border-green-500', 'bg-green-50');
}

function unhighlight(e) {
    uploadArea.classList.remove('border-green-500', 'bg-green-50');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        mainPhotoInput.files = files;
        previewMainImage(mainPhotoInput);
    }
}
</script>
@endsection