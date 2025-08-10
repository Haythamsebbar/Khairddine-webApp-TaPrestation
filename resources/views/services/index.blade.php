@extends('layouts.app')

@section('title', 'Tous les services - TaPrestation')

@section('content')
<div class="bg-blue-50">
    <!-- Bannière d'en-tête -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">
                    Services Professionnels
                </h1>
                <p class="text-lg text-blue-700">
                    Découvrez l'expertise de nos prestataires qualifiés pour tous vos besoins.
                </p>
            </div>
        </div>
    </div>
                        
        <!-- Section des filtres -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-8">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-blue-800 mb-2">Filtres de recherche</h3>
                    <p class="text-lg text-blue-700">Affinez votre recherche pour trouver le service parfait</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('services.index') }}" class="space-y-6" id="filtersForm" style="display: none;">
                <!-- Première ligne de filtres -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Catégorie -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                        <div class="relative">
                            <i class="fas fa-tags absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="category" id="category" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Prix minimum -->
                    <div>
                        <label for="price_min" class="block text-sm font-medium text-gray-700 mb-2">Prix minimum</label>
                        <div class="relative">
                            <i class="fas fa-euro-sign absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" placeholder="Tous les prix" min="0" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <!-- Disponibilité -->
                    <div>
                        <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Disponibilité</label>
                        <div class="relative">
                            <i class="fas fa-calendar-check absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="availability" id="availability" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Toutes les disponibilités</option>
                                <option value="immediate" {{ request('availability') == 'immediate' ? 'selected' : '' }}>Disponible immédiatement</option>
                                <option value="week" {{ request('availability') == 'week' ? 'selected' : '' }}>Dans la semaine</option>
                                <option value="month" {{ request('availability') == 'month' ? 'selected' : '' }}>Dans le mois</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Tri par -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Trier par</label>
                        <div class="relative">
                            <i class="fas fa-sort absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="sort" id="sort" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="">Pertinence</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Deuxième ligne de filtres -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Localisation -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Localisation</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="location" id="location" value="{{ request('location') }}" placeholder="Ville ou code postal" class="w-full pl-10 pr-4 py-3 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <!-- Services premium -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="premium" value="1" {{ request('premium') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Services premium uniquement</span>
                        </label>
                    </div>
                    
                    <!-- Avec portfolio -->
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="with_portfolio" value="1" {{ request('with_portfolio') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class.ml-2 text-sm text-gray-700">Avec portfolio</span>
                        </label>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t-2 border-blue-200">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                        Appliquer les filtres
                    </button>
                    
                    <button type="button" onclick="clearFilters()" class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        Effacer tout
                    </button>
                    
                    @if(request()->anyFilled(['search', 'category', 'price_min', 'price_max', 'location', 'availability', 'premium', 'with_portfolio']))
                        <a href="{{ route('services.index') }}" class="bg-white hover:bg-gray-50 text-blue-600 border border-blue-200 font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                            Réinitialiser
                        </a>
                    @endif
                </div>
            </form>
            
            <!-- Affichage des résultats -->
            <div class="flex items-center justify-between pt-4 border-t-2 border-blue-200 mt-6">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-blue-800">Résultats :</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
                        {{ $services->total() }} service(s)
                    </span>
                </div>
                @if($services->total() > 0)
                    <div class="text-sm font-semibold text-blue-700">
                        {{ $services->pluck('prestataire_id')->unique()->count() }} prestataires actifs
                    </div>
                @endif
            </div>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    const filtersForm = document.getElementById('filtersForm');
    const buttonText = document.getElementById('filterButtonText');
    const chevron = document.getElementById('filterChevron');
    
    toggleButton.addEventListener('click', function() {
        if (filtersForm.style.display === 'none') {
            filtersForm.style.display = 'block';
            buttonText.textContent = 'Masquer les filtres';
            chevron.classList.remove('fa-chevron-down');
            chevron.classList.add('fa-chevron-up');
        } else {
            filtersForm.style.display = 'none';
            buttonText.textContent = 'Afficher les filtres';
            chevron.classList.remove('fa-chevron-up');
            chevron.classList.add('fa-chevron-down');
        }
    });
});
</script>

        <!-- Section des résultats -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($services as $service)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-blue-100 service-card">
                        <!-- Images du service -->
                        @if($service->images && $service->images->count() > 0)
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ asset('storage/' . $service->images->first()->image_path) }}" 
                                     alt="{{ $service->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                @if($service->images->count() > 1)
                                    <div class="absolute top-3 right-3 bg-black bg-opacity-60 text-white px-2 py-1 rounded-full text-xs">
                                        <i class="fas fa-images mr-1"></i>
                                        {{ $service->images->count() }}
                                    </div>
                                @endif
                                @if($service->price)
                                    <div class="absolute bottom-3 right-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-lg shadow-lg">
                                            <span class="text-lg font-bold">{{ number_format($service->price, 0, ',', ' ') }}€</span>
                                            @if($service->price_type)
                                                <div class="text-xs text-white opacity-90">/ {{ $service->price_type }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="relative h-64 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-blue-400 mb-2"></i>
                                    <p class="text-blue-600 font-medium">Aucune image</p>
                                </div>
                                @if($service->price)
                                    <div class="absolute bottom-3 right-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-3 py-2 rounded-lg shadow-lg">
                                            <span class="text-lg font-bold">{{ number_format($service->price, 0, ',', ' ') }}€</span>
                                            @if($service->price_type)
                                                <div class="text-xs text-white opacity-90">/ {{ $service->price_type }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Contenu de la carte -->
                        <div class="p-6">
                            <!-- En-tête avec titre et prestataire -->
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-blue-900 mb-2 line-clamp-2">
                                {{ $service->title }}
                            </h3>
                                <div class="flex items-center text-gray-600 text-sm">
                                    <div class="relative mr-2">
                                        @if($service->prestataire->photo)
                                            <img src="{{ asset('storage/' . $service->prestataire->photo) }}" alt="{{ $service->prestataire->user->name }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600 text-xs"></i>
                                            </div>
                                        @endif
                                        @if($service->prestataire->isVerified())
                                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-white" style="font-size: 8px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $service->prestataire->user->name }}</span>
                                        @if($service->prestataire->isVerified())
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1" style="font-size: 10px;"></i>
                                                Vérifié
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700 mb-4 line-clamp-3 leading-relaxed">{{ $service->description }}</p>
                            
                            @if($service->categories->count() > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($service->categories->take(3) as $category)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                        @if($service->categories->count() > 3)
                                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-3 py-1 rounded-full">
                                                +{{ $service->categories->count() - 3 }} autres
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Informations complémentaires -->
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-6 pt-4 border-t border-gray-100">
                                <span class="flex items-center font-medium">
                                    {{ $service->address ? $service->address . ', ' : '' }}{{ $service->postal_code ? $service->postal_code . ' ' : '' }}{{ $service->city ? $service->city : ($service->prestataire->ville ?? 'Non spécifié') }}
                                </span>
                                <span class="flex items-center font-medium">
                                    {{ $service->created_at->diffForHumans() }}
                                </span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-col space-y-3 mt-auto pt-4">
                                <a href="{{ route('services.show', $service) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center flex items-center justify-center">
                                    Voir les détails
                                </a>
                                
                                @auth
                                    @if(auth()->user()->role === 'client')
                                        <a href="{{ route('bookings.create', $service) }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-3 px-4 rounded-lg transition duration-200 text-center flex items-center justify-center">
                                            Réserver maintenant
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-3 px-4 rounded-lg transition duration-200 text-center flex items-center justify-center">
                                        Se connecter pour réserver
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Message d'état vide harmonisé -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-blue-100">
                <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                    <div class="text-3xl text-blue-600">🔍</div>
                </div>
                <h3 class="text-xl font-bold text-blue-900 mb-3">Aucun service trouvé</h3>
                <p class="text-blue-700 mb-2">Nous n'avons trouvé aucun service correspondant à vos critères de recherche.</p>
                <p class="text-blue-600 mb-6">Essayez de modifier vos filtres ou explorez tous nos services.</p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(request()->anyFilled(['search', 'category', 'price_min', 'price_max', 'location', 'availability', 'premium', 'with_portfolio']))
                        <a href="{{ route('services.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Réinitialiser les filtres
                        </a>
                    @else
                        <a href="{{ route('services.index') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Voir tous les services
                        </a>
                    @endif
                    
                    <a href="{{ route('home') }}" 
                       class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-3 px-6 rounded-lg transition duration-200">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        @endif
    
        <!-- Pagination -->
        @if($services->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $services->appends(request()->query())->links() }}
            </div>
        @endif
        </div>
    </div>
</div>
@endsection