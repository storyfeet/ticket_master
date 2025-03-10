<?php

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;

beforeEach(function() {

    $this->artisan( 'migrate --seed' );
    $normal = getUser('normal');
    $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"message_test",
            "content"=>"Who can message a ticket?",
        ]);
});

function getUser($name){
    return User::query()
        ->where('name','=',$name)
        ->first();
}
function getTestTicketID(){
    return Ticket::query()
        ->where('subject','=','message_test')
        ->first()['id'];
}

function numMessagesAdded(){
    $tid = getTestTicketID();
    return TicketMessage::query()
        ->where('ticket_id','=',$tid)
        ->count();
}

it("Creator can add message to ticket",function(){
    $normal = getUser('normal');
    $ticketId = getTestTicketID();
    $addResponse = $this->actingAs($normal)
        ->post("/user/new_ticket_message",[
            "message"=>"Can the creator?",
            "ticket_id"=>$ticketId,
        ]);
    $addResponse->assertOk();
    $this->assertEquals(numMessagesAdded(),1);

});
it("Admin can add message to ticket",function(){
    $admin = getUser('admin');
    $ticketId = getTestTicketID();
    $addResponse = $this->actingAs($admin)
        ->post("/user/new_ticket_message",[
            "message"=>"Can the creator?",
            "ticket_id"=>$ticketId,
        ]);
    $addResponse->assertOk();
    $this->assertEquals(numMessagesAdded(),1);

});

it("Other can add message to ticket",function(){
    $other = getUser('other');
    $ticketId = getTestTicketID();
    $addResponse = $this->actingAs($other)
        ->post("/user/new_ticket_message",[
            "message"=>"Can the creator?",
            "ticket_id"=>$ticketId,
        ]);
    $addResponse->assertStatus(403);
    $this->assertEquals(numMessagesAdded(),0);

});
