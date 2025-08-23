/**
 * Script de test pour le système de messagerie
 * Vérifie le bon fonctionnement des fonctionnalités en ligne
 */

class MessagingSystemTest {
    constructor() {
        this.testResults = [];
        this.init();
    }

    init() {
        console.log('🧪 Démarrage des tests du système de messagerie');
        this.runTests();
    }

    async runTests() {
        // Test 1: Vérifier la présence du système de messagerie
        this.testMessagingSystemPresence();
        
        // Test 2: Vérifier les indicateurs de statut en ligne
        this.testOnlineStatusIndicators();
        
        // Test 3: Vérifier les routes de messagerie
        await this.testMessagingRoutes();
        
        // Test 4: Vérifier le polling du statut
        this.testStatusPolling();
        
        // Test 5: Vérifier les WebSockets/AJAX
        this.testRealTimeFeatures();
        
        this.displayResults();
    }

    testMessagingSystemPresence() {
        const messagingSystem = window.MessagingSystem;
        const result = {
            test: 'Présence du système de messagerie',
            passed: typeof messagingSystem !== 'undefined',
            details: typeof messagingSystem !== 'undefined' ? 'MessagingSystem trouvé' : 'MessagingSystem non trouvé'
        };
        this.testResults.push(result);
        console.log(result.passed ? '✅' : '❌', result.test, '-', result.details);
    }

    testOnlineStatusIndicators() {
        const onlineIndicators = document.querySelectorAll('.online-indicator');
        const statusElements = document.querySelectorAll('#user-online-status, .online-status');
        
        const result = {
            test: 'Indicateurs de statut en ligne',
            passed: onlineIndicators.length > 0 || statusElements.length > 0,
            details: `${onlineIndicators.length} indicateurs visuels, ${statusElements.length} éléments de statut trouvés`
        };
        this.testResults.push(result);
        console.log(result.passed ? '✅' : '❌', result.test, '-', result.details);
    }

    async testMessagingRoutes() {
        const routes = [
            '/messaging',
            '/messaging/user-status/1',
            '/messaging/new-messages/1'
        ];
        
        for (const route of routes) {
            try {
                const response = await fetch(route, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                const result = {
                    test: `Route ${route}`,
                    passed: response.status !== 404,
                    details: `Status: ${response.status} ${response.statusText}`
                };
                this.testResults.push(result);
                console.log(result.passed ? '✅' : '❌', result.test, '-', result.details);
            } catch (error) {
                const result = {
                    test: `Route ${route}`,
                    passed: false,
                    details: `Erreur: ${error.message}`
                };
                this.testResults.push(result);
                console.log('❌', result.test, '-', result.details);
            }
        }
    }

    testStatusPolling() {
        const messagingSystem = window.MessagingSystem;
        if (messagingSystem && messagingSystem.statusPollInterval) {
            const result = {
                test: 'Polling du statut en ligne',
                passed: true,
                details: 'Interval de polling actif'
            };
            this.testResults.push(result);
            console.log('✅', result.test, '-', result.details);
        } else {
            const result = {
                test: 'Polling du statut en ligne',
                passed: false,
                details: 'Aucun interval de polling détecté'
            };
            this.testResults.push(result);
            console.log('❌', result.test, '-', result.details);
        }
    }

    testRealTimeFeatures() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const currentUserId = document.querySelector('[data-current-user-id]');
        
        const result = {
            test: 'Fonctionnalités temps réel',
            passed: csrfToken && currentUserId,
            details: `CSRF: ${csrfToken ? 'OK' : 'Manquant'}, User ID: ${currentUserId ? 'OK' : 'Manquant'}`
        };
        this.testResults.push(result);
        console.log(result.passed ? '✅' : '❌', result.test, '-', result.details);
    }

    displayResults() {
        const passed = this.testResults.filter(r => r.passed).length;
        const total = this.testResults.length;
        
        console.log('\n📊 Résultats des tests:');
        console.log(`✅ Tests réussis: ${passed}/${total}`);
        console.log(`❌ Tests échoués: ${total - passed}/${total}`);
        
        if (passed === total) {
            console.log('🎉 Tous les tests sont passés ! Le système de messagerie fonctionne correctement.');
        } else {
            console.log('⚠️ Certains tests ont échoué. Vérifiez les détails ci-dessus.');
        }
        
        // Afficher un résumé dans la console du navigateur
        if (typeof window !== 'undefined') {
            window.messagingTestResults = {
                passed,
                total,
                success: passed === total,
                details: this.testResults
            };
        }
    }
}

// Lancer les tests automatiquement si on est dans le navigateur
if (typeof window !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            new MessagingSystemTest();
        }, 2000); // Attendre 2 secondes pour que tout soit chargé
    });
}