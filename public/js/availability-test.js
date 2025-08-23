/**
 * Script de test automatisé pour les fonctionnalités JavaScript
 * de la gestion de disponibilité des prestataires
 */

class AvailabilityTester {
    constructor() {
        this.testResults = [];
        this.init();
    }

    init() {
        console.log('🧪 Initialisation des tests de disponibilité');
        this.runAllTests();
    }

    runAllTests() {
        console.log('🚀 Démarrage des tests automatisés...');
        
        // Test 1: Vérification de l'initialisation
        this.testInitialization();
        
        // Test 2: Test des toggles de jours
        this.testDayToggles();
        
        // Test 3: Test des fonctions utilitaires
        this.testUtilityFunctions();
        
        // Test 4: Test de la validation des formulaires
        this.testFormValidation();
        
        // Afficher les résultats
        this.displayResults();
    }

    testInitialization() {
        console.log('📋 Test 1: Initialisation');
        
        try {
            // Vérifier que les fonctions existent
            const functions = ['toggleDayInputs', 'toggleTimeInputs', 'copyWeekSchedule', 'resetToDefault'];
            functions.forEach(func => {
                if (typeof window[func] === 'function') {
                    this.addResult('✅', `Fonction ${func} existe`);
                } else {
                    this.addResult('❌', `Fonction ${func} manquante`);
                }
            });
            
            // Vérifier la présence des éléments DOM essentiels
            const dayCards = document.querySelectorAll('.day-card');
            if (dayCards.length > 0) {
                this.addResult('✅', `${dayCards.length} cartes de jours trouvées`);
            } else {
                this.addResult('❌', 'Aucune carte de jour trouvée');
            }
            
        } catch (error) {
            this.addResult('❌', `Erreur d'initialisation: ${error.message}`);
        }
    }

    testDayToggles() {
        console.log('🔄 Test 2: Toggles des jours');
        
        try {
            // Tester l'activation/désactivation des jours
            for (let day = 0; day < 7; day++) {
                const checkbox = document.querySelector(`input[name="days[${day}][is_active]"]`);
                const inputs = document.getElementById(`day-inputs-${day}`);
                
                if (checkbox && inputs) {
                    // Test activation
                    checkbox.checked = true;
                    if (typeof toggleDayInputs === 'function') {
                        toggleDayInputs(day);
                        
                        if (!inputs.classList.contains('opacity-50')) {
                            this.addResult('✅', `Jour ${day + 1}: Activation OK`);
                        } else {
                            this.addResult('❌', `Jour ${day + 1}: Activation échouée`);
                        }
                    }
                    
                    // Test désactivation
                    checkbox.checked = false;
                    if (typeof toggleDayInputs === 'function') {
                        toggleDayInputs(day);
                        
                        if (inputs.classList.contains('opacity-50')) {
                            this.addResult('✅', `Jour ${day + 1}: Désactivation OK`);
                        } else {
                            this.addResult('❌', `Jour ${day + 1}: Désactivation échouée`);
                        }
                    }
                } else {
                    this.addResult('⚠️', `Jour ${day + 1}: Éléments DOM manquants`);
                }
            }
        } catch (error) {
            this.addResult('❌', `Erreur toggle jours: ${error.message}`);
        }
    }

    testUtilityFunctions() {
        console.log('🛠️ Test 3: Fonctions utilitaires');
        
        try {
            // Test toggleTimeInputs
            const timeInputs = document.getElementById('time-inputs');
            if (timeInputs && typeof toggleTimeInputs === 'function') {
                // Test affichage
                toggleTimeInputs('custom_hours');
                if (!timeInputs.classList.contains('hidden')) {
                    this.addResult('✅', 'toggleTimeInputs: Affichage OK');
                } else {
                    this.addResult('❌', 'toggleTimeInputs: Affichage échoué');
                }
                
                // Test masquage
                toggleTimeInputs('unavailable');
                if (timeInputs.classList.contains('hidden')) {
                    this.addResult('✅', 'toggleTimeInputs: Masquage OK');
                } else {
                    this.addResult('❌', 'toggleTimeInputs: Masquage échoué');
                }
            } else {
                this.addResult('⚠️', 'toggleTimeInputs: Éléments non trouvés');
            }
            
            // Test des fonctions d'action (sans exécution réelle)
            if (typeof copyWeekSchedule === 'function') {
                this.addResult('✅', 'copyWeekSchedule: Fonction disponible');
            }
            
            if (typeof resetToDefault === 'function') {
                this.addResult('✅', 'resetToDefault: Fonction disponible');
            }
            
        } catch (error) {
            this.addResult('❌', `Erreur fonctions utilitaires: ${error.message}`);
        }
    }

    testFormValidation() {
        console.log('📝 Test 4: Validation des formulaires');
        
        try {
            // Vérifier la présence des champs requis
            const requiredFields = [
                'input[type="time"]',
                'select[name*="slot_duration"]',
                'input[type="checkbox"]'
            ];
            
            requiredFields.forEach(selector => {
                const elements = document.querySelectorAll(selector);
                if (elements.length > 0) {
                    this.addResult('✅', `${elements.length} champs ${selector} trouvés`);
                } else {
                    this.addResult('⚠️', `Aucun champ ${selector} trouvé`);
                }
            });
            
            // Test de validation des heures
            const timeInputs = document.querySelectorAll('input[type="time"]');
            timeInputs.forEach((input, index) => {
                if (input.value && this.isValidTime(input.value)) {
                    this.addResult('✅', `Heure ${index + 1}: Format valide (${input.value})`);
                } else if (input.value) {
                    this.addResult('❌', `Heure ${index + 1}: Format invalide (${input.value})`);
                }
            });
            
        } catch (error) {
            this.addResult('❌', `Erreur validation: ${error.message}`);
        }
    }

    isValidTime(timeString) {
        const timeRegex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
        return timeRegex.test(timeString);
    }

    addResult(status, message) {
        this.testResults.push({ status, message, timestamp: new Date().toLocaleTimeString() });
    }

    displayResults() {
        console.log('📊 Résultats des tests:');
        console.log('========================');
        
        let passed = 0;
        let failed = 0;
        let warnings = 0;
        
        this.testResults.forEach(result => {
            console.log(`${result.status} [${result.timestamp}] ${result.message}`);
            
            if (result.status === '✅') passed++;
            else if (result.status === '❌') failed++;
            else if (result.status === '⚠️') warnings++;
        });
        
        console.log('========================');
        console.log(`📈 Résumé: ${passed} réussis, ${failed} échoués, ${warnings} avertissements`);
        
        // Afficher dans la page si possible
        this.displayResultsInPage(passed, failed, warnings);
    }

    displayResultsInPage(passed, failed, warnings) {
        const resultsContainer = document.getElementById('test-results');
        if (resultsContainer) {
            const summary = document.createElement('div');
            summary.className = 'mt-4 p-3 bg-gray-100 rounded';
            summary.innerHTML = `
                <h3 class="font-semibold text-gray-800 mb-2">Résumé des tests automatisés:</h3>
                <div class="text-sm space-y-1">
                    <p class="text-green-600">✅ Tests réussis: ${passed}</p>
                    <p class="text-red-600">❌ Tests échoués: ${failed}</p>
                    <p class="text-yellow-600">⚠️ Avertissements: ${warnings}</p>
                </div>
            `;
            resultsContainer.appendChild(summary);
        }
    }
}

// Démarrer les tests automatiquement si on est sur la page de test
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => new AvailabilityTester(), 1000);
    });
} else {
    setTimeout(() => new AvailabilityTester(), 1000);
}

// Exporter pour utilisation manuelle
window.AvailabilityTester = AvailabilityTester;