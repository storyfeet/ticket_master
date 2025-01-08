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

