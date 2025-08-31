@extends('layouts.app')

@section('content')
<style>
/* Enhanced blue color scheme and styling from bookings/create.blade.php */
.filter-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
}

.filter-button:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
}

.prestataire-card {
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.prestataire-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(59, 130, 246, 0.25);
    border-color: #93c5fd;
}

.specialty-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background-color: #dbeafe;
    color: #1e40af;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.75rem;
}

.prestataire-avatar {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 9999px;
    overflow: hidden;
    background-color: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #bfdbfe;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

@media (max-width: 640px) {
    .prestataire-avatar {
        width: 3rem;
        height: 3rem;
    }
}

.input-with-icon {
    position: relative;
}

.input-with-icon .icon {
    position: absolute;
    left: 1rem;
    top: 2.7rem;
    color: #6b7280;
    z-index: 10;
}

.input-with-icon input,
.input-with-icon select {
    padding-left: 2.5rem;
    border-radius: 0.75rem;
    border: 2px solid #d1d5db;
    transition: all 0.2s ease;
}

.input-with-icon input:focus,
.input-with-icon select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.filter-container {
    background-color: #f0f9ff;
    border-radius: 1rem;
    border: 1px solid #bfdbfe;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);
}

/* Enhanced button styles */
.btn-primary {
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.2s ease-in-out;
    border: none;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
}

.btn-primary:hover {
    background-color: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 6px 8px rgba(59, 130, 246, 0.3);
}

.btn-secondary {
    background-color: #e5e7eb;
    color: #374151;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.2s ease;
    border: none;
}

.btn-secondary:hover {
    background-color: #d1d5db;
    transform: translateY(-1px);
}

/* Enhanced toggle button */
#toggleFilters {
    background-color: #3b82f6;
    color: white;
    font-weight: 600;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
}

#toggleFilters:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
}

/* Enhanced rating stars */
.rating-star {
    transition: all 0.2s ease;
}

.rating-star:hover {
    transform: scale(1.1);
}

