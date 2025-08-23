@extends('layouts.admin-modern')

@section('content')
<div class="bg-blue-50 min-h-screen">
    <!-- Header -->
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6 sm:mb-8">
                <a href="{{ route('administrateur.clients.index') }}" class="inline-flex items-center bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2.5 px-4 rounded-lg transition duration-200 text-sm">
                    <i class="bi bi-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            <!-- Profil du client - Colonne de gauche -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                    <div class="px-4 sm:px-6 py-4 border-b border-blue-200">
                        <h3 class="text-xl font-bold text-blue-800">Profil du client</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <!-- Avatar et informations de base -->
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-blue-600 font-bold text-3xl">{{ substr($client->user->name, 0, 1) }}</span>
                            </div>
                            <h4 class="text-xl font-bold text-blue-900 mb-2">{{ $client->user->name }}</h4>
                            <p class="text-blue-600 mb-4">{{ $client->user->email }}</p>
                            
                            <!-- Statut -->
                            <div class="mb-6">
                                @if($client->user->blocked_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Bloqué depuis le {{ $client->user->blocked_at->format('d/m/Y à H:i') }}</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Actif</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3 mb-6">
                            @if(auth()->id() != $client->user_id)
                                <form action="{{ route('administrateur.clients.toggle-block', $client->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full {{ $client->user->blocked_at ? 'bg-green-100 hover:bg-green-200 text-green-800' : 'bg-yellow-100 hover:bg-yellow-200 text-yellow-800' }} font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="bi {{ $client->user->blocked_at ? 'bi-unlock' : 'bi-lock' }} mr-2"></i> 
                                        {{ $client->user->blocked_at ? 'Débloquer' : 'Bloquer' }}
                                    </button>
                                </form>
                                <form action="{{ route('administrateur.clients.destroy', $client->id) }}" method="POST" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-800 font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                        <i class="bi bi-trash mr-2"></i> Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Informations de base -->
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-sm font-bold text-blue-800 mb-1">Date d'inscription</h5>
                                <p class="text-blue-700">{{ $client->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-blue-800 mb-1">Dernière mise à jour</h5>
                                <p class="text-blue-700">{{ $client->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        <!-- Statistiques -->
                        <div class="mt-6">
                            <h5 class="text-lg font-bold text-blue-800 mb-4">Statistiques</h5>
                            <div class="space-y-3">
                                <div class="bg-blue-600 text-white rounded-lg p-3">
                                    <div class="text-center">
                                        <h5 class="text-xl font-bold mb-1">{{ $client->bookings->count() }}</h5>
                                        <p class="text-blue-100 text-sm">Réservations</p>
                                    </div>
                                </div>
                                <div class="bg-green-600 text-white rounded-lg p-3">
                                    <div class="text-center">
                                        <h5 class="text-xl font-bold mb-1">{{ $client->reviews->count() }}</h5>
                                        <p class="text-green-100 text-sm">Avis publiés</p>
                                    </div>
                                </div>
                                <div class="bg-indigo-600 text-white rounded-lg p-3">
                                    <div class="text-center">
                                        <h5 class="text-xl font-bold mb-1">{{ $client->follows->count() }}</h5>
                                        <p class="text-indigo-100 text-sm">Prestataires suivis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations détaillées - Colonne de droite -->
            <div class="lg:col-span-2">
                <div class="space-y-6">

                    <!-- Dernières réservations -->
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                        <div class="px-6 py-4 border-b border-blue-200">
                            <h3 class="text-xl font-bold text-blue-800">Dernières réservations</h3>
                        </div>
                        <div class="p-6">
                            @if($client->bookings->count() > 0)
                                <div class="space-y-4">
                                    @foreach($client->bookings->sortByDesc('created_at')->take(5) as $booking)
                                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                                            <div class="flex-1">
                                                <span class="text-blue-900 font-medium block">{{ $booking->service->title }}</span>
                                                <small class="text-blue-600 text-sm">{{ $booking->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($booking->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                                {{ $booking->status == 'pending' ? 'En attente' : ($booking->status == 'completed' ? 'Terminée' : 'En cours') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-blue-600 text-center py-8">Aucune réservation</p>
                            @endif
                        </div>
                    </div>

                    <!-- Derniers avis -->
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                        <div class="px-6 py-4 border-b border-blue-200">
                            <h3 class="text-xl font-bold text-blue-800">Derniers avis</h3>
                        </div>
                        <div class="p-6">
                            @if($client->reviews->count() > 0)
                                <div class="space-y-4">
                                    @foreach($client->reviews->sortByDesc('created_at')->take(5) as $review)
                                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                                            <div class="flex-1">
                                                <div class="flex mb-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-yellow-400' : 'bi-star text-gray-300' }} text-sm"></i>
                                                    @endfor
                                                </div>
                                                <small class="text-blue-600 text-sm block mb-1">{{ $review->created_at->format('d/m/Y') }}</small>
                                                <span class="text-blue-900 text-sm block">{{ Str::limit($review->comment, 100) }}</span>
                                            </div>
                                            <a href="{{ route('administrateur.reviews.show', $review->id) }}" class="ml-3 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-blue-600 text-center py-8">Aucun avis publié</p>
                            @endif
                        </div>
                    </div>

                    <!-- Prestataires suivis -->
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                        <div class="px-6 py-4 border-b border-blue-200">
                            <h3 class="text-xl font-bold text-blue-800">Prestataires suivis</h3>
                        </div>
                        <div class="p-6">
                            @if($client->follows->count() > 0)
                                <div class="space-y-4">
                                    @foreach($client->follows as $prestataire)
                                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                                            <div class="flex-1">
                                                <span class="text-blue-900 font-medium block">{{ $prestataire->user->name }}</span>
                                                <small class="text-blue-600 text-sm">{{ $prestataire->sector }}</small>
                                            </div>
                                            <a href="{{ route('administrateur.prestataires.show', $prestataire->id) }}" class="ml-3 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-blue-600 text-center py-8">Aucun prestataire suivi</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 50px;
        height: 50px;
        background-color: #007bff;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
    }
    .bi.small {
        font-size: 0.8rem;
    }
</style>
@endsection