<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Ticket;

class AdminController extends Controller {

    public static function isAdmin($user):bool{
        return Role::query()
            ->where('user','=',$user->id)
            ->where('role','=','admin')
            ->count() > 0;
    }

    /**
    * Return the list of open tickets paginated
    */
    public function getOpen(){
        $tickets = Ticket::query()
            ->where('status',false)
            ->join('users','tickets.user','=','users.id')
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }

    /**
    * Return the list of closed tickets paginated
    */
    public function getClosed(){
        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('status',true)
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }
}


