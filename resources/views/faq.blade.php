@extends('layouts.app')

@section('title', 'FAQ - TaPrestation')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-6xl mx-auto px-2 sm:px-3 lg:px-4 xl:px-6 py-3 sm:py-4 lg:py-6">
        <!-- En-tête -->
        <div class="mb-3 sm:mb-4 text-center">
            <h1 class="text-lg sm:text-xl font-bold text-blue-900 mb-1">Questions Fréquentes</h1>
            <p class="text-xs text-blue-700 px-0">Trouvez rapidement des réponses aux questions les plus courantes</p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-md border border-blue-200 p-2 sm:p-3 mb-3 sm:mb-4">
            <div class="relative max-w-2xl mx-auto">
                <input type="text" 
                       id="faq-search"
                       placeholder="Rechercher une question..." 
                       class="w-full px-2.5 py-1.5 sm:px-3 sm:py-2 pl-8 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- FAQ Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 sm:gap-3">
            <!-- General Questions -->
            <div class="bg-white rounded-lg shadow-md border border-blue-200 overflow-hidden">
                <div class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-blue-50 border-b border-blue-200">
                    <h2 class="text-sm font-bold text-blue-800 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Questions Générales
                    </h2>
                </div>
                <div class="p-2.5 sm:p-3 space-y-2.5 sm:space-y-3">
                    <div class="border-l-4 border-blue-200 pl-2 hover:bg-blue-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Qu'est-ce que TaPrestation ?</h3>
                        <p class="text-gray-600 text-xs">TaPrestation est une plateforme qui connecte les clients avec des prestataires qualifiés pour tous types de services. Nous facilitons la mise en relation entre les particuliers et professionnels.</p>
                    </div>
                    <div class="border-l-4 border-green-200 pl-2 hover:bg-green-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">L'inscription est-elle gratuite ?</h3>
                        <p class="text-gray-600 text-xs">Oui, l'inscription sur TaPrestation est totalement gratuite pour les clients et les prestataires. Nous ne prenons des commissions que sur les prestations réalisées.</p>
                    </div>
                    <div class="border-l-4 border-purple-200 pl-2 hover:bg-purple-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment fonctionne la plateforme ?</h3>
                        <p class="text-gray-600 text-xs">Les clients publient leurs demandes de service, les prestataires intéressés soumettent leurs offres, et les clients choisissent le prestataire qui leur convient le mieux.</p>
                    </div>
                </div>
            </div>

            <!-- For Clients -->
            <div class="bg-white rounded-lg shadow-md border border-blue-200 overflow-hidden">
                <div class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-green-50 border-b border-gray-200">
                    <h2 class="text-sm font-bold text-green-800 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Pour les Clients
                    </h2>
                </div>
                <div class="p-2.5 sm:p-3 space-y-2.5 sm:space-y-3">
                    <div class="border-l-4 border-blue-200 pl-2 hover:bg-blue-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment publier une demande de service ?</h3>
                        <p class="text-gray-600 text-xs">Après inscription, rendez-vous dans votre tableau de bord et cliquez sur "Publier une demande". Décrivez votre projet en détail pour recevoir les meilleures offres.</p>
                    </div>
                    <div class="border-l-4 border-green-200 pl-2 hover:bg-green-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment choisir le bon prestataire ?</h3>
                        <p class="text-gray-600 text-xs">Consultez les profils, les avis, les certifications et les portfolios. N'hésitez pas à communiquer avec plusieurs prestataires avant de faire votre choix.</p>
                    </div>
                    <div class="border-l-4 border-purple-200 pl-2 hover:bg-purple-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment payer pour une prestation ?</h3>
                        <p class="text-gray-600 text-xs">Les paiements se font via notre système sécurisé. Vous ne payez qu'une fois la prestation terminée et validée.</p>
                    </div>
                </div>
            </div>

            <!-- For Providers -->
            <div class="bg-white rounded-lg shadow-md border border-blue-200 overflow-hidden">
                <div class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-purple-50 border-b border-gray-200">
                    <h2 class="text-sm font-bold text-purple-800 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Pour les Prestataires
                    </h2>
                </div>
                <div class="p-2.5 sm:p-3 space-y-2.5 sm:space-y-3">
                    <div class="border-l-4 border-blue-200 pl-2 hover:bg-blue-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment devenir prestataire ?</h3>
                        <p class="text-gray-600 text-xs">Inscrivez-vous en tant que prestataire, complétez votre profil professionnel avec vos compétences, certifications et portfolio, puis soumettez votre demande de vérification.</p>
                    </div>
                    <div class="border-l-4 border-green-200 pl-2 hover:bg-green-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment recevoir des demandes ?</h3>
                        <p class="text-gray-600 text-xs">Une fois votre profil vérifié, vous recevrez des notifications pour les demandes correspondant à vos compétences. Vous pouvez également rechercher des demandes manuellement.</p>
                    </div>
                    <div class="border-l-4 border-purple-200 pl-2 hover:bg-purple-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Quels sont les frais de commission ?</h3>
                        <p class="text-gray-600 text-xs">Nous prélevons une commission de 10% sur chaque prestation réalisée via notre plateforme. Ce taux peut varier selon les catégories de services.</p>
                    </div>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="bg-white rounded-lg shadow-md border border-blue-200 overflow-hidden">
                <div class="px-2.5 py-1.5 sm:px-3 sm:py-2 bg-yellow-50 border-b border-gray-200">
                    <h2 class="text-sm font-bold text-yellow-800 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Support Technique
                    </h2>
                </div>
                <div class="p-2.5 sm:p-3 space-y-2.5 sm:space-y-3">
                    <div class="border-l-4 border-blue-200 pl-2 hover:bg-blue-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">La plateforme est-elle disponible sur mobile ?</h3>
                        <p class="text-gray-600 text-xs">Oui, TaPrestation est entièrement responsive et accessible sur tous les appareils. Une application mobile sera bientôt disponible.</p>
                    </div>
                    <div class="border-l-4 border-green-200 pl-2 hover:bg-green-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">J'ai oublié mon mot de passe, que faire ?</h3>
                        <p class="text-gray-600 text-xs">Cliquez sur "Mot de passe oublié" sur la page de connexion et suivez les instructions pour réinitialiser votre mot de passe via votre email.</p>
                    </div>
                    <div class="border-l-4 border-purple-200 pl-2 hover:bg-purple-50 transition duration-200 p-1.5 sm:p-2 rounded">
                        <h3 class="font-semibold text-gray-800 mb-1 text-xs">Comment contacter le support ?</h3>
                        <p class="text-gray-600 text-xs">Vous pouvez nous contacter via le formulaire de contact, par email à support@taprestation.com, ou via le chat en direct disponible dans votre tableau de bord.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Still need help? -->
        <div class="mt-3 sm:mt-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-md overflow-hidden">
            <div class="px-2.5 py-3 sm:px-3 sm:py-4 text-center">
                <div class="inline-flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white bg-opacity-20 text-white mb-2 sm:mb-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                    </svg>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-white mb-1.5">Vous n'avez pas trouvé votre réponse ?</h3>
                <p class="text-blue-100 mb-2 sm:mb-3 max-w-2xl mx-auto text-xs">
                    Notre équipe de support est là pour vous aider. Contactez-nous pour une assistance personnalisée.
                </p>
                <div class="flex flex-col gap-2 sm:flex-row sm:gap-2.5 justify-center">
                    <a href="{{ route('contact') }}" class="inline-flex items-center px-2.5 py-1.5 sm:px-3 sm:py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold shadow-sm text-xs">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contacter le support
                    </a>
                    <a href="{{ route('client.help.index') }}" class="inline-flex items-center px-2.5 py-1.5 sm:px-3 sm:py-2 bg-transparent border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold text-xs">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Centre d'aide complet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('faq-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const faqItems = document.querySelectorAll('.border-l-4');
    
    faqItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});
</script>
@endsection