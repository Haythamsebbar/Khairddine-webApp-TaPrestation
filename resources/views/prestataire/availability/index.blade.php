@extends('layouts.app')

@section('title', 'Gestion des disponibilités')

@section('content')
<div class="min-h-screen bg-blue-50">
    <div class="container mx-auto py-4 sm:py-6 md:py-8 px-2 sm:px-4">
        <!-- En-tête amélioré -->
        <div class="mb-6 md:mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-blue-900 mb-1 sm:mb-2">
                        Gestion de vos disponibilités
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg text-blue-700">Configurez vos créneaux horaires pour chaque jour de la semaine</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 sm:px-6 py-3 sm:py-4">
                <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-2">
                    📅 Disponibilités hebdomadaires
                </h3>
            </div>
            <div class="p-4 sm:p-6">
                        <form action="{{ route('prestataire.availability.updateWeekly') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Jour</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Actif</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider hidden sm:table-cell">Début</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider hidden sm:table-cell">Fin</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Horaires</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-bold text-blue-900 uppercase tracking-wider">Durée (min)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $daysMap = [
                                    0 => 'Dimanche',
                                    1 => 'Lundi',
                                    2 => 'Mardi',
                                    3 => 'Mercredi',
                                    4 => 'Jeudi',
                                    5 => 'Vendredi',
                                    6 => 'Samedi',
                                ];
                            @endphp
                            @foreach($weeklyAvailability as $day)
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-bold text-blue-900">
                                        <span class="sm:hidden">{{ substr($daysMap[$day->day_of_week] ?? 'Jour inconnu', 0, 3) }}</span>
                                        <span class="hidden sm:inline">{{ $daysMap[$day->day_of_week] ?? 'Jour inconnu' }}</span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="days[{{ $day->day_of_week }}][is_active]" {{ $day->is_active ? 'checked' : '' }} 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <input type="time" name="days[{{ $day->day_of_week }}][start_time]" value="{{ $day->start_time }}" 
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                        <input type="time" name="days[{{ $day->day_of_week }}][end_time]" value="{{ $day->end_time }}" 
                                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap sm:hidden">
                                        <div class="flex flex-col gap-1">
                                            <input type="time" name="days[{{ $day->day_of_week }}][start_time]" value="{{ $day->start_time }}" 
                                                   class="block w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-xs" 
                                                   placeholder="Début">
                                            <input type="time" name="days[{{ $day->day_of_week }}][end_time]" value="{{ $day->end_time }}" 
                                                   class="block w-full px-2 py-1 border border-gray-300 rounded shadow-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-xs" 
                                                   placeholder="Fin">
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                        <input type="number" name="days[{{ $day->day_of_week }}][slot_duration]" value="{{ $day->slot_duration }}" 
                                               class="block w-full px-2 sm:px-3 py-1 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs sm:text-sm" 
                                               min="15" step="15" placeholder="30">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 sm:mt-6 flex justify-center sm:justify-end">
                    <button type="submit" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base w-full sm:w-auto">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection