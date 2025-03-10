<?php

use App\Models\Ticket;
use App\Models\User;

beforeEach(function (){
    $this->artisan( 'migrate --seed' );
    $normal = getUser('normal');
    $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"stats ticket",
            "content"=>"Is this the best stats?",
        ]);
    $tid = Ticket::query()
        ->where("subject",'=','stats ticket')
        ->first()['id'];

    $this->actingAs($normal)
        ->post("/user/new_ticket_message",[
            "ticket_id"=>$tid,
            "message"=>"Can I be champion?",
            "close"=>true,
        ]);
});
function getUser($name){
    return User::query()
        ->where('name','=',$name)
        ->first();
}

it("Stats finds most recent ticket", function () {
    $response = $this->get("/stats");
    $response->assertOk();
    $response->assertJsonPath("last_processed.subject","stats ticket");
});
