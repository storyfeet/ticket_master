<?php

use Database\Seeders\OrderStatusSeeder;
use Database\Seeders\TransactionStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database can be used',function(){
    $this->migrate();
    $this->seed();
    $response = $this->get("/tickets/open");
    $response->assertStatus(200);
});
