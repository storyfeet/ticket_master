<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    //
    public function open(){
        $tickets = Ticket::query()->where('status',false)->orderBy('id')->paginate(3);

        return $tickets;
    }

    public function closed(){
        $tickets = Ticket::query()->where('status',true)->orderBy('id')->paginate(3);

        return $tickets;
    }

    public function user($email){

        $tickets = DB::table('tickets')
            ->join('users','tickets.user','=','users.id')
            ->where('users.email',$email)
            ->paginate(3);
        return $tickets;
    }
}
