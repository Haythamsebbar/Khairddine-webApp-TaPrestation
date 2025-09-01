@extends('layouts.app')

@push('styles')
<style>
    /* Adding the violet color scheme and styling from prestataire view */
    .profile-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .profile-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.25);
        border-color: #c4b5fd;
    }
    
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-left: 0.75rem;
    }
    
    /* Enhanced button styles */
    .btn-primary {
        background-color: #8b5cf6;
        color: white;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease-in-out;
        border: none;
        box-shadow: 0 4px 6px rgba(139, 92, 246, 0.2);
    }
    
    .btn-primary:hover {
        background-color: #7c3aed;
        transform: translateY(-1px);
        box-shadow: 0 6px 8px rgba(139, 92, 246, 0.3);
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
    
    /* Delete avatar button */
    .delete-avatar-btn {
        background-color: #f5f3ff;
        color: #7c3aed;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid #ddd6fe;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .delete-avatar-btn:hover {
        background-color: #ede9fe;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(139, 92, 246, 0.1);
    }
    
    /* Status badge enhancement */
    .status-badge {
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .status-pending {
        background-color: #f3e8ff;
        color: #7c2d12;
        border: 1px solid #d8b4fe;
    }
    
    .status-accepted {
        background-color: #ede9fe;
        color: #4c1d95;
        border: 1px solid #c4b5fd;
    }
    
    .status-completed {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    
    .status-rejected {
        background-color: #f5f3ff;
        color: #4c1d95;
        border: 1px solid #c7d2fe;
    }
    
    /* Adding the same color variations used in dashboard cards */
    .bg-purple-50 {
        background-color: #f5f3ff;
    }
    
    .text-purple-600 {
        color: #7c3aed;
    }
    
    .bg-purple-100 {
        background-color: #ede9fe;
    }
    
    .text-purple-700 {
        color: #6d28d9;
    }
    
    .bg-purple-200 {
        background-color: #ddd6fe;
    }
    
    .text-purple-800 {
        color: #5b21b6;
    }
    
    .bg-purple-500 {
        background-color: #8b5cf6;
    }
    
    .text-purple-900 {
        color: #4c1d95;
    }
    
    .border-purple-200 {
        border-color: #ddd6fe;
    }
    
    .border-purple-300 {
        border-color: #c4b5fd;
    }
    
    /* Responsive improvements */
    @media (max-width: 640px) {
        .section-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-title {
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .stat-icon {
            width: 2rem;
            height: 2rem;
        }
        
        .profile-card {
            padding: 1rem;
        }
        
        .delete-avatar-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-purple-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 sm:mb-8">
            <div class="text-center mb-6 sm:mb-8 fade-in-up">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-purple-900 mb-2">Mon Profil</h1>
                <p class="text-lg sm:text-xl text-purple-700 px-4">Gérez vos informations personnelles et vos préférences</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            <div class="lg:col-span-3">
                <div class="profile-card bg-white rounded-xl shadow-lg border border-purple-200 p-4 sm:p-6">
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
                        <div class="mb-6 bg-purple-50 border border-purple-200 text-purple-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Informations de base -->
                            <div class="profile-card bg-white rounded-xl shadow-lg border border-purple-200 p-4 sm:p-6">
                                <div class="section-header">
                                    <div class="stat-icon bg-purple-50">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="section-title text-purple-900">Informations personnelles</h3>
                                        <p class="text-sm text-purple-700">Ces informations seront visibles par les prestataires.</p>
                                    </div>
                                </div>
                                <div class="mt-5 md:mt-0">
                                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-4 sm:gap-6">
                                        <!-- Avatar -->
                                        <div class="col-span-1 sm:col-span-6">
                                            <label class="block text-sm font-semibold text-purple-700 mb-2">Photo de profil</label>
                                            <div class="mt-1 flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-5">
                                                @if(auth()->user()->client && auth()->user()->client->avatar)
                                                    <img class="h-20 w-20 rounded-full object-cover shadow-lg" src="{{ Storage::url(auth()->user()->client->avatar) }}" alt="Avatar actuel">
                                                @else
                                                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center shadow-lg">
                                                        <span class="text-xl font-medium text-purple-700">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col space-y-2 w-full sm:w-auto">
                                                    <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100">
                                                    @if(auth()->user()->client && auth()->user()->client->avatar)
                                                        <button type="button" onclick="deleteAvatar()" class="delete-avatar-btn w-full sm:w-auto">
                                                            Supprimer la photo
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Nom -->
                                        <div class="col-span-1 sm:col-span-3">
                                            <label for="name" class="block text-sm font-semibold text-purple-700 mb-2">Nom complet</label>
                                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-purple-300 rounded-lg px-4 py-3 transition duration-200" required>
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="col-span-1 sm:col-span-3">
                                            <label for="email" class="block text-sm font-semibold text-purple-700 mb-2">Email</label>
                                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-purple-300 rounded-lg px-4 py-3 transition duration-200" required>
                                        </div>
                                        
                                        <!-- Téléphone -->
                                        <div class="col-span-1 sm:col-span-3">
                                            <label for="phone" class="block text-sm font-semibold text-purple-700 mb-2">Téléphone</label>
                                            <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->client->phone ?? '') }}" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-purple-300 rounded-lg px-4 py-3 transition duration-200">
                                        </div>
                                        
                                        <!-- Localisation -->
                                        <div class="col-span-1 sm:col-span-3">
                                            <label for="location" class="block text-sm font-semibold text-purple-700 mb-2">Localisation</label>
                                            <input type="text" name="location" id="location" value="{{ old('location', auth()->user()->client->location ?? '') }}" placeholder="Ville, Région" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-purple-300 rounded-lg px-4 py-3 transition duration-200">
                                        </div>
                                        
                                        <!-- Bio -->
                                        <div class="col-span-1 sm:col-span-6">
                                            <label for="bio" class="block text-sm font-semibold text-purple-700 mb-2">Présentation</label>
                                            <textarea name="bio" id="bio" rows="4" class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-purple-300 rounded-lg px-4 py-3 transition duration-200" placeholder="Présentez-vous en quelques mots...">{{ old('bio', auth()->user()->client->bio ?? '') }}</textarea>
                                            <p class="mt-2 text-sm text-purple-600">Décrivez vos besoins habituels, vos préférences ou votre secteur d'activité.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Boutons d'action -->
                            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 mt-6">
                                <a href="{{ route('client.dashboard') }}" class="bg-white py-3 px-6 border border-purple-300 rounded-lg shadow-sm text-base font-bold text-purple-700 hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 profile-card text-center transition duration-200 w-full sm:w-auto">
                                    Annuler
                                </a>
                                <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-lg text-base font-bold rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 profile-card transition duration-200 hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Section Suppression du compte -->
                <div class="profile-card bg-white rounded-xl shadow-lg border border-red-200 p-4 sm:p-6 mt-6">
                    <div class="section-header">
                        <div class="stat-icon bg-red-50">
                            <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="section-title text-red-600">Zone dangereuse</h3>
                            <p class="text-sm text-purple-700">Actions irréversibles concernant votre compte.</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex flex-col sm:flex-row">
                                <div class="flex-shrink-0 mb-3 sm:mb-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="sm:ml-4 flex-1">
                                    <h3 class="text-sm font-bold text-red-800">Supprimer définitivement mon compte</h3>
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
                                        <button type="button" onclick="openDeleteModal()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 profile-card w-full sm:w-auto">
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
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-xl rounded-xl bg-white border-purple-200">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-purple-900 mt-3">Confirmer la suppression</h3>
            <div class="mt-4 px-7 py-3">
                <p class="text-purple-700 mb-4">
                    Pour confirmer la suppression de votre compte, veuillez :
                </p>
                <form id="deleteForm" method="POST" action="{{ route('client.profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="password" class="block text-md font-bold text-purple-800 mb-2">Saisissez votre mot de passe :</label>
                        <input type="password" id="password" name="password" required class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmation" class="block text-md font-bold text-purple-800 mb-2">Tapez "DELETE" pour confirmer :</label>
                        <input type="text" id="confirmation" name="confirmation" required class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" placeholder="DELETE">
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4 mt-6">
                        <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 bg-purple-100 text-purple-800 rounded-lg hover:bg-purple-200 transition-colors font-bold profile-card">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 profile-card">
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