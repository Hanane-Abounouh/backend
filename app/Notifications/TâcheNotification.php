<?php

namespace App\Events;

use App\Models\Projet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjetInvité implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projet;
    public $utilisateurId;

    public function __construct(Projet $projet, $utilisateurId)
    {
        $this->projet = $projet;
        $this->utilisateurId = $utilisateurId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->utilisateurId);
    }

    public function broadcastWith()
    {
        return [
            'projet' => $this->projet,
            'message' => 'Vous avez été invité à rejoindre le projet.'
        ];
    }
}
