<!-- Profil du client/prestataire -->
<div class="bg-white rounded-lg shadow-sm border p-4 w-full max-w-md mx-auto">
    @if(auth()->user()->role === 'client')
        <div class="text-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-center">
                <i class="fas fa-user-tie text-blue-500 mr-2"></i> 
                Prestataire
            </h2>
        </div>
        
        <div class="flex flex-col items-center space-y-3 mb-4">
            <div class="relative flex-shrink-0">
                @if($booking->prestataire->photo)
                    <img src="{{ asset('storage/' . $booking->prestataire->photo) }}" 
                         alt="{{ $booking->prestataire->user->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                @elseif($booking->prestataire->user->avatar)
                    <img src="{{ asset('storage/' . $booking->prestataire->user->avatar) }}" 
                         alt="{{ $booking->prestataire->user->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-xl">{{ substr($booking->prestataire->user->name, 0, 1) }}</span>
                    </div>
                @endif
                @if($booking->prestataire->isVerified())
                    <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                    </div>
                @endif
            </div>
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2">
                    <h3 class="text-sm font-medium text-gray-900">{{ $booking->prestataire->user->name }}</h3>
                    @if($booking->prestataire->isVerified())
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Vérifié
                        </span>
                    @endif
                </div>
                @if($booking->prestataire->location)
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $booking->prestataire->location }}
                    </p>
                @endif
            </div>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('prestataires.show', $booking->prestataire) }}" 
               class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-user mr-2"></i> Voir le profil
            </a>
            <a href="{{ route('messaging.conversation', $booking->prestataire->user) }}" 
               class="w-full inline-flex items-center justify-center px-3 py-2 border border-blue-300 text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-comment mr-2"></i> Message
            </a>
        </div>
    @else
        <div class="text-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-center">
                <i class="fas fa-user text-blue-500 mr-2"></i> 
                Client
            </h2>
        </div>
        
        <div class="flex flex-col items-center space-y-3 mb-4">
            <div class="flex-shrink-0">
                @if($booking->client->user->profile_photo)
                    <img src="{{ asset('storage/' . $booking->client->user->profile_photo) }}" 
                         alt="{{ $booking->client->user->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                @elseif($booking->client->user->avatar)
                    <img src="{{ asset('storage/' . $booking->client->user->avatar) }}" 
                         alt="{{ $booking->client->user->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 bg-gray-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold text-xl">{{ substr($booking->client->user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="text-center">
                <h3 class="text-sm font-medium text-gray-900">{{ $booking->client->user->name }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-user-tag mr-1"></i>
                    Client
                </p>
            </div>
        </div>
        
        <div class="text-center">
            <a href="{{ route('messaging.conversation', $booking->client->user) }}" 
               class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-comment mr-2"></i> Envoyer un message
            </a>
        </div>
    @endif
    
    @if($booking->status === 'completed' && auth()->user()->role === 'client')
        <div class="border-t pt-3 mt-3 text-center">
            <a href="{{ route('reviews.create', ['prestataire' => $booking->prestataire->id, 'booking' => $booking->id]) }}" 
               class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                <i class="fas fa-star mr-2"></i> Laisser un avis
            </a>
        </div>
    @endif
</div>