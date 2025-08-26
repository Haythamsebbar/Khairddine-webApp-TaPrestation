@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messaging.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/messaging.js') }}" defer></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="messaging-page h-screen flex flex-col bg-gray-50 md:bg-white md:py-6" data-current-user-id="{{ Auth::id() }}" data-conversation-user-id="{{ $otherUser->id ?? '' }}">
    <header class="messaging-header bg-white border-b border-gray-200 sticky top-0 z-20 md:static md:border-0">
        <div class="max-w-7xl mx-auto px-0 sm:px-3 md:px-4 lg:px-8">
            <div class="messaging-container bg-white md:rounded-lg md:shadow-sm overflow-hidden">
                <div class="conversation-header p-3 sm:p-4 md:p-6 border-b border-gray-200">
                    <div class="flex items-center flex-1 min-w-0">
                        <a href="{{ route('messaging.index') }}" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-all duration-200 mr-3 sm:mr-4 touch-manipulation">
                            <i class="fas fa-arrow-left text-sm sm:text-base"></i>
                        </a>
                        
                        <div class="conversation-avatar flex-shrink-0">
                            @if(isset($otherUser) && $otherUser->client && $otherUser->client->photo)
                                <img src="{{ asset('storage/' . $otherUser->client->photo) }}" 
                                     alt="{{ $otherUser->name }}" 
                                     class="avatar-image">
                            @elseif(isset($otherUser) && $otherUser->prestataire && $otherUser->prestataire->photo)
                                <img src="{{ asset('storage/' . $otherUser->prestataire->photo) }}" 
                                     alt="{{ $otherUser->name }}" 
                                     class="avatar-image">
                            @elseif(isset($otherUser))
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="avatar-placeholder">
                                    ?
                                </div>
                            @endif
                            <div class="online-indicator {{ (isset($otherUser) && ($otherUser->is_online ?? false)) ? 'online' : 'offline' }}"></div>
                        </div>
                        
                        <div class="conversation-info flex-1 min-w-0">
                            <h2 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 truncate">{{ $otherUser->name ?? 'User' }}</h2>
                            <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-500">
                                @if(isset($otherUser) && $otherUser->role === 'prestataire')
                                    <span class="flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">
                                        <i class="fas fa-tools mr-1"></i>
                                        <span class="hidden sm:inline">Prestataire</span>
                                        <span class="sm:hidden">Prest.</span>
                                    </span>
                                @elseif(isset($otherUser))
                                    <span class="flex items-center px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">
                                        <i class="fas fa-user mr-1"></i>
                                        <span>Client</span>
                                    </span>
                                @endif
                                @if(isset($otherUser) && ($otherUser->is_online ?? false))
                                    <span class="flex items-center text-green-600">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                        <span class="hidden sm:inline">En ligne</span>
                                    </span>
                                @else
                                    <span class="flex items-center text-gray-400">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                        <span class="hidden sm:inline">{{ isset($otherUser) ? ($otherUser->online_status ?? 'Hors ligne') : 'Hors ligne' }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-1 sm:space-x-2 relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-all duration-200 touch-manipulation" title="Plus d'options">
                            <i class="fas fa-ellipsis-v text-sm"></i>
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
                             class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-blue-200 z-30">
                            <div class="py-1">
                                <button onclick="openDeleteModal()" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors flex items-center">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer la conversation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <main class="flex-1 flex flex-col bg-white md:bg-gray-50 overflow-hidden">
        <div class="flex-1 overflow-y-auto bg-white md:bg-gray-50" id="messages-container">
            <div class="max-w-4xl mx-auto px-3 sm:px-4 md:px-6 py-4 sm:py-6" id="messages-list">
                @forelse($messages as $message)
                    <div class="mb-4 sm:mb-6 {{ $message->sender_id === Auth::id() ? 'flex justify-end' : 'flex justify-start' }}" 
                         data-message-id="{{ $message->id }}">
                        <div class="max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl {{ $message->sender_id === Auth::id() ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200' }} rounded-2xl px-3 sm:px-4 py-2 sm:py-3 shadow-sm">
                            <div class="text-sm sm:text-base leading-relaxed break-words">
                                {{ $message->content }}
                            </div>
                            @if($message->attachments && count($message->attachments) > 0)
                                <div class="mt-2 sm:mt-3 space-y-2">
                                    @foreach($message->attachments as $attachment)
                                        <div class="{{ $message->sender_id === Auth::id() ? 'bg-indigo-500' : 'bg-gray-50' }} rounded-lg p-2 sm:p-3">
                                            <a href="{{ Storage::url($attachment) }}" target="_blank" class="flex items-center space-x-2 {{ $message->sender_id === Auth::id() ? 'text-white hover:text-indigo-100' : 'text-gray-700 hover:text-gray-900' }} transition-colors duration-200">
                                                <i class="fas fa-paperclip text-xs sm:text-sm"></i>
                                                <span class="text-xs sm:text-sm font-medium truncate">{{ basename($attachment) }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex items-center justify-between mt-2 text-xs {{ $message->sender_id === Auth::id() ? 'text-indigo-100' : 'text-gray-500' }}">
                                <span>{{ $message->created_at->format('H:i') }}</span>
                                @if($message->sender_id === Auth::id())
                                    <span class="ml-2" data-message-id="{{ $message->id }}">
                                        @if($message->read_at)
                                            <i class="fas fa-check-double text-indigo-200" title="Lu le {{ $message->read_at->format('d/m/Y à H:i') }}"></i>
                                        @else
                                            <i class="fas fa-check text-indigo-300" title="Envoyé"></i>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-h-96 text-center px-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                            <i class="fas fa-comment-dots text-2xl sm:text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Commencez la conversation</h3>
                        <p class="text-sm sm:text-base text-gray-600 max-w-sm">
                            Envoyez votre premier message à {{ isset($otherUser) ? $otherUser->name : 'ce contact' }} pour démarrer la discussion.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
                    
        <!-- Formulaire d'envoi de message -->
        <div class="bg-white border-t border-gray-200 px-3 sm:px-4 md:px-6 py-3 sm:py-4">
            <div class="max-w-4xl mx-auto">
                <form id="message-form" action="{{ isset($otherUser) ? route('messaging.store', $otherUser) : '#' }}" method="POST" class="flex items-end space-x-2 sm:space-x-3">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $otherUser->id ?? '' }}">
                    
                    <button type="button" class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-colors duration-200" title="Joindre un fichier">
                        <i class="fas fa-paperclip text-sm"></i>
                    </button>
                    
                    <div class="flex-1 relative">
                        <textarea 
                            name="content" 
                            id="message-input" 
                            placeholder="Tapez votre message..." 
                            rows="1"
                            required
                            maxlength="1000"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-2xl resize-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm sm:text-base">{{ request('message', '') }}</textarea>
                        
                        <div class="flex items-center justify-between mt-1 px-1">
                            <div class="typing-indicator text-xs text-gray-500" id="typing-indicator" style="display: none;">
                                {{ isset($otherUser) ? $otherUser->name : 'L\'utilisateur' }} est en train d'écrire...
                            </div>
                            <div class="text-xs text-gray-400">
                                <span id="char-count">0</span>/1000
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 transition-colors duration-200 touch-manipulation" title="Émojis">
                        <i class="fas fa-smile text-xs sm:text-sm"></i>
                    </button>
                    
                    <button type="submit" class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-700 text-white transition-colors duration-200 touch-manipulation" id="send-button">
                        <i class="fas fa-paper-plane text-xs sm:text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
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
                    Êtes-vous sûr de vouloir supprimer définitivement votre conversation avec <strong>{{ $otherUser->name ?? 'cet utilisateur' }}</strong> ?
                </p>
                <p class="text-red-600 text-sm mb-6">
                    <i class="fas fa-warning mr-1"></i>
                    Cette action est irréversible. Tous les messages seront définitivement supprimés.
                </p>
                
                <form id="deleteForm" method="POST" action="{{ isset($otherUser) ? route('messaging.delete', $otherUser) : '#' }}">
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
        // Initialiser le système de messagerie pour la conversation
        if (typeof MessagingSystem !== 'undefined') {
            window.messagingSystem = new MessagingSystem();
            
            // Faire défiler vers le bas au chargement
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            
            // Compteur de caractères
            const messageInput = document.getElementById('message-input');
            const charCount = document.getElementById('char-count');
            
            if (messageInput && charCount) {
                messageInput.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
                
                // Auto-resize du textarea
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                });
            }
        }
    });
    
    function openDeleteModal() {
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
</script>
@endsection