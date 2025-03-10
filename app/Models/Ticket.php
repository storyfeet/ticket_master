<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
* Ticket represents a request for help in some form,
* Users may create them, and once dealt with administraters may close them
*/
class Ticket extends Model
{
    use HasFactory;
    //

    static function getIdBySubject($subject){
        return Ticket::query()->where("subject",'=',$subject)->first()->id;
    }
}
