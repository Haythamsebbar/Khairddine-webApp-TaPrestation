@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6">
        <div class="max-w-6xl mx-auto">
            <!-- En-tête -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6 border border-purple-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-purple-800 mb-1 sm:mb-2">Gestion des vidéos</h1>
                        <p class="text-purple-600 text-sm sm:text-base">Gérez vos vidéos et modifiez leurs informations</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('prestataire.videos.create') }}" 
                           class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-medium text-sm sm:font-bold">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter une vidéo
                        </a>
                    </div>
                </div>
            </div>

            <!-- Messages de statut -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg mb-4 sm:mb-6 shadow-md text-xs sm:text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg mb-4 sm:mb-6 shadow-md text-xs sm:text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($videos->isEmpty())
                <!-- État vide -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-6 sm:p-8 text-center border border-purple-200">
                    <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 bg-purple-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 002 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-purple-800 mb-2 sm:mb-3">Aucune vidéo</h3>
                    <p class="text-purple-600 mb-4 sm:mb-6 text-sm sm:text-base">Vous n'avez pas encore ajouté de vidéos. Commencez par créer votre première vidéo.</p>
                    <a href="{{ route('prestataire.videos.create') }}" 
                       class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-medium text-sm sm:font-bold">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter ma première vidéo
                    </a>
                </div>
             @else
                 <!-- Liste des vidéos -->
                 <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden border border-purple-200">
                     <div class="px-4 py-3 sm:px-6 sm:py-4 border-b border-purple-200 bg-purple-50">
                         <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-purple-800">Mes vidéos ({{ $videos->count() }})</h2>
                     </div>
                
                <div class="divide-y divide-gray-200">
                    @foreach($videos as $video)
                        <div class="p-3 sm:p-4 md:p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                                <!-- Miniature vidéo -->
                                <div class="flex-shrink-0">
                                    <div class="relative w-24 h-16 sm:w-28 sm:h-20 md:w-32 md:h-24 bg-gray-900 rounded-lg overflow-hidden">
                                        <video class="w-full h-full object-cover" 
                                               src="{{ Storage::disk('public')->url($video->video_path) }}"
                                               muted
                                               preload="metadata">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="bg-black bg-opacity-50 rounded-full p-1.5 sm:p-2">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @if($video->duration)
                                            <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 bg-black bg-opacity-75 text-white text-xs px-1.5 py-0.5 sm:px-2 sm:py-1 rounded">
                                                {{ gmdate('i:s', $video->duration) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Informations vidéo -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                        <div class="flex-1">
                                            <h3 class="text-base sm:text-lg font-medium text-gray-900 truncate">
                                                {{ $video->title ?: 'Vidéo sans titre' }}
                                            </h3>
                                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                                Créée le {{ $video->created_at->format('d/m/Y à H:i') }}
                                            </p>
                                            @if($video->description)
                                                <p class="text-gray-600 mt-2 line-clamp-2 text-xs sm:text-sm">
                                                    {{ $video->description }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <!-- Statistiques -->
                                        <div class="flex items-center space-x-2 sm:space-x-3 text-xs sm:text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                {{ $video->likes->count() }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                {{ $video->comments->count() }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Statut -->
                                    <div class="mt-2 sm:mt-3">
                                        @if($video->status === 'published')
                                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Publiée
                                            </span>
                                        @elseif($video->status === 'processing')
                                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                En traitement
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="mt-3 sm:mt-4 flex flex-wrap gap-2">
                                        <a href="{{ route('prestataire.videos.edit', $video) }}" 
                                           class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 border border-purple-300 shadow-md text-xs sm:text-sm leading-4 font-medium rounded-lg text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Modifier
                                        </a>
                                        
                                        <form action="{{ route('prestataire.videos.destroy', $video) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 border border-red-300 shadow-md text-xs sm:text-sm leading-4 font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                     @endforeach
                 </div>
             </div>
             @endif
         </div>
     </div>
</div>
@endsection

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