@extends('layouts.app')

@section('content')
<div class="py-10">
    <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold leading-tight text-gray-900">Mon Profil</h1>
            <p class="mt-2 text-sm text-gray-600">Gérez vos informations personnelles et vos préférences</p>
        </div>
    </header>
    <main>
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Informations de base -->
                        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-1">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Informations personnelles</h3>
                                    <p class="mt-1 text-sm text-gray-500">Ces informations seront visibles par les prestataires.</p>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div class="grid grid-cols-6 gap-6">
                                        <!-- Avatar -->
                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-gray-700">Photo de profil</label>
                                            <div class="mt-1 flex items-center space-x-5">
                                                @if(auth()->user()->client && auth()->user()->client->avatar)
                                                    <img class="h-20 w-20 rounded-full" src="{{ Storage::url(auth()->user()->client->avatar) }}" alt="Avatar actuel">
                                                @else
                                                    <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-xl font-medium text-gray-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col space-y-2">
                                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                                    @if(auth()->user()->client && auth()->user()->client->avatar)
                                                        <button type="button" onclick="deleteAvatar()" class="text-sm text-red-600 hover:text-red-500">Supprimer la photo</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Nom -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                        </div>
                                        
                                        <!-- Téléphone -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                                            <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->client->phone ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <!-- Localisation -->
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="location" class="block text-sm font-medium text-gray-700">Localisation</label>
                                            <input type="text" name="location" id="location" value="{{ old('location', auth()->user()->client->location ?? '') }}" placeholder="Ville, Région" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        
                                        <!-- Bio -->
                                        <div class="col-span-6">
                                            <label for="bio" class="block text-sm font-medium text-gray-700">Présentation</label>
                                            <textarea name="bio" id="bio" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Présentez-vous en quelques mots...">{{ old('bio', auth()->user()->client->bio ?? '') }}</textarea>
                                            <p class="mt-2 text-sm text-gray-500">Décrivez vos besoins habituels, vos préférences ou votre secteur d'activité.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Préférences -->
                        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <div class="md:col-span-1">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Préférences</h3>
                                    <p class="mt-1 text-sm text-gray-500">Configurez vos préférences de communication et de notifications.</p>
                                </div>
                                <div class="mt-5 md:mt-0 md:col-span-2">
                                    <div class="space-y-6">
                                        <!-- Notifications -->
                                        <fieldset>
                                            <legend class="text-base font-medium text-gray-900">Notifications</legend>
                                            <div class="mt-4 space-y-4">
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="email_notifications" name="email_notifications" type="checkbox" {{ old('email_notifications', auth()->user()->client->email_notifications ?? true) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="email_notifications" class="font-medium text-gray-700">Notifications par email</label>
                                                        <p class="text-gray-500">Recevoir des notifications pour les nouveaux messages et offres.</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="sms_notifications" name="sms_notifications" type="checkbox" {{ old('sms_notifications', auth()->user()->client->sms_notifications ?? false) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="sms_notifications" class="font-medium text-gray-700">Notifications SMS</label>
                                                        <p class="text-gray-500">Recevoir des SMS pour les messages urgents.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        
                                        <!-- Visibilité du profil -->
                                        <fieldset>
                                            <legend class="text-base font-medium text-gray-900">Visibilité</legend>
                                            <div class="mt-4 space-y-4">
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="public_profile" name="public_profile" type="checkbox" {{ old('public_profile', auth()->user()->client->public_profile ?? true) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="public_profile" class="font-medium text-gray-700">Profil public</label>
                                                        <p class="text-gray-500">Permettre aux prestataires de voir votre profil et vos avis.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('client.dashboard') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Annuler
                            </a>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Section Suppression du compte -->
                <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mt-6">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <h3 class="text-lg font-medium leading-6 text-red-600">Zone dangereuse</h3>
                            <p class="mt-1 text-sm text-gray-500">Actions irréversibles concernant votre compte.</p>
                        </div>
                        <div class="mt-5 md:mt-0 md:col-span-2">
                            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Supprimer définitivement mon compte</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Cette action est irréversible. Toutes vos données seront définitivement supprimées :</p>
                                            <ul class="list-disc list-inside mt-2 space-y-1">
                                                <li>Vos informations personnelles</li>
                                                <li>Votre historique de demandes</li>
                                                <li>Vos messages et conversations</li>
                                                <li>Vos évaluations et avis</li>
                                            </ul>
                                        </div>
                                        <div class="mt-4">
                                            <button type="button" onclick="openDeleteModal()" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Supprimer mon compte
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Pour confirmer la suppression de votre compte, veuillez :
                </p>
                <form id="deleteForm" method="POST" action="{{ route('client.profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Saisissez votre mot de passe :</label>
                        <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmation" class="block text-sm font-medium text-gray-700 mb-2">Tapez "DELETE" pour confirmer :</label>
                        <input type="text" id="confirmation" name="confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="DELETE">
                    </div>
                    
                    <div class="flex justify-center space-x-4 mt-6">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteAvatar() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
        fetch('{{ route('client.profile.delete-avatar') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression de la photo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression de la photo.');
        });
    }
}

function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteForm').reset();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection