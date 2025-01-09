<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $TICKETS_USER = [
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
    //
    public function open(){
        $tickets = Ticket::query()
            ->where('status',false)
            ->join('users','tickets.user','=','users.id')
            ->orderByDesc('tickets.id')
            ->select($this->TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }

    public function closed(){
        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('status',true)
            ->orderByDesc('tickets.id')
            ->select($this->TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }

    public function user($email){

        $tickets = DB::table('tickets')
            ->join('users','tickets.user','=','users.id')
            ->where('users.email',$email)
            ->orderByDesc('tickets.updated_at')
            ->select($this->TICKETS_USER)
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

    public function newTicket() {
        $user = Auth::user();
        validator([
            'subject' => ['required'],
            'content' => ['required']
        ])->validate();

        $subject = request()->post('subject');
        Ticket::factory()->create([
            'user'=>$user->id,
            'subject'=>$subject,
            'content'=>request()->post('content'),
            'status'=>false,
        ]);

        return redirect('/')->with([
            'message'=>"Ticket Created " . $subject,
        ]);
    }

    public function closeTicket(){
        $user = Auth::user();
        $id = request()->post('ticket_id');
        if ($id == NULL ){
            return [
                "error"=>"No ticket_id provided",
                "request"=>request()->all(),
            ];
        }
        $update = Ticket::query()
            ->where('id','=',$id)
            ->where('user','=',$user->id)
            ->update(['status'=>1]);

        return ["count"=>$update];


    }

}
