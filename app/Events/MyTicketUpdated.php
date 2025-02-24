<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class MyTicketUpdated implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    var int $user_id;
    var string $message;

    function __construct($user_id,$ticket){
        $this->user_id = $user_id;
        $this->message = json_encode($ticket);
    }
    function broadcastOn(){
        return new Channel("my_tickets" . $this->user_id );
    }
    public function broadcastAs():string{
        return $this->message;
    }
}
