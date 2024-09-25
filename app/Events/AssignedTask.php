<?php
namespace App\Events;

use App\Models\Tâche;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TâcheAssignée implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tâche;

    public function __construct(Tâche $tâche)
    {
        $this->tâche = $tâche;
    }

    // Définir le canal sur lequel cet événement sera diffusé
    public function broadcastOn()
    {
        return new PrivateChannel('tâche.' . $this->tâche->id);
    }

    public function broadcastWith()
    {
        return ['tâche' => $this->tâche];
    }
}
