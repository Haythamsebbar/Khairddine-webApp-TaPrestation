<!-- Détails de la réservation -->
<div class="bg-white rounded-lg shadow-sm border p-4 w-full max-w-md mx-auto">
    <div class="text-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-center">
            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
            Détails
        </h2>
    </div>
    
    <div class="space-y-3">
        <!-- Date et heure -->
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-calendar text-blue-500 mr-2 w-4 text-sm"></i>
                <span class="text-sm text-gray-600">Date</span>
            </div>
            <span class="text-sm font-medium text-gray-900">{{ $booking->booking_datetime->format('d/m/Y à H:i') }}</span>
        </div>
        
        <!-- Durée -->
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-clock text-green-500 mr-2 w-4 text-sm"></i>
                <span class="text-sm text-gray-600">Durée</span>
            </div>
            <span class="text-sm font-medium text-gray-900">{{ $booking->service->duration }} min</span>
        </div>
        
        <!-- Fin prévue -->
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-clock text-orange-500 mr-2 w-4 text-sm"></i>
                <span class="text-sm text-gray-600">Fin prévue</span>
            </div>
            <span class="text-sm font-medium text-gray-900">{{ $booking->booking_datetime->copy()->addMinutes($booking->service->duration)->format('H:i') }}</span>
        </div>
        
        <!-- Prix -->
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center">
                <i class="fas fa-euro-sign text-yellow-500 mr-2 w-4 text-sm"></i>
                <span class="text-sm text-gray-600">Prix</span>
            </div>
            <span class="text-sm font-bold text-green-600">{{ number_format($booking->total_price, 2) }} €</span>
        </div>
        
        <!-- Créée le -->
        <div class="flex items-center justify-between py-2">
            <div class="flex items-center">
                <i class="fas fa-plus-circle text-purple-500 mr-2 w-4 text-sm"></i>
                <span class="text-sm text-gray-600">Créée le</span>
            </div>
            <span class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('d/m/Y') }}</span>
        </div>
    </div>
    
    <!-- Service info -->
    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
        <h3 class="text-sm font-medium text-gray-900 mb-2 flex items-center justify-center">
            <i class="fas fa-cogs text-blue-500 mr-2"></i>
            Service
        </h3>
        <div class="bg-blue-50 rounded-lg p-3 text-center">
            <h4 class="font-medium text-blue-900 text-sm">{{ $booking->service->name }}</h4>
            @if($booking->service->description)
                <p class="text-xs text-blue-700 mt-1 line-clamp-2">{{ Str::limit($booking->service->description, 100) }}</p>
            @endif
        </div>
    </div>
</div>