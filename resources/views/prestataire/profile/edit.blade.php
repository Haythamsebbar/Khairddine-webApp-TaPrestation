@extends('layouts.app')

@section('title', 'Mon Profil Professionnel')

@section('content')
<div class="min-h-screen bg-purple-50">
    <div class="container mx-auto py-4 sm:py-6 md:py-8 px-2 sm:px-4">
        <!-- En-tête amélioré -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                    <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-purple-900 mb-1 sm:mb-2">
                            Mon Profil Professionnel
                        </h1>
                        <p class="text-sm sm:text-base md:text-lg text-purple-700">Gérez vos informations professionnelles et votre présentation</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('prestataire.profile.preview') }}" class="inline-flex items-center px-3 sm:px-4 py-2 border border-transparent text-xs sm:text-sm font-bold rounded-xl text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Aperçu du profil public
                    </a>
                </div>
            </div>
        </div>
    
        <!-- Indicateur de complétion -->
        <div class="mb-6 md:mb-8 bg-white rounded-xl shadow-lg border border-purple-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-purple-900">Complétion du profil</h3>
                </div>
                <span class="text-2xl sm:text-3xl font-extrabold text-purple-600">{{ $completion_percentage ?? 0 }}%</span>
            </div>
            <div class="w-full bg-purple-100 rounded-full h-3 sm:h-4 mb-3">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 h-3 sm:h-4 rounded-full transition-all duration-500 shadow-sm" style="width: {{ $completion_percentage ?? 0 }}%"></div>
            </div>
            <p class="text-sm sm:text-base text-purple-700">
                @if(($completion_percentage ?? 0) < 70)
                    Complétez votre profil pour attirer plus de clients. Un profil complet inspire confiance !
                @elseif(($completion_percentage ?? 0) < 90)
                    Excellent ! Votre profil est presque complet. Ajoutez quelques détails pour le finaliser.
                @else
                    Parfait ! Votre profil est complet et prêt à attirer de nouveaux clients.
                @endif
            </p>
        </div>
                
        @if ($errors->any())
            <div class="mb-6 md:mb-8 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-red-100 to-pink-100 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-base font-bold text-red-800 mb-2">Erreurs détectées :</h3>
                        <div class="text-sm text-red-700">
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

        @if (session('success'))
            <div class="mb-6 md:mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm sm:text-base font-bold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
                
        <form action="{{ route('prestataire.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6 md:space-y-8">
                <!-- Photo de profil -->
                <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-4 sm:p-6">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-purple-900">Photo de profil</h3>
                            <p class="text-sm text-purple-700">Une photo claire et professionnelle inspire confiance aux clients.</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                        @if($prestataire && $prestataire->photo)
                            <img class="h-20 w-20 sm:h-24 sm:w-24 rounded-full object-cover border-4 border-purple-200 shadow-lg" src="{{ asset('storage/' . $prestataire->photo) }}" alt="Photo actuelle">
                        @else
                            <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center border-4 border-purple-200 shadow-lg">
                                <span class="text-xl sm:text-2xl font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*" class="block w-full text-sm text-purple-700 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-purple-50 file:to-indigo-50 file:text-purple-700 hover:file:from-purple-100 hover:file:to-indigo-100 file:shadow-sm">
                            <p class="mt-1 text-xs text-purple-600">Format recommandé : JPEG, PNG. Taille max : 2MB</p>
                            @if($prestataire && $prestataire->photo)
                                <button type="button" onclick="deletePhoto()" class="mt-2 text-sm text-red-600 hover:text-red-500 font-medium">Supprimer la photo</button>
                            @endif
                        </div>
                    </div>
                </div>
                        
                <!-- Informations de base -->
                <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-4 sm:p-6">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-purple-900">Informations personnelles</h3>
                            <p class="text-sm text-purple-700">Ces informations seront visibles par les clients.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nom -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-purple-900 mb-2">Nom complet *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" required>
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-purple-900 mb-2">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" required>
                        </div>
                        
                        <!-- Téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-bold text-purple-900 mb-2">Téléphone</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $prestataire->phone ?? '') }}" class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        </div>
                        
                        <!-- Secteur d'activité -->
                        <div>
                            <label for="sector" class="block text-sm font-bold text-purple-900 mb-2">Secteur d'activité</label>
                            <select name="sector" id="sector" class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-white">
                                <option value="">Sélectionnez un secteur</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('sector', $prestataire->sector ?? '') === $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                        
                <!-- Présentation / Biographie -->
                <div class="bg-white rounded-xl shadow-lg border border-purple-100 p-4 sm:p-6">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-bold text-purple-900">Présentation professionnelle</h3>
                            <p class="text-sm text-purple-700">Décrivez votre expertise, votre expérience et ce qui vous différencie. Minimum 200 caractères.</p>
                        </div>
                    </div>
                    <div>
                        <textarea name="description" id="description" rows="6" class="w-full px-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none" placeholder="Présentez votre expertise, votre expérience, vos points forts et votre manière de travailler...">{{ old('description', $prestataire->description ?? '') }}</textarea>
                        <div class="mt-2 flex justify-between items-center">
                            <p class="text-sm text-purple-700">Cette description améliore l'aspect humain de votre profil et favorise la confiance client.</p>
                            <span id="char-count" class="text-sm font-bold text-purple-600">0/2000</span>
                        </div>
                    </div>
                </div>
                        
                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-6 border-t border-purple-100">
                    <a href="{{ route('prestataire.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base bg-white border-2 border-purple-200 text-purple-700 font-bold rounded-xl shadow-lg hover:bg-purple-50 hover:border-purple-300 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="hidden sm:inline">Annuler</span>
                        <span class="sm:hidden">Annuler</span>
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-purple-700 hover:to-indigo-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span class="hidden sm:inline">Enregistrer les modifications</span>
                        <span class="sm:hidden">Enregistrer</span>
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Section Suppression du compte -->
        <div class="bg-white rounded-2xl shadow-xl border border-red-100 mt-6 sm:mt-8">
            <div class="px-4 sm:px-8 py-4 sm:py-6 border-b border-red-100">
                <h3 class="text-lg sm:text-xl font-bold text-red-600">Zone dangereuse</h3>
                <p class="mt-2 text-xs sm:text-sm text-gray-600">Actions irréversibles concernant votre compte.</p>
            </div>
            <div class="px-4 sm:px-8 py-4 sm:py-6">
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row">
                        <div class="flex-shrink-0 mb-3 sm:mb-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="sm:ml-4">
                            <h3 class="text-base sm:text-lg font-semibold text-red-800">Supprimer définitivement mon compte</h3>
                            <div class="mt-2 sm:mt-3 text-xs sm:text-sm text-red-700">
                                <p class="mb-2 sm:mb-3">Cette action est irréversible. Toutes vos données seront définitivement supprimées :</p>
                                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                                    <li>Vos informations personnelles et profil</li>
                                    <li>Tous vos services et offres</li>
                                    <li>Votre historique de missions</li>
                                    <li>Vos messages et conversations</li>
                                    <li>Vos évaluations et avis reçus</li>
                                    <li>Votre portfolio et réalisations</li>
                                </ul>
                            </div>
                            <div class="mt-4 sm:mt-6">
                                <button type="button" onclick="openDeleteModal()" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 w-full sm:w-auto justify-center">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="hidden sm:inline">Supprimer mon compte</span>
                                    <span class="sm:hidden">Supprimer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-11/12 max-w-md sm:w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-red-100">
                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mt-2">Confirmer la suppression</h3>
            <div class="mt-2 px-3 sm:px-7 py-3">
                <p class="text-xs sm:text-sm text-gray-500 mb-4">
                    Pour confirmer la suppression de votre compte, veuillez :
                </p>
                <form id="deleteForm" method="POST" action="{{ route('prestataire.profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="password" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Saisissez votre mot de passe :</label>
                        <input type="password" id="password" name="password" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmation" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Tapez "DELETE" pour confirmer :</label>
                        <input type="text" id="confirmation" name="confirmation" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="DELETE">
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-3 sm:space-x-4 mt-6">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-sm bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors order-2 sm:order-1">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors order-1 sm:order-2">
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Compteur de caractères pour la description
document.getElementById('description').addEventListener('input', function() {
    const count = this.value.length;
    const counter = document.getElementById('char-count');
    counter.textContent = count + '/2000';
    
    if (count < 200) {
        counter.className = 'text-sm text-red-500';
    } else if (count > 1800) {
        counter.className = 'text-sm text-orange-500';
    } else {
        counter.className = 'text-sm text-green-500';
    }
});

// Initialiser le compteur
document.addEventListener('DOMContentLoaded', function() {
    const description = document.getElementById('description');
    if (description.value) {
        description.dispatchEvent(new Event('input'));
    }
});

// Supprimer la photo de profil
function deletePhoto() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
        fetch('{{ route('prestataire.profile.delete-photo') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection