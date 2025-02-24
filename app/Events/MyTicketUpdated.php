<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class MyTicketUpdated implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var int $user_id;
    public array $ticket;

    function __construct($user_id,$ticket){

        $this->user_id = $user_id;
        $this->ticket = $ticket->toArray();
    }
    function broadcastOn(){

        return new PrivateChannel("my_tickets." . $this->user_id );
    }
    public function broadcastAs():string{
        return "updated";
    }

    public function broadcastWith() :array {
        return $this->ticket;
    }
}
