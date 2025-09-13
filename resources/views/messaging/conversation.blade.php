@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messaging.css') }}">
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

/* Custom styling for message input - using !important to override CSS file */
.message-input-container {
    border-top: 2px solid #e5e7eb !important;
    background-color: #f9fafb !important;
    position: fixed !important;
    bottom: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 100 !important;
    padding: 8px 16px !important;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1) !important;
    flex-shrink: 0 !important;
    width: 100% !important;
}

#message-input {
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    line-height: 1.5;
    border: 2px solid #e5e7eb !important;
    background-color: white !important;
    font-size: 1rem;
    min-height: 46px !important;
    max-height: 120px !important;
    overflow-y: auto !important;
}

#message-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    border-color: #3b82f6;
    outline: none;
}

#send-button {
    background-color: #3b82f6 !important;
    border-radius: 9999px !important; /* fully rounded */
    transform: translateY(0);
    transition: all 0.2s ease;
    height: 46px !important;
    width: 46px !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    flex-shrink: 0 !important;
}

#send-button:hover {
    background-color: #2563eb !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15) !important;
}

#send-button:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
}

/* Ensure the main container takes full height */
.messaging-page {
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
    position: relative !important;
}

/* Ensure messages container takes available space */
.messages-container {
    flex: 1 !important;
    overflow-y: auto !important;
    padding-bottom: 80px !important; /* Add padding at bottom to ensure content isn't hidden behind input */
}

/* Make sure conversation view takes full height */
.conversation-view {
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
    overflow: hidden !important;
}

/* Reduce conversation message spacing */
.message {
    margin-bottom: 0.5rem !important;
}

.message-bubble {
    padding: 0.5rem 0.75rem !important;
}

/* Fix scrolling issues */
html, body {
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
}

/* Make scrolling smoother */
.messages-container::-webkit-scrollbar {
    width: 6px !important;
}

.messages-container::-webkit-scrollbar-thumb {
    background-color: rgba(203, 213, 225, 0.8) !important;
    border-radius: 3px !important;
}

