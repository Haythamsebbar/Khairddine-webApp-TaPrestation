<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ClientRequest;
use App\Models\Booking;

class MissionCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $clientRequest;
    protected $booking;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\ClientRequest  $clientRequest
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function __construct(ClientRequest $clientRequest, Booking $booking)
    {
        $this->clientRequest = $clientRequest;
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('prestataire.bookings.index');
        
        return (new MailMessage)
            ->subject('Mission marquée comme terminée')
            ->greeting('Bonjour ' . $notifiable->name . '!')
            ->line('Le client a marqué la mission "' . $this->clientRequest->title . '" comme terminée.')
            ->line('Vous pouvez maintenant recevoir une évaluation pour cette mission.')
            ->action('Voir vos réservations', $url)
            ->line('Merci d\'utiliser notre plateforme!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'client_request_id' => $this->clientRequest->id,
            'booking_id' => $this->booking->id,
            'message' => 'Le client a marqué la mission "' . $this->clientRequest->title . '" comme terminée',
            'type' => 'mission_completed',
            'url' => route('prestataire.bookings.index')
        ];
    }
}