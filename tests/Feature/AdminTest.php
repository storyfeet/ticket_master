<?php

use App\Models\Ticket;
use App\Models\User;
use function Pest\Laravel\artisan;

beforeEach(function(){
    artisan("migrate --seed");
});

it("Admin can access all tickets",function($path){
    $admin = User::getByName("admin");
    $response = $this->actingAs($admin)
        ->get($path);
    $response->assertOk();
})->with([
    "/admin/get_open",
    "/admin/get_closed",
    "/admin/get_user_tickets/normal@tickets.com",
]);
it("Non Admin cannot access other's tickets",function($path){
    $normal = User::getByName("normal");
    $response = $this->actingAs($normal)
        ->get($path);
    $response->assertStatus(403);
})->with([
    "/admin/get_open",
    "/admin/get_closed",
    "/admin/get_user_tickets/normal@tickets.com",
]);

it("get_user_tickets fails with bad email",function(){
    $admin = User::getByName("admin");
    $response = $this->actingAs($admin)
        ->get("/admin/get_user_tickets/cat");
    $response->assertStatus(400);

});

it("advanced ticket filter fails on bad data",function($data){
    $admin = User::getByName("admin");
    $qData = http_build_query($data);
    $response = $this->actingAs($admin)
        ->get("/admin/get_advanced_tickets?".$qData);
    $response->assertStatus(400);
})->with([
    [["status"=>"other"]],
    [["order_by"=>"num_cats"]],
    [["per_page"=>0]],
    [["per_page"=>3.5]],
    [["per_page"=>"hello"]],
]);

it("advanced_filter can find tickets",function($data){
    $normal = User::getByName("normal");
    $response = $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"spanish",
            "content"=>"How to say hello",
        ]);
    $response->assertOk();
    $qData = http_build_query($data);

    $admin = User::getByName("admin");
    $response = $this->actingAs($admin)
        ->get("/admin/get_advanced_tickets?".$qData);
    $response->assertOk();
    $response->assertJsonFragment(["subject"=>"spanish"]);

})->with([
    [["user_like"=>"norm","order_by"=>"created","ascending"=>true]],
    [["content_like"=>"span","status"=>"open","order_by"=>"user","per_page"=>5]],
]);

it("advanced_filter can find closed tickets",function($data){
    $normal = User::getByName("normal");
    $response = $this->actingAs($normal)
        ->post("/user/new_ticket",[
            "subject"=>"spanish",
            "content"=>"How to say hello",
        ]);
    $response->assertOk();
    $tid = Ticket::getIdBySubject("spanish");
    $closeResponse = $this->actingAs($normal)
        ->post("/user/new_ticket_message",[
            "ticket_id"=>$tid,
            "message"=>"closing",
            "close"=>true,
        ]);
    $closeResponse->assertOk();
    $qData = http_build_query($data);

    $admin = User::getByName("admin");
    $response = $this->actingAs($admin)
        ->get("/admin/get_advanced_tickets?".$qData);
    $response->assertOk();
    $response->assertJsonFragment(["subject"=>"spanish"]);

})->with([
    [["user_like"=>"norm","status"=>"closed","order_by"=>"created","ascending"=>true]],
    [["content_like"=>"span","status"=>"closed","order_by"=>"user","per_page"=>5]],
]);

