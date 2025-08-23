@extends('layouts.app')

@section('title', 'Mes équipements à louer')

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush

@section('content')
<div class="bg-green-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-green-900 mb-2">Mes équipements</h1>
                <p class="text-lg text-green-700">Gérez vos équipements à louer</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <h2 class="text-xl font-bold text-green-900">Tableau de bord</h2>
                            <p class="text-green-700">Suivez et gérez vos équipements</p>
                        </div>
                    </div>
                    <a href="{{ route('prestataire.equipment.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un équipement
                    </a>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-tools text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Total des équipements</p>
                            <p class="text-2xl font-semibold text-green-900">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Disponibles</p>
                            <p class="text-2xl font-semibold text-green-900">{{ $stats['available'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">En location</p>
                            <p class="text-2xl font-semibold text-green-900">{{ $stats['rented'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-euro-sign text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Revenus ce mois</p>
                            <p class="text-2xl font-semibold text-green-900">{{ number_format($stats['monthly_revenue'] ?? 0, 0, ',', ' ') }}€</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-6">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-center sm:text-left">
                        <h2 class="text-xl font-bold text-green-900 mb-1">Filtres et recherche</h2>
                        <p class="text-sm text-green-700">Affinez votre recherche pour trouver l'équipement parfait</p>
                    </div>
                    <button type="button" id="toggleFilters" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                        <span id="filterButtonText">Afficher les filtres</span>
                        <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                    </button>
                </div>
                
                <form method="GET" action="{{ route('prestataire.equipment.index') }}" class="flex flex-wrap gap-4" id="filtersForm" style="display: none;">
                    <div class="flex-1 min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom..." class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <select name="category" class="px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="status" class="px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Tous les statuts</option>
                            <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>En location</option>
                            <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Indisponible</option>
                        </select>
                    </div>
                    
                    <div>
                        <select name="sort" class="px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Plus récent</option>
                            <option value="created_at_asc" {{ request('sort') === 'created_at_asc' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                            <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                    
                    @if(request()->hasAny(['search', 'status', 'category', 'sort']))
                        <a href="{{ route('prestataire.equipment.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-times mr-2"></i>Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            <!-- Liste des équipements -->
            @if($equipment->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($equipment as $item)
                        <div class="bg-white rounded-xl shadow-lg border border-green-200 hover:shadow-xl transition duration-200 flex flex-col">
                            <!-- Image -->
                            <div class="relative">
                                @if($item->main_photo)
                                    <img src="{{ Storage::url($item->main_photo) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover rounded-t-xl">
                                @else
                                    <div class="w-full h-48 bg-green-50 rounded-t-xl flex items-center justify-center">
                                        <i class="fas fa-tools text-green-400 text-3xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Badge statut -->
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-500 text-white',
                                        'rented' => 'bg-yellow-500 text-white',
                                        'maintenance' => 'bg-red-500 text-white',
                                        'unavailable' => 'bg-gray-500 text-white'
                                    ];
                                @endphp
                                <span class="absolute top-2 left-2 {{ $statusColors[$item->availability_status] ?? 'bg-gray-500 text-white' }} px-2 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-circle mr-1"></i>{{ $item->formatted_availability_status }}
                                </span>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="p-4 flex-grow">
                                <h3 class="font-semibold text-lg text-green-900 mb-2 line-clamp-2">{{ $item->name }}</h3>
                                <p class="text-green-700 text-sm mb-3 line-clamp-3">{{ $item->description }}</p>
                                
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if($item->category)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $item->category->name }}</span>
                                    @endif
                                    @if($item->subcategory)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $item->subcategory->name }}</span>
                                    @endif
                                    @if(!$item->category && !$item->subcategory)
                                        <span class="text-xs text-green-500 italic">Non catégorisé</span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center text-sm text-green-600">
                                    <span><i class="fas fa-star mr-1"></i>{{ number_format($item->average_rating ?? 0, 1) }} ({{ $item->reviews_count ?? 0 }})</span>
                                    <span><i class="fas fa-euro-sign mr-1"></i>{{ number_format($item->daily_rate, 0, ',', ' ') }}€/jour</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="p-4 bg-green-50 rounded-b-xl border-t border-green-200">
                                <div class="flex gap-2">
                                    <a href="{{ route('prestataire.equipment.show', $item) }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg">
                                        <i class="fas fa-eye mr-1"></i>Voir
                                    </a>
                                    <a href="{{ route('prestataire.equipment.edit', $item) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg">
                                        <i class="fas fa-edit mr-1"></i>Modifier
                                    </a>
                                    
                                    <form action="{{ route('prestataire.equipment.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $equipment->appends(request()->query())->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg border border-green-200 p-8 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tools text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-green-900 mb-2">Aucun équipement trouvé</h3>
                    <p class="text-green-700 mb-6">Vous n'avez pas encore ajouté d'équipement.</p>
                    <a href="{{ route('prestataire.equipment.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>Ajouter un équipement
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@endpush

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