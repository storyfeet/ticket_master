<?php

use App\Models\User;

beforeEach(function() {
    $this->artisan( 'migrate --seed' );
});

function getAdmin(){
    return User::query()->where('name','=','admin')->first();
}
function getNormal(){
    return User::query()->where('name','=','normal')->first();

}

it("Admin can create User",function() {
    $admin = getAdmin();
    $response = $this->actingAs($admin)
        ->post("/admin/create_user",[
            "name" => "alex",
            "email" => "alex@gmail.com",
            "password" => "alex_alex_alex",
        ]);
    $response->assertOk();
    $newUser = User::query()->where('name','=','alex')->first();
    $this->assertNotNull($newUser);
    $this->assertEquals("alex@gmail.com",$newUser->email);
});


it("Normal cannot create User",function() {
    $normal = getNormal();
    $response = $this->actingAs($normal)
        ->post("/admin/create_user",[
            "name" => "alex",
            "email" => "alex@gmail.com",
            "password" => "alex_alex_alex",
        ]);
    $response->assertStatus(403);
    $newUser = User::query()->where('name','=','alex')->first();
    $this->assertNull($newUser);
});

it("Cannot create same user twice",function() {
    $admin = getAdmin();
    $response_1 = $this->actingAs($admin)
        ->post("/admin/create_user",[
            "name" => "alex",
            "email" => "alex@gmail.com",
            "password" => "alex_alex_alex",
        ]);
    $response_1->assertOk();

    $response_2 = $this->actingAs($admin)
        ->post("/admin/create_user",[
            "name" => "alex",
            "email" => "alex@gmail.com",
            "password" => "alex_alex_alex",
        ]);
    $response_2->assertStatus(400);

});
