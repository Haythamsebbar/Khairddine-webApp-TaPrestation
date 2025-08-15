@extends('layouts.app')

@section('title', 'Ventes urgentes - TaPrestation')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Bannière d'en-tête -->
    <div class="bg-red-600 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-grid-pattern"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="text-center">
                <div class="inline-flex items-center justify-center bg-white bg-opacity-25 rounded-full w-16 h-16 mb-4">
                    <i class="fas fa-bolt text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                    Ventes Urgentes
                </h1>
                <p class="mt-4 text-xl text-red-100 max-w-2xl mx-auto">
                    Saisissez les meilleures affaires avant qu'il ne soit trop tard.
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Section des filtres -->
        <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 mb-8">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-red-800 mb-2">Filtres de recherche</h3>
                    <p class="text-lg text-red-700">Affinez votre recherche pour trouver les meilleures ventes urgentes</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('urgent-sales.index') }}" class="space-y-6" id="filtersForm" style="display: none;">
                <!-- Conserver les paramètres de recherche principaux -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                
                <!-- Première ligne de filtres -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Mot-clé -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Mot-clé</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Ordinateur portable, etc." class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <!-- Ville -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="city" id="city" value="{{ request('city') }}" placeholder="Paris, Lyon..." class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <!-- État -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">État</label>
                        <div class="relative">
                            <i class="fas fa-cog absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="condition" id="condition" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="">Tous les états</option>
                                @foreach($conditions as $value => $label)
                                    <option value="{{ $value }}" {{ request('condition') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Prix maximum -->
                    <div>
                        <label for="price_max" class="block text-sm font-medium text-gray-700 mb-2">Prix maximum</label>
                        <div class="relative">
                            <i class="fas fa-euro-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" placeholder="Prix max" min="0" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
                
                <!-- Deuxième ligne de filtres -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tri par -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <div class="relative">
                            <i class="fas fa-sort absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="sort" id="sort" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="">Pertinence</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récent</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="urgent" {{ request('sort') == 'urgent' ? 'selected' : '' }}>Urgence</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Ventes urgentes uniquement -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="urgent_only" value="1" {{ request('urgent_only') ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Ventes urgentes uniquement</span>
                        </label>
                    </div>
                    
                    <!-- Avec livraison -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="with_delivery" value="1" {{ request('with_delivery') ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Avec livraison</span>
                        </label>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t-2 border-red-200">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                        Appliquer les filtres
                    </button>
                    
                    <button type="button" onclick="clearFilters()" class="flex-1 bg-red-100 hover:bg-red-200 text-red-800 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        Effacer tout
                    </button>
                    
                    @if(request()->anyFilled(['search', 'city', 'condition', 'price_max', 'sort', 'urgent_only', 'with_delivery']))
                        <a href="{{ route('urgent-sales.index') }}" class="bg-white hover:bg-gray-50 text-red-600 border border-red-200 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Affichage des résultats -->
            <div class="flex items-center justify-between pt-4 border-t-2 border-red-200 mt-6">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-red-800">Résultats :</span>
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                        {{ $urgentSales->total() }} vente(s)
                    </span>
                </div>
                @if($urgentSales->total() > 0)
                    <div class="text-sm font-semibold text-red-700">
                        {{ $urgentSales->pluck('prestataire_id')->unique()->count() }} prestataires actifs
                    </div>
                @endif
            </div>
        </div>

        <!-- Résultats -->
        <div>

                <!-- Ventes urgentes en vedette -->
                @if($featuredSales->count() > 0 && !request()->hasAny(['search', 'city', 'price_min', 'price_max', 'condition']))
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-lg shadow-lg p-6 mb-8 text-white">
                        <h2 class="text-2xl font-bold mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Ventes urgentes du moment
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($featuredSales as $sale)
                                <a href="{{ route('urgent-sales.show', $sale) }}" class="bg-white/10 backdrop-blur-sm rounded-lg p-4 hover:bg-white/20 transition duration-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium">{{ Str::limit($sale->title, 30) }}</span>
                                        <span class="bg-white/20 px-2 py-1 rounded text-xs font-bold">URGENT</span>
                                    </div>
                                    <div class="text-2xl font-bold">{{ number_format($sale->price, 2) }}€</div>
                                    <div class="text-sm opacity-90">{{ $sale->prestataire->user->name }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Liste des ventes -->
                @if($urgentSales->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($urgentSales as $sale)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                                <a href="{{ route('urgent-sales.show', $sale) }}" class="block">
                                    <!-- Image -->
                                    <div class="relative h-48 bg-gray-200">
                                        @if($sale->photos && count($sale->photos) > 0)
                                            <img src="{{ Storage::url($sale->photos[0]) }}" alt="{{ $sale->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        @if($sale->is_urgent)
                                            <div class="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-md text-xs font-bold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                                </svg>
                                                URGENT
                                            </div>
                                        @endif
                                        
                                        <div class="absolute top-3 right-3 bg-black/70 text-white px-2 py-1 rounded-md text-xs font-medium">
                                            {{ ucfirst($sale->condition) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Contenu -->
                                    <div class="p-5">
                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 text-lg">{{ $sale->title }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $sale->description }}</p>
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-2xl font-bold text-red-600">{{ number_format($sale->price, 2) }}€</div>
                                            @if($sale->quantity > 1)
                                                <div class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Qté: {{ $sale->quantity }}</div>
                                            @endif
                                        </div>
                                        
                                       
                                        
                                        <div class="pt-3 border-t border-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full mr-2 flex items-center justify-center">
                                                        @if($sale->prestataire->user->avatar)
                                                            <img src="{{ Storage::url($sale->prestataire->user->avatar) }}" alt="{{ $sale->prestataire->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                                        @else
                                                            <span class="text-xs font-medium">{{ substr($sale->prestataire->user->name, 0, 1) }}</span>
                                                        @endif
                                                    </div>
                                                    <span class="truncate">{{ $sale->prestataire->user->name }}</span>
                                                </div>
                                                <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 text-xs font-medium">
                                                    Contacter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $urgentSales->links() }}
                    </div>
                @else
                    <!-- Message d'état vide -->
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucune vente urgente trouvée</h3>
                            <p class="text-gray-600 mb-6">Nous n'avons trouvé aucune vente urgente correspondant à vos critères de recherche. Essayez de modifier vos filtres ou explorez toutes nos ventes.</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                @if(request()->anyFilled(['search', 'city', 'condition']))
                                    <a href="{{ route('urgent-sales.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Réinitialiser les filtres
                                    </a>
                                @else
                                    <a href="{{ route('urgent-sales.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Voir toutes les ventes
                                    </a>
                                @endif
                                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour basculer l'affichage des filtres
function toggleFilters() {
    const filtersForm = document.getElementById('filtersForm');
    const filterButtonText = document.getElementById('filterButtonText');
    const filterChevron = document.getElementById('filterChevron');
    
    if (filtersForm.style.display === 'none' || filtersForm.style.display === '') {
        filtersForm.style.display = 'block';
        filterButtonText.textContent = 'Masquer les filtres';
        filterChevron.classList.remove('fa-chevron-down');
        filterChevron.classList.add('fa-chevron-up');
    } else {
        filtersForm.style.display = 'none';
        filterButtonText.textContent = 'Afficher les filtres';
        filterChevron.classList.remove('fa-chevron-up');
        filterChevron.classList.add('fa-chevron-down');
    }
}

// Fonction pour effacer tous les filtres
function clearFilters() {
    const form = document.getElementById('filtersForm');
    const inputs = form.querySelectorAll('input[type="text"], input[type="number"], select');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');
    
    inputs.forEach(input => {
        input.value = '';
    });
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Rediriger vers la page sans paramètres
    window.location.href = '{{ route("urgent-sales.index") }}';
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    toggleButton.addEventListener('click', toggleFilters);
    
    // Afficher les filtres si des paramètres sont présents
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = Array.from(urlParams.keys()).some(key => 
        ['search', 'city', 'condition', 'price_max', 'sort', 'urgent_only', 'with_delivery'].includes(key) && urlParams.get(key)
    );
    
    if (hasFilters) {
        toggleFilters();
    }
});
</script>

@endsection