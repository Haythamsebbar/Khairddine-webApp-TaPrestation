@extends('layouts.app')

@section('content')
<div class="bg-blue-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-4 sm:mb-6 lg:mb-8 text-center">
                <h1 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-extrabold text-blue-900 mb-1 sm:mb-2">Créer un nouveau service</h1>
                <p class="text-sm sm:text-base lg:text-lg text-blue-700">Étape 3 : Photos</p>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="flex items-center space-x-2 sm:space-x-3 lg:space-x-4">
                        <a href="{{ route('prestataire.services.create.step2') }}" class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                            <i class="fas fa-arrow-left text-base sm:text-lg lg:text-xl"></i>
                        </a>
                        <div>
                            <h2 class="text-base sm:text-lg lg:text-xl font-bold text-blue-900">Étape 3 sur 4</h2>
                            <p class="text-xs sm:text-sm lg:text-base text-blue-700 hidden sm:block">Photos</p>
                        </div>
                    </div>
                </div>
                
                <!-- Barre de progression -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1 sm:space-x-2 lg:space-x-4 w-full overflow-x-auto">
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">Informations</span>
                            <span class="ml-1 text-xs font-medium text-green-600 sm:hidden">Info</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-green-600 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                <i class="fas fa-check text-xs"></i>
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-green-600 hidden sm:inline">Prix & Catégorie</span>
                            <span class="ml-1 text-xs font-medium text-green-600 sm:hidden">Prix</span>
                        </div>
                        <div class="flex-1 h-1 bg-gray-200 rounded min-w-4">
                            <div class="h-1 bg-blue-600 rounded" style="width: 75%"></div>
                        </div>
                        <div class="flex items-center flex-shrink-0">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs sm:text-sm font-bold">
                                3
                            </div>
                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-blue-600 hidden sm:inline">Photos</span>
                            <span class="ml-1 text-xs font-medium text-blue-600 sm:hidden">Photo</span>
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

            <form method="POST" action="{{ route('prestataire.services.create.step3.store') }}" enctype="multipart/form-data" id="step3Form">
                @csrf

                <!-- Photos -->
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-3 sm:p-4 lg:p-6 mb-4 sm:mb-6">
                    <h2 class="text-base sm:text-lg lg:text-xl font-bold text-blue-900 mb-3 sm:mb-4 border-b border-blue-200 pb-2">
                        <i class="fas fa-camera text-purple-600 mr-1 sm:mr-2 text-sm sm:text-base"></i>Photos de votre service
                    </h2>
                    
                    <div class="mb-3 sm:mb-4">
                        <p class="text-xs sm:text-sm text-blue-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-xs sm:text-sm"></i>
                            Ajoutez des photos de qualité pour présenter votre service. Les photos aident les clients à mieux comprendre ce que vous proposez.
                        </p>
                        <div class="bg-blue-50 p-2 sm:p-3 rounded-lg">
                            <h4 class="font-semibold text-blue-800 mb-1 sm:mb-2 text-xs sm:text-sm">Conseils pour de bonnes photos :</h4>
                            <ul class="text-xs sm:text-sm text-blue-700 space-y-0.5 sm:space-y-1">
                                <li>• Utilisez un bon éclairage naturel</li>
                                <li>• Montrez votre travail sous différents angles</li>
                                <li>• Incluez des photos avant/après si applicable</li>
                                <li>• Évitez les photos floues ou sombres</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="border-2 border-dashed border-blue-300 rounded-lg p-3 sm:p-4 lg:p-6 text-center bg-blue-50 hover:border-blue-400 transition-colors">
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('images').click()">
                            <i class="fas fa-cloud-upload-alt text-blue-400 text-2xl sm:text-3xl lg:text-4xl mb-2 sm:mb-3 lg:mb-4"></i>
                            <p class="text-blue-600 mb-1 sm:mb-2 text-xs sm:text-sm lg:text-base font-semibold">Cliquez pour ajouter des photos ou glissez-déposez</p>
                            <p class="text-blue-500 text-xs sm:text-sm">Maximum 5 photos • Formats acceptés : JPG, PNG, GIF • 5MB max par photo</p>
                        </div>
                        <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-3 lg:gap-4 mt-2 sm:mt-3 lg:mt-4 hidden"></div>
                    </div>
                    
                    @error('images')
                        <p class="text-red-500 text-xs sm:text-sm mt-1 sm:mt-2">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-xs sm:text-sm mt-1 sm:mt-2">{{ $message }}</p>
                    @enderror
                    
                    <div class="mt-3 sm:mt-4 p-2 sm:p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-xs sm:text-sm text-yellow-800">
                            <i class="fas fa-lightbulb mr-1 text-xs sm:text-sm"></i>
                            <strong>Optionnel :</strong> Vous pouvez passer cette étape et ajouter des photos plus tard depuis votre tableau de bord.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-4 sm:pt-6 lg:pt-8 border-t border-blue-200 space-y-3 sm:space-y-0">
                    <a href="{{ route('prestataire.services.create.step2') }}" class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 rounded-lg transition duration-200 font-medium text-center text-xs sm:text-sm lg:text-base">
                        <i class="fas fa-arrow-left mr-1 sm:mr-2 text-xs sm:text-sm"></i><span class="hidden xs:inline">Précédent</span><span class="xs:hidden">Retour</span>
                    </a>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-3 w-full sm:w-auto">
                        <button type="button" onclick="skipPhotos()" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3 rounded-lg transition duration-200 font-medium text-xs sm:text-sm lg:text-base">
                            <i class="fas fa-forward mr-1 sm:mr-2 text-xs sm:text-sm"></i><span class="hidden xs:inline">Passer cette étape</span><span class="xs:hidden">Passer</span>
                        </button>
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3 rounded-lg transition duration-200 font-semibold shadow-lg hover:shadow-xl text-xs sm:text-sm lg:text-base">
                            <span class="hidden xs:inline">Suivant : Localisation</span><span class="xs:hidden">Suivant</span><i class="fas fa-arrow-right ml-1 sm:ml-2 text-xs sm:text-sm"></i>
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
document.addEventListener('DOMContentLoaded', function () {
    // Image Preview
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    const uploadArea = document.getElementById('upload-area');

    window.previewImages = function(input) {
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
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg shadow-md">
                            <button type="button" onclick="removeImage(${index})" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                ${index + 1}
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });

            if (files.length < 5) {
                const addMore = document.createElement('div');
                addMore.className = 'flex items-center justify-center h-24 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-gray-400 transition-colors bg-gray-50';
                addMore.innerHTML = '<div class="text-center"><i class="fas fa-plus text-gray-400 text-xl mb-1"></i><p class="text-xs text-gray-500">Ajouter</p></div>';
                addMore.onclick = () => imageInput.click();
                previewContainer.appendChild(addMore);
            }
        } else {
            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        }
    }

    window.removeImage = function(index) {
        const dt = new DataTransfer();
        const files = imageInput.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        imageInput.files = dt.files;
        previewImages(imageInput);
    }
    
    // Fonction pour passer l'étape photos
    window.skipPhotos = function() {
        // Créer un formulaire temporaire pour passer à l'étape suivante sans photos
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("prestataire.services.create.step3.store") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const skipInput = document.createElement('input');
        skipInput.type = 'hidden';
        skipInput.name = 'skip_photos';
        skipInput.value = '1';
        
        form.appendChild(csrfToken);
        form.appendChild(skipInput);
        document.body.appendChild(form);
        form.submit();
    }
    
    // Validation des fichiers
    imageInput.addEventListener('change', function() {
        const files = this.files;
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            
            // Vérifier le type de fichier
            if (!allowedTypes.includes(file.type)) {
                alert(`Le fichier "${file.name}" n'est pas un format d'image valide. Formats acceptés : JPG, PNG, GIF.`);
                this.value = '';
                return;
            }
            
            // Vérifier la taille du fichier
            if (file.size > maxSize) {
                alert(`Le fichier "${file.name}" est trop volumineux. Taille maximum : 5MB.`);
                this.value = '';
                return;
            }
        }
        
        // Vérifier le nombre de fichiers
        if (files.length > 5) {
            alert('Vous ne pouvez sélectionner que 5 photos maximum.');
            this.value = '';
            return;
        }
    });
    
    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-100');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-100');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        imageInput.files = files;
        previewImages(imageInput);
    }
});
</script>
@endpush