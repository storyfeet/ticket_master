<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Events\TicketsUpdated;

/**
* Handles requesrs relating to creating and viewing tickets.
*/
class TicketController extends Controller
{
    // This selection list is the format for all three ticket collection requests.
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


    /**
    * Return a set of useful stats about the application
    */
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

    /**
     * Create a nicer view of the stats data
     */
    public function statsPage(){
        return view('StatsPage',$this->stats());
    }

    /**
    * Create a new ticket based on the post data recieved.
    * Associated with the new user
    */
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

        event(new TicketsUpdated());
        return redirect('/')->with([
            'message'=>"Ticket Created " . $subject,
        ]);
    }

    /**
    * Check the user has the authority to close a ticket, then close it.
    */
    public function closeTicket(){
        $user = Auth::user();
        $id = request()->post('ticket_id');
        if ($id == NULL ){
            return [
                "error"=>"No ticket_id provided",
                "request"=>request()->all(),
            ];
        }
        if( HomeController::isAdmin($user)){
            $update = Ticket::query()
                ->where('id','=',$id)
                ->update(['status'=>1]);
        }else{
            $update = Ticket::query()
                ->where('id','=',$id)
                ->where('user','=',$user->id)
                ->update(['status'=>1]);
        }

        event(new TicketsUpdated());

        return ["count"=>$update];


    }

}
