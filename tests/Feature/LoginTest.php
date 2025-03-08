<?php

beforeEach(function() {
    $this->artisan( 'migrate --seed' );
});

it('login works for real user', function() {
    $response = $this->post("/login",[
        "email"=>"normal@tickets.com",
        "password"=>"normalnormal"
    ]);
    $response->assertStatus(200);
    //$response->assertHeader('Location',"http://localhost");
});

it('Login fails for non user', function() {
    $response = $this->post("/login",[
        "email"=>"bad@bad.bad",
        "password"=>"bad bad bad"
    ]);
    $response->assertStatus(403);
    //TODO work out why back(), doesn't return to loginhome (when it does in browser)
});

it("Login fails with bad data", function($payload) {
    $response = $this->post("/login",$payload);
    $response->assertStatus(400);
    //TODO work out why back(), doesn't return to loginhome (when it does in browser)
})->with([
    [["email"=>"san","password"=>"bad bad bad"]],
    [["email"=>"bad@gmail.gom","password"=>""]],
    [["email"=>"","password"=>"sandwiches"]],
    [[]]
]);
