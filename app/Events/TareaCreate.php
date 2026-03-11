<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TareaCreate  implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $post;
    public $userId;

    public function __construct($tarea)
    {
        $this->post = $tarea;
        $this->userId = $this->post->id_user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        /* return new Channel('crearTarea'); */
        return new PrivateChannel('crearTarea.'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'create';
    }

    public function broadcastWith(): array
    {
        return [
            $this->post->id_user
        ];
    }
}
