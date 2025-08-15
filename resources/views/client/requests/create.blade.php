@extends('layouts.app')

@section('title', 'Publier une demande de prestation')

@section('content')
<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full mb-4">
                    <i class="fas fa-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-blue-900 mb-2">Publier une demande de prestation</h1>
                <p class="text-lg text-blue-700">Décrivez votre projet et recevez des propositions de prestataires qualifiés</p>
            </div>

            <!-- Messages de session -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-md">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium">Veuillez corriger les erreurs suivantes :</h3>
                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire principal -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <form action="{{ route('client.requests.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="p-6 md:p-8">
                        <!-- Question sur le type de service -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-question text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg md:text-xl font-semibold text-gray-900">Quel service cherchez-vous ?</h3>
                                    <p class="text-sm text-gray-600 mt-1">(location, annonces, développement web, design, marketing, etc.)</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="relative">
                                    <input type="radio" id="service_location" name="service_type" value="location" class="sr-only peer" {{ old('service_type') == 'location' ? 'checked' : '' }}>
                                    <label for="service_location" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-home text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Location</span>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" id="service_annonces" name="service_type" value="annonces" class="sr-only peer" {{ old('service_type') == 'annonces' ? 'checked' : '' }}>
                                    <label for="service_annonces" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-bullhorn text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Annonces</span>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" id="service_web" name="service_type" value="developpement_web" class="sr-only peer" {{ old('service_type') == 'developpement_web' ? 'checked' : '' }}>
                                    <label for="service_web" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-code text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Développement Web</span>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" id="service_design" name="service_type" value="design" class="sr-only peer" {{ old('service_type') == 'design' ? 'checked' : '' }}>
                                    <label for="service_design" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-paint-brush text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Design</span>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" id="service_marketing" name="service_type" value="marketing" class="sr-only peer" {{ old('service_type') == 'marketing' ? 'checked' : '' }}>
                                    <label for="service_marketing" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-chart-line text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Marketing</span>
                                    </label>
                                </div>
                                
                                <div class="relative">
                                    <input type="radio" id="service_autre" name="service_type" value="autre" class="sr-only peer" {{ old('service_type') == 'autre' ? 'checked' : '' }}>
                                    <label for="service_autre" class="flex items-center p-4 bg-gray-50 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <i class="fas fa-ellipsis-h text-blue-500 mr-3"></i>
                                        <span class="font-medium text-gray-900">Autre</span>
                                    </label>
                                </div>
                            </div>
                            @error('service_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Titre de la demande -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>
                                Titre de votre demande *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('title') border-red-500 @enderror" 
                                   placeholder="Ex: Création d'un site web e-commerce"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description détaillée -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-blue-500 mr-2"></i>
                                Description détaillée *
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="6"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('description') border-red-500 @enderror" 
                                      placeholder="Décrivez votre projet en détail : objectifs, contraintes, délais, spécifications techniques..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget et localisation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-euro-sign text-blue-500 mr-2"></i>
                                    Budget estimé
                                </label>
                                <select id="budget" 
                                        name="budget" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('budget') border-red-500 @enderror">
                                    <option value="">Sélectionnez votre budget</option>
                                    <option value="moins_500" {{ old('budget') == 'moins_500' ? 'selected' : '' }}>Moins de 500€</option>
                                    <option value="500_1000" {{ old('budget') == '500_1000' ? 'selected' : '' }}>500€ - 1 000€</option>
                                    <option value="1000_2500" {{ old('budget') == '1000_2500' ? 'selected' : '' }}>1 000€ - 2 500€</option>
                                    <option value="2500_5000" {{ old('budget') == '2500_5000' ? 'selected' : '' }}>2 500€ - 5 000€</option>
                                    <option value="plus_5000" {{ old('budget') == 'plus_5000' ? 'selected' : '' }}>Plus de 5 000€</option>
                                    <option value="a_discuter" {{ old('budget') == 'a_discuter' ? 'selected' : '' }}>À discuter</option>
                                </select>
                                @error('budget')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                    Localisation
                                </label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('location') border-red-500 @enderror" 
                                       placeholder="Ville, région ou 'À distance'">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Date limite -->
                        <div class="mb-6">
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                Date limite souhaitée
                            </label>
                            <input type="date" 
                                   id="due_date" 
                                   name="due_date" 
                                   value="{{ old('due_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('due_date') border-red-500 @enderror">
                            @error('due_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fichiers joints -->
                        <div class="mb-8">
                            <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-paperclip text-blue-500 mr-2"></i>
                                Fichiers joints (optionnel)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors duration-200">
                                <input type="file" 
                                       id="attachments" 
                                       name="attachments[]" 
                                       multiple 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                       class="hidden">
                                <label for="attachments" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600">Cliquez pour ajouter des fichiers</p>
                                    <p class="text-sm text-gray-500 mt-2">PDF, DOC, images (max 10MB par fichier)</p>
                                </label>
                            </div>
                            @error('attachments.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-gray-50 px-6 md:px-8 py-4 flex flex-col sm:flex-row gap-3 sm:justify-end">
                        <a href="{{ route('client.dashboard') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Publier ma demande de prestation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion des fichiers joints
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = e.target.files;
    const label = e.target.nextElementSibling;
    
    if (files.length > 0) {
        const fileNames = Array.from(files).map(file => file.name).join(', ');
        label.innerHTML = `
            <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
            <p class="text-green-600 font-medium">${files.length} fichier(s) sélectionné(s)</p>
            <p class="text-sm text-gray-500 mt-2">${fileNames}</p>
        `;
    }
});
</script>
@endsection