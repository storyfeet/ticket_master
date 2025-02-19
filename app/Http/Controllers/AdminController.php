<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Ticket;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller {


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

    public function getUserTickets($email){


//        $email = request()->get('email');
        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email','=',$email)
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }
}


