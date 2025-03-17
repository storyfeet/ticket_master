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
    public const TICKETS_USER = [
        'tickets.id as ticket_id',
        'tickets.user as user_id',
        'users.name',
        'users.email',
        'tickets.subject',
        'tickets.content',
        'tickets.status',
        'tickets.created_at',
        'tickets.updated_at',
    ];
    static function getIdBySubject($subject){
        return Ticket::query()->where("subject",'=',$subject)->first()->id;
    }
}
