@extends('layouts.app')

@section('title', $urgentSale->title)

@section('content')
<div class="bg-red-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('prestataire.urgent-sales.index') }}" class="text-red-600 hover:text-red-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-extrabold text-red-900 mb-2">{{ $urgentSale->title }}</h1>
                    <div class="flex items-center mt-2 space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($urgentSale->status === 'active') bg-green-100 text-green-800
                            @elseif($urgentSale->status === 'sold') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $urgentSale->status_label }}
                        </span>
                        
                        <span class="text-red-700 text-sm">
                            Publié le {{ $urgentSale->created_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3">
                @if($urgentSale->canBeEdited())
                    <a href="{{ route('prestataire.urgent-sales.edit', $urgentSale) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                @endif
                
                @if($urgentSale->status === 'active')
                    <form action="{{ route('prestataire.urgent-sales.update-status', $urgentSale) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="status" value="sold">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200" onclick="return confirm('Marquer cette vente comme vendue ?')">
                            <i class="fas fa-check mr-2"></i>Marquer comme vendu
                        </button>
                    </form>
                @endif
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <div class="py-1">
                            @if($urgentSale->status === 'inactive')
                                <form action="{{ route('prestataire.urgent-sales.update-status', $urgentSale) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-play mr-2"></i>Activer
                                    </button>
                                </form>
                            @elseif($urgentSale->status === 'active')
                                <form action="{{ route('prestataire.urgent-sales.update-status', $urgentSale) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="inactive">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-pause mr-2"></i>Désactiver
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('urgent-sales.show', $urgentSale) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-external-link-alt mr-2"></i>Voir en public
                            </a>
                            
                            <form action="{{ route('prestataire.urgent-sales.destroy', $urgentSale) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-trash mr-2"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Colonne de gauche - Informations -->
            <div class="space-y-6">
                <!-- Informations principales -->
                <div class="bg-white rounded-xl shadow-lg border border-red-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-red-800 mb-5 border-b-2 border-red-200 pb-3">Informations</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm font-medium text-red-600">Prix</span>
                                <div class="text-3xl font-extrabold text-red-600">{{ number_format($urgentSale->price, 0, ',', ' ') }} €</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-red-600">État</span>
                                <div class="text-lg font-semibold text-red-900">{{ $urgentSale->condition_label }}</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-red-600">Quantité</span>
                                <div class="text-lg font-semibold text-red-900">{{ $urgentSale->quantity }}</div>
                            </div>
                            
                            <div>
                                <span class="text-sm font-medium text-red-600">Localisation</span>
                                <div class="text-lg font-semibold text-red-900">
                                    <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>{{ $urgentSale->location }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photos -->
                @if($urgentSale->photos && count(json_decode($urgentSale->photos, true) ?? []) > 0)
                    <div class="bg-white rounded-xl shadow-lg border border-red-200">
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-red-800 mb-5 border-b-2 border-red-200 pb-3">Photos</h2>
                            
                            <!-- Photo principale -->
                            <div class="mb-4">
                                <img id="main-image" src="{{ Storage::url(json_decode($urgentSale->photos, true)[0]) }}" alt="{{ $urgentSale->title }}" class="w-full h-64 object-cover rounded-lg">
                            </div>
                            
                            <!-- Miniatures -->
                            @if(count(json_decode($urgentSale->photos, true) ?? []) > 1)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach(json_decode($urgentSale->photos, true) ?? [] as $index => $photo)
                                        <img src="{{ Storage::url($photo) }}" alt="Photo {{ $index + 1 }}" class="w-full h-16 object-cover rounded cursor-pointer hover:opacity-75 transition-opacity {{ $index === 0 ? 'ring-2 ring-blue-500' : '' }}" onclick="changeMainImage('{{ Storage::url($photo) }}', this)">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Description -->
                <div class="bg-white rounded-xl shadow-lg border border-red-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-red-800 mb-5 border-b-2 border-red-200 pb-3">Description</h2>
                        <div class="prose max-w-none">
                            {!! nl2br(e($urgentSale->description)) !!}
                        </div>
                    </div>
                </div>
                
                

                
            </div>
            
            <!-- Colonne de droite - Messages reçus -->
            <div>
                <div>
                    <!-- Statistiques détaillées -->
                <div class="bg-white rounded-xl shadow-lg border border-red-200">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-red-800 mb-5 border-b-2 border-red-200 pb-3">Statistiques</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-2xl font-bold text-red-600">{{ $urgentSale->views_count }}</div>
                                <div class="text-sm text-red-700">Vues</div>
                            </div>
                            
                            <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-2xl font-bold text-red-600">{{ $urgentSale->contact_count }}</div>
                                <div class="text-sm text-red-700">Contacts</div>
                            </div>
                            
                            <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-2xl font-bold text-red-600">{{ $urgentSale->created_at->diffForHumans() }}</div>
                                <div class="text-sm text-red-700">En ligne depuis</div>
                            </div>
                            
                            <div class="text-center p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-2xl font-bold text-red-600">{{ $urgentSale->updated_at->diffForHumans() }}</div>
                                <div class="text-sm text-red-700">Dernière modification</div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                @if($relatedMessages->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg border border-red-200 h-fit">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-5">
                                <h2 class="text-2xl font-bold text-red-800 border-b-2 border-red-200 pb-3">Messages reçus</h2>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">{{ $relatedMessages->count() }}</span>
                            </div>
                            
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($relatedMessages as $message)
                                    <div class="bg-white border border-red-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-red-900 text-base">{{ $message->sender->name }}</span>
                                                    <div class="text-xs text-red-600 mt-1">
                                                        <i class="fas fa-clock mr-1"></i>{{ $message->created_at->format('d/m/Y à H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-xs text-red-500 bg-red-100 px-2 py-1 rounded-full">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="bg-red-50 rounded-lg p-4 mb-4 border border-red-200">
                                            <p class="text-red-800 leading-relaxed">{{ $message->content }}</p>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-red-600">
                                                <i class="fas fa-envelope mr-1"></i>Message reçu
                                            </div>
                                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-reply mr-2"></i>Répondre
                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6 text-center">
                                <a href="{{ route('messaging.index') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-red-100 hover:bg-red-200 text-red-700 hover:text-red-900 font-medium rounded-lg transition-colors duration-200 border border-red-300 hover:border-red-400">
                                    <i class="fas fa-envelope mr-2"></i>Voir tous les messages
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-lg border border-red-200 h-fit">
                        <div class="p-6 text-center">
                            <i class="fas fa-comments text-red-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-semibold text-red-900 mb-2">Aucun message</h3>
                            <p class="text-red-700 text-sm">Vous n'avez pas encore reçu de message concernant cette vente.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function changeMainImage(src, element) {
    document.getElementById('main-image').src = src;
    
    // Retirer la bordure de toutes les miniatures
    document.querySelectorAll('.grid img').forEach(img => {
        img.classList.remove('ring-2', 'ring-blue-500');
    });
    
    // Ajouter la bordure à la miniature cliquée
    element.classList.add('ring-2', 'ring-blue-500');
}
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

.prose {
    line-height: 1.6;
}
</style>
@endpush