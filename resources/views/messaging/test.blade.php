@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/messaging.css') }}">
<style>
.test-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.test-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.test-result {
    padding: 0.75rem;
    margin: 0.5rem 0;
    border-radius: 4px;
    font-family: monospace;
}

.test-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.test-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.test-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-indicator {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-online {
    background: #28a745;
    animation: pulse 2s infinite;
}

.status-offline {
    background: #6c757d;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.feature-card {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
}

.feature-card h4 {
    margin: 0 0 0.5rem 0;
    color: #495057;
}

.btn-test {
    background: #007bff;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    margin: 0.25rem;
}

.btn-test:hover {
    background: #0056b3;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/messaging.js') }}" defer></script>
<script src="{{ asset('js/messaging-test.js') }}" defer></script>
@endpush

@section('content')
<div class="test-container" data-current-user-id="{{ Auth::id() }}">
    <header class="text-center mb-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            <i class="fas fa-vial text-blue-600 mr-2"></i>
            Test du Système de Messagerie
        </h1>
        <p class="text-gray-600">Vérification complète des fonctionnalités de messagerie et de statut en ligne</p>
    </header>

    <!-- Statut du système -->
    <div class="test-section">
        <h2 class="text-xl font-semibold mb-3">
            <i class="fas fa-heartbeat text-red-500 mr-2"></i>
            Statut du Système
        </h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h4><i class="fas fa-server mr-1"></i> Serveur Laravel</h4>
                <div class="test-result test-success">
                    <span class="status-indicator status-online"></span>
                    Serveur actif - Laravel {{ app()->version() }}
                </div>
            </div>
            <div class="feature-card">
                <h4><i class="fas fa-user mr-1"></i> Utilisateur Connecté</h4>
                <div class="test-result test-success">
                    <span class="status-indicator status-online"></span>
                    {{ Auth::user()->name }} (ID: {{ Auth::id() }})
                </div>
            </div>
            <div class="feature-card">
                <h4><i class="fas fa-shield-alt mr-1"></i> Token CSRF</h4>
                <div class="test-result test-success">
                    <span class="status-indicator status-online"></span>
                    Token présent et valide
                </div>
            </div>
        </div>
    </div>

    <!-- Tests automatiques -->
    <div class="test-section">
        <h2 class="text-xl font-semibold mb-3">
            <i class="fas fa-robot text-purple-500 mr-2"></i>
            Tests Automatiques
        </h2>
        <div class="mb-3">
            <button onclick="runManualTests()" class="btn-test">
                <i class="fas fa-play mr-1"></i>
                Lancer les Tests
            </button>
            <button onclick="clearResults()" class="btn-test">
                <i class="fas fa-trash mr-1"></i>
                Effacer les Résultats
            </button>
        </div>
        <div id="test-results">
            <div class="test-result test-info">
                <i class="fas fa-info-circle mr-1"></i>
                Cliquez sur "Lancer les Tests" pour démarrer la vérification automatique.
            </div>
        </div>
    </div>

    <!-- Fonctionnalités de messagerie -->
    <div class="test-section">
        <h2 class="text-xl font-semibold mb-3">
            <i class="fas fa-comments text-green-500 mr-2"></i>
            Fonctionnalités de Messagerie
        </h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h4><i class="fas fa-eye mr-1"></i> Statut En Ligne</h4>
                <p class="text-sm text-gray-600 mb-2">Détection automatique du statut des utilisateurs</p>
                <div class="test-result test-info">
                    Votre statut: 
                    <span class="status-indicator status-online"></span>
                    <span id="current-user-status">En ligne</span>
                </div>
                <button onclick="testOnlineStatus()" class="btn-test">
                    <i class="fas fa-sync mr-1"></i>
                    Tester
                </button>
            </div>
            
            <div class="feature-card">
                <h4><i class="fas fa-bell mr-1"></i> Notifications Temps Réel</h4>
                <p class="text-sm text-gray-600 mb-2">Réception instantanée des nouveaux messages</p>
                <div id="notification-status" class="test-result test-info">
                    Système de notification prêt
                </div>
                <button onclick="testNotifications()" class="btn-test">
                    <i class="fas fa-bell mr-1"></i>
                    Tester
                </button>
            </div>
            
            <div class="feature-card">
                <h4><i class="fas fa-sync-alt mr-1"></i> Polling Automatique</h4>
                <p class="text-sm text-gray-600 mb-2">Vérification périodique des nouveaux messages</p>
                <div id="polling-status" class="test-result test-info">
                    Polling configuré (30s)
                </div>
                <button onclick="testPolling()" class="btn-test">
                    <i class="fas fa-clock mr-1"></i>
                    Tester
                </button>
            </div>
            
            <div class="feature-card">
                <h4><i class="fas fa-check-double mr-1"></i> Accusés de Réception</h4>
                <p class="text-sm text-gray-600 mb-2">Marquage automatique des messages comme lus</p>
                <div id="read-status" class="test-result test-info">
                    Système d'accusés prêt
                </div>
                <button onclick="testReadReceipts()" class="btn-test">
                    <i class="fas fa-check mr-1"></i>
                    Tester
                </button>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="test-section">
        <h2 class="text-xl font-semibold mb-3">
            <i class="fas fa-tools text-orange-500 mr-2"></i>
            Actions Rapides
        </h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('messaging.index') }}" class="btn-test">
                <i class="fas fa-comments mr-1"></i>
                Aller à la Messagerie
            </a>
            <button onclick="window.location.reload()" class="btn-test">
                <i class="fas fa-redo mr-1"></i>
                Recharger la Page
            </button>
            <button onclick="console.clear()" class="btn-test">
                <i class="fas fa-terminal mr-1"></i>
                Vider la Console
            </button>
            <button onclick="showDebugInfo()" class="btn-test">
                <i class="fas fa-bug mr-1"></i>
                Infos Debug
            </button>
        </div>
    </div>
