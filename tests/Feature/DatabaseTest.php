<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ticket;
use App\Models\User;
use App\Console\Commands\ProcessTickets;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->artisan( 'migrate --seed' );
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


it('admin can see even more', function(){
    $users = User::query()->where('name','=','admin')->get();
    $this->assertEquals($users->count(),1);

    $response = $this->actingAs($users[0])->get("/");
    $response->assertOk();
});


it ('admin can close tickets', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this->actingAs($user)->post("/user/new_ticket",[
        'subject'=>"user_ticket_test",
        'content'=>"there is content on the user_ticket_test",
    ]);
    $response->assertOk();

    $ticket = Ticket::query()->where('subject','=',"user_ticket_test")->first();

    $admin = User::query()->where('name','=','admin')->first();

    $adminResponse = $this->actingAs($admin)
        ->post("/user/new_ticket_message",[
            'ticket_id'=>$ticket->id,
            'message'=>"I have solved this ticket",
            'close'=>true,
        ]);

    $ticket = Ticket::query()
        ->where('subject','=',"user_ticket_test")
        ->first();
    $this->assertEquals($ticket->status,true);

});

it ('user can close ticket', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this
        ->actingAs($user)
        ->post("/user/new_ticket",[
            'subject'=>"user_close_test",
            'content'=>"there is content on the user_closing_ticket_test",
        ]);

    $response->assertOk();

    $ticket = Ticket::query()->where('subject','=',"user_close_test")->get()[0];


    $closeResponse = $this->actingAs($user)
        ->post("/user/new_ticket_message",[
            'ticket_id'=>$ticket->id,
            'message'=>"ticket_complete",
            'close'=>true,
        ]);
    $closeResponse->assertOk();

    $ticket = Ticket::query()->where('subject','=',"user_close_test")->first();
    $this->assertEquals($ticket->status,true);



});


it ('Null ticket can\'t be closed', function(){

    $user = User::query()->where('name','=','normal')->get()[0];

    $response = $this->actingAs($user)
        ->post("/user/new_ticket_message",[
                "ticket_id"=>"500",
                "message"=>"this ticket does not exist",
                "close"=>"true",
            ]);
    $response->assertStatus(400);

});
