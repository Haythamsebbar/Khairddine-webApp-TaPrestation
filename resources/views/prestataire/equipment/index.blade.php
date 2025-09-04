@extends('layouts.app')

@section('title', 'Mes équipements à louer')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100">
    <!-- Success message -->
    @if(session('success') || session('equipment_just_created'))
    <div class="container mx-auto px-4 py-4">
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        {{ session('success') ?? 'Équipement créé avec succès ! Vous ne pouvez pas revenir en arrière pour éviter les doublons.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- En-tête moderne -->
    <div class="bg-white shadow-lg border-b-4 border-green-600">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-green-900 mb-2">Mes équipements</h1>
                    <p class="text-green-700">Gérez vos équipements à louer</p>
                </div>
                <a href="{{ route('prestataire.equipment.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter un équipement
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques modernes -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition duration-200">
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
            
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition duration-200">
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
            
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition duration-200">
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
            
            <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 hover:shadow-xl transition duration-200">
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

        <!-- Filtres modernes -->
        <div class="bg-white rounded-xl shadow-lg border border-green-200 p-6 mb-8">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-center sm:text-left">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-1">Filtrer les équipements</h2>
                    <p class="text-sm text-gray-600">Affinez votre recherche par catégories et sous-catégories</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('prestataire.equipment.index') }}" class="flex flex-wrap gap-4" id="filtersForm" style="display: none;">
                <!-- Parent Category Filter -->
                <div>
                    <select name="category" id="parentCategory" class="px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-green-900">
                        <option value="">Catégorie principale</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subcategory Filter -->
                <div>
                    <select name="subcategory" id="subcategory" class="px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-green-900">
                        <option value="">Sous-catégorie</option>
                        @if(request('category') && $subcategories)
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <select name="status" class="px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-green-900">
                        <option value="">Tous les statuts</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>En location</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="unavailable" {{ request('status') === 'unavailable' ? 'selected' : '' }}>Indisponible</option>
                    </select>
                </div>
                
                <div>
                    <select name="sort" class="px-3 py-2 border border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-green-900">
                        <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Plus récent</option>
                        <option value="created_at_asc" {{ request('sort') === 'created_at_asc' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                
                @if(request()->hasAny(['status', 'category', 'subcategory', 'sort']))
                    <a href="{{ route('prestataire.equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('toggleFilters');
        const filtersForm = document.getElementById('filtersForm');
        const buttonText = document.getElementById('filterButtonText');
        const chevron = document.getElementById('filterChevron');
        const parentCategorySelect = document.getElementById('parentCategory');
        const subcategorySelect = document.getElementById('subcategory');
        
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
        
        // Handle parent category change to update subcategories
        if (parentCategorySelect && subcategorySelect) {
            parentCategorySelect.addEventListener('change', function() {
                const parentId = this.value;
                
                // Clear subcategory options
                subcategorySelect.innerHTML = '<option value="">Sous-catégorie</option>';
                
                if (parentId) {
                    // In a real implementation, we would make an AJAX call to get subcategories
                    // For now, we'll just submit the form to refresh the page with the new options
                    this.form.submit();
                }
            });
        }
    });
</script>
@endpush

@endsection

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