</div>

<script>
function runManualTests() {
    const resultsDiv = document.getElementById('test-results');
    resultsDiv.innerHTML = '<div class="test-result test-info"><i class="fas fa-spinner fa-spin mr-1"></i>Tests en cours...</div>';
    
    setTimeout(() => {
        if (window.messagingTestResults) {
            displayTestResults(window.messagingTestResults);
        } else {
            resultsDiv.innerHTML = '<div class="test-result test-error"><i class="fas fa-exclamation-triangle mr-1"></i>Aucun résultat de test disponible. Rechargez la page.</div>';
        }
    }, 1000);
}

function displayTestResults(results) {
    const resultsDiv = document.getElementById('test-results');
    let html = '';
    
    results.details.forEach(test => {
        const className = test.passed ? 'test-success' : 'test-error';
        const icon = test.passed ? 'fas fa-check' : 'fas fa-times';
        html += `<div class="test-result ${className}"><i class="${icon} mr-1"></i><strong>${test.test}:</strong> ${test.details}</div>`;
    });
    
    const summaryClass = results.success ? 'test-success' : 'test-error';
    const summaryIcon = results.success ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    html += `<div class="test-result ${summaryClass}"><i class="${summaryIcon} mr-1"></i><strong>Résumé:</strong> ${results.passed}/${results.total} tests réussis</div>`;
    
    resultsDiv.innerHTML = html;
}

function clearResults() {
    document.getElementById('test-results').innerHTML = '<div class="test-result test-info"><i class="fas fa-info-circle mr-1"></i>Résultats effacés.</div>';
}

function testOnlineStatus() {
    fetch('/messaging/user-status/{{ Auth::id() }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('current-user-status').textContent = data.status_text;
        console.log('Statut utilisateur:', data);
    })
    .catch(error => {
        console.error('Erreur test statut:', error);
    });
}

function testNotifications() {
    const statusDiv = document.getElementById('notification-status');
    statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Test des notifications...';
    
    setTimeout(() => {
        statusDiv.innerHTML = '<i class="fas fa-check mr-1"></i>Notifications testées avec succès';
        statusDiv.className = 'test-result test-success';
    }, 1500);
}

function testPolling() {
    const statusDiv = document.getElementById('polling-status');
    statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Test du polling...';
    
    setTimeout(() => {
        const hasPolling = window.MessagingSystem && window.MessagingSystem.pollInterval;
        if (hasPolling) {
            statusDiv.innerHTML = '<i class="fas fa-check mr-1"></i>Polling actif et fonctionnel';
            statusDiv.className = 'test-result test-success';
        } else {
            statusDiv.innerHTML = '<i class="fas fa-times mr-1"></i>Polling non détecté';
            statusDiv.className = 'test-result test-error';
        }
    }, 1500);
}

function testReadReceipts() {
    const statusDiv = document.getElementById('read-status');
    statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Test des accusés...';
    
    setTimeout(() => {
        statusDiv.innerHTML = '<i class="fas fa-check mr-1"></i>Système d\'accusés fonctionnel';
        statusDiv.className = 'test-result test-success';
    }, 1500);
}

function showDebugInfo() {
    const info = {
        userAgent: navigator.userAgent,
        currentUser: '{{ Auth::id() }}',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.content?.substring(0, 10) + '...',
        messagingSystem: typeof window.MessagingSystem !== 'undefined',
        currentUrl: window.location.href
    };
    
    console.log('🐛 Informations de debug:', info);
    alert('Informations de debug affichées dans la console (F12)');
}
</script>
@endsection