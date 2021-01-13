<?php

namespace JuHeData\CasLogin\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuthLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $authUser;

    /**
     * Create a new event instance.
     * @param $authUser
     * @return void
     */
    public function __construct($authUser)
    {
        $this->authUser = $authUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
