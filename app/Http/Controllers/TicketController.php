<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\TicketsUpdated;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\Helper;

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

    public const MESSAGE_USER = [
        'ticket_messages.id as message_id',
        'ticket_messages.ticket_id as ticket_id',
        'ticket_messages.user_id as author_id',
        'ticket_messages.message',
        'ticket_messages.created_at',
        'ticket_messages.updated_at',
        'users.name as author_name',

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
    public function statsPage():Response{
        return view('StatsPage',$this->stats());
    }

    /**
    * Create a new ticket based on the post data recieved.
    * Associated with the new user
    */
    public function newTicket() :Response{
        $user = Auth::user();
        try {

            request()->validate([
                'subject' => ['required'],
                'content' => ['required']
            ]);

        }catch (ValidationException $e){
            return response(["errors"=>$e->errors()],400);
        }

        $subject = request()->post('subject');
        Ticket::factory()->create([
            'user'=>$user->id,
            'subject'=>$subject,
            'content'=>request()->post('content'),
            'status'=>false,
        ]);

        event(new TicketsUpdated());

        return response(["Ticket Created"],200);
    }

    /**
    * Check the user has the authority to close a ticket, then close it.
    */
    public function closeTicket():Response{
        $user = Auth::user();
        $id = request()->post('ticket_id');
        if ($id == NULL ){
            return [
                "error"=>"No ticket_id provided",
                "request"=>request()->all(),
            ];
        }
        if( Helper::isAdmin($user)){
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

    function newTicketMessage():Response{
        try {
            request()->validate([
                'message'=>['required'],
                'ticket_id'=>['required'],
            ]);
        }catch (ValidationException $e){
            return response(400,['errors'=>$e->errors()]);
        }
        $ticket = Ticket::query()
            ->where('id','=',request()->get('ticket_id'))
            ->first();
        if ($ticket == null) {
            return Helper::errResponse(400,'ticket',"Ticket Doesn't exist");
        }
        if ($ticket->status == 1) {
            return Helper::errResponse(403,'ticket',"Ticket Already Closed");
        }

        $user = Auth::user();
        if (! Helper::canEdit($user,$ticket)){
            return Helper::errResponse(403,'auth','Only ticket creator or admin may add message');
        }

        TicketMessage::factory()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => request()->get('message'),
        ]);
        return response(['status'=>'Message Created'],200);

    }

    function getTicketMessages():Response{
        try {
            request()->validate([
                'ticket_id'=>['required'],
            ]);
        }catch (ValidationException $e){
            return response(['errors'=>$e->errors()],400);
        }

        $ticketId = request()->get('ticket_id');
        $ticket = Ticket::query()
            ->where('id','=',$ticketId)
            ->first();
        if ($ticket == null) {
            return Helper::errResponse(400,'ticket',"Ticket Doesn't exist");
        }
        $user = Auth::user();
        if (! Helper::canEdit($user,$ticket)){
            return Helper::errResponse(403,'auth','Only ticket creator or admin may view ticket messages');
        }

        return response(TicketMessage::query()
            ->join('users','ticket_messages.user_id','=','users.id')
            ->select(TicketController::MESSAGE_USER)
            ->where('ticket_id','=',$ticketId)
            ->orderBy('created_at')
            ->get(),200);
    }



}
