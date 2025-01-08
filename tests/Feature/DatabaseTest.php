<?php

use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ticket;
use App\Models\User;

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

