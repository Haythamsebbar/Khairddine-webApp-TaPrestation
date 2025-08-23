@extends('layouts.app')

@section('title', 'Ajouter un équipement')

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-green-900 mb-2">Ajouter un équipement</h1>
                <p class="text-base sm:text-lg text-green-700">Créez une nouvelle annonce pour votre équipement en 4 étapes simples</p>
            </div>

            <!-- Barre de progression -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-4 sm:p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-green-900">Processus de création</h2>
                    <span class="text-sm text-green-600">Étape 1 sur 4</span>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                            1
                        </div>
                        <span class="ml-2 text-sm font-medium text-green-900">Informations de base</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            2
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Tarifs et conditions</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                            3
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Photos</span>
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

            <!-- Redirection automatique -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                <p class="text-green-700 mb-2">Redirection vers la première étape...</p>
                <p class="text-sm text-gray-600">Si vous n'êtes pas redirigé automatiquement, <a href="{{ route('prestataire.equipment.create.step1') }}" class="text-green-600 hover:text-green-800 underline">cliquez ici</a>.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirection automatique après 1 seconde
    setTimeout(function() {
        window.location.href = '{{ route("prestataire.equipment.create.step1") }}';
    }, 1000);
</script>
@endsection