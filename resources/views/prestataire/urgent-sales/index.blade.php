@extends('layouts.app')

@section('title', 'Mes Annonces')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-100">
    <!-- En-tête moderne -->
    <div class="bg-white shadow-lg border-b-4 border-red-600">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-red-900 mb-2">Mes Annonces</h1>
                    <p class="text-red-700">Gérez vos annonces et opportunités</p>
                </div>
                <a href="{{ route('prestataire.urgent-sales.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter une vente
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques modernes -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-tag text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-700">Total des annonces</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-700">Actives</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-eye text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-700">Vues totales</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $stats['total_views'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-700">Contacts reçus</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $stats['total_contacts'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres modernes -->
        <div class="bg-white rounded-xl shadow-lg border border-red-200 p-6 mb-8">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-center sm:text-left">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-1">Filtrer les ventes urgentes</h2>
                    <p class="text-sm text-gray-600">Affinez votre recherche pour trouver la vente urgente parfaite</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('prestataire.urgent-sales.index') }}" class="flex flex-wrap gap-4" id="filtersForm" style="display: none;">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par titre..." class="w-full px-3 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-red-900">
                </div>
                
                <div>
                    <select name="status" class="px-3 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-red-900">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Vendu</option>
                        
                    </select>
                </div>
                
                <div>
                    <select name="condition" class="px-3 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-red-900">
                        <option value="">Toutes les conditions</option>
                        <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>Neuf</option>
                        <option value="like_new" {{ request('condition') === 'like_new' ? 'selected' : '' }}>Comme neuf</option>
                        <option value="good" {{ request('condition') === 'good' ? 'selected' : '' }}>Bon état</option>
                        <option value="fair" {{ request('condition') === 'fair' ? 'selected' : '' }}>État correct</option>
                        <option value="poor" {{ request('condition') === 'poor' ? 'selected' : '' }}>Mauvais état</option>
                    </select>
                </div>
                
                <div>
                    <select name="sort" class="px-3 py-2 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-red-900">
                        <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Plus récent</option>
                        <option value="created_at_asc" {{ request('sort') === 'created_at_asc' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                        <option value="views_desc" {{ request('sort') === 'views_desc' ? 'selected' : '' }}>Plus vues</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                
                @if(request()->hasAny(['search', 'status', 'condition', 'sort']))
                    <a href="{{ route('prestataire.urgent-sales.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                @endif
            </form>
        </div>

    <!-- Liste des ventes modernes -->
    @if($urgentSales->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($urgentSales as $sale)
                <div class="bg-white rounded-xl shadow-lg border border-red-200 hover:shadow-xl transition duration-200">
                    <!-- Image -->
                    <div class="relative">
                        @if($sale->photos && count(json_decode($sale->photos, true) ?? []) > 0)
                            <img src="{{ Storage::url(json_decode($sale->photos, true)[0]) }}" alt="{{ $sale->title }}" class="w-full h-48 object-cover rounded-t-xl">
                        @else
                            <div class="w-full h-48 bg-red-50 rounded-t-xl flex items-center justify-center">
                                <i class="fas fa-image text-red-300 text-3xl"></i>
                            </div>
                        @endif
                        
                        <!-- Badge statut -->
                        <span class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-semibold shadow-md
                            @if($sale->status === 'active') bg-green-100 text-green-800
                            @elseif($sale->status === 'sold') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $sale->status_label }}
                        </span>
                    </div>
                    
                    <!-- Contenu -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-red-900 mb-2 line-clamp-2">{{ $sale->title }}</h3>
                        <p class="text-red-700 text-sm mb-3 line-clamp-2">{{ $sale->description }}</p>
                        
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-2xl font-bold text-red-600">{{ number_format($sale->price, 0, ',', ' ') }} DH</span>
                            <span class="text-sm text-red-700 bg-red-100 px-2 py-1 rounded-lg border border-red-200">
                                {{ ucfirst($sale->condition) }}
                            </span>
                        </div>
                        
                        <!-- Statistiques -->
                        <div class="flex justify-between text-sm text-red-600 mb-4">
                            <span><i class="fas fa-eye mr-1"></i>{{ $sale->views_count }} vues</span>
                            <span><i class="fas fa-envelope mr-1"></i>{{ $sale->contacts_count }} contacts</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $sale->city }}</span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('prestataire.urgent-sales.show', $sale) }}" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-center py-2 px-3 rounded-lg text-sm transition duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-1"></i>Voir
                            </a>
                            
                            <a href="{{ route('prestataire.urgent-sales.edit', $sale) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-3 rounded-lg text-sm transition duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-1"></i>Modifier
                            </a>
                            
                            @if($sale->contacts_count > 0)
                                <a href="{{ route('prestataire.urgent-sales.contacts', $sale) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm transition duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-envelope mr-1"></i>Contacts
                                </a>
                            @endif
                            
                            <form action="{{ route('prestataire.urgent-sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm transition duration-200 shadow-md hover:shadow-lg" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination moderne -->
        <div class="mt-8">
            {{ $urgentSales->appends(request()->query())->links() }}
        </div>
    @else
        <!-- État vide moderne -->
        <div class="bg-white rounded-xl shadow-lg border border-red-200 p-12 text-center">
            <div class="mb-6">
                <i class="fas fa-tag text-red-300 text-6xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-red-900 mb-3">Aucune vente urgente trouvée</h3>
            <p class="text-red-700 mb-6 max-w-md mx-auto">Vous n'avez pas encore créé de vente urgente ou aucune ne correspond à vos critères de recherche.</p>
            <a href="{{ route('prestataire.urgent-sales.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>Créer ma première vente
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleFilters');
        const filtersForm = document.getElementById('filtersForm');
        const buttonText = document.getElementById('filterButtonText');
        const chevron = document.getElementById('filterChevron');
        
        let filtersVisible = false;
        
        toggleButton.addEventListener('click', function() {
            filtersVisible = !filtersVisible;
            
            if (filtersVisible) {
                filtersForm.style.display = 'flex';
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
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush