<?php

//namespace Tests\Feature;

it('stability', function ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with(["/","/loginhome"]);







