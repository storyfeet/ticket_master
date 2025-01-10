<?php

use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ticket;
use App\Models\User;
use App\Console\Commands\ProcessTickets;

uses(RefreshDatabase::class);

beforeEach(function() {
    $this->artisan( 'migrate' );
});

test('empty database can be used',function(){
    $response = $this->get("/tickets/open");
    $response->assertStatus(200);
});

test('empty database has no',function(){
    $qr = User::query()->select(['name','email'])->where('name','Test User')->get();
    $this->assertEquals($qr->count(),0);
});


it('empty database stability', function ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with(["/tickets/open","/tickets/closed","/users/test@example.com/tickets","/users/nobody@nothing.co.uk/tickets","/stats","/stats_page"]);