/* Enhanced action buttons */
.action-button {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.action-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Verified badge enhancement */
.verified-badge {
    background-color: #f59e0b;
    color: white;
    font-weight: 600;
    border-radius: 9999px;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Empty state enhancement */
.empty-state {
    background-color: #f0f9ff;
    border-radius: 1rem;
    border: 2px dashed #93c5fd;
    padding: 2rem;
    text-align: center;
}

/* Pagination enhancement */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination a,
.pagination span {
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.pagination a:hover {
    background-color: #dbeafe;
    color: #1e40af;
}

.pagination .active {
    background-color: #3b82f6;
    color: white;
}
</style>

<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
        <div class="mb-8 text-center">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-blue-900 mb-2">Nos prestataires de services</h1>
            <p class="text-lg text-blue-700">Trouvez le professionnel parfait pour vos besoins</p>
        </div>
    
    <!-- Filtres -->
    <div class="filter-container p-5 sm:p-6 mb-8">
        <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="text-center sm:text-left">
                <h2 class="text-xl sm:text-2xl font-bold text-blue-800 mb-1">Filtrer les prestataires</h2>
                <p class="text-sm text-blue-600">Affinez votre recherche pour trouver le prestataire parfait</p>
            </div>
            <button type="button" id="toggleFilters" class="px-5 py-3 rounded-lg transition duration-300 shadow-lg hover:shadow-xl flex items-center justify-center text-base font-bold">
                <span id="filterButtonText">Afficher les filtres</span>
                <i class="fas fa-chevron-down ml-2" id="filterChevron"></i>
            </button>
        </div>
        
        <form action="{{ route('prestataires.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="filtersForm" style="display: none;">
            <div class="input-with-icon">
                <label for="name" class="block text-sm font-semibold text-blue-800 mb-2">Nom</label>
                <span class="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input type="text" name="name" id="name" value="{{ request('name') }}" 
                    class="w-full text-base rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200" placeholder="Rechercher par nom">
            </div>
            
            <div class="input-with-icon">
                <label for="secteur" class="block text-sm font-semibold text-blue-800 mb-2">Secteur d'activité</label>
                <span class="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <input type="text" name="secteur" id="secteur" value="{{ request('secteur') }}" 
                    class="w-full text-base rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200" placeholder="Secteur d'activité">
            </div>
            
            <div class="input-with-icon">
                <label for="category" class="block text-sm font-semibold text-blue-800 mb-2">Catégorie de service</label>
                <span class="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </span>
                <select name="category" id="category" 
                    class="w-full text-base rounded-lg border-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition duration-200">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 sm:gap-0">
                <button type="submit" class="filter-button w-full sm:w-auto px-5 py-3">
                    <span class="icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </span>
                    Filtrer
                </button>
                @if(request()->anyFilled(['name', 'secteur', 'category']))
                    <a href="{{ route('prestataires.index') }}" class="sm:ml-3 text-center sm:text-left text-blue-600 hover:text-blue-800 font-semibold py-3 px-4 text-base rounded-lg bg-blue-100 hover:bg-blue-200 transition duration-200 flex items-center justify-center">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Liste des prestataires -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @forelse($prestataires as $prestataire)
            <div class="prestataire-card bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="p-5 sm:p-6">
                    <div class="flex items-center mb-4">
                        <div class="prestataire-avatar mr-4 relative flex-shrink-0">
                            @if($prestataire->photo)
                                <img src="{{ asset('storage/' . $prestataire->photo) }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @elseif($prestataire->user->avatar)
                                <img src="{{ asset('storage/' . $prestataire->user->avatar) }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @elseif($prestataire->user->profile_photo_url)
                                <img src="{{ $prestataire->user->profile_photo_url }}" alt="{{ $prestataire->user->name }}" class="h-full w-full object-cover rounded-full">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-500 rounded-full">
                                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($prestataire->isVerified())
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center border-2 border-white shadow-md">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <h3 class="text-xl font-bold text-blue-900 truncate">{{ $prestataire->user->name }}</h3>
                                @if($prestataire->isVerified())
                                    <span class="verified-badge flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Vérifié
                                    </span>
                                @endif
                            </div>
                            <span class="specialty-badge mt-1">{{ $prestataire->secteur_activite }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center my-3">
                        @php
                            $rating = isset($prestataire->reviews) ? $prestataire->reviews->avg('rating') : 0;
                            $rating = round($rating * 2) / 2;
                        @endphp
                        
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current rating-star" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                    </svg>
                                @elseif($i - 0.5 <= $rating)
                                    <svg class="w-5 h-5 text-yellow-400 fill-current rating-star" viewBox="0 0 24 24">
                                        <path d="M12 15.4V6.1L13.71 10.13L18.09 10.5L14.77 13.39L15.76 17.67M22 9.24L14.81 8.63L12 2L9.19 8.63L2 9.24L7.45 13.97L5.82 21L12 17.27L18.18 21L16.54 13.97L22 9.24Z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 fill-current rating-star" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        
                        <span class="ml-2 text-sm text-blue-700 font-semibold">
                            {{ number_format($rating, 1) }}
                            <span class="text-blue-500">({{ isset($prestataire->reviews) ? $prestataire->reviews->count() : 0 }})</span>
                        </span>
                    </div>
                    
                    <p class="text-gray-700 mb-4 line-clamp-3">{{ $prestataire->description }}</p>
                    
                    <div class="mt-4 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
                        <div>
                            @if($prestataire->city || $prestataire->address || $prestataire->postal_code)
                                <div class="flex items-start text-sm text-blue-600 mb-2">
                                    <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        @if($prestataire->address)
                                            <div>{{ $prestataire->address }}</div>
                                        @endif
                                        @if($prestataire->city)
                                            <div>
                                                {{ $prestataire->city }}
                                                @if($prestataire->postal_code)
                                                    , {{ $prestataire->postal_code }}
                                                @endif
                                            </div>
                                        @elseif($prestataire->postal_code)
                                            <div>{{ $prestataire->postal_code }}</div>
                                        @endif
                                        @if($prestataire->country && $prestataire->country !== 'France')
                                            <div>{{ $prestataire->country }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('prestataires.show', $prestataire) }}" class="action-button inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg shadow-sm text-base font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                            Voir le profil
                        </a>
                    </div>
                    
                    @auth
                        @if(auth()->user()->isClient())
                            <div class="mt-3 pt-3 border-t border-gray-100 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('messaging.start', $prestataire) }}" class="action-button flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                    <svg class="-ml-1 mr-1 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Contacter
                                </a>
                                @if(auth()->user()->client && auth()->user()->client->isFollowing($prestataire->id))
                                    <form action="{{ route('client.prestataire-follows.unfollow', $prestataire) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-button w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                            <svg class="-ml-1 mr-1 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4 4 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            Suivi
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('client.prestataire-follows.follow', $prestataire) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="action-button w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                            <svg class="-ml-1 mr-1 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4 4 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
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
        @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 empty-state">
                <div class="text-blue-500 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-blue-900 mb-2">Aucun prestataire trouvé</h3>
                <p class="text-blue-800 mb-6">Aucun prestataire ne correspond à vos critères de recherche.</p>
                @if(request()->anyFilled(['name', 'secteur', 'category']))
                    <a href="{{ route('prestataires.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-lg hover:shadow-xl">
                        Réinitialiser les filtres
                    </a>
                @endif
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-8">
        <div class="pagination">
            {{ $prestataires->withQueryString()->links() }}
        </div>
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