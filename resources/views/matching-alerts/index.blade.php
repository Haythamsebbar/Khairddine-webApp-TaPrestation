@extends('layouts.app')

@section('title', 'Mes Alertes de Correspondance')

@section('content')
<div class="container-fluid py-3 py-sm-4">
    <div class="row">
        <div class="col-12">
            <!-- En-tête avec statistiques -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 mb-sm-4">
                <div class="text-center text-md-start">
                    <h1 class="h4 h3 mb-1">Mes Alertes de Correspondance</h1>
                    <p class="text-muted mb-0 small">Gérez vos alertes et découvrez de nouveaux prestataires</p>
                </div>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double d-inline d-sm-none"></i>
                        <span class="d-none d-sm-inline">
                            <i class="fas fa-check-double"></i> Tout marquer comme lu
                        </span>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearDismissed()">
                        <i class="fas fa-trash d-inline d-sm-none"></i>
                        <span class="d-none d-sm-inline">
                            <i class="fas fa-trash"></i> Supprimer les ignorées
                        </span>
                    </button>
                    <a href="{{ route('matching-alerts.export') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download d-inline d-sm-none"></i>
                        <span class="d-none d-sm-inline">
                            <i class="fas fa-download"></i> Exporter CSV
                        </span>
                    </a>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-3 mb-sm-4 g-2 g-sm-3">
                <div class="col-6 col-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-2 p-sm-3">
                            <div class="text-primary mb-1 mb-sm-2">
                                <i class="fas fa-bell fa-lg fa-2x"></i>
                            </div>
                            <h5 class="mb-0 mb-sm-1">{{ $stats['total'] }}</h5>
                            <p class="text-muted mb-0 small">Total des alertes</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-2 p-sm-3">
                            <div class="text-warning mb-1 mb-sm-2">
                                <i class="fas fa-envelope fa-lg fa-2x"></i>
                            </div>
                            <h5 class="mb-0 mb-sm-1">{{ $stats['unread'] }}</h5>
                            <p class="text-muted mb-0 small">Non lues</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-2 p-sm-3">
                            <div class="text-success mb-1 mb-sm-2">
                                <i class="fas fa-star fa-lg fa-2x"></i>
                            </div>
                            <h5 class="mb-0 mb-sm-1">{{ $stats['high_match'] }}</h5>
                            <p class="text-muted mb-0 small">Correspondances élevées</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-2 p-sm-3">
                            <div class="text-info mb-1 mb-sm-2">
                                <i class="fas fa-chart-line fa-lg fa-2x"></i>
                            </div>
                            <h5 class="mb-0 mb-sm-1">{{ number_format($stats['average_score'], 1) }}%</h5>
                            <p class="text-muted mb-0 small">Score moyen</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card border-0 shadow-sm mb-3 mb-sm-4">
                <div class="card-body p-3 p-sm-4">
                    <form method="GET" action="{{ route('matching-alerts.index') }}" class="row g-2 g-sm-3">
                        <div class="col-12 col-md-3">
                            <label for="status" class="form-label small">Statut</label>
                            <select name="status" id="status" class="form-select form-select-sm">
                                <option value="">Tous les statuts</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues</option>
                                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Ignorées</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nouvelles</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="match_level" class="form-label small">Niveau de correspondance</label>
                            <select name="match_level" id="match_level" class="form-select form-select-sm">
                                <option value="">Tous les niveaux</option>
                                <option value="high" {{ request('match_level') == 'high' ? 'selected' : '' }}>Élevé</option>
                                <option value="medium" {{ request('match_level') == 'medium' ? 'selected' : '' }}>Moyen</option>
                                <option value="low" {{ request('match_level') == 'low' ? 'selected' : '' }}>Faible</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="min_score" class="form-label small">Score minimum</label>
                            <input type="number" name="min_score" id="min_score" class="form-control form-control-sm" 
                                   min="0" max="100" value="{{ request('min_score') }}" placeholder="0-100">
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm btn-primary flex-fill">
                                <i class="fas fa-filter"></i> <span class="d-none d-sm-inline">Filtrer</span>
                            </button>
                            <a href="{{ route('matching-alerts.index') }}" class="btn btn-sm btn-outline-secondary flex-fill">
                                <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Réinit.</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des alertes -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 p-sm-3">
                    @if($alerts->count() > 0)
                        <div class="row g-2 g-sm-3">
                            @foreach($alerts as $alert)
                                <div class="col-12">
                                    <div class="card border-start border-3 {{ $alert->is_read ? 'border-secondary' : 'border-primary' }} h-100 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="row align-items-center g-2">
                                                <div class="col-12 col-md-8">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                        <h6 class="mb-0 me-1">
                                                            <a href="{{ route('matching-alerts.show', $alert) }}" class="text-decoration-none">
                                                                {{ $alert->prestataire->user->name ?? 'Prestataire' }}
                                                            </a>
                                                        </h6>
                                                        @if($alert->is_new)
                                                            <span class="badge bg-success">Nouveau</span>
                                                        @endif
                                                        @if(!$alert->is_read)
                                                            <span class="badge bg-primary">Non lu</span>
                                                        @endif
                                                        @if($alert->is_dismissed)
                                                            <span class="badge bg-secondary">Ignoré</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-muted mb-2 small">
                                                        <i class="fas fa-search me-1"></i>
                                                        Recherche: <strong>{{ $alert->savedSearch->name }}</strong>
                                                    </p>
                                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                                        <span class="badge bg-{{ $alert->match_level_color }} small">
                                                            {{ $alert->formatted_score }} - {{ $alert->match_level_name }}
                                                        </span>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ $alert->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex flex-wrap gap-1 justify-content-md-end">
                                                        <a href="{{ route('matching-alerts.show', $alert) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye d-inline d-sm-none"></i>
                                                            <span class="d-none d-sm-inline">Voir</span>
                                                        </a>
                                                        @if(!$alert->is_read)
                                                            <button class="btn btn-sm btn-outline-success" onclick="markAsRead({{ $alert->id }})">
                                                                <i class="fas fa-check d-inline d-sm-none"></i>
                                                                <span class="d-none d-sm-inline">Lu</span>
                                                            </button>
                                                        @endif
                                                        @if(!$alert->is_dismissed)
                                                            <button class="btn btn-sm btn-outline-warning" onclick="dismissAlert({{ $alert->id }})">
                                                                <i class="fas fa-times d-inline d-sm-none"></i>
                                                                <span class="d-none d-sm-inline">Ignorer</span>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAlert({{ $alert->id }})">
                                                            <i class="fas fa-trash d-inline d-sm-none"></i>
                                                            <span class="d-none d-sm-inline">Suppr.</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3 mt-sm-4">
                            {{ $alerts->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-4 py-sm-5">
                            <i class="fas fa-bell-slash fa-2x fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-2">Aucune alerte trouvée</h5>
                            <p class="text-muted mb-3 small">Créez des recherches sauvegardées pour recevoir des alertes de correspondance.</p>
                            <a href="{{ route('saved-searches.index') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Créer une recherche
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(alertId) {
    fetch(`/api/matching-alerts/${alertId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

function dismissAlert(alertId) {
    if (confirm('Êtes-vous sûr de vouloir ignorer cette alerte ?')) {
        fetch(`/api/matching-alerts/${alertId}/dismiss`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

function deleteAlert(alertId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')) {
        fetch(`/api/matching-alerts/${alertId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

function markAllAsRead() {
    if (confirm('Marquer toutes les alertes comme lues ?')) {
        fetch('/api/matching-alerts/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

function clearDismissed() {
    if (confirm('Supprimer toutes les alertes ignorées ?')) {
        fetch('/api/matching-alerts/clear-dismissed', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}
</script>
@endsection