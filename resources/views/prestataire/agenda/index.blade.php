@extends('layouts.app')

@section('title', 'Mon Agenda - Prestataire')

@section('content')
<div class="min-h-screen bg-blue-50">
    <div class="container mx-auto py-8 px-4">
        <!-- En-tête amélioré -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6-6V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-3" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-extrabold text-blue-900 mb-2">
                            Mon Agenda
                        </h1>
                        <p class="text-lg text-blue-700">Gérez vos réservations et planifiez vos prestations</p>
                    </div>
                </div>
                
                <!-- Navigation et boutons de vue -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Boutons de navigation -->
                    <div class="flex items-center bg-white rounded-xl shadow-lg border border-blue-200 p-1">
                        <button onclick="navigateCalendar('prev')" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition duration-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button onclick="navigateCalendar('today')" class="px-3 py-2 text-sm font-bold text-blue-800 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition duration-200">
                            Aujourd'hui
                        </button>
                        <button onclick="navigateCalendar('next')" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition duration-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Sélecteur de vue (onglets arrondis) -->
                    <div class="bg-white rounded-xl shadow-lg border border-blue-200 p-1 flex">
                        <button onclick="changeView('month')" class="view-btn px-4 py-2 text-sm font-bold rounded-lg transition duration-200 {{ $view === 'month' ? 'bg-blue-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'text-blue-800 hover:text-blue-900 hover:bg-blue-50' }}">
                            Mois
                        </button>
                        <button onclick="changeView('week')" class="view-btn px-4 py-2 text-sm font-bold rounded-lg transition duration-200 {{ $view === 'week' ? 'bg-blue-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'text-blue-800 hover:text-blue-900 hover:bg-blue-50' }}">
                            Semaine
                        </button>
                        <button onclick="changeView('day')" class="view-btn px-4 py-2 text-sm font-bold rounded-lg transition duration-200 {{ $view === 'day' ? 'bg-blue-600 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5' : 'text-blue-800 hover:text-blue-900 hover:bg-blue-50' }}">
                            Jour
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Légende des couleurs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                🎨 Code couleur des événements
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                    <div>
                        <span class="text-sm font-medium text-blue-800">🛠️ Services</span>
                        <p class="text-xs text-blue-600">Prestations de services</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                    <div>
                        <span class="text-sm font-medium text-green-800">⚙️ Équipements</span>
                        <p class="text-xs text-green-600">Locations d'équipements</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg border border-red-200">
                    <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                    <div>
                        <span class="text-sm font-medium text-red-800">⚡ Ventes urgentes</span>
                        <p class="text-xs text-red-600">Ventes à traiter rapidement</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Disposition responsive : Desktop (liste à gauche, calendrier à droite) / Mobile (liste en haut, calendrier en dessous) -->
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
            <!-- Liste des demandes récentes (Desktop: gauche, Mobile: haut) -->
            <div class="xl:col-span-1 order-1 xl:order-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-4">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            📋 Demandes récentes
                        </h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $recentBookings->count() }}
                        </span>
                    </div>
                    
                    <!-- Filtre de recherche -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="quickSearch" placeholder="Rechercher..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Liste des demandes -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($recentBookings as $booking)
                            <div class="p-3 border border-blue-200 rounded-lg hover:border-blue-400 hover:shadow-md transition-all duration-200 cursor-pointer bg-blue-50 hover:bg-blue-100" 
                                 onclick="showBookingDetails({{ $booking->id }})">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-blue-900 truncate">
                                            {{ $booking->service->title ?? 'Service' }}
                                        </h4>
                                        <p class="text-xs text-blue-700 mt-1">
                                             {{ $booking->client->user->name ?? 'N/A' }}
                                         </p>
                                         <p class="text-xs text-blue-600 mt-1">
                                             {{ $booking->start_datetime->format('d/m H:i') }}
                                         </p>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-orange-100 text-orange-800
                                        @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a2 2 0 100-4 2 2 0 000 4zm6-6V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-3" />
                                </svg>
                                <h3 class="mt-2 text-sm font-bold text-blue-900">Aucune demande</h3>
                                <p class="mt-1 text-sm text-blue-600">Aucune demande récente trouvée.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Lien vers toutes les demandes -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('prestataire.bookings.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                            Voir toutes les demandes
                            <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Calendrier principal (Desktop: droite, Mobile: dessous) -->
            <div class="xl:col-span-3 order-2 xl:order-2">
                <div class="bg-white rounded-xl shadow-lg border border-blue-200 overflow-hidden">
                    <div class="p-6">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal pour les détails de réservation -->
<div id="bookingModal" class="fixed inset-0 bg-blue-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-blue-200 w-11/12 md:w-3/4 lg:w-1/2 shadow-2xl rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-blue-900" id="modalTitle">Détails de la réservation</h3>
                <button onclick="closeModal()" class="text-blue-400 hover:text-blue-600 transition duration-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Le contenu sera chargé dynamiquement -->
                <div class="space-y-3">
                    <div>
                        <span class="font-semibold text-blue-800">Client:</span>
                        <span id="modal-client-name" class="text-blue-700"></span>
                    </div>
                    <div id="modal-service-section">
                        <span class="font-semibold text-blue-800">Service:</span>
                        <span id="modal-service-name" class="text-blue-700"></span>
                    </div>
                    <div id="modal-equipment-section" style="display: none;">
                        <span class="font-semibold text-blue-800">Équipement:</span>
                        <span id="modal-equipment-name" class="text-blue-700"></span>
                    </div>
                    <div id="modal-time-section">
                        <span class="font-semibold text-blue-800">Heure:</span>
                        <span id="modal-start-time" class="text-blue-700"></span>
                    </div>
                    <div id="modal-date-section" style="display: none;">
                        <span class="font-semibold text-blue-800">Période:</span>
                        <span id="modal-date-range" class="text-blue-700"></span>
                    </div>
                    <div>
                        <span class="font-semibold text-blue-800">Statut:</span>
                        <span id="modal-status" class="px-2 py-1 rounded text-sm font-medium"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    /* Calendrier principal */
    #calendar {
        background: white;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
    }
    
    /* En-tête du calendrier */
    .fc-header-toolbar {
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1rem !important;
    }
    
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937;
    }
    
    /* Boutons de navigation */
    .fc-button {
        background: #f3f4f6 !important;
        border: 1px solid #d1d5db !important;
        color: #374151 !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }
    
    .fc-button:hover {
        background: #e5e7eb !important;
        border-color: #9ca3af !important;
    }
    
    .fc-button:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .fc-button-active {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }
    
    /* Grille du calendrier */
    .fc-scrollgrid {
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
    
    .fc-col-header {
        background: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
    }
    
    .fc-col-header-cell {
        padding: 0.75rem 0.5rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
    }
    
    /* Cellules des jours */
    .fc-daygrid-day {
        border: 1px solid #f3f4f6 !important;
        min-height: 120px !important;
        background: white !important;
        transition: background-color 0.2s ease !important;
    }
    
    .fc-daygrid-day:hover {
        background: #f8fafc !important;
    }
    
    /* Weekends grisés */
    .fc-day-sat, .fc-day-sun {
        background: #f9fafb !important;
    }
    
    /* Date du jour mise en avant */
    .fc-day-today {
        background: #eff6ff !important;
        border: 2px solid #3b82f6 !important;
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background: #3b82f6 !important;
        color: white !important;
        border-radius: 50% !important;
        width: 28px !important;
        height: 28px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 700 !important;
        margin: 4px !important;
    }
    
    /* Numéros des jours */
    .fc-daygrid-day-number {
        padding: 4px 8px !important;
        font-weight: 600 !important;
        color: #374151 !important;
        text-decoration: none !important;
    }
    
    /* Événements */
    .fc-event {
        border: none !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        margin: 2px 4px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .fc-event:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Couleurs des événements par type */
    .fc-event.event-service {
        background: #dbeafe !important;
        color: #1e40af !important;
        border-left: 4px solid #3b82f6 !important;
    }
    
    .fc-event.event-equipment {
        background: #dcfce7 !important;
        color: #166534 !important;
        border-left: 4px solid #22c55e !important;
    }
    
    .fc-event.event-urgent_sale {
        background: #fee2e2 !important;
        color: #991b1b !important;
        border-left: 4px solid #ef4444 !important;
    }
    
    /* Titre des événements */
    .fc-event-title {
        font-weight: 700 !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
    }
    
    .fc-event-time {
        font-weight: 800 !important;
        margin-right: 4px !important;
    }
    
    /* Tooltip personnalisé */
    .event-tooltip {
        position: absolute;
        z-index: 1000;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        max-width: 300px;
        font-size: 13px;
        line-height: 1.4;
    }
    
    .event-tooltip h4 {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .event-tooltip p {
        margin: 4px 0;
        color: #6b7280;
    }
    
    .event-tooltip .tooltip-type {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .tooltip-type.service {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .tooltip-type.equipment {
        background: #dcfce7;
        color: #166534;
    }
    
    .tooltip-type.urgent_sale {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .event-tooltip .tooltip-link {
        display: inline-block;
        margin-top: 8px;
        padding: 6px 12px;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    
    .event-tooltip .tooltip-link:hover {
        background: #2563eb;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column !important;
            gap: 1rem !important;
        }
        
        .fc-toolbar-title {
            font-size: 1.25rem !important;
        }
        
        .fc-daygrid-day {
            min-height: 80px !important;
        }
        
        .fc-event {
            font-size: 10px !important;
            padding: 2px 4px !important;
        }
    }
    
    /* Focus pour l'accessibilité */
    .fc-event:focus {
        outline: 2px solid #3b82f6 !important;
        outline-offset: 2px !important;
    }
    
    .fc-daygrid-day:focus-within {
        background: #f0f9ff !important;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
    let calendar;
    let currentView = '{{ $view }}';
    
    document.addEventListener('DOMContentLoaded', function() {
        @if($view !== 'list')
            initCalendar();
        @endif
    });
    
    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: currentView === 'week' ? 'timeGridWeek' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            locale: 'fr',
            firstDay: 1,
            height: 'auto',
            events: '{{ route("prestataire.agenda.events") }}',
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                // Remplir la modal avec les informations de l'événement
                document.getElementById('modal-client-name').textContent = props.clientName;
                
                // Gérer l'affichage selon le type d'événement
                if (props.itemType === 'equipment_rental') {
                    // Afficher les sections équipement
                    document.getElementById('modal-service-section').style.display = 'none';
                    document.getElementById('modal-equipment-section').style.display = 'block';
                    document.getElementById('modal-time-section').style.display = 'none';
                    document.getElementById('modal-date-section').style.display = 'block';
                    
                    document.getElementById('modal-equipment-name').textContent = props.equipmentName;
                    document.getElementById('modal-date-range').textContent = props.startDate + ' - ' + props.endDate;
                } else {
                    // Afficher les sections service
                    document.getElementById('modal-service-section').style.display = 'block';
                    document.getElementById('modal-equipment-section').style.display = 'none';
                    document.getElementById('modal-time-section').style.display = 'block';
                    document.getElementById('modal-date-section').style.display = 'none';
                    
                    document.getElementById('modal-service-name').textContent = props.serviceName;
                    document.getElementById('modal-start-time').textContent = props.startTime;
                }
                
                document.getElementById('modal-status').textContent = props.status;
                
                // Styliser le statut
                const statusElement = document.getElementById('modal-status');
                statusElement.className = 'px-2 py-1 rounded text-sm font-medium';
                
                switch(props.status.toLowerCase()) {
                    case 'pending':
                        statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
                        break;
                    case 'confirmed':
                    case 'active':
                        statusElement.classList.add('bg-green-100', 'text-green-800');
                        break;
                    case 'completed':
                        statusElement.classList.add('bg-blue-100', 'text-blue-800');
                        break;
                    case 'cancelled':
                        statusElement.classList.add('bg-red-100', 'text-red-800');
                        break;
                    default:
                        statusElement.classList.add('bg-gray-100', 'text-gray-800');
                }
                
                // Afficher la modal
                 document.getElementById('eventModal').classList.remove('hidden');
            },
            eventDidMount: function(info) {
                // Ajouter des tooltips
                info.el.setAttribute('title', 
                    info.event.title + '\n' +
                    'Client: ' + info.event.extendedProps.client + '\n' +
                    'Statut: ' + info.event.extendedProps.status
                );
            }
        });
        
        calendar.render();
    }
    
    function changeView(view) {
        currentView = view;
        
        // Mettre à jour les boutons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.classList.remove('bg-blue-100', 'text-blue-700');
            btn.classList.add('text-gray-500', 'hover:text-gray-700');
        });
        
        event.target.classList.remove('text-gray-500', 'hover:text-gray-700');
        event.target.classList.add('bg-blue-100', 'text-blue-700');
        
        // Rediriger avec le nouveau paramètre de vue
        const url = new URL(window.location);
        url.searchParams.set('view', view);
        window.location.href = url.toString();
    }
    
    function applyFilters() {
        const url = new URL(window.location);
        
        // Récupérer les valeurs des filtres
        const search = document.getElementById('search').value;
        const service = document.getElementById('service_filter').value;
        const status = document.getElementById('status_filter').value;
        
        // Mettre à jour les paramètres URL
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (service) url.searchParams.set('service', service);
        else url.searchParams.delete('service');
        
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        
        window.location.href = url.toString();
    }
    
    function clearFilters() {
        const url = new URL(window.location);
        url.searchParams.delete('search');
        url.searchParams.delete('service');
        url.searchParams.delete('status');
        url.searchParams.delete('client');
        window.location.href = url.toString();
    }
    
    function showBookingDetails(bookingId) {
        fetch(`/prestataire/agenda/booking/${bookingId}`)
            .then(response => response.json())
            .then(data => {
                const booking = data.booking;
                const modalContent = document.getElementById('modalContent');
                
                modalContent.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service</label>
                                <p class="mt-1 text-sm text-gray-900">${booking.service?.title || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Numéro de réservation</label>
                                <p class="mt-1 text-sm text-gray-900">#${booking.booking_number}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Client</label>
                                <p class="mt-1 text-sm text-gray-900">${booking.client?.user?.name || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Statut</label>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                    booking.status === 'confirmed' ? 'bg-green-100 text-green-800' :
                                    booking.status === 'pending' ? 'bg-orange-100 text-orange-800' :
                                    booking.status === 'completed' ? 'bg-indigo-100 text-indigo-800' :
                                    'bg-red-100 text-red-800'
                                }">
                                    ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date et heure</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(booking.start_datetime).toLocaleString('fr-FR')}</p>
                            </div>
                            <!-- Informations financières supprimées -->
                        </div>
                        
                        ${booking.client_notes ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes du client</label>
                                <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">${booking.client_notes}</p>
                            </div>
                        ` : ''}
                        
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            ${data.canConfirm ? `
                                <button onclick="updateBookingStatus(${booking.id}, 'confirmed')" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Confirmer
                                </button>
                            ` : ''}
                            
                            ${data.canCancel ? `
                                <button onclick="cancelBooking(${booking.id})" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Annuler
                                </button>
                            ` : ''}
                            
                            ${data.canComplete ? `
                                <button onclick="updateBookingStatus(${booking.id}, 'completed')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Marquer terminé
                                </button>
                            ` : ''}
                            
                            <button onclick="closeModal()" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold px-4 py-2 rounded-lg text-sm transition duration-200">
                                Fermer
                            </button>
                        </div>
                    </div>
                `;
                
                document.getElementById('bookingModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des détails');
            });
    }
    
    function updateBookingStatus(bookingId, status) {
        fetch(`/prestataire/agenda/booking/${bookingId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la mise à jour');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la mise à jour');
        });
    }
    
    function cancelBooking(bookingId) {
        const reason = prompt('Raison de l\'annulation (optionnel):');
        if (reason === null) return; // Utilisateur a annulé
        
        fetch(`/prestataire/agenda/booking/${bookingId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                status: 'cancelled',
                reason: reason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de l\'annulation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation');
        });
    }
    
    function closeModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }
    
    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endpush