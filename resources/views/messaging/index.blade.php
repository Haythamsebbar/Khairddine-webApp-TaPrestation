@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messaging.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/messaging.js') }}" defer></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-blue-900 mb-2">
                    <i class="fas fa-comments text-blue-600 mr-2 sm:mr-3"></i>
                    Messagerie
                </h1>
                <p class="text-base sm:text-lg text-blue-700 px-4">Communiquez avec vos prestataires ou clients en temps réel</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-blue-200 bg-blue-50">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
                        <div class="text-center sm:text-left">
                            <h3 class="text-xl sm:text-2xl font-bold text-blue-800 mb-1">Mes conversations</h3>
                            <p class="text-sm sm:text-base text-blue-600">
                                {{ $conversations->count() }} conversation{{ $conversations->count() > 1 ? 's' : '' }}
                            </p>
                        </div>
                        <div class="flex items-center justify-center sm:justify-end space-x-2 sm:space-x-3">
                            <div class="text-xs sm:text-sm text-blue-600 flex items-center">
                                <i class="fas fa-circle text-green-500 mr-1 sm:mr-2 text-xs"></i>
                                <span class="hidden sm:inline">En ligne</span>
                                <span class="sm:hidden">●</span>
                            </div>
                            <button onclick="location.reload()" class="px-2 sm:px-4 py-1.5 sm:py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition-colors font-medium text-sm sm:text-base">
                                <i class="fas fa-sync-alt mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Actualiser</span>
                                <span class="sm:hidden">↻</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="conversations-list">
                    @if($conversations->count() > 0)
                        @foreach($conversations as $conversation)
                            <div class="conversation-item border-b border-blue-100 last:border-b-0 hover:bg-blue-50 transition-colors cursor-pointer" 
                                 data-user-id="{{ $conversation['user']->id }}"
                                 onclick="window.location.href='{{ route('messaging.conversation', $conversation['user']->id) }}'">
                                <div class="flex items-center p-3 sm:p-6 space-x-3 sm:space-x-4 relative">
                                    <div class="conversation-avatar flex-shrink-0 relative">
                                        @if($conversation['user']->profile_photo_url)
                                            <img src="{{ $conversation['user']->profile_photo_url }}" 
                                                 alt="{{ $conversation['user']->name }}" 
                                                 class="w-10 h-10 sm:w-14 sm:h-14 rounded-full object-cover border-2 sm:border-3 border-blue-200 shadow-sm">
                                        @else
                                            <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm sm:text-lg border-2 sm:border-3 border-blue-200 shadow-sm">
                                                {{ strtoupper(substr($conversation['user']->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        @if($conversation['user']->is_online ?? false)
                                            <div class="absolute -bottom-0.5 -right-0.5 sm:-bottom-1 sm:-right-1 w-3 h-3 sm:w-4 sm:h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                        @endif
                                    </div>
                                
                                    <div class="conversation-content flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1 sm:mb-2">
                                            <h4 class="font-bold text-blue-900 text-base sm:text-lg truncate pr-2 sm:pr-4">{{ $conversation['user']->name }}</h4>
                                            <div class="flex items-center space-x-1 sm:space-x-3 flex-shrink-0">
                                                @if($conversation['last_message'])
                                                    <span class="text-xs sm:text-sm text-blue-600 whitespace-nowrap hidden sm:inline">
                                                        {{ $conversation['last_message']->created_at->diffForHumans() }}
                                                    </span>
                                                    <span class="text-xs text-blue-600 whitespace-nowrap sm:hidden">
                                                        {{ $conversation['last_message']->created_at->format('H:i') }}
                                                    </span>
                                                @endif
                                                @if($conversation['unread_count'] > 0)
                                                    <span class="bg-blue-600 text-white text-xs sm:text-sm font-bold px-2 sm:px-3 py-0.5 sm:py-1 rounded-full min-w-[1.25rem] sm:min-w-[1.5rem] text-center shadow-md">
                                                        {{ $conversation['unread_count'] }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center space-x-1 sm:space-x-2 mb-1 sm:mb-2">
                                            @if($conversation['user']->role === 'prestataire')
                                                <i class="fas fa-tools text-blue-500 text-xs sm:text-sm"></i>
                                                <span class="text-xs sm:text-sm text-blue-700 font-medium">Prestataire</span>
                                            @else
                                                <i class="fas fa-user text-green-500 text-xs sm:text-sm"></i>
                                                <span class="text-xs sm:text-sm text-blue-700 font-medium">Client</span>
                                            @endif
                                        </div>
                                        
                                        @if($conversation['last_message'])
                                            <p class="text-blue-600 line-clamp-2 leading-relaxed mb-2 sm:mb-3 text-sm sm:text-base">
                                                @if($conversation['last_message']->sender_id === Auth::id())
                                                    <i class="fas fa-reply text-blue-400 mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                                @endif
                                                {{ Str::limit($conversation['last_message']->content, 80) }}
                                            </p>
                                        @else
                                            <p class="text-blue-500 italic mb-2 sm:mb-3 text-sm sm:text-base">
                                                <i class="fas fa-comment-dots mr-1 sm:mr-2 text-xs sm:text-sm"></i>
                                                Commencer la conversation
                                            </p>
                                        @endif
                                        
                                        <!-- Three dots menu positioned on the right -->
                                        <div class="absolute top-2 sm:top-4 right-2 sm:right-4 z-10" onclick="event.stopPropagation()">
                                            <div class="relative" x-data="{ open: false }">
                                                <button @click="open = !open" 
                                                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-1.5 sm:py-2 px-2 sm:px-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                                    <i class="fas fa-ellipsis-v text-xs sm:text-sm"></i>
                                                </button>
                                                
                                                <!-- Dropdown Menu -->
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 scale-95"
                                                     x-transition:enter-end="opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="opacity-100 scale-100"
                                                     x-transition:leave-end="opacity-0 scale-95"
                                                     class="absolute right-0 bottom-full mb-2 w-48 sm:w-56 bg-white rounded-xl shadow-lg border border-blue-200 z-30">
                                                    <div class="py-1 sm:py-2">
                                                        @if($conversation['user']->role === 'prestataire' && isset($conversation['user']->prestataire) && $conversation['user']->prestataire)
                                                            <a href="{{ route('prestataires.show', $conversation['user']->prestataire) }}" 
                                                               class="flex items-center w-full text-left px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-blue-700 hover:bg-blue-50 hover:text-blue-800 transition-colors">
                                                                <i class="fas fa-user mr-2 sm:mr-3 text-blue-500"></i>
                                                                <span class="hidden sm:inline">Voir le profil du prestataire</span>
                                                                <span class="sm:hidden">Profil</span>
                                                            </a>
                                                        @else
                                                            <div class="flex items-center w-full px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-gray-500">
                                                                <i class="fas fa-user mr-2 sm:mr-3 text-gray-400"></i>
                                                                <span class="hidden sm:inline">Profil client non public</span>
                                                                <span class="sm:hidden">Non public</span>
                                                            </div>
                                                        @endif
                                                        
                                                        <hr class="my-1 border-blue-100">
                                                        
                                                        <button onclick="openDeleteModal({{ $conversation['user']->id }}, {{ json_encode($conversation['user']->name) }})" 
                                                                class="flex items-center w-full text-left px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                                            <i class="fas fa-trash mr-2 sm:mr-3 text-red-500"></i>
                                                            <span class="hidden sm:inline">Supprimer la conversation</span>
                                                            <span class="sm:hidden">Supprimer</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state flex flex-col items-center justify-center p-6 sm:p-12 text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-blue-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                                <i class="fas fa-comments text-2xl sm:text-3xl text-blue-400"></i>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-blue-900 mb-2 sm:mb-3">Aucune conversation</h3>
                            <p class="text-sm sm:text-base text-blue-700 max-w-sm sm:max-w-md leading-relaxed mb-6 sm:mb-8 px-4">
                                Vous n'avez pas encore de conversations. Commencez à échanger avec des 
                                {{ Auth::user()->role === 'client' ? 'prestataires' : 'clients' }} pour voir vos messages ici.
                            </p>
                            @if(Auth::user()->role === 'client')
                                <div class="empty-action">
                                    <a href="{{ route('prestataires.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm sm:text-base">
                                        <i class="fas fa-search mr-1 sm:mr-2"></i>
                                        <span class="hidden sm:inline">Trouver des prestataires</span>
                                        <span class="sm:hidden">Chercher</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-xl rounded-xl bg-white border-blue-200">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-blue-900 mt-3">Supprimer la conversation</h3>
            <div class="mt-4 px-7 py-3">
                <p class="text-blue-700 mb-4">
                    Êtes-vous sûr de vouloir supprimer définitivement votre conversation avec <strong id="deleteUserName"></strong> ?
                </p>
                <p class="text-red-600 text-sm mb-6">
                    <i class="fas fa-warning mr-1"></i>
                    Cette action est irréversible. Tous les messages seront définitivement supprimés.
                </p>
                
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex justify-center space-x-4 mt-6">
                        <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors font-bold">
                            <i class="fas fa-times mr-2"></i>
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser le système de messagerie
        if (typeof MessagingSystem !== 'undefined') {
            window.messagingSystem = new MessagingSystem();
        }
    });
    
    function openDeleteModal(userId, userName) {
        document.getElementById('deleteUserName').textContent = userName;
        document.getElementById('deleteForm').action = `/messaging/${userId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
    
    // Fermer le modal avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection