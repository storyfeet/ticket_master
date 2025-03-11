<?php

use App\Models\EmailVerificationCode;
use App\Models\User;

beforeEach(function () {
    $this->artisan('migrate --seed');
});

it("User can verify email",function(){
    $normal = User::getByName("normal");
    $this->assertNull($normal->email_verified_at);

    $url = EmailVerificationCode::createUrlPath($normal,60);

    $response = $this->get($url,[]);
    $response->assertOk();

    $n2 = User::getByName("normal");
    $this->assertNotNull($n2->email_verified_at);



});
