<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;


uses(RefreshDatabase::class);

beforeEach(function() {
    $this->artisan( 'migrate' );
});

test("Main open paths return success",function ($url){
    $response = $this->get($url);
    $response->assertStatus(200);
})->with(["/"]);

test('user post routes fail with no user',function($url){
    $response = $this->post($url);
    $response->assertStatus(403);
})->with([
    "/user/new_ticket_message",
    "/user/request_verification_email",
    "/user/new_ticket",
    "/user/close_ticket",
]);

test('user get routes fail with no user',function($url){
    $response = $this->get($url);
    $response->assertStatus(403);
})->with([
    "/user/get_all",
    "/user/get_open",
    "/user/get_closed",
    ]);

test('empty database has no users',function(){
    $qr = User::query()->select(['name','email'])->where('name','Test User')->get();
    $this->assertEquals($qr->count(),0);
});


it('empty database refuses non-admin post',function($url){
    $response = $this->post($url);
    $response->assertStatus(403);
})->with(["/admin/create_user"]);

it('empty database refuses non-admin get', function ($url) {
    $response = $this->get($url);
    $response->assertStatus(403);
})->with([
    "/admin/get_open",
    "/admin/get_closed",
    "/admin/get_user_tickets/dave@example.com",
    "/admin/get_advanced_tickets",
    ]);


