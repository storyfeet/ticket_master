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

    public function stats(){
        $numTickets = Ticket::query()->count();
        $numUnprocessed = Ticket::query()->where("status",false)->count();

        $ticketChamps = DB::table('tickets')
            ->join('users','tickets.user','=','users.id')
            ->select('users.name','users.email',DB::raw('COUNT(*) as total'))
            ->groupBy('users.id')
            ->orderByDesc('total')
            ->take(1)
            ->get();

        $ticketChamp = NULL;
        if ($ticketChamps->count() > 0){
            $ticketChamp = $ticketChamps[0];
        }

        $lastProcesseds = Ticket::query()
            ->where('status',true)
            ->orderByDesc('updated_at')
            ->take(1)
            ->get();

        $lastProcessed = NULL;
        if ($lastProcesseds->count() > 0){
            $lastProcessed = $lastProcesseds[0];
        }

        return [
            'total_tickets' => $numTickets,
            'total_unprocessed_tickets' => $numUnprocessed,
            'most_tickets' => $ticketChamp,
            'last_processed' => $lastProcessed,
        ];


    }

}
