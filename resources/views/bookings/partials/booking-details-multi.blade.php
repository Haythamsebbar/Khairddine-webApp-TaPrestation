<!-- Détails de la réservation multiple -->
<div class="bg-white rounded-xl shadow-lg border border-blue-200 p-6">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-blue-800 flex items-center justify-center">
            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
            Détails de la session
        </h2>
    </div>
    
    <div class="space-y-4">
        <!-- Créneaux totaux -->
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-list text-blue-500 mr-3 w-5 text-base"></i>
                <span class="text-gray-600">Créneaux</span>
            </div>
            <span class="font-medium text-gray-900">{{ $allBookings->count() }} créneaux</span>
        </div>
        
        <!-- Période totale -->
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-calendar-week text-green-500 mr-3 w-5 text-base"></i>
                <span class="text-gray-600">Période</span>
            </div>
            <div class="text-right">
                <div class="font-medium text-gray-900">{{ $allBookings->first()->start_datetime->format('d/m/Y') }}</div>
                @if($allBookings->first()->start_datetime->format('Y-m-d') !== $allBookings->last()->start_datetime->format('Y-m-d'))
                    <div class="text-sm text-gray-500">au {{ $allBookings->last()->start_datetime->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>
        
        <!-- Durée totale -->
        @php
            $totalDuration = $allBookings->sum(function($booking) {
                return $booking->start_datetime->diffInMinutes($booking->end_datetime);
            });
            $hours = floor($totalDuration / 60);
            $minutes = $totalDuration % 60;
        @endphp
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-clock text-orange-500 mr-3 w-5 text-base"></i>
                <span class="text-gray-600">Durée totale</span>
            </div>
            <span class="font-medium text-gray-900">
                @if($hours > 0)
                    {{ $hours }}h{{ $minutes > 0 ? sprintf('%02d', $minutes) : '' }}
                @else
                    {{ $minutes }} min
                @endif
            </span>
        </div>
        
        <!-- Prix total -->
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-euro-sign text-yellow-500 mr-3 w-5 text-base"></i>
                <span class="text-gray-600">Prix total</span>
            </div>
            <span class="font-bold text-green-600">{{ number_format($totalSessionPrice, 2) }} €</span>
        </div>
        
        <!-- Créée le -->
        <div class="flex items-center justify-between py-3">
            <div class="flex items-center">
                <i class="fas fa-plus-circle text-purple-500 mr-3 w-5 text-base"></i>
                <span class="text-gray-600">Créée le</span>
            </div>
            <span class="font-medium text-gray-900">{{ $currentBooking->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
    
    <!-- Service info -->
    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center justify-center">
            <i class="fas fa-cogs text-blue-500 mr-2"></i>
            Service
        </h3>
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <h4 class="font-medium text-blue-900">{{ $currentBooking->service->name }}</h4>
            @if($currentBooking->service->description)
                <p class="text-blue-700 mt-2">{{ Str::limit($currentBooking->service->description, 100) }}</p>
            @endif
            <div class="text-blue-600 mt-2 font-medium">
                {{ number_format($currentBooking->service->price, 2) }} € par créneau
            </div>
        </div>
    </div>
    
    <!-- Détail du créneau actuel -->
    <div class="mt-6 pt-6 border-t border-gray-100">
        <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center justify-center">
            <i class="fas fa-eye text-green-500 mr-2"></i>
            Créneau actuel
        </h3>
        <div class="bg-green-50 rounded-lg p-4 text-center">
            <div class="font-medium text-green-900">
                {{ $currentBooking->start_datetime->format('d/m/Y à H:i') }}
            </div>
            <div class="text-green-700 mt-1">
                Fin prévue: {{ $currentBooking->end_datetime->format('H:i') }}
            </div>
            <div class="text-green-600 mt-1 font-medium">
                {{ $currentBooking->service->duration }} minutes
            </div>
        </div>
    </div>
</div>