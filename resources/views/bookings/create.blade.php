@extends('layouts.app')

@section('content')
<style>
.slot-option {
    transition: all 0.2s ease-in-out;
}

.slot-option:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.slot-checkbox:checked + div {
    border-color: #3b82f6 !important;
    background-color: #dbeafe !important;
    color: #1e3a8a !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: scale(1.02);
}

.slot-selected {
    border-color: #3b82f6 !important;
    background-color: #dbeafe !important;
    color: #1e3a8a !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    transform: scale(1.02);
}

@keyframes slotSelect {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1.02); }
}

.slot-animate {
    animation: slotSelect 0.3s ease-in-out;
}
</style>

<div class="bg-blue-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2">Nouvelle Réservation</h1>
                <p class="text-lg text-blue-700">Réservez un créneau pour le service sélectionné</p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations du service -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6 sticky top-8">
                        <h2 class="text-2xl font-bold text-blue-800 mb-5 border-b-2 border-blue-200 pb-3">Détails du service</h2>
                        
                        <div class="space-y-5">
                            <div>
                                <h3 class="font-bold text-lg text-gray-800">{{ $service->name }}</h3>
                                <p class="text-gray-600 text-sm mt-1">{{ Str::limit($service->description, 100) }}</p>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">Prix :</span>
                                    <span class="font-bold text-2xl text-blue-600">{{ number_format($service->price, 2) }} €</span>
                                </div>
                                
                                @if($service->duration)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 font-medium">Durée :</span>
                                        <span class="text-gray-800 font-semibold">{{ $service->duration }} min</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="font-bold text-gray-800 mb-3">Prestataire</h4>
                                <a href="{{ route('prestataires.show', $prestataire) }}" class="flex items-center gap-4 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                    @if($prestataire->photo)
                                        <img src="{{ Storage::url($prestataire->photo) }}" 
                                             alt="{{ $prestataire->user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @elseif($prestataire->user->avatar)
                                        <img src="{{ Storage::url($prestataire->user->avatar) }}" 
                                             alt="{{ $prestataire->user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @elseif($prestataire->user->profile_photo)
                                        <img src="{{ asset('storage/' . $prestataire->user->profile_photo) }}" 
                                             alt="{{ $prestataire->user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @elseif($prestataire->user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $prestataire->user->profile_photo_path) }}" 
                                             alt="{{ $prestataire->user->name }}" 
                                             class="w-12 h-12 rounded-full object-cover border-2 border-blue-200">
                                    @else
                                        <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center border-2 border-blue-300">
                                            <span class="text-blue-800 font-bold text-xl">{{ substr($prestataire->user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-blue-900">{{ $prestataire->user->name }}</div>
                                        @if($prestataire->location)
                                            <div class="text-sm text-blue-700 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                                {{ $prestataire->location }}
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Formulaire de réservation -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-8">
                        <h2 class="text-2xl font-bold text-blue-800 mb-6 border-b-2 border-blue-200 pb-3">Sélectionner un créneau</h2>
                        
                        <form action="{{ route('bookings.store') }}" method="POST" x-data="bookingForm()">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <input type="hidden" name="prestataire_id" value="{{ $prestataire->id }}">
                            
                            @if(count($availableSlots) > 0 && collect($availableSlots)->where('is_booked', false)->count() > 0)
                                <!-- Informations sur les horaires -->
                                @php
                                    $firstSlot = collect($availableSlots)->first();
                                    $hasBreak = $firstSlot && $firstSlot['break_start_time'] && $firstSlot['break_end_time'];
                                @endphp
                                @if($firstSlot)
                                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <h3 class="text-lg font-semibold text-blue-800 mb-3">Informations sur les horaires</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-700">Horaires de travail :</span>
                                                <span class="text-blue-600 font-semibold">
                                                    {{ \Carbon\Carbon::parse($firstSlot['availability_start'])->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($firstSlot['availability_end'])->format('H:i') }}
                                                </span>
                                            </div>
                                            @if($hasBreak)
                                                <div>
                                                    <span class="font-medium text-gray-700">Pause :</span>
                                                    <span class="text-orange-600 font-semibold">
                                                        {{ \Carbon\Carbon::parse($firstSlot['break_start_time'])->format('H:i') }} - 
                                                        {{ \Carbon\Carbon::parse($firstSlot['break_end_time'])->format('H:i') }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="font-medium text-gray-700">Durée des créneaux :</span>
                                                <span class="text-green-600 font-semibold">{{ $firstSlot['duration'] }} minutes</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-md font-semibold text-gray-800">Créneaux disponibles</label>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" @click="clearAllSlots()" 
                                                    x-show="selectedSlots.length > 0"
                                                    class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors">
                                                🗑️ Effacer tout
                                            </button>
                                            <div class="text-xs text-gray-500">
                                                <span x-show="selectedSlots.length === 0">Aucun créneau sélectionné</span>
                                                <span x-show="selectedSlots.length > 0" x-text="selectedSlots.length + ' créneau(x) sélectionné(s)'"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Légende -->
                                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Légende :</p>
                                        <div class="flex flex-wrap gap-4 text-xs">
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 border-2 border-gray-300 bg-white rounded mr-2"></div>
                                                <span class="text-gray-600">Disponible</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 border-2 border-blue-400 bg-blue-100 rounded mr-2"></div>
                                                <span class="text-gray-600">Sélectionné</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 border-2 border-orange-300 bg-orange-50 rounded mr-2"></div>
                                                <span class="text-gray-600">Demande en attente (vous pouvez quand même réserver)</span>
                                            </div>
                                            <div class="flex items-center">
                                                <div class="w-4 h-4 border-2 border-gray-200 bg-gray-100 rounded mr-2"></div>
                                                <span class="text-gray-600">Réservé (non disponible)</span>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <p class="text-xs text-blue-700 font-medium mb-1">💡 Conseils :</p>
                                            <ul class="text-xs text-gray-600 space-y-1">
                                                <li>• Cliquez sur plusieurs créneaux pour réserver plusieurs heures</li>
                                                <li>• Utilisez Ctrl+A (Cmd+A sur Mac) pour sélectionner tous les créneaux disponibles</li>
                                                <li>• Appuyez sur Échap pour désélectionner tous les créneaux</li>
                                                <li>• Le prix total s'ajuste automatiquement selon le nombre de créneaux</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        @php
                                            $groupedSlots = collect($availableSlots)->groupBy(function($slot) {
                                                return $slot['datetime']->format('Y-m-d');
                                            });
                                        @endphp

                                        @foreach($groupedSlots as $date => $slots)
                                            <div x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }" class="border-2 border-gray-200 rounded-lg overflow-hidden">
                                                <button type="button" @click="open = !open" class="w-full flex justify-between items-center p-4 bg-blue-50 hover:bg-blue-100 focus:outline-none transition duration-200">
                                                    <span class="font-bold text-blue-900">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                                                    <svg class="w-6 h-6 text-blue-600 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                                <div x-show="open" x-transition class="p-4 bg-white">
                                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                                        @foreach($slots as $slot)
                                                            @if($slot['is_booked'])
                                                                <!-- Créneau réservé (confirmé) - grisé et non cliquable -->
                                                                <div class="relative">
                                                                    <div class="border-2 border-gray-200 rounded-lg p-3 text-center font-semibold text-gray-400 bg-gray-100 cursor-not-allowed opacity-60">
                                                                        <div class="text-sm font-bold">{{ $slot['datetime']->format('H:i') }} - {{ $slot['end_datetime']->format('H:i') }}</div>
                                                                        <div class="text-xs text-gray-500 mt-1">{{ $slot['duration'] }} min</div>
                                                                        <div class="text-xs mt-1 text-red-500 font-semibold">Réservé</div>
                                                                    </div>
                                                                </div>
                                                            @elseif(isset($slot['has_pending']) && $slot['has_pending'])
                                                                <!-- Créneau avec demande en attente - toujours disponible pour d'autres demandes -->
                                                                <label class="relative slot-option" data-slot="{{ $slot['datetime']->toDateTimeString() }}">
                                                                    <input type="checkbox" name="selected_slots[]" value="{{ $slot['datetime']->toDateTimeString() }}" 
                                                                           class="sr-only peer slot-checkbox"
                                                                           @change="toggleSlot('{{ $slot['datetime']->toDateTimeString() }}', '{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}', '{{ $slot['datetime']->format('H:i') }} - {{ $slot['end_datetime']->format('H:i') }}')">
                                                                    <div class="border-2 border-orange-300 rounded-lg p-3 cursor-pointer transition-all duration-200 text-center font-semibold text-gray-700 bg-orange-50
                                                                                peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:text-blue-900 peer-checked:shadow-lg peer-checked:scale-105
                                                                                hover:border-orange-400 hover:bg-orange-100">
                                                                        <div class="text-sm font-bold">{{ $slot['datetime']->format('H:i') }} - {{ $slot['end_datetime']->format('H:i') }}</div>
                                                                        <div class="text-xs text-gray-500 mt-1">{{ $slot['duration'] }} min</div>
                                                                        <div class="text-xs mt-1 text-orange-600 font-semibold">Demande en attente</div>
                                                                    </div>
                                                                </label>
                                                            @else
                                                                <!-- Créneau disponible - cliquable -->
                                                                <label class="relative slot-option" data-slot="{{ $slot['datetime']->toDateTimeString() }}">
                                                                    <input type="checkbox" name="selected_slots[]" value="{{ $slot['datetime']->toDateTimeString() }}" 
                                                                           class="sr-only peer slot-checkbox"
                                                                           @change="toggleSlot('{{ $slot['datetime']->toDateTimeString() }}', '{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}', '{{ $slot['datetime']->format('H:i') }} - {{ $slot['end_datetime']->format('H:i') }}')">
                                                                    <div class="border-2 border-gray-300 rounded-lg p-3 cursor-pointer transition-all duration-200 text-center font-semibold text-gray-700
                                                                                peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:text-blue-900 peer-checked:shadow-lg peer-checked:scale-105
                                                                                hover:border-blue-400 hover:bg-blue-50">
                                                                        <div class="text-sm font-bold">{{ $slot['datetime']->format('H:i') }} - {{ $slot['end_datetime']->format('H:i') }}</div>
                                                                        <div class="text-xs text-gray-500 mt-1">{{ $slot['duration'] }} min</div>
                                                                    </div>
                                                                </label>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Sélection actuelle -->
                                <div x-show="selectedSlots.length > 0" class="my-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-md shadow-sm">
                                    <h3 class="font-semibold text-green-800 mb-2">Créneaux sélectionnés :</h3>
                                    <div class="space-y-1">
                                        <template x-for="slot in selectedSlots" :key="slot.datetime">
                                            <div class="flex items-center justify-between bg-white rounded px-3 py-2 text-sm">
                                                <span class="font-medium text-gray-900" x-text="slot.date + ' à ' + slot.time"></span>
                                                <button type="button" @click="removeSlot(slot.datetime)" class="text-red-500 hover:text-red-700 font-medium text-xs">
                                                    Supprimer
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-green-200">
                                        <p class="text-sm text-green-700">
                                            <span class="font-medium">Total: <span x-text="selectedSlots.length"></span> créneau(x)</span>
                                            <span class="ml-4 font-bold">Prix: <span x-text="(selectedSlots.length * {{ $service->price }}).toFixed(2)"></span> €</span>
                                        </p>
                                    </div>
                                </div>

                            @else
                                <div class="text-center py-10 px-6 bg-blue-100 rounded-lg border-2 border-dashed border-blue-300">
                                    <div class="text-blue-500 mb-4">
                                        <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-blue-900 mb-2">Aucun créneau disponible</h3>
                                    <p class="text-blue-800">Ce prestataire n'a pas de créneaux disponibles pour les 30 prochains jours.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('prestataires.show', $prestataire) }}" class="text-white bg-blue-600 hover:bg-blue-700 font-bold py-2 px-5 rounded-lg transition duration-300">
                                            Contacter le prestataire
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            @if(count($availableSlots) > 0)
                                <div class="mb-6">
                                    <label for="client_notes" class="block text-md font-semibold text-gray-800 mb-2">
                                        Notes ou demandes particulières (optionnel)
                                    </label>
                                    <textarea id="client_notes" name="client_notes" rows="4" 
                                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                              placeholder="Décrivez vos besoins spécifiques, questions ou demandes particulières..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Maximum 1000 caractères. 
                                        <span x-show="selectedSlots.length > 1" class="text-blue-600 font-medium">
                                            Ces notes s'appliqueront à tous les créneaux sélectionnés.
                                        </span>
                                    </p>
                                </div>
                                
                                <div class="border-t-2 border-blue-200 pt-6">
                                    <div class="flex justify-between items-center mb-5">
                                        <span class="text-xl font-bold text-blue-900">Total à payer :</span>
                                        <span class="text-3xl font-extrabold text-blue-600" x-text="(selectedSlots.length * {{ $service->price }}).toFixed(2) + ' €'">{{ number_format($service->price, 2) }} €</span>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <a href="{{ route('services.index', request()->session()->get('services_filters', [])) }}" 
                                           class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-6 py-3 rounded-lg text-center transition duration-200">
                                            Retour
                                        </a>
                                        <button type="submit" 
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:shadow-none disabled:transform-none"
                                                :disabled="selectedSlots.length === 0">
                                            <span x-show="selectedSlots.length === 0">Sélectionnez un créneau</span>
                                            <span x-show="selectedSlots.length === 1">Confirmer la réservation</span>
                                            <span x-show="selectedSlots.length > 1" x-text="'Confirmer ' + selectedSlots.length + ' réservations'"></span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="border-t-2 border-blue-200 pt-6">
                                    <a href="{{ route('services.index', request()->session()->get('services_filters', [])) }}" 
                                       class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold px-6 py-3 rounded-lg text-center transition duration-200 block">
                                        Retour aux services
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingForm', () => ({
        selectedSlots: [],
        selectedSlot: {
            date: '',
            time: ''
        },
        
        toggleSlot(datetime, date, time) {
            const existingIndex = this.selectedSlots.findIndex(slot => slot.datetime === datetime);
            
            if (existingIndex >= 0) {
                // Remove slot if already selected
                this.selectedSlots.splice(existingIndex, 1);
                this.animateSlotRemoval(datetime);
            } else {
                // Add slot if not selected
                this.selectedSlots.push({
                    datetime: datetime,
                    date: date,
                    time: time
                });
                this.animateSlotSelection(datetime);
            }
            
            // Sort slots by datetime
            this.selectedSlots.sort((a, b) => new Date(a.datetime) - new Date(b.datetime));
            
            // Update visual state of slots
            this.updateSlotVisuals();
            
            // Update form validation
            this.updateFormValidation();
        },
        
        removeSlot(datetime) {
            const index = this.selectedSlots.findIndex(slot => slot.datetime === datetime);
            if (index >= 0) {
                this.selectedSlots.splice(index, 1);
                this.animateSlotRemoval(datetime);
                this.updateSlotVisuals();
                this.updateFormValidation();
            }
        },
        
        animateSlotSelection(datetime) {
            const slotElement = document.querySelector(`[data-slot="${datetime}"] div`);
            if (slotElement) {
                slotElement.classList.add('slot-animate');
                setTimeout(() => {
                    slotElement.classList.remove('slot-animate');
                }, 300);
            }
        },
        
        animateSlotRemoval(datetime) {
            const slotElement = document.querySelector(`[data-slot="${datetime}"] div`);
            if (slotElement) {
                slotElement.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    slotElement.style.transform = '';
                }, 200);
            }
        },
        
        updateSlotVisuals() {
            // Update visual state of all slot checkboxes
            document.querySelectorAll('.slot-checkbox').forEach(checkbox => {
                const slotValue = checkbox.value;
                const isSelected = this.selectedSlots.some(slot => slot.datetime === slotValue);
                checkbox.checked = isSelected;
                
                // Update parent label styling
                const label = checkbox.closest('.slot-option');
                if (label) {
                    const slotDiv = label.querySelector('div');
                    if (isSelected) {
                        // Add selected styling
                        slotDiv.classList.add('slot-selected');
                        if (slotDiv.classList.contains('bg-white')) {
                            slotDiv.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
                            slotDiv.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-900');
                        } else if (slotDiv.classList.contains('bg-orange-50')) {
                            slotDiv.classList.remove('bg-orange-50', 'border-orange-300', 'text-gray-700');
                            slotDiv.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-900');
                        }
                    } else {
                        // Remove selected styling
                        slotDiv.classList.remove('slot-selected');
                        if (slotDiv.innerHTML.includes('Demande en attente')) {
                            slotDiv.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-900');
                            slotDiv.classList.add('bg-orange-50', 'border-orange-300', 'text-gray-700');
                        } else {
                            slotDiv.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-900');
                            slotDiv.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
                        }
                    }
                }
            });
        },
        
        updateFormValidation() {
            // Enable/disable submit button
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = this.selectedSlots.length === 0;
            }
        },
        
        getTotalPrice() {
            return (this.selectedSlots.length * {{ $service->price }}).toFixed(2);
        },
        
        clearAllSlots() {
            if (this.selectedSlots.length > 0 && confirm('Êtes-vous sûr de vouloir désélectionner tous les créneaux ?')) {
                this.selectedSlots = [];
                this.updateSlotVisuals();
                this.updateFormValidation();
            }
        },
        
        // Backward compatibility method
        setSelectedSlot(date, time) {
            this.selectedSlot.date = date;
            this.selectedSlot.time = time;
        },
        
        init() {
            // Initialize form validation state
            this.updateFormValidation();
        }
    }))
})

// Add keyboard shortcuts for better UX
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + A to select all available slots
    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
        e.preventDefault();
        const availableSlots = document.querySelectorAll('.slot-checkbox:not(:disabled)');
        availableSlots.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.click();
            }
        });
    }
    
    // Escape to clear all selections
    if (e.key === 'Escape') {
        const alpineComponent = Alpine.$data(document.querySelector('[x-data="bookingForm()"]'));
        if (alpineComponent && alpineComponent.selectedSlots.length > 0) {
            alpineComponent.clearAllSlots();
        }
    }
});
</script>
@endsection