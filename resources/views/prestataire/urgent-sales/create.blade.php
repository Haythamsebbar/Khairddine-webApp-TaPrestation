@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('title', 'Ajouter une Vente Urgente')

@section('content')
<div class="bg-red-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-red-900 mb-2">Ajouter une Vente Urgente</h1>
                <p class="text-base sm:text-lg text-red-700">Créez une nouvelle annonce pour votre vente urgente</p>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-4 sm:p-6 mb-6">
                <!-- Display validation errors if any -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Erreurs de validation détectées :</p>
                                <ul class="mt-2 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                                <p class="mt-2 text-sm"><strong>Le formulaire vous redirigera automatiquement vers l'étape contenant l'erreur.</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <a href="{{ route('prestataire.urgent-sales.index') }}" class="text-red-600 hover:text-red-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-lg sm:text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-red-900">Nouvelle vente urgente</h2>
                            <p class="text-sm sm:text-base text-red-700 hidden sm:block">Étape <span id="current-step">1</span> sur 4</p>
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4 w-full">
                        <!-- Étape 1 -->
                        <div class="flex items-center">
                            <div id="step-1-indicator" class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold">
                                1
                            </div>
                            <span class="ml-2 text-sm font-medium text-red-900 hidden sm:inline">Informations</span>
                        </div>
                        
                        <!-- Ligne de connexion -->
                        <div id="line-1" class="flex-1 h-1 bg-gray-300 mx-2"></div>
                        
                        <!-- Étape 2 -->
                        <div class="flex items-center">
                            <div id="step-2-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">
                                2
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-600 hidden sm:inline">Localisation</span>
                        </div>
                        
                        <!-- Ligne de connexion -->
                        <div id="line-2" class="flex-1 h-1 bg-gray-300 mx-2"></div>
                        
                        <!-- Étape 3 -->
                        <div class="flex items-center">
                            <div id="step-3-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">
                                3
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-600 hidden sm:inline">Description</span>
                        </div>
                        
                        <!-- Ligne de connexion -->
                        <div id="line-3" class="flex-1 h-1 bg-gray-300 mx-2"></div>
                        
                        <!-- Étape 4 -->
                        <div class="flex items-center">
                            <div id="step-4-indicator" class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold">
                                4
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-600 hidden sm:inline">Révision</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire multi-étapes -->
            <form action="{{ route('prestataire.urgent-sales.store') }}" method="POST" enctype="multipart/form-data" id="urgent-sale-form">
                @csrf
                
                <!-- Étape 1: Informations de base -->
                <div id="step-1" class="step-content">
                    @include('prestataire.urgent-sales.steps.step1')
                </div>
                
                <!-- Étape 2: Localisation -->
                <div id="step-2" class="step-content hidden">
                    @include('prestataire.urgent-sales.steps.step2')
                </div>
                
                <!-- Étape 3: Description et photos -->
                <div id="step-3" class="step-content hidden">
                    @include('prestataire.urgent-sales.steps.step3')
                </div>
                
                <!-- Étape 4: Révision et publication -->
                <div id="step-4" class="step-content hidden">
                    @include('prestataire.urgent-sales.steps.step4')
                </div>
                
                <!-- Navigation -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-6 sm:pt-8 border-t border-red-200 gap-4 sm:gap-0 bg-white rounded-xl shadow-lg border border-red-200 p-4 sm:p-6 mt-6">
                    <button type="button" id="prev-btn" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-3 rounded-lg transition duration-200 font-medium hidden">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    
                    <div class="flex space-x-4">
                        <a href="{{ route('prestataire.urgent-sales.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-4 sm:px-6 py-3 rounded-lg transition duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        
                        <button type="button" id="next-btn" class="bg-red-600 hover:bg-red-700 text-white px-6 sm:px-8 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl">
                            Suivant<i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        
                        <button type="submit" id="submit-btn" class="bg-red-600 hover:bg-red-700 text-white px-6 sm:px-8 py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl hidden">
                            <i class="fas fa-check mr-2"></i>Publier maintenant
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let currentStep = 1;
const totalSteps = 4;
let urgentSaleMap = null;
let currentMarker = null;

// Gestion des étapes
function showStep(step) {
    // Masquer toutes les étapes
    document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
    
    // Afficher l'étape courante
    document.getElementById(`step-${step}`).classList.remove('hidden');
    
    // Mettre à jour l'indicateur d'étape
    const currentStepEl = document.getElementById('current-step');
    if (currentStepEl) {
        currentStepEl.textContent = step;
    }
    
    // Mettre à jour les indicateurs visuels
    updateStepIndicators(step);
    
    // Gérer les boutons de navigation
    updateNavigationButtons(step);
    
    // Actions spécifiques par étape
    if (step === 2 && !urgentSaleMap) {
        setTimeout(initializeUrgentSaleMap, 100);
    }
    
    if (step === 4) {
        // Call updateReviewStep if it exists (defined in step4.blade.php)
        setTimeout(function() {
            if (typeof updateReviewStep === 'function') {
                updateReviewStep();
            }
        }, 100);
    }
}

function updateStepIndicators(step) {
    for (let i = 1; i <= totalSteps; i++) {
        const indicator = document.getElementById(`step-${i}-indicator`);
        const line = document.getElementById(`line-${i}`);
        
        if (indicator) {
            if (i < step) {
                // Étapes complétées
                indicator.className = 'w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-sm font-bold';
                indicator.innerHTML = '<i class="fas fa-check"></i>';
            } else if (i === step) {
                // Étape courante
                indicator.className = 'w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center text-sm font-bold';
                indicator.textContent = i;
            } else {
                // Étapes futures
                indicator.className = 'w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-bold';
                indicator.textContent = i;
            }
        }
        
        if (line) {
            if (i < step) {
                line.className = 'flex-1 h-1 bg-green-600 mx-2';
            } else {
                line.className = 'flex-1 h-1 bg-gray-300 mx-2';
            }
        }
    }
}

function updateNavigationButtons(step) {
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    // Bouton précédent
    if (step === 1) {
        prevBtn.classList.add('hidden');
    } else {
        prevBtn.classList.remove('hidden');
    }
    
    // Boutons suivant/soumettre
    if (step === totalSteps) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

function validateCurrentStep() {
    switch (currentStep) {
        case 1:
            return validateStep1();
        case 2:
            return validateStep2();
        case 3:
            return validateStep3();
        case 4:
            return validateStep4();
        default:
            return true;
    }
}

// Add validation for step 4
function validateStep4() {
    const termsCheckbox = document.getElementById('terms-checkbox');
    const contactCheckbox = document.getElementById('contact-checkbox');
    
    if (!termsCheckbox || !termsCheckbox.checked) {
        alert('Veuillez accepter les conditions d\'utilisation.');
        if (termsCheckbox) termsCheckbox.focus();
        return false;
    }
    
    if (!contactCheckbox || !contactCheckbox.checked) {
        alert('Veuillez accepter d\'\u00eatre contacté par les acheteurs.');
        if (contactCheckbox) contactCheckbox.focus();
        return false;
    }
    
    return true;
}

function validateStep1() {
    const title = document.getElementById('title').value.trim();
    const price = document.getElementById('price').value;
    const condition = document.getElementById('condition').value;
    const parentCategory = document.getElementById('parent_category_id').value;
    const quantity = document.getElementById('quantity').value;
    
    if (!title) {
        alert('Veuillez saisir un titre pour votre vente.');
        document.getElementById('title').focus();
        return false;
    }
    
    if (!price || parseFloat(price) <= 0) {
        alert('Veuillez saisir un prix valide.');
        document.getElementById('price').focus();
        return false;
    }
    
    if (!condition) {
        alert('Veuillez sélectionner l\'état du produit.');
        document.getElementById('condition').focus();
        return false;
    }
    
    if (!parentCategory) {
        alert('Veuillez sélectionner une catégorie principale.');
        document.getElementById('parent_category_id').focus();
        return false;
    }
    
    if (!quantity || parseInt(quantity) <= 0) {
        alert('Veuillez saisir une quantité valide.');
        document.getElementById('quantity').focus();
        return false;
    }
    
    return true;
}

function validateStep2() {
    try {
        const locationEl = document.getElementById('selectedAddress');
        const latitudeEl = document.getElementById('selectedLatitude');
        const longitudeEl = document.getElementById('selectedLongitude');
        
        if (!locationEl || !latitudeEl || !longitudeEl) {
            console.error('Location elements not found');
            return false;
        }
        
        const location = locationEl.value.trim();
        const latitude = latitudeEl.value;
        const longitude = longitudeEl.value;
        
        if (!location || !latitude || !longitude) {
            alert('Veuillez sélectionner une localisation sur la carte ou saisir une adresse.');
            return false;
        }
        
        return true;
    } catch (error) {
        console.error('Error in validateStep2:', error);
        return false;
    }
}

function validateStep3() {
    const description = document.getElementById('description').value.trim();
    
    if (!description) {
        alert('Veuillez saisir une description.');
        document.getElementById('description').focus();
        return false;
    }
    
    return true;
}



// Function to detect which step has validation errors (simplified)
function getStepWithErrors() {
    // Only redirect to error step if we have actual Laravel validation errors
    const hasValidationErrors = @json($errors->any());
    
    if (!hasValidationErrors) {
        return 1; // Always start at step 1 if no validation errors
    }
    
    // Check for specific step errors only if validation errors exist
    const step1Errors = @json($errors->has('title') || $errors->has('price') || $errors->has('condition') || $errors->has('parent_category_id') || $errors->has('quantity'));
    const step2Errors = @json($errors->has('location') || $errors->has('latitude') || $errors->has('longitude'));
    const step3Errors = @json($errors->has('description') || $errors->has('photos'));
    
    if (step1Errors) return 1;
    if (step2Errors) return 2;
    if (step3Errors) return 3;
    
    return 1; // Default to step 1
}

// Événements de navigation
document.addEventListener('DOMContentLoaded', function() {
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('urgent-sale-form');
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Next button clicked, current step:', currentStep);
            
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    console.log('Moving to step:', currentStep);
                    showStep(currentStep);
                }
            } else {
                console.log('Validation failed for step:', currentStep);
            }
        });
    } else {
        console.error('Next button not found!');
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }
    
    // Handle submit button
    if (submitBtn && form) {
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Submit button clicked, current step:', currentStep);
            
            // First, ensure we're on step 4
            if (currentStep !== 4) {
                console.log('Not on step 4, redirecting to step 4');
                currentStep = 4;
                showStep(currentStep);
                return;
            }
            
            // Validate step 4 first (the current visible step)
            if (!validateStep4()) {
                console.log('Step 4 validation failed - checkboxes not checked');
                return;
            }
            
            console.log('Step 4 validation passed, now validating all previous steps...');
            
            // Validate all previous steps without changing UI
            let allStepsValid = true;
            let firstInvalidStep = null;
            
            // Validate step 1
            const title = document.getElementById('title')?.value?.trim();
            const price = document.getElementById('price')?.value;
            const condition = document.getElementById('condition')?.value;
            const parentCategory = document.getElementById('parent_category_id')?.value;
            const quantity = document.getElementById('quantity')?.value;
            
            if (!title || !price || parseFloat(price) <= 0 || !condition || !parentCategory || !quantity || parseInt(quantity) <= 0) {
                allStepsValid = false;
                firstInvalidStep = 1;
            }
            
            // Validate step 2 if step 1 is valid
            if (allStepsValid) {
                const locationEl = document.getElementById('selectedAddress');
                const latitudeEl = document.getElementById('selectedLatitude');
                const longitudeEl = document.getElementById('selectedLongitude');
                
                const location = locationEl?.value?.trim();
                const latitude = latitudeEl?.value;
                const longitude = longitudeEl?.value;
                
                if (!location || !latitude || !longitude) {
                    allStepsValid = false;
                    firstInvalidStep = 2;
                }
            }
            
            // Validate step 3 if previous steps are valid
            if (allStepsValid) {
                const description = document.getElementById('description')?.value?.trim();
                if (!description) {
                    allStepsValid = false;
                    firstInvalidStep = 3;
                }
            }
            
            if (allStepsValid) {
                console.log('All steps valid, submitting form');
                console.log('Form data check:');
                console.log('- Title:', title);
                console.log('- Price:', price);
                console.log('- Location:', location);
                console.log('- Description:', description);
                form.submit();
            } else {
                console.log('Form validation failed at step:', firstInvalidStep);
                alert(`Veuillez compléter toutes les informations requises à l'étape ${firstInvalidStep}.`);
                currentStep = firstInvalidStep;
                showStep(currentStep);
            }
        });
    }
    
    // Determine initial step - only use error detection if we have actual validation errors
    const hasActualErrors = @json($errors->any());
    if (hasActualErrors) {
        const errorStep = getStepWithErrors();
        currentStep = errorStep;
        console.log('Starting at step:', currentStep, 'due to validation errors');
    } else {
        currentStep = 1;
        console.log('Starting at step 1 (no validation errors)');
    }
    
    // Initialisation
    showStep(currentStep);
});
</script>
@endpush