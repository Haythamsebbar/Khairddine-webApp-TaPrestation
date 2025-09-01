@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Modifier la vidéo</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Mettez à jour les informations de votre vidéo.</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('prestataire.videos.update', $video) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                    <div>
                        <label for="title" class="block text-xs sm:text-sm font-medium text-gray-700">Titre</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base" value="{{ old('title', $video->title) }}">
                    </div>

                    <div>
                        <label for="description" class="block text-xs sm:text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base">{{ old('description', $video->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Aperçu de la vidéo</label>
                        <div class="rounded-lg overflow-hidden border border-gray-200">
                            <video class="w-full h-auto bg-black max-h-48 sm:max-h-64" controls>
                                <source src="{{ Storage::disk('public')->url($video->video_path) }}" type="video/mp4">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        </div>
                    </div>
                </div>

                <div class="px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row justify-end items-center gap-3">
                    <a href="{{ route('prestataire.videos.manage') }}" class="text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 w-full sm:w-auto text-center py-2">Annuler</a>
                    <button type="submit" class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-xs sm:text-sm w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection