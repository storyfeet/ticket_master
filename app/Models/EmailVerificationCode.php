<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    use HasFactory;


    static function createCode($userId,$ttl){
        $code = str()->random(32);
        EmailVerificationCode::factory()->create([
            'user_id'=>$userId,
            'code'=>$code,
            'ttl_minutes'=> $ttl,
        ]);
        return $code;
    }

    static function createUrlPath($user,$ttl):string{
        $code = self::createCode($user->id,$ttl);
        return "/verify/".$user->email."/".$code;
    }
}