.messages-container::-webkit-scrollbar-track {
    background-color: transparent !important;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/messaging.js') }}" defer></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="bg-blue-50 h-screen w-screen flex flex-col overflow-hidden conversation-view">
    <div class="flex-1 flex flex-col w-full h-full overflow-hidden">
        <div class="messaging-page bg-white flex-1 overflow-hidden flex flex-col h-full w-full shadow-md sm:shadow-lg md:shadow-xl">
            <header class="messaging-header bg-white border-b border-blue-200 sticky top-0 z-20 w-full">
                <div class="conversation-header p-4 md:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="conversation-avatar flex-shrink-0 relative mr-4">
                                @if(isset($otherUser))
                                    @php
                                        $photoUrl = $otherUser->profile_photo_url;
                                    @endphp
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" 
                                             alt="{{ $otherUser->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg border-2 border-blue-200">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white {{ ($otherUser->is_online ?? false) ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-800 font-bold text-lg border-2 border-blue-300">
                                        ?
                                    </div>
                                @endif
                            </div>
                            
                            <div class="conversation-info flex-1 min-w-0">
                                <h2 class="text-xl font-bold text-blue-900 truncate">{{ $otherUser->name ?? 'User' }}</h2>
                                <div class="flex items-center space-x-2 text-sm text-blue-600 mt-1">
                                    @if(isset($otherUser) && $otherUser->role === 'prestataire')
                                        <span class="flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full font-medium">
                                            <i class="fas fa-tools mr-1"></i>
                                            <span>Prestataire</span>
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
                                            <span>En ligne</span>
                                        </span>
                                    @else
                                        <span class="flex items-center text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                            <span>{{ isset($otherUser) ? ($otherUser->online_status ?? 'Hors ligne') : 'Hors ligne' }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="flex items-center space-x-3">
                            <a href="{{ Auth::user()->hasRole('client') ? route('messaging.index') : (Auth::user()->hasRole('prestataire') ? route('prestataire.prestataire.messages.index') : '#') }}" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-800 hover:text-blue-900 transition-all duration-200">
                                <i class="fas fa-arrow-left text-base"></i>
                            </a>
                            
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-800 hover:text-blue-900 transition-all duration-200" title="Plus d'options">
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
            </header>
            
            <main class="flex-1 flex flex-col bg-white overflow-hidden" style="padding-bottom: 80px;">
                <div class="messages-container flex-1 overflow-y-auto bg-white" id="messages-container">
                    <div class="px-4 md:px-6 py-4 flex-1" id="messages-list">
                        @forelse($messages as $message)
                            <div class="mb-4 {{ $message->sender_id === Auth::id() ? 'flex justify-end' : 'flex justify-start' }}" 
                                 data-message-id="{{ $message->id }}">
                                <div class="max-w-xs md:max-w-md lg:max-w-lg {{ $message->sender_id === Auth::id() ? 'bg-blue-600 text-white' : 'bg-white border border-blue-200' }} rounded-2xl px-4 py-2 shadow-sm">
                                    <div class="text-base leading-relaxed break-words">
                                        {{ $message->content }}
                                    </div>
                                    @if($message->attachments && count($message->attachments) > 0)
                                        <div class="mt-2 space-y-1">
                                            @foreach($message->attachments as $attachment)
                                                <div class="{{ $message->sender_id === Auth::id() ? 'bg-blue-500' : 'bg-blue-50' }} rounded-lg p-2">
                                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="flex items-center space-x-1 {{ $message->sender_id === Auth::id() ? 'text-white hover:text-blue-100' : 'text-blue-700 hover:text-blue-900' }} transition-colors duration-200">
                                                        <i class="fas fa-paperclip text-xs"></i>
                                                        <span class="text-xs font-medium truncate">{{ basename($attachment) }}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="flex items-center justify-between mt-1 text-xs {{ $message->sender_id === Auth::id() ? 'text-blue-100' : 'text-blue-500' }}">
                                        <span>{{ $message->created_at->format('H:i') }}</span>
                                        @if($message->sender_id === Auth::id())
                                            <span class="ml-1" data-message-id="{{ $message->id }}">
                                                @if($message->read_at)
                                                    <i class="fas fa-check-double text-blue-200" title="Lu le {{ $message->read_at->format('d/m/Y à H:i') }}"></i>
                                                @else
                                                    <i class="fas fa-check text-blue-300" title="Envoyé"></i>
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full min-h-[60vh] text-center px-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-comment-dots text-2xl text-blue-400"></i>
                                </div>
                                <h3 class="text-lg font-bold text-blue-900 mb-2">Commencez la conversation</h3>
                                <p class="text-sm text-blue-700 max-w-xs">
                                    Envoyez votre premier message à {{ isset($otherUser) ? $otherUser->name : 'ce contact' }} pour démarrer la discussion.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
                            
            </main>
        </div>
    </div>
</div>

<!-- Formulaire d'envoi de message -->
<div class="message-input-container bg-gray-50 border-t-2 border-blue-200 px-4 md:px-6 py-2 w-full">
                    <div class="w-full max-w-7xl mx-auto">
                        <form id="message-form" action="{{ isset($otherUser) ? (Auth::user()->hasRole('client') ? route('client.messaging.store', $otherUser) : (Auth::user()->hasRole('prestataire') ? url('prestataire/messages/' . $otherUser->id) : '#')) : '#' }}" method="POST" class="flex items-end space-x-2">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $otherUser->id ?? '' }}">
                            
                            <div class="flex-1 relative">
                                <textarea 
                                    name="content" 
                                    id="message-input" 
                                    placeholder="Tapez votre message..." 
                                    rows="1"
                                    required
                                    maxlength="1000"
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-300 text-sm shadow-sm"
                                    style="min-height: 40px;">{{ request('message', '') }}</textarea>
                                
                                <div class="flex items-center justify-between mt-1 px-1">
                                    <div class="typing-indicator text-xs text-blue-600" id="typing-indicator" style="display: none;">
                                        {{ isset($otherUser) ? $otherUser->name : 'L\'utilisateur' }} est en train d'écrire...
                                    </div>
                                    <div class="text-xs text-blue-400">
                                        <span id="char-count">0</span>/1000
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 hover:bg-blue-700 text-white transition-colors duration-200 shadow-md hover:shadow-lg" id="send-button">
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </form>
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
                    Êtes-vous sûr de vouloir supprimer définitivement votre conversation avec <strong>{{ $otherUser->name ?? 'cet utilisateur' }}</strong> ?
                </p>
                <p class="text-red-600 text-sm mb-6">
                    <i class="fas fa-warning mr-1"></i>
                    Cette action est irréversible. Tous les messages seront définitivement supprimés.
                </p>
                
                <form id="deleteForm" method="POST" action="{{ isset($otherUser) ? (Auth::user()->hasRole('client') ? route('client.messaging.delete', $otherUser) : (Auth::user()->hasRole('prestataire') ? url('prestataire/messages/' . $otherUser->id) : '#')) : '#' }}">
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
                
                // Also scroll on window resize to handle keyboard appearance on mobile
                window.addEventListener('resize', function() {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });
            }
            
            // Compteur de caractères
            const messageInput = document.getElementById('message-input');
            const charCount = document.getElementById('char-count');
            
            if (messageInput && charCount) {
                // Update character count
                messageInput.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
                
                // Initial character count
                charCount.textContent = messageInput.value.length;
                
                // Auto-resize du textarea
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = Math.min(Math.max(this.scrollHeight, 40), 120) + 'px';
                });
                
                // Ensure textarea has correct height on load
                setTimeout(function() {
                    messageInput.style.height = 'auto';
                    messageInput.style.height = Math.min(Math.max(messageInput.scrollHeight, 40), 120) + 'px';
                    messageInput.focus();
                }, 100);

                // Add keypress event for Enter key to send message
                messageInput.addEventListener('keypress', function(e) {
                    // Check if Enter key is pressed without Shift key
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        document.getElementById('send-button').click();
                    }
                });
            }
            
            // Override the form submission to manually handle it
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                // Remove any listeners added by MessagingSystem by cloning the form
                const clonedForm = messageForm.cloneNode(true);
                messageForm.parentNode.replaceChild(clonedForm, messageForm);
                
                // Add our own submit handler
                clonedForm.addEventListener('submit', function(e) {
                    const content = clonedForm.querySelector('#message-input').value;
                    const receiverId = clonedForm.querySelector('input[name="receiver_id"]').value;
                    
                    if (!content || content.trim() === '') {
                        e.preventDefault();
                        alert('Veuillez saisir un message.');
                        return false;
                    }
                    
                    if (!receiverId) {
                        e.preventDefault();
                        alert('Erreur: destinataire non spécifié.');
                        return false;
                    }
                    
                    // Show sending indicator
                    const sendButton = clonedForm.querySelector('#send-button');
                    if (sendButton) {
                        const originalIcon = sendButton.innerHTML;
                        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i>';
                        sendButton.disabled = true;
                        
                        // Reset after a short delay
                        setTimeout(function() {
                            sendButton.innerHTML = originalIcon;
                            sendButton.disabled = false;
                        }, 1000);
                    }
                    
                    // Allow the form to submit normally if all validation passes
                    return true;
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
@endsection