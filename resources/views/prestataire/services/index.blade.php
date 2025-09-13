@extends('layouts.app')

@section('title', 'Mes Services')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Success message -->
    @if(session('success') || session('service_just_created'))
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
                        {{ session('success') ?? 'Service créé avec succès ! Vous ne pouvez pas revenir en arrière pour éviter les doublons.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- En-tête moderne -->
    <div class="bg-white shadow-lg border-b-4 border-blue-600">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-blue-900 mb-2">Mes Services</h1>
                    <p class="text-blue-700">Gérez et organisez vos services professionnels</p>
                </div>
                <a href="{{ route('prestataire.services.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Ajouter un service
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Statistiques modernes -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-concierge-bell text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-700">Total des services</p>
                        <p class="text-2xl font-semibold text-blue-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-700">Services Réservables</p>
                        <p class="text-2xl font-semibold text-blue-900">{{ $stats['reservable'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-700">Réservations totales</p>
                        <p class="text-2xl font-semibold text-blue-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 hover:shadow-xl transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-check-double text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-700">Réservations confirmées</p>
                        <p class="text-2xl font-semibold text-blue-900">{{ $stats['confirmed_bookings'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres modernes -->
        <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 mb-8">
            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-center sm:text-left">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-1">Filtrer les services</h2>
                    <p class="text-sm text-gray-600">Affinez votre recherche pour trouver le service parfait</p>
                </div>
                <button type="button" id="toggleFilters" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                    <span id="filterButtonText">Afficher les filtres</span>
                    <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('prestataire.services.index') }}" class="flex flex-wrap gap-4" id="filtersForm" style="display: none;">
                <!-- Parent Category Filter -->
                <div>
                    <select name="parent_category" id="parentCategory" class="px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-blue-900">
                        <option value="">Catégorie principale</option>
                        @foreach($parentCategories as $category)
                            <option value="{{ $category->id }}" {{ request('parent_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Subcategory Filter -->
                <div>
                    <select name="subcategory" id="subcategory" class="px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-blue-900">
                        <option value="">Sous-catégorie</option>
                        @if(request('parent_category'))
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <select name="status" class="px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-blue-900">
                        <option value="">Tous les statuts</option>
                        <option value="reservable" {{ request('status') === 'reservable' ? 'selected' : '' }}>Réservable</option>
                        <option value="non-reservable" {{ request('status') === 'non-reservable' ? 'selected' : '' }}>Non réservable</option>
                    </select>
                </div>
                
                <div>
                    <select name="sort" class="px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-blue-900">
                        <option value="created_at_desc" {{ request('sort') === 'created_at_desc' ? 'selected' : '' }}>Plus récent</option>
                        <option value="created_at_asc" {{ request('sort') === 'created_at_asc' ? 'selected' : '' }}>Plus ancien</option>
                        <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Titre (A-Z)</option>
                        <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Titre (Z-A)</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                
                @if(request()->hasAny(['status', 'parent_category', 'subcategory', 'sort']))
                    <a href="{{ route('prestataire.services.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i>Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        <!-- Liste des services -->
        @if($services->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($services as $service)
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 hover:shadow-xl transition duration-200 flex flex-col h-full">
                        <!-- Image -->
                        <div class="relative">
                            @if($service->images->isNotEmpty())
                                <img src="{{ Storage::url($service->images->first()->image_path) }}" alt="{{ $service->title }}" class="w-full h-48 object-cover rounded-t-xl">
                            @else
                                <div class="w-full h-48 bg-blue-50 rounded-t-xl flex items-center justify-center">
                                    <i class="fas fa-image text-blue-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            <!-- Badge réservable -->
                            @if($service->reservable)
                                <span class="absolute top-2 left-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-calendar-check mr-1"></i>Réservable
                                </span>
                            @endif
                        </div>
                        
                        <!-- Contenu -->
                        <div class="p-4 flex-grow">
                            <h3 class="font-semibold text-lg text-blue-900 mb-2 line-clamp-2">{{ $service->title }}</h3>
                            <p class="text-blue-700 text-sm mb-3 line-clamp-3">{{ $service->description }}</p>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                @forelse($service->categories as $category)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $category->name }}</span>
                                @empty
                                    <span class="text-xs text-blue-500 italic">Non catégorisé</span>
                                @endforelse
                            </div>

                            <div class="flex justify-between items-center text-sm text-blue-600">
                                <span><i class="fas fa-book-open mr-1"></i>{{ $service->bookings->count() }} réservations</span>
                                <span><i class="fas fa-tag mr-1"></i>{{ $service->price ? number_format($service->price, 2, ',', ' ') . ' €' : 'Prix sur devis' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-4 bg-blue-50 rounded-b-xl border-t border-blue-200">
                            <div class="flex gap-2">
                                <a href="{{ route('services.show', $service) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </a>
                                <a href="{{ route('prestataire.services.edit', $service) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg">
                                    <i class="fas fa-edit mr-1"></i>Modifier
                                </a>
                                @if($service->reservable)
                                    <a href="{{ route('prestataire.availabilities.index', $service) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-lg transition duration-200 text-sm shadow-md hover:shadow-lg" title="Gérer les disponibilités">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                @endif
                                
                                <form action="{{ route('prestataire.services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
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
                {{ $services->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-8 text-center">
                <div class="w-24 h-24 mx-auto mb-6 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-concierge-bell text-blue-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-blue-900 mb-2">Aucun service trouvé</h3>
                <p class="text-blue-700 mb-6">Affinez vos critères de recherche ou créez un nouveau service.</p>
                <a href="{{ route('prestataire.services.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>Créer un service
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

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
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush