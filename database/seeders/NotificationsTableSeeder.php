<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Offer;
use App\Models\Service;
use App\Models\Booking;
use App\Notifications\NewMessageNotification;
use App\Notifications\NewOfferNotification;
use App\Notifications\OfferAcceptedNotification;
use App\Notifications\OfferRejectedNotification;
use App\Notifications\NewReviewNotification;
use App\Notifications\PrestataireApprovedNotification;
use App\Notifications\AnnouncementStatusNotification;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\MissionCompletedNotification;
use App\Notifications\NewBookingNotification;
use App\Notifications\NewEquipmentRentalRequestNotification;
use Carbon\Carbon;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vider les notifications existantes
        Notification::truncate();
        
        // Récupération des utilisateurs
        $clients = User::where('role', 'client')->get();
        $prestataires = User::where('role', 'prestataire')->get();
        $admin = User::where('role', 'administrateur')->first();
        
        // Récupération des données pour les notifications
        $offers = Offer::all();
        $bookings = Booking::all();
        
        $this->command->info('Création de notifications de test...');
        
        // Notifications pour les clients
        foreach ($clients->take(5) as $index => $client) {
            $this->createClientNotifications($client, $index, $prestataires, $offers, $bookings);
        }
        
        // Notifications pour les prestataires
        foreach ($prestataires->take(5) as $index => $prestataire) {
            $this->createPrestataireNotifications($prestataire, $index, $clients, $offers, $bookings);
        }
        
        // Notifications pour l'administrateur
        if ($admin) {
            $this->createAdminNotifications($admin, $clients, $prestataires);
        }
        
        $this->command->info('Notifications de test créées avec succès !');
    }
    
    /**
     * Créer des notifications pour un client
     */
    private function createClientNotifications($client, $index, $prestataires, $offers, $bookings)
    {
        // 1. Notification de bienvenue
        Notification::create([
            'type' => 'welcome',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $client->id,
            'data' => [
                'title' => 'Bienvenue sur TaPrestation !',
                'message' => 'Merci de vous être inscrit sur notre plateforme. Découvrez nos prestataires qualifiés et publiez vos demandes.',
                'type' => 'welcome'
            ],
            'read_at' => $index === 0 ? null : Carbon::now()->subDays(rand(1, 5)),
            'created_at' => Carbon::now()->subDays(rand(5, 10)),
            'updated_at' => Carbon::now()->subDays(rand(1, 5)),
        ]);
        
        // 2. Nouvelle offre reçue
        if (!$prestataires->isEmpty()) {
            $randomPrestataire = $prestataires->random();
            Notification::create([
                'type' => NewOfferNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $client->id,
                'data' => [
                    'title' => 'Nouvelle offre reçue',
                    'message' => $randomPrestataire->name . ' vous a envoyé une offre pour votre demande de service. Prix proposé: ' . (500 + $index * 100) . '€',
                    'prestataire_name' => $randomPrestataire->name,
                    'prestataire_id' => $randomPrestataire->id,
                    'price' => 500 + $index * 100,
                    'url' => '/client/offers',
                    'type' => 'new_offer'
                ],
                'read_at' => $index % 2 === 0 ? null : Carbon::now()->subDays(rand(1, 3)),
                'created_at' => Carbon::now()->subDays(rand(1, 7)),
                'updated_at' => Carbon::now()->subDays(rand(1, 3)),
            ]);
        }
        
        // 3. Message reçu
        if (!$prestataires->isEmpty()) {
            $randomPrestataire = $prestataires->random();
            Notification::create([
                'type' => NewMessageNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $client->id,
                'data' => [
                    'title' => 'Nouveau message',
                    'message' => 'Vous avez reçu un nouveau message de ' . $randomPrestataire->name . '. Consultez votre messagerie pour y répondre.',
                    'sender_name' => $randomPrestataire->name,
                    'sender_id' => $randomPrestataire->id,
                    'url' => '/messages',
                    'type' => 'new_message'
                ],
                'read_at' => $index % 3 === 0 ? null : Carbon::now()->subHours(rand(1, 24)),
                'created_at' => Carbon::now()->subDays(rand(1, 3)),
                'updated_at' => Carbon::now()->subHours(rand(1, 24)),
            ]);
        }
        
        // 4. Réservation confirmée
        if (!$prestataires->isEmpty()) {
            $randomPrestataire = $prestataires->random();
            Notification::create([
                'type' => BookingConfirmedNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $client->id,
                'data' => [
                    'title' => 'Réservation confirmée',
                    'message' => 'Votre réservation avec ' . $randomPrestataire->name . ' a été confirmée pour le ' . Carbon::now()->addDays(rand(1, 14))->format('d/m/Y') . '.',
                    'prestataire_name' => $randomPrestataire->name,
                    'booking_date' => Carbon::now()->addDays(rand(1, 14))->format('d/m/Y'),
                    'url' => '/client/bookings',
                    'type' => 'booking_confirmed'
                ],
                'read_at' => $index % 4 === 0 ? null : Carbon::now()->subHours(rand(2, 48)),
                'created_at' => Carbon::now()->subHours(rand(2, 48)),
                'updated_at' => Carbon::now()->subHours(rand(2, 48)),
            ]);
        }
        
        // 5. Mission terminée
        if (!$prestataires->isEmpty() && $index < 3) {
            $randomPrestataire = $prestataires->random();
            Notification::create([
                'type' => MissionCompletedNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $client->id,
                'data' => [
                    'title' => 'Mission terminée',
                    'message' => $randomPrestataire->name . ' a marqué votre mission comme terminée. N\'oubliez pas de laisser un avis !',
                    'prestataire_name' => $randomPrestataire->name,
                    'url' => '/client/missions',
                    'type' => 'mission_completed'
                ],
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(rand(1, 12)),
                'updated_at' => Carbon::now()->subHours(rand(1, 12)),
            ]);
        }
    }
    
    /**
     * Créer des notifications pour un prestataire
     */
    private function createPrestataireNotifications($prestataire, $index, $clients, $offers, $bookings)
    {
        // 1. Notification de bienvenue
        Notification::create([
            'type' => 'welcome',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $prestataire->id,
            'data' => [
                'title' => 'Bienvenue chez TaPrestation !',
                'message' => 'Merci de rejoindre notre communauté de prestataires. Complétez votre profil pour recevoir vos premières demandes.',
                'type' => 'welcome'
            ],
            'read_at' => $index === 0 ? null : Carbon::now()->subDays(rand(1, 5)),
            'created_at' => Carbon::now()->subDays(rand(5, 10)),
            'updated_at' => Carbon::now()->subDays(rand(1, 5)),
        ]);
        
        // 2. Nouvelle demande de service
        if (!$clients->isEmpty()) {
            $randomClient = $clients->random();
            $services = ['Développement web', 'Design graphique', 'Plomberie', 'Électricité', 'Jardinage', 'Nettoyage'];
            $service = $services[array_rand($services)];
            
            Notification::create([
                'type' => NewEquipmentRentalRequestNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $prestataire->id,
                'data' => [
                    'title' => 'Nouvelle demande de service',
                    'message' => 'Une nouvelle demande pour "' . $service . '" de ' . $randomClient->name . ' correspond à vos compétences. Budget: ' . (300 + $index * 150) . '€',
                    'client_name' => $randomClient->name,
                    'service_title' => $service,
                    'budget' => 300 + $index * 150,
                    'url' => '/prestataire/requests',
                    'type' => 'new_request'
                ],
                'read_at' => $index % 2 === 0 ? null : Carbon::now()->subDays(rand(1, 3)),
                'created_at' => Carbon::now()->subDays(rand(1, 7)),
                'updated_at' => Carbon::now()->subDays(rand(1, 3)),
            ]);
        }
        
        // 3. Offre acceptée
        if (!$clients->isEmpty()) {
            $randomClient = $clients->random();
            Notification::create([
                'type' => OfferAcceptedNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $prestataire->id,
                'data' => [
                    'title' => 'Offre acceptée !',
                    'message' => 'Félicitations ! ' . $randomClient->name . ' a accepté votre offre de ' . (600 + $index * 100) . '€. Contactez-le pour organiser le travail.',
                    'client_name' => $randomClient->name,
                    'offer_price' => 600 + $index * 100,
                    'url' => '/prestataire/offers',
                    'type' => 'offer_accepted'
                ],
                'read_at' => $index % 3 === 0 ? null : Carbon::now()->subHours(rand(1, 24)),
                'created_at' => Carbon::now()->subDays(rand(1, 3)),
                'updated_at' => Carbon::now()->subHours(rand(1, 24)),
            ]);
        }
        
        // 4. Nouvelle évaluation
        if (!$clients->isEmpty()) {
            $randomClient = $clients->random();
            $rating = rand(4, 5);
            $comments = [
                'Excellent travail, très professionnel !',
                'Service de qualité, je recommande vivement.',
                'Travail soigné et dans les délais.',
                'Très satisfait du résultat final.',
                'Prestataire à l\'écoute et compétent.'
            ];
            
            Notification::create([
                'type' => NewReviewNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $prestataire->id,
                'data' => [
                    'title' => 'Nouvelle évaluation reçue',
                    'message' => $randomClient->name . ' vous a laissé une évaluation ' . $rating . '/5 étoiles : "' . $comments[array_rand($comments)] . '"',
                    'client_name' => $randomClient->name,
                    'rating' => $rating,
                    'comment' => $comments[array_rand($comments)],
                    'url' => '/prestataire/reviews',
                    'type' => 'new_review'
                ],
                'read_at' => $index % 2 === 0 ? null : Carbon::now()->subDays(rand(1, 2)),
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
                'updated_at' => Carbon::now()->subDays(rand(1, 2)),
            ]);
        }
        
        // 5. Statut d'annonce
        if ($index < 3) {
            $status = $index === 0 ? 'approved' : ($index === 1 ? 'rejected' : 'pending');
            $statusMessages = [
                'approved' => 'Votre annonce a été approuvée et est maintenant visible par les clients.',
                'rejected' => 'Votre annonce a été rejetée. Veuillez la modifier selon nos recommandations.',
                'pending' => 'Votre annonce est en cours de vérification par notre équipe.'
            ];
            
            Notification::create([
                'type' => AnnouncementStatusNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $prestataire->id,
                'data' => [
                    'title' => 'Statut de votre annonce',
                    'message' => $statusMessages[$status],
                    'status' => $status,
                    'url' => '/prestataire/announcements',
                    'type' => 'announcement_status'
                ],
                'read_at' => $status === 'pending' ? null : Carbon::now()->subHours(rand(2, 12)),
                'created_at' => Carbon::now()->subHours(rand(2, 12)),
                'updated_at' => Carbon::now()->subHours(rand(2, 12)),
            ]);
        }
    }
    
    /**
     * Créer des notifications pour l'administrateur
     */
    private function createAdminNotifications($admin, $clients, $prestataires)
    {
        // 1. Nouveau prestataire à approuver
        if (!$prestataires->isEmpty()) {
            $randomPrestataire = $prestataires->random();
            Notification::create([
                'type' => PrestataireApprovedNotification::class,
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $admin->id,
                'data' => [
                    'title' => 'Nouveau prestataire à vérifier',
                    'message' => $randomPrestataire->name . ' s\'est inscrit comme prestataire et attend votre validation. Vérifiez ses documents.',
                    'prestataire_name' => $randomPrestataire->name,
                    'prestataire_id' => $randomPrestataire->id,
                    'url' => '/admin/prestataires',
                    'type' => 'new_prestataire'
                ],
                'read_at' => null,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ]);
        }
        
        // 2. Signalement d'évaluation
        if (!$clients->isEmpty()) {
            $randomClient = $clients->random();
            Notification::create([
                'type' => 'reported_content',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => $admin->id,
                'data' => [
                    'title' => 'Contenu signalé',
                    'message' => 'Une évaluation de ' . $randomClient->name . ' a été signalée pour contenu inapproprié. Intervention requise.',
                    'reporter_name' => $randomClient->name,
                    'url' => '/admin/reports',
                    'type' => 'reported_content'
                ],
                'read_at' => null,
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(6),
            ]);
        }
        
        // 3. Statistiques hebdomadaires
        Notification::create([
            'type' => 'weekly_stats',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $admin->id,
            'data' => [
                'title' => 'Rapport hebdomadaire',
                'message' => 'Cette semaine: ' . rand(15, 35) . ' nouvelles inscriptions, ' . rand(50, 120) . ' demandes publiées, ' . rand(80, 200) . ' offres envoyées.',
                'new_users' => rand(15, 35),
                'new_requests' => rand(50, 120),
                'new_offers' => rand(80, 200),
                'url' => '/admin/dashboard',
                'type' => 'weekly_stats'
            ],
            'read_at' => Carbon::now()->subDays(2),
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(2),
        ]);
    }
}
