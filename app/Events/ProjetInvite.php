<?php

namespace App\Events;

use App\Models\Projet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjetInvité implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projet;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Projet $projet, $userId)
    {
        $this->projet = $projet;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('projet.' . $this->projet->id);
    }

    /**
     * Optionally, specify the event name if you want to customize it
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'InvitationProjet';
    }
}
