<?php

//namespace Tests\Feature;

it('stability', function ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with(["/","/loginhome"]);


it('Only users can see', function($url){
    $response = $this->get($url);

    $response->assertStatus(302);

})->with(["/ticketform","/logout"]);






