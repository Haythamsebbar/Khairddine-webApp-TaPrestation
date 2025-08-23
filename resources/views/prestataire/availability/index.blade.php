@extends('layouts.app')

@section('title', 'Gestion des disponibilités - Prestataire')

@section('styles')
<style>
    .day-card {
        transition: all 0.3s ease;
        border: 2px solid #dbeafe;
    }
    .day-card.active {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .day-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }
    .time-input {
        border: 2px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem;
        width: 100%;
        transition: all 0.2s;
    }
    .time-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Cacher les champs de configuration par défaut */
    .day-inputs-hidden {
        display: none;
    }
    
    /* Animation pour l'affichage des champs */
    .day-inputs-container {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    /* Améliorations responsives */
    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .day-card {
            padding: 1rem;
        }
        
        .quick-config-buttons {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .quick-config-buttons button {
            width: 100%;
            text-align: center;
        }
        
        .time-inputs-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .config-summary {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
    }
    
    @media (min-width: 641px) and (max-width: 1024px) {
        .time-inputs-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="bg-blue-50 min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
        <div class="max-w-6xl mx-auto">
            <!-- En-tête -->
            <div class="mb-6 sm:mb-8 text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-blue-900 mb-2">Gestion des disponibilités</h1>
                <p class="text-base sm:text-lg text-blue-700 px-2">Configurez vos horaires de travail et gérez vos exceptions</p>
                <div class="mt-4 sm:mt-6">
                    <a href="{{ route('prestataire.dashboard') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold rounded-lg transition duration-200 text-sm sm:text-base">
                        ← Retour au tableau de bord
                    </a>
                </div>
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-8">
                <!-- Section principale: Disponibilités hebdomadaires -->
                <div class="w-full">
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-blue-200">
                            <h2 class="text-xl sm:text-2xl font-bold text-blue-800">Disponibilités hebdomadaires</h2>
                            <p class="text-sm sm:text-base text-blue-700 mt-1">Définissez vos horaires de travail pour chaque jour de la semaine</p>
                            
                            <!-- Boutons de configuration rapide -->
                            <div class="mt-4 quick-config-buttons flex flex-wrap gap-2">
                                <button type="button" onclick="setBusinessHours()" class="px-4 py-2 bg-green-100 hover:bg-green-200 text-green-800 rounded-lg text-sm font-medium transition duration-200">
                                    Horaires bureau (9h-17h)
                                </button>
                                <button type="button" onclick="setShopHours()" class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg text-sm font-medium transition duration-200">
                                    Horaires magasin (10h-19h)
                                </button>
                                <button type="button" onclick="setFlexibleHours()" class="px-4 py-2 bg-orange-100 hover:bg-orange-200 text-orange-800 rounded-lg text-sm font-medium transition duration-200">
                                    Horaires flexibles (8h-20h)
                                </button>
                                <button type="button" onclick="clearAll()" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 rounded-lg text-sm font-medium transition duration-200">
                                    Tout effacer
                                </button>
                            </div>
                            
                            <!-- Section d'aide -->
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <h4 class="text-sm font-semibold text-blue-800 mb-2">Conseils pour configurer rapidement :</h4>
                                <ul class="text-xs text-blue-700 space-y-1">
                                    <li>• Utilisez les boutons de configuration rapide ci-dessus pour des horaires prédéfinis</li>
                                    <li>• Configurez un jour puis utilisez "Copier ces horaires" pour l'appliquer aux autres</li>
                                    <li>• Laissez les pauses vides si vous n'en avez pas besoin</li>
                                    <li>• La durée des créneaux détermine la granularité de vos réservations</li>
                                </ul>
                            </div>
                        </div>
                
                <form action="{{ route('prestataire.availability.updateWeekly') }}" method="POST" class="p-4 sm:p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        @php
                            $days = [
                                1 => 'Lundi',
                                2 => 'Mardi', 
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                0 => 'Dimanche'
                            ];
                        @endphp
                        
                        @foreach($days as $dayNumber => $dayName)
                            @php
                                $availability = $weeklyAvailability->firstWhere('day_of_week', $dayNumber);
                            @endphp
                            
                            <div class="day-card rounded-xl p-4 md:p-6 {{ $availability && $availability->is_active ? 'active' : '' }}">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-3">
                                    <h3 class="text-lg md:text-xl font-bold text-blue-900">{{ $dayName }}</h3>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" 
                                               name="days[{{ $dayNumber }}][is_active]" 
                                               value="1"
                                               {{ $availability && $availability->is_active ? 'checked' : '' }}
                                               class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-blue-300"
                                               onchange="toggleDayInputs({{ $dayNumber }})">
                                        <span class="ml-2 text-sm font-semibold text-blue-800">Actif</span>
                                    </label>
                                </div>
                                
                                <div id="day-inputs-{{ $dayNumber }}" class="day-inputs-container {{ $availability && $availability->is_active ? '' : 'day-inputs-hidden' }}">
                                    <!-- Bouton de copie pour ce jour -->
                                    <div class="mb-4 flex justify-end">
                                        <button type="button" onclick="copyDayToAll({{ $dayNumber }})" class="px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded text-xs font-medium transition duration-200">
                                            Copier ces horaires à tous les jours
                                        </button>
                                    </div>
                                    
                                    <div class="time-inputs-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-blue-800 mb-2">Heure de début</label>
                                            <input type="time" 
                                                   name="days[{{ $dayNumber }}][start_time]" 
                                                   value="{{ $availability ? $availability->start_time?->format('H:i') : '09:00' }}"
                                                   class="time-input">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-semibold text-blue-800 mb-2">Heure de fin</label>
                                            <input type="time" 
                                                   name="days[{{ $dayNumber }}][end_time]" 
                                                   value="{{ $availability ? $availability->end_time?->format('H:i') : '17:00' }}"
                                                   class="time-input">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-semibold text-blue-800 mb-2">Pause début</label>
                                            <input type="time" 
                                                   name="days[{{ $dayNumber }}][break_start_time]" 
                                                   value="{{ $availability && $availability->break_start_time ? $availability->break_start_time->format('H:i') : '' }}"
                                                   class="time-input">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-semibold text-blue-800 mb-2">Pause fin</label>
                                            <input type="time" 
                                                   name="days[{{ $dayNumber }}][break_end_time]" 
                                                   value="{{ $availability && $availability->break_end_time ? $availability->break_end_time->format('H:i') : '' }}"
                                                   class="time-input">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <label class="block text-sm font-semibold text-blue-800 mb-2">Durée des créneaux (minutes)</label>
                                        <select name="days[{{ $dayNumber }}][slot_duration]" class="time-input">
                                            <option value="15" {{ $availability && $availability->slot_duration == 15 ? 'selected' : '' }}>15 minutes</option>
                                            <option value="30" {{ $availability && $availability->slot_duration == 30 ? 'selected' : '' }}>30 minutes</option>
                                            <option value="60" {{ $availability && $availability->slot_duration == 60 ? 'selected' : '' }}>1 heure</option>
                                            <option value="120" {{ $availability && $availability->slot_duration == 120 ? 'selected' : '' }}>2 heures</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                        <div class="border-t-2 border-blue-200 pt-6 mt-8">
                            <!-- Résumé des jours configurés -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <div class="config-summary flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-800">Résumé de votre configuration</h4>
                                        <p class="text-xs text-gray-600 mt-1" id="config-summary">Chargement...</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-blue-600" id="active-days-count">0</div>
                                        <div class="text-xs text-gray-500">jours actifs</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center">
                                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" onclick="return validateForm()">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Enregistrer les disponibilités
                                </button>
                            </div>
                        </div>
                </form>
                 </div>
             </div>
         </div>
     </div>
 </div>
@endsection

@section('scripts')
<script>
    // Configuration rapide : Horaires de bureau (9h-17h, Lun-Ven)
    function setBusinessHours() {
        const weekDays = [1, 2, 3, 4, 5]; // Lundi à Vendredi
        const weekendDays = [0, 6]; // Dimanche et Samedi
        
        weekDays.forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
            const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
            const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`);
            const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`);
            const slotDuration = document.querySelector(`select[name="days[${day}][slot_duration]"]`);
            
            if (checkbox) checkbox.checked = true;
            if (startTime) startTime.value = '09:00';
            if (endTime) endTime.value = '17:00';
            if (breakStart) breakStart.value = '12:00';
            if (breakEnd) breakEnd.value = '13:00';
            if (slotDuration) slotDuration.value = '60';
            
            toggleDayInputs(day);
        });
        
        weekendDays.forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox) {
                checkbox.checked = false;
                toggleDayInputs(day);
            }
        });
        
        updateConfigSummary();
        alert('Horaires de bureau configurés (9h-17h, Lun-Ven) !');
    }
    
    // Configuration rapide : Horaires de magasin (10h-19h, Lun-Sam)
    function setShopHours() {
        const workDays = [1, 2, 3, 4, 5, 6]; // Lundi à Samedi
        const restDay = [0]; // Dimanche
        
        workDays.forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
            const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
            const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`);
            const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`);
            const slotDuration = document.querySelector(`select[name="days[${day}][slot_duration]"]`);
            
            if (checkbox) checkbox.checked = true;
            if (startTime) startTime.value = '10:00';
            if (endTime) endTime.value = '19:00';
            if (breakStart) breakStart.value = '13:00';
            if (breakEnd) breakEnd.value = '14:00';
            if (slotDuration) slotDuration.value = '30';
            
            toggleDayInputs(day);
        });
        
        restDay.forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox) {
                checkbox.checked = false;
                toggleDayInputs(day);
            }
        });
        
        updateConfigSummary();
        alert('Horaires de magasin configurés (10h-19h, Lun-Sam) !');
    }
    
    // Configuration rapide : Horaires flexibles (8h-20h, tous les jours)
    function setFlexibleHours() {
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
            const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
            const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`);
            const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`);
            const slotDuration = document.querySelector(`select[name="days[${day}][slot_duration]"]`);
            
            if (checkbox) checkbox.checked = true;
            if (startTime) startTime.value = '08:00';
            if (endTime) endTime.value = '20:00';
            if (breakStart) breakStart.value = '12:00';
            if (breakEnd) breakEnd.value = '13:00';
            if (slotDuration) slotDuration.value = '45';
            
            toggleDayInputs(day);
        });
        
        updateConfigSummary();
        alert('Horaires flexibles configurés (8h-20h, tous les jours) !');
    }
    
    // Effacer toutes les configurations
    function clearAll() {
        if (!confirm('Êtes-vous sûr de vouloir désactiver tous les jours ?')) {
            return;
        }
        
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox) {
                checkbox.checked = false;
                toggleDayInputs(day);
            }
        });
        
        updateConfigSummary();
        alert('Tous les horaires ont été effacés !');
    }
    
    function toggleDayInputs(dayNumber) {
        const checkbox = document.querySelector(`input[name="days[${dayNumber}][is_active]"]`);
        const inputs = document.getElementById(`day-inputs-${dayNumber}`);
        const card = checkbox.closest('.day-card');
        
        if (checkbox.checked) {
            inputs.classList.remove('day-inputs-hidden');
            card.classList.add('active');
        } else {
            inputs.classList.add('day-inputs-hidden');
            card.classList.remove('active');
        }
    }
    
    // Copier les horaires d'un jour à tous les autres jours
    function copyDayToAll(sourceDay) {
        if (!confirm('Voulez-vous copier les horaires de ce jour à tous les autres jours ?')) {
            return;
        }
        
        const sourceData = {
            isActive: document.querySelector(`input[name="days[${sourceDay}][is_active]"]`).checked,
            startTime: document.querySelector(`input[name="days[${sourceDay}][start_time]"]`).value,
            endTime: document.querySelector(`input[name="days[${sourceDay}][end_time]"]`).value,
            breakStart: document.querySelector(`input[name="days[${sourceDay}][break_start_time]"]`).value,
            breakEnd: document.querySelector(`input[name="days[${sourceDay}][break_end_time]"]`).value,
            slotDuration: document.querySelector(`select[name="days[${sourceDay}][slot_duration]"]`).value
        };
        
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            if (day !== sourceDay) {
                const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
                const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
                const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
                const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`);
                const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`);
                const slotDuration = document.querySelector(`select[name="days[${day}][slot_duration]"]`);
                
                if (checkbox) checkbox.checked = sourceData.isActive;
                if (startTime) startTime.value = sourceData.startTime;
                if (endTime) endTime.value = sourceData.endTime;
                if (breakStart) breakStart.value = sourceData.breakStart;
                if (breakEnd) breakEnd.value = sourceData.breakEnd;
                if (slotDuration) slotDuration.value = sourceData.slotDuration;
                
                toggleDayInputs(day);
            }
        });
        
        updateConfigSummary();
        alert('Horaires copiés avec succès !');
    }
    
    function resetToDefault() {
        if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les horaires aux valeurs par défaut ?')) {
            // Réinitialiser tous les champs aux valeurs par défaut
            const days = [1, 2, 3, 4, 5]; // Lundi à Vendredi
            days.forEach(day => {
                const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
                const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
                const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
                const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`);
                const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`);
                const slotDuration = document.querySelector(`select[name="days[${day}][slot_duration]"]`);
                
                if (checkbox) checkbox.checked = true;
                if (startTime) startTime.value = '09:00';
                if (endTime) endTime.value = '17:00';
                if (breakStart) breakStart.value = '12:00';
                if (breakEnd) breakEnd.value = '13:00';
                if (slotDuration) slotDuration.value = '60';
                
                toggleDayInputs(day);
            });
            
            // Désactiver weekend
            [0, 6].forEach(day => {
                const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
                if (checkbox) {
                    checkbox.checked = false;
                    toggleDayInputs(day);
                }
            });
        }
    }
    
    // Validation du formulaire
    function validateForm() {
        const activeDays = [0, 1, 2, 3, 4, 5, 6].filter(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            return checkbox && checkbox.checked;
        });
        
        if (activeDays.length === 0) {
            alert('⚠️ Vous devez activer au moins un jour de la semaine !');
            return false;
        }
        
        // Vérifier que les heures sont cohérentes pour chaque jour actif
        for (let day of activeDays) {
            const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`).value;
            const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`).value;
            const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`).value;
            const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`).value;
            
            if (startTime >= endTime) {
                const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                alert(`⚠️ ${dayNames[day]} : L'heure de fin doit être après l'heure de début !`);
                return false;
            }
            
            if (breakStart && breakEnd && breakStart >= breakEnd) {
                const dayNames = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                alert(`⚠️ ${dayNames[day]} : L'heure de fin de pause doit être après l'heure de début de pause !`);
                return false;
            }
        }
        
        return true;
    }
    
    // Mettre à jour le résumé de configuration
    function updateConfigSummary() {
        const dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        const activeDays = [];
        let totalHours = 0;
        
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox && checkbox.checked) {
                activeDays.push(dayNames[day]);
                
                const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`).value;
                const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`).value;
                const breakStart = document.querySelector(`input[name="days[${day}][break_start_time]"]`).value;
                const breakEnd = document.querySelector(`input[name="days[${day}][break_end_time]"]`).value;
                
                if (startTime && endTime) {
                    const start = new Date(`2000-01-01T${startTime}:00`);
                    const end = new Date(`2000-01-01T${endTime}:00`);
                    let dayHours = (end - start) / (1000 * 60 * 60);
                    
                    if (breakStart && breakEnd) {
                        const breakStartTime = new Date(`2000-01-01T${breakStart}:00`);
                        const breakEndTime = new Date(`2000-01-01T${breakEnd}:00`);
                        const breakHours = (breakEndTime - breakStartTime) / (1000 * 60 * 60);
                        dayHours -= breakHours;
                    }
                    
                    totalHours += dayHours;
                }
            }
        });
        
        document.getElementById('active-days-count').textContent = activeDays.length;
        
        if (activeDays.length === 0) {
            document.getElementById('config-summary').textContent = 'Aucun jour configuré';
        } else {
            const summary = `${activeDays.join(', ')} • ~${Math.round(totalHours)}h/semaine`;
            document.getElementById('config-summary').textContent = summary;
        }
    }
    
    // Initialiser l'état des inputs au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox) {
                toggleDayInputs(day);
                // Ajouter des écouteurs pour mettre à jour le résumé
                checkbox.addEventListener('change', updateConfigSummary);
                
                const inputs = ['start_time', 'end_time', 'break_start_time', 'break_end_time'];
                inputs.forEach(inputType => {
                    const input = document.querySelector(`input[name="days[${day}][${inputType}]"]`);
                    if (input) {
                        input.addEventListener('change', updateConfigSummary);
                    }
                });
            }
        });
        
        // Mettre à jour le résumé initial
        updateConfigSummary();
    });
</script>
@endsection