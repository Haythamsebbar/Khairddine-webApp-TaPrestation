@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/prestataires-list.css') }}">
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 sm:mb-6 text-center sm:text-left">Nos prestataires de services</h1>
    
    <!-- Filtres -->
    <div class="filter-container p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-center sm:text-left">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-700 mb-1">Filtrer les prestataires</h2>
                <p class="text-sm text-gray-600">Affinez votre recherche pour trouver le prestataire parfait</p>
            </div>
            <button type="button" id="toggleFilters" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center text-sm sm:text-base">
                <span id="filterButtonText">Afficher les filtres</span>
                <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
            </button>
        </div>
        
        <form action="{{ route('prestataires.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4" id="filtersForm" style="display: none;">
            <div class="input-with-icon">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <span class="icon">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input type="text" name="name" id="name" value="{{ request('name') }}" 
                    class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div class="input-with-icon">
                <label for="secteur" class="block text-sm font-medium text-gray-700 mb-1">Secteur d'activité</label>
                <span class="icon">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <input type="text" name="secteur" id="secteur" value="{{ request('secteur') }}" 
                    class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            
            <div class="input-with-icon">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie de service</label>
                <span class="icon">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </span>
                <select name="category" id="category" 
                    class="w-full text-sm sm:text-base rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 sm:gap-0 col-span-1 sm:col-span-2 lg:col-span-1">
                <button type="submit" class="filter-button w-full sm:w-auto">
                    <span class="icon">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </span>
                    Filtrer
                </button>
                @if(request()->anyFilled(['name', 'secteur', 'category', 'skill']))
                    <a href="{{ route('prestataires.index') }}" class="sm:ml-2 text-center sm:text-left text-gray-600 hover:text-gray-800 font-medium py-2 px-4 text-sm">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Liste des prestataires -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @forelse($prestataires as $prestataire)
            <div class="prestataire-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4 sm:p-6 card-body">
                    <div class="flex items-center mb-3 sm:mb-4">
                        <div class="prestataire-avatar mr-3 sm:mr-4 relative flex-shrink-0">
                            @if($prestataire->photo)
                                <img src="{{ asset('storage/' . $prestataire->photo) }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @elseif($prestataire->user->avatar)
                                <img src="{{ asset('storage/' . $prestataire->user->avatar) }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @elseif($prestataire->user->profile_photo_url)
                                <img src="{{ $prestataire->user->profile_photo_url }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-500 rounded-full">
                                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($prestataire->isVerified())
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center border-2 border-white">
                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 truncate">{{ $prestataire->user->name }}</h3>
                                @if($prestataire->isVerified())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 self-start sm:self-auto">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Vérifié
                                    </span>
                                @endif
                            </div>
                            <span class="specialty-badge text-xs sm:text-sm">{{ $prestataire->secteur_activite }}</span>
                        </div>
                    </div>
                    
                    <p class="text-gray-700 mb-3 sm:mb-4 line-clamp-3 text-sm sm:text-base">{{ $prestataire->description }}</p>
                    
                    @if($prestataire->skills->count() > 0)
                        <div class="mb-3 sm:mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Compétences:</h4>
                            <div class="flex flex-wrap gap-1 sm:gap-2">
                                @foreach($prestataire->skills->take(3) as $skill)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 sm:px-2.5 py-0.5 rounded">
                                        {{ $skill->name }}
                                    </span>
                                @endforeach
                                @if($prestataire->skills->count() > 3)
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 sm:px-2.5 py-0.5 rounded">+{{ $prestataire->skills->count() - 3 }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 sm:gap-0">
                        <a href="{{ route('prestataires.show', $prestataire) }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Voir le profil
                        </a>
                        @auth
                            @if(auth()->user()->isClient())
                                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                    <a href="{{ route('messaging.start', $prestataire) }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Contacter
                                    </a>
                                    @if(auth()->user()->client && auth()->user()->client->isFollowing($prestataire->id))
                                        <form action="{{ route('client.prestataire-follows.unfollow', $prestataire) }}" method="POST" class="flex-1 sm:flex-initial">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-1 mr-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                Suivi
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('client.prestataire-follows.follow', $prestataire) }}" method="POST" class="flex-1 sm:flex-initial">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="-ml-1 mr-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                                Suivre
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 bg-white rounded-lg shadow-md p-6 sm:p-8 text-center">
                <p class="text-gray-600 text-base sm:text-lg">Aucun prestataire ne correspond à vos critères de recherche.</p>
                @if(request()->anyFilled(['name', 'secteur', 'category', 'skill']))
                    <a href="{{ route('prestataires.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-medium text-sm sm:text-base">
                        Réinitialiser les filtres
                    </a>
                @endif
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-6 sm:mt-8">
        {{ $prestataires->withQueryString()->links() }}
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
        
        let filtersVisible = false;
        
        toggleButton.addEventListener('click', function() {
            filtersVisible = !filtersVisible;
            
            if (filtersVisible) {
                filtersForm.style.display = 'grid';
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