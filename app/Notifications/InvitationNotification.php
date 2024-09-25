<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class InvitationNotification extends Notification
{
    use Queueable;

    protected $projet;

    public function __construct($projet)
    {
        $this->projet = $projet;
    }

    // Détermine les canaux de notification
    public function via($notifiable)
    {
        return ['mail', 'broadcast']; // Ajoutez 'mail' pour envoyer des emails
    }

    // Configure le message pour l'envoi d'email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Invitation à rejoindre le projet')
            ->line('Vous avez été invité à rejoindre le projet : ' . $this->projet->name)
            ->action('Voir le projet', url('/projets/' . $this->projet->id))
            ->line('Merci de votre participation !');
    }

    // Configure le message pour la diffusion
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Vous avez été invité à rejoindre le projet : ' . $this->projet->name,
            'projet' => $this->projet,
        ]);
    }
}
