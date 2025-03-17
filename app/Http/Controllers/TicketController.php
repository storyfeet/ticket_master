<?php

namespace App\Http\Controllers;


use App\Events\TicketsUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\MyTicketUpdated;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Helpers\Helper;

/**
* Handles requesrs relating to creating and viewing tickets.
*/
class TicketController extends Controller
{
    // This selection list is the format for all three ticket collection requests.


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

        return response(["event-ticket_created"],200);
    }

    /**
    * close a ticket.  Make sure authorisation is checked before calling this
    */
    public function closeTicket($ticket_id){
        $update = Ticket::query()
            ->where('id','=',$ticket_id)
            ->update(['status'=>1]);


        return ["count"=>$update];
    }

    public function getWholeTicket($ticket_id):Ticket{
        return Ticket::query()
            ->select(Ticket::TICKETS_USER)
            ->where('tickets.id','=',$ticket_id)
            ->join('users','users.id','=','tickets.user')
            ->first();

    }

    function newTicketMessage():Response{
        try {
            request()->validate([
                'message'=>['required'],
                'ticket_id'=>['required'],
            ]);
        }catch (ValidationException $e){
            return response(['errors'=>$e->errors()],400);
        }
        $ticket = Ticket::query()
            ->where('id','=',request()->get('ticket_id'))
            ->first();
        if ($ticket == null) {
            return Helper::errResponse(400,'ticket',"err-ticket_does_not_exist");
        }
        if ($ticket->status == 1) {
            return Helper::errResponse(403,'ticket',"err-ticket_already_closed");
        }

        $user = Auth::user();
        if (! Helper::canEdit($user,$ticket)){
            return Helper::errResponse(403,'auth','err-not_authorised_to_access_ticket');
        }

        TicketMessage::factory()->create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => request()->get('message'),
        ]);

        Log::debug("Updated ticket : ".$ticket->id);
        event(new TicketsUpdated());
        event(new MyTicketUpdated($ticket->user,$ticket));

        if (request()->boolean('close')){
            $this->closeTicket($ticket->id);
            $tk = $this->getWholeTicket($ticket->id);
            return response([
                'status'=>'event-ticket_closed_with_message',
                'ticket'=>$tk,
            ],200);
        }


        return response(['status'=>'event-message_created'],200);
    }

    function getTicketMessages($ticketId):Response{

        $ticket = Ticket::query()
            ->where('id','=',$ticketId)
            ->first();
        if ($ticket == null) {
            return Helper::errResponse(400,'ticket',"err-ticket_does_not_exist");
        }
        $user = Auth::user();
        if (! Helper::canEdit($user,$ticket)){
            return Helper::errResponse(403,'auth','err-not_authorised_to_access_ticket');
        }

        return response(TicketMessage::query()
            ->join('users','ticket_messages.user_id','=','users.id')
            ->select(self::MESSAGE_USER)
            ->where('ticket_id','=',$ticketId)
            ->orderBy('ticket_messages.created_at')
            ->get(),200);
    }

}
