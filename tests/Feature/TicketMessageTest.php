<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Log;

beforeEach(function (){
    $this->artisan("migrate:fresh --seed");
});

it("Can add and see messages",function(){
    $normal = User::getByName("normal");

    $this->actingAs($normal)
        ->post("/user/new_ticket",[
                "subject"=>"can_see_messages",
                "content"=>"can I see the messages?",
            ]);
    $tid = Ticket::getIdBySubject("can_see_messages");

    $admin = User::getByName("admin");
    $this->actingAs($admin)
        ->post("/user/new_ticket_message",[
            "ticket_id"=>$tid,
            "message"=>"can you see this one?",
        ]);

    $messages = $this->actingAs($normal)
        ->get("/user/get_ticket_messages/".$tid);
    Log::debug("Bad Response");
    //Log::debug($messages->content());
    $messages->assertOk();
    $messages->assertJsonFragment(["message"=>"can you see this one?"]);

});
