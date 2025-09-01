@extends('layouts.app')

@section('title', 'À propos - TaPrestation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-6xl mx-auto px-2 sm:px-3 lg:px-4 xl:px-6 py-3 sm:py-4 lg:py-6">
        <!-- En-tête -->
        <div class="mb-3 sm:mb-4 text-center">
            <h1 class="text-lg sm:text-xl font-bold text-blue-900 mb-1">À propos de TaPrestation</h1>
            <p class="text-xs text-blue-700 px-0">La plateforme qui connecte les clients avec les meilleurs prestataires de services</p>
        </div>

        <!-- Mission Section -->
        <div class="bg-white rounded-lg shadow-md border border-blue-200 p-2.5 sm:p-3 mb-2.5 sm:mb-3">
            <h2 class="text-base font-bold text-center mb-2.5 sm:mb-3 text-blue-900">Notre Mission</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3">
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <p class="text-gray-600 mb-2 text-xs">
                        TaPrestation a été créée avec une vision simple : faciliter la rencontre entre les clients ayant des besoins spécifiques et les prestataires qualifiés capables de les satisfaire.
                    </p>
                    <p class="text-gray-600 text-xs">
                        Nous croyons que chaque projet mérite d'être réalisé par le bon professionnel, et que chaque prestataire mérite de trouver les clients qui valorisent son expertise.
                    </p>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <h3 class="text-sm font-semibold mb-2 text-blue-800 border-b-2 border-blue-200 pb-1">Nos Valeurs</h3>
                    <ul class="space-y-1">
                        <li class="flex items-center p-1.5 rounded-lg hover:bg-blue-100 transition duration-200">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700 font-medium text-xs">Qualité et professionnalisme</span>
                        </li>
                        <li class="flex items-center p-1.5 rounded-lg hover:bg-blue-100 transition duration-200">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700 font-medium text-xs">Transparence et confiance</span>
                        </li>
                        <li class="flex items-center p-1.5 rounded-lg hover:bg-blue-100 transition duration-200">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700 font-medium text-xs">Innovation et simplicité</span>
                        </li>
                        <li class="flex items-center p-1.5 rounded-lg hover:bg-blue-100 transition duration-200">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full mr-1.5 flex-shrink-0"></span>
                            <span class="text-gray-700 font-medium text-xs">Support et accompagnement</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-md border border-blue-200 p-2.5 sm:p-3 mb-2.5 sm:mb-3">
            <h2 class="text-base font-bold text-center mb-2.5 sm:mb-3 text-blue-900">Pourquoi choisir TaPrestation ?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 text-center hover:shadow-md transition duration-300">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-blue-800">Prestataires Vérifiés</h3>
                    <p class="text-gray-600 text-xs">Tous nos prestataires sont soigneusement sélectionnés et vérifiés pour garantir la qualité de leurs services.</p>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 text-center hover:shadow-md transition duration-300">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-blue-800">Tarifs Transparents</h3>
                    <p class="text-gray-600 text-xs">Pas de frais cachés. Les prestataires affichent leurs tarifs clairement et vous savez exactement ce que vous payez.</p>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 text-center hover:shadow-md transition duration-300">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-blue-800">Communication Facilitée</h3>
                    <p class="text-gray-600 text-xs">Notre système de messagerie intégré vous permet de communiquer facilement avec les prestataires.</p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white rounded-lg shadow-md border border-blue-200 p-2.5 sm:p-3 mb-2.5 sm:mb-3">
            <h2 class="text-base font-bold text-center mb-2.5 sm:mb-3 text-blue-900">TaPrestation en chiffres</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center">
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <div class="text-lg sm:text-xl font-bold text-blue-600 mb-1">500+</div>
                    <div class="text-gray-600 text-xs">Prestataires actifs</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <div class="text-lg sm:text-xl font-bold text-green-600 mb-1">1000+</div>
                    <div class="text-gray-600 text-xs">Projets réalisés</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <div class="text-lg sm:text-xl font-bold text-purple-600 mb-1">50+</div>
                    <div class="text-gray-600 text-xs">Catégories de services</div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-100 p-2.5 sm:p-3 hover:shadow-md transition duration-300">
                    <div class="text-lg sm:text-xl font-bold text-orange-600 mb-1">4.8/5</div>
                    <div class="text-gray-600 text-xs">Note moyenne</div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-md overflow-hidden">
            <div class="px-2.5 py-3 sm:px-3 sm:py-4 text-center">
                <h2 class="text-base font-bold mb-1.5 text-white">Une question ? Contactez-nous</h2>
                <p class="text-blue-100 mb-2 sm:mb-3 max-w-2xl mx-auto text-xs">
                    Notre équipe est là pour vous accompagner dans votre expérience sur TaPrestation.
                </p>
                <div class="flex flex-col gap-2 sm:flex-row sm:gap-2.5 justify-center">
                    <a href="mailto:contact@taprestation.com" class="bg-white text-blue-600 px-2.5 py-1.5 rounded-lg font-semibold hover:bg-blue-50 transition duration-300 shadow-sm text-xs">
                        Nous contacter
                    </a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white px-2.5 py-1.5 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300 text-xs">
                        Rejoindre TaPrestation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection