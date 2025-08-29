<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-handshake text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900 hidden sm:block">TaPrestation</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition-all duration-200">
                        <i class="fas fa-home mr-2 text-xs"></i>{{ __('Accueil') }}
                    </x-nav-link>
                    
                    <!-- Trois sections principales -->
                    <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition-all duration-200">
                        <i class="fas fa-briefcase mr-2 text-xs text-blue-500"></i>{{ __('Services') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('equipment.index')" :active="request()->routeIs('equipment.*')" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-green-600 border-b-2 border-transparent hover:border-green-600 transition-all duration-200">
                        <i class="fas fa-tools mr-2 text-xs text-green-500"></i>{{ __('Matériel à louer') }}
                    </x-nav-link>

                    <x-nav-link :href="route('urgent-sales.index')" :active="request()->routeIs('urgent-sales.*')" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-red-600 border-b-2 border-transparent hover:border-red-600 transition-all duration-200">
                        <i class="fas fa-bolt mr-2 text-xs text-red-500"></i>{{ __('Annonces') }}
                    </x-nav-link>

                    <x-nav-link :href="route('videos.feed')" :active="request()->routeIs('videos.feed')" class="px-3 py-2 text-sm font-medium text-gray-700 hover:text-purple-600 border-b-2 border-transparent hover:border-purple-600 transition-all duration-200">
                        <i class="fas fa-video mr-2 text-xs text-purple-500"></i>{{ __('Vidéos') }}
                    </x-nav-link>
            
                </div>
            </div>

            <!-- User Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-3">
                @auth
                    <!-- Icônes fonctionnelles -->
                    <div class="flex items-center space-x-2">
                        <!-- Messagerie -->
                        <a href="{{ route('messaging.index') }}" class="relative p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" id="messaging-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <!-- Badge de notification dynamique -->
                            <span id="unread-messages-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </a>
                        
                        <!-- Notifications -->
                        <x-notification-dropdown />
                        
                        @if(Auth::user()->hasRole('prestataire'))
                            <!-- Mon Agenda (pour prestataires) -->
                            <a href="{{ route('prestataire.agenda.index') }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </a>
                            
                        @endif
                    </div>
                    
                    <x-dropdown align="right" width="80">
                        <x-slot name="trigger">
                            <button class="flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200">
                                <!-- Photo de profil -->
                                <div class="flex-shrink-0 mr-2">
                                    @if(Auth::user()->profile_photo_url)
                <img class="h-8 w-8 rounded-full object-cover border-2 border-gray-200" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                @else
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold border-2 border-gray-200">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Prénom uniquement -->
                                <div class="text-left hidden md:block">
                                    <div class="font-medium text-gray-800">{{ explode(' ', Auth::user()->name)[0] }}</div>
                                </div>

                                <!-- Icône flèche -->
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- En-tête du menu -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex items-center">
                                    @if(Auth::user()->profile_photo_url)
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
                        @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Liens du menu -->
                            <div class="py-1">
                                <!-- Tableau de bord (toujours en premier) -->
                                @if(Auth::user()->hasRole('client'))
                                    <x-dropdown-link :href="route('client.dashboard')" class="flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        {{ __('Tableau de bord') }}
                                    </x-dropdown-link>

                                    
                                @elseif(Auth::user()->hasRole('prestataire'))
                                    <x-dropdown-link :href="route('prestataire.dashboard')" class="flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        {{ __('Tableau de bord') }}
                                    </x-dropdown-link>
                                @else
                                    <x-dropdown-link :href="route('dashboard')" class="flex items-center font-medium">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        {{ __('Tableau de bord') }}
                                    </x-dropdown-link>
                                @endif

                                <!-- Séparateur -->
                                <div class="border-t border-gray-100 my-1"></div>

                                <!-- Mon profil -->
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('Mon profil') }}
                                </x-dropdown-link>

                                @if(Auth::user()->hasRole('prestataire'))
                                    <!-- Bloc 1: Mes services -->
                                    <div class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200">
                                        Mes services
                                    </div>
                                    <x-dropdown-link :href="route('prestataire.services.create')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Ajouter un service') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('prestataire.services.index')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                        </svg>
                                        {{ __('Gérer mes prestations') }}
                                    </x-dropdown-link>
                                    
                                    
                                    <x-dropdown-link :href="route('prestataire.videos.manage')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('Mes Vidéos') }}
                                    </x-dropdown-link>

                                    <!-- Bloc 2: Location de matériel -->
                                    <div class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200 mt-3">
                                        Location de matériel
                                    </div>
                                    <x-dropdown-link :href="route('prestataire.equipment.create')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Ajouter un équipement') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('prestataire.equipment.index')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        {{ __('Voir mes équipements') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('prestataire.equipment-rental-requests.index')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('Gérer les demandes') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('prestataire.equipment-rentals.index')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-green-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('Revenus liés à la location') }}
                                    </x-dropdown-link>

                                    <!-- Bloc 3: Annonces -->
                                    <div class="px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 border-b border-gray-200 mt-3">
                                        Annonces
                                    </div>
                                    <x-dropdown-link :href="route('prestataire.urgent-sales.create')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        {{ __('Mettre un produit en vente') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('prestataire.urgent-sales.index')" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        {{ __('Voir mes ventes actives') }}
                                    </x-dropdown-link>


                                    
                                    <!-- Séparateur -->
                                    <div class="border-t border-gray-200 mt-3"></div>

                                @elseif(Auth::user()->hasRole('client'))
                                    <!-- Menu spécifique client -->

                                    
                                    <x-dropdown-link :href="route('messaging.index')" class="flex items-center">
                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        {{ __('Mes messages') }}
                                    </x-dropdown-link>
                                @endif

                                <!-- Paramètres du compte -->
                                <x-dropdown-link :href="route('profile.settings')" class="flex items-center">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('Paramètres du compte') }}
                                </x-dropdown-link>
                            </div>

                            <!-- Séparateur et déconnexion -->
                            <div class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" class="flex items-center text-red-600 hover:bg-red-50"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        {{ __('Déconnexion') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Menu pour visiteurs non connectés -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all duration-200">
                            Connexion
                        </a>
                        
                        <!-- Menu déroulant inscription -->
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-all duration-200 shadow-sm flex items-center">
                                Inscription
                            </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Icons and Hamburger -->
            <div class="-mr-2 flex items-center space-x-2 sm:hidden">
                @auth
                    <!-- Icône de messagerie mobile -->
                    <a href="{{ route('messaging.index') }}" class="relative p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <!-- Badge de notification pour mobile -->
                        <span id="unread-messages-badge-mobile" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center hidden">0</span>
                    </a>
                    
                    <!-- Notifications mobile -->
                    <div class="relative" x-data="notificationDropdownMobile()">
                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a50.002 50.002 0 00-2.5-2.5V8a6 6 0 10-12 0v2.5c-1 1-2.5 2.5-2.5 2.5L5 17h5m5 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @php
                                $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                            @endif
                        </button>
                        
                        <!-- Dropdown mobile des notifications -->
                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50" style="display: none;">
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                                    @if($unreadCount > 0)
                                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-800">
                                            Tout marquer comme lu
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                
                                @php
                                    $recentNotifications = Auth::user()->notifications()->whereNull('read_at')->orderBy('created_at', 'desc')->limit(3)->get();
                                @endphp
                                
                                @if($recentNotifications->isEmpty())
                                    <div class="text-center py-6">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.5-3.5a50.002 50.002 0 00-2.5-2.5V8a6 6 0 10-12 0v2.5c-1 1-2.5 2.5-2.5 2.5L5 17h5m5 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-500">Aucune nouvelle notification</p>
                                    </div>
                                @else
                                    <div class="space-y-3 max-h-64 overflow-y-auto">
                                        @foreach($recentNotifications as $notification)
                                            @php
                                            $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
                                            $title = $data['title'] ?? 'Notification';
                                            $message = $data['message'] ?? '';
                                            @endphp
                                            
                                            <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $title }}</p>
                                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $message }}</p>
                                                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-800">
                                            Voir toutes les notifications
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endauth
                
                <!-- Hamburger -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-4 pb-3 space-y-1 px-4">
            <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                <i class="fas fa-home mr-3 text-blue-500 text-sm"></i>
                Accueil
            </a>
            
            <!-- Trois sections principales -->
            <a href="{{ route('services.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                <i class="fas fa-briefcase mr-3 text-blue-500 text-sm"></i>
                Services
            </a>
            
            <a href="{{ route('equipment.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-green-600 border-l-3 border-transparent hover:border-green-600 transition-all duration-200">
                <i class="fas fa-tools mr-3 text-green-500 text-sm"></i>
                Matériel à louer
            </a>

            <a href="{{ route('videos.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-purple-600 border-l-3 border-transparent hover:border-purple-600 transition-all duration-200">
                <i class="fas fa-video mr-3 text-purple-500 text-sm"></i>
                Vidéos
            </a>
            
            <a href="{{ route('urgent-sales.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-red-600 border-l-3 border-transparent hover:border-red-600 transition-all duration-200">
                <i class="fas fa-bolt mr-3 text-red-500 text-sm"></i>
                Vente urgente
            </a>
            

            

        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 mb-4">
                    <div class="flex items-center space-x-3">
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        <div>
                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="space-y-1 px-4 pb-4">
                    <!-- Tableau de bord (toujours en premier) -->
                    @if(Auth::user()->hasRole('client'))
                        <a href="{{ route('client.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200 font-medium">
                            <i class="fas fa-tachometer-alt mr-3 text-blue-500 text-sm"></i>
                            Tableau de bord
                        </a>
                    @elseif(Auth::user()->hasRole('prestataire'))
                        <a href="{{ route('prestataire.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200 font-medium">
                            <i class="fas fa-tachometer-alt mr-3 text-blue-500 text-sm"></i>
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200 font-medium">
                            <i class="fas fa-tachometer-alt mr-3 text-blue-500 text-sm"></i>
                            Tableau de bord
                        </a>
                    @endif
                    
                    <!-- Séparateur -->
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                        <i class="fas fa-user mr-3 text-gray-500 text-sm"></i>
                        Mon profil
                    </a>
                    
                    @if(Auth::user()->hasRole('prestataire'))
                        <a href="{{ route('prestataire.services.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                            <i class="fas fa-briefcase mr-3 text-gray-500 text-sm"></i>
                            Mes services
                        </a>
                        <a href="{{ route('prestataire.equipment.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                            <i class="fas fa-tools mr-3 text-gray-500 text-sm"></i>
                            Matériel à louer
                        </a>


                        <a href="{{ route('messaging.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                            <i class="fas fa-comments mr-3 text-gray-500 text-sm"></i>
                            Mes messages
                        </a>
                    @elseif(Auth::user()->hasRole('client'))
                        <a href="{{ route('messaging.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                            <i class="fas fa-comments mr-3 text-gray-500 text-sm"></i>
                            Mes messages
                        </a>
                    @endif
                    
                    <a href="{{ route('profile.settings') }}" class="flex items-center px-4 py-3 text-gray-700 hover:text-blue-600 border-l-3 border-transparent hover:border-blue-600 transition-all duration-200">
                        <i class="fas fa-cog mr-3 text-gray-500 text-sm"></i>
                        Paramètres du compte
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:text-red-700 border-l-3 border-transparent hover:border-red-600 transition-all duration-200">
                            <i class="fas fa-sign-out-alt mr-3 text-sm"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div> 
        @else
            <div class="pt-4 pb-4 border-t border-gray-200 px-4">
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all duration-200 border border-gray-200">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-all duration-200">
                        Inscription
                    </a>
                </div>
            </div>
        @endauth
    </div>
</nav>

<script>
function notificationDropdownMobile() {
    return {
        open: false,
        modalOpen: false,
        modalTitle: '',
        modalMessage: '',
        modalTime: '',
        modalUrl: '',
        showModal(id, title, message, time, url) {
            this.modalTitle = title;
            this.modalMessage = message;
            this.modalTime = time;
            this.modalUrl = url;
            this.modalOpen = true;
            // Mark as read via AJAX
            fetch(`/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
        },
        closeModal() {
            this.modalOpen = false;
        }
    }
}
</script>