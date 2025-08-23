@extends('layouts.app')

@section('title', 'Test - Gestion de Disponibilité')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Test des Fonctionnalités JavaScript - Gestion de Disponibilité</h1>
            
            <!-- Test des boutons d'activation/désactivation des jours -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Test 1: Activation/Désactivation des jours</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $index => $day)
                    <div class="day-card rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-900">{{ $day }}</h4>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="days[{{ $index }}][is_active]" class="sr-only peer" onchange="toggleDayInputs({{ $index }})" {{ in_array($index, [1,2,3,4,5]) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        
                        <div id="day-inputs-{{ $index }}" class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Début</label>
                                <input type="time" name="days[{{ $index }}][start_time]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" value="09:00">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Fin</label>
                                <input type="time" name="days[{{ $index }}][end_time]" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" value="17:00">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Test des exceptions -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Test 2: Gestion des exceptions</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type d'exception</label>
                        <select onchange="toggleTimeInputs(this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="unavailable">Indisponible</option>
                            <option value="custom_hours">Horaires personnalisés</option>
                        </select>
                    </div>
                    
                    <div id="time-inputs" class="hidden space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Heure de début</label>
                                <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Heure de fin</label>
                                <input type="time" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Test des actions rapides -->
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Test 3: Actions rapides</h2>
                <div class="space-y-3">
                    <button onclick="copyWeekSchedule()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Copier la semaine
                    </button>
                    
                    <button onclick="resetToDefault()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Réinitialiser
                    </button>
                </div>
            </div>
            
            <!-- Résultats des tests -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold text-blue-800 mb-4">Résultats des tests</h2>
                <div id="test-results" class="space-y-2 text-sm">
                    <p class="text-blue-700">Cliquez sur les éléments ci-dessus pour tester les fonctionnalités JavaScript.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Copier les fonctions JavaScript de la vue originale
    function toggleDayInputs(dayNumber) {
        try {
            const checkbox = document.querySelector(`input[name="days[${dayNumber}][is_active]"]`);
            const inputs = document.getElementById(`day-inputs-${dayNumber}`);
            const card = checkbox ? checkbox.closest('.day-card') : null;
            
            if (!checkbox || !inputs) {
                logTest(`Éléments manquants pour le jour ${dayNumber + 1}`);
                return;
            }
            
            if (checkbox.checked) {
                inputs.classList.remove('opacity-50', 'pointer-events-none');
                if (card) card.classList.add('active');
                logTest(`Jour ${dayNumber + 1} activé - Inputs accessibles`);
            } else {
                inputs.classList.add('opacity-50', 'pointer-events-none');
                if (card) card.classList.remove('active');
                logTest(`Jour ${dayNumber + 1} désactivé - Inputs désactivés`);
            }
        } catch (error) {
            logTest(`Erreur dans toggleDayInputs: ${error.message}`);
        }
    }
    
    function toggleTimeInputs(type) {
        try {
            const timeInputs = document.getElementById('time-inputs');
            if (!timeInputs) {
                logTest('Élément time-inputs non trouvé');
                return;
            }
            
            if (type === 'custom_hours') {
                timeInputs.classList.remove('hidden');
                logTest('Horaires personnalisés affichés');
            } else {
                timeInputs.classList.add('hidden');
                logTest('Horaires personnalisés masqués');
            }
        } catch (error) {
            logTest(`Erreur dans toggleTimeInputs: ${error.message}`);
        }
    }
    
    function copyWeekSchedule() {
        logTest('Fonction "Copier la semaine" appelée');
        alert('Test réussi : Fonction copier la semaine');
    }
    
    function resetToDefault() {
        if (confirm('Test : Êtes-vous sûr de vouloir réinitialiser ?')) {
            logTest('Réinitialisation confirmée');
            
            // Réinitialiser les jours de semaine
            const days = [1, 2, 3, 4, 5];
            days.forEach(day => {
                const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
                const startTime = document.querySelector(`input[name="days[${day}][start_time]"]`);
                const endTime = document.querySelector(`input[name="days[${day}][end_time]"]`);
                
                if (checkbox) checkbox.checked = true;
                if (startTime) startTime.value = '09:00';
                if (endTime) endTime.value = '17:00';
                
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
            
            logTest('Réinitialisation terminée - Semaine: 9h-17h, Weekend: désactivé');
        } else {
            logTest('Réinitialisation annulée');
        }
    }
    
    function logTest(message) {
        try {
            const results = document.getElementById('test-results');
            if (!results) {
                console.log(`Test Log: ${message}`);
                return;
            }
            
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('p');
            logEntry.className = 'text-gray-700';
            logEntry.innerHTML = `<span class="text-gray-500">[${timestamp}]</span> ${message}`;
            results.appendChild(logEntry);
            results.scrollTop = results.scrollHeight;
        } catch (error) {
            console.error('Erreur dans logTest:', error.message);
            console.log(`Test Log: ${message}`);
        }
    }
    
    // Initialiser l'état des inputs au chargement
    document.addEventListener('DOMContentLoaded', function() {
        logTest('Page de test chargée - Initialisation des états');
        
        [0, 1, 2, 3, 4, 5, 6].forEach(day => {
            const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
            if (checkbox) {
                toggleDayInputs(day);
            }
        });
        
        logTest('Initialisation terminée');
    });
</script>

<style>
    .day-card.active {
        background-color: #f0f9ff;
        border-color: #0ea5e9;
    }
    
    .opacity-50 {
        opacity: 0.5;
    }
    
    .pointer-events-none {
        pointer-events: none;
    }
    
    #test-results {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endsection