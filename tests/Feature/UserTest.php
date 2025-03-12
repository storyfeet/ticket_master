<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->artisan('migrate --seed');

    User::factory()->create([
        "name"=>"newbie",
        "email"=>"newbie@gmail.com",
        "password"=>Hash::make('newbienewbienewbie'),
    ]);

    $normal = User::getByName("newbie");
    $this->actingAs($normal)
        ->post("/user/new_ticket",[
           "subject"=>"still_open",
           "content"=>"a ticket that's still open",
        ]);

    $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"still_open",
            "content"=>"a ticket that will stay open",
        ]);
    $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"already_closed",
            "content"=>"a ticket that's gonna be closed right away",
        ]);
    $closeID = Ticket::getIdBySubject("already_closed");
    $this->actingAs($normal)
        ->post("/user/new_ticket_message",[
                "ticket_id"=>$closeID,
                "message"=>"closing",
                "close"=>true,
            ]);

});

it("user ticket routes find tickets",function($path,$find){
    $normal = User::getByName("newbie");
    $response = $this->actingAs($normal)->get($path);
    $response->assertOk();
    foreach ($find as $f){
        $response->assertJsonFragment(["subject"=>$f]);
    }

})->with([
    ["path"=>"/user/get_open","find"=>["still_open"]],
    ["path"=>"/user/get_closed","find"=>["already_closed"]],
    ["path"=>"/user/get_all","find"=>["still_open","already_closed"]],
 ]);
