<?php

use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ticket;
use App\Models\User;
use App\Console\Commands\ProcessTickets;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->artisan( 'migrate --seed' );
});

test('database can be used',function(){
    $response = $this->get("/tickets/open");
    $response->assertStatus(200);
});

test('seeded database has members',function(){
    $qr = User::query()->select(['name','email'])->where('name','Test User')->get();
    $this->assertEquals($qr->count(),1);
    $this->assertEquals($qr[0]->email,'test@example.com');
});

test('tickets can update',function(){
    $tp = new ProcessTickets();
    for ($i = 0; $i < 9; $i +=1){
        $tp->processRandomTicket();
    }
    $count = Ticket::query()->where('status',true)->count() ;
    $this->assertEquals($count,9);
    $count = Ticket::query()->where('status',false)->count() ;
    $this->assertEquals($count,11);
});

it('database stability', function ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with(["/tickets/open","/tickets/closed","/users/test@example.com/tickets","/users/nobody@nothing.co.uk/tickets","/stats","/stats_page"]);

it('login works for real user', function() {
    $response = $this->post("/login",[
        "email"=>"normal@tickets.com",
        "password"=>"normalnormal"
    ]);
    $response->assertStatus(302);
    $response->assertHeader('Location',"http://localhost");
});

it('Login doesn\'t work for non user', function() {
    $response = $this->post("/login",[
        "email"=>"bad@bad.bad",
        "password"=>"bad bad bad"
    ]);
    $response->assertStatus(302);
    //TODO work out why back(), doesn't return to loginhome (when it does in browser)
});


it('logged in user can see more stuff' , function($url){
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get($url);
    $response->assertOk();
})->with(["/","/ticketform"]);


it('admin can see even more', function(){
    $users = User::query()->where('name','=','admin')->get();
    $this->assertEquals($users->count(),1);

    $response = $this->actingAs($users[0])->get("/");
    $response->assertOk();
});


it ('admin can close tickets', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this->followingRedirects()->actingAs($user)->post("/tickets/new",[
        'subject'=>"user_ticket_test",
        'content'=>"there is content on the user_ticket_test",
    ]);
    $response->assertStatus(200);

    $ticket = Ticket::query()->where('subject','=',"user_ticket_test")->get()[0];

    $admin = User::query()->where('name','=','admin')->get()[0];

    $adminResponse = $this->actingAs($admin)
        ->post("/tickets/close_ticket",[
            'ticket_id'=>$ticket->id,
        ]);

    $ticket = Ticket::query()->where('subject','=',"user_ticket_test")->get()[0];
    $this->assertEquals($ticket->status,true);

    // In this case there should be a processed ticket, so covers another angle
    $statsResponse = $this->get("/stats");
    $statsResponse->assertOk();


});

it ('user can close ticket', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this->followingRedirects()
        ->actingAs($user)
        ->post("/tickets/new",[
            'subject'=>"user_close_test",
            'content'=>"there is content on the user_closing_ticket_test",
        ]);

    $response->assertStatus(200);

    $ticket = Ticket::query()->where('subject','=',"user_close_test")->get()[0];


    $adminResponse = $this->actingAs($user)
        ->post("/tickets/close_ticket",[
            'ticket_id'=>$ticket->id,
        ]);

    $ticket = Ticket::query()->where('subject','=',"user_close_test")->get()[0];
    $this->assertEquals($ticket->status,true);



});


it ('Null ticket can\'t be closed', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this->actingAs($user)
        ->post("/tickets/close_ticket",[ ]);
    $response->assertOk();

});
