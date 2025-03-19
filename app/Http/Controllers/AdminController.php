<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller {

    const ORDER_BY = [
        'user' => 'users.name',
        'email' =>'users.email',
        'subject' => 'tickets.subject',
        'content' => 'tickets.content',
        'created' => 'tickets.created_at',
        'updated' => 'tickets.updated_at',
        ];
    const CAN_ORDER_BY = [
        'user','email','subject','content','created','updated',
    ];
    const CAN_STATUS = ["open","closed","any"];

    /**
    * Return the list of open tickets paginated
    */
    public function getOpen(){
        $tickets = Ticket::query()
            ->where('status',false)
            ->join('users','tickets.user','=','users.id')
            ->orderByDesc('tickets.updated_at')
            ->select(Ticket::TICKETS_USER)
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
            ->select(Ticket::TICKETS_USER)
            ->paginate(3);
        return $tickets;
    }

    public function createUser()
    {
        try {
            request()->validate([
                    'name' => ['required','string','max:50','unique:App\Models\User'],
                    'email'=>['required','email','unique:App\Models\User'],
                    'password' => ['required','min:10'],
                ]);
        }catch (ValidationException $e){
            return response(['errors'=>$e->errors()],400);
        }
        $created = User::factory()->create(
            [
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
            ]
        );
        $result = ['created'=>$created];
        //TODO consider allowing roles tobe added as needed,
        // maybe ',' split string
        return response($result,200);

    }
    public function getUserTickets($email){
        $vd = Validator::make(['email'=>$email],
            ['email'=>["email"]]
        );
        if ($vd->fails()){
            return response(['errors'=>$vd->errors()],400);
        }

//        $email = request()->get('email');
        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email','=',$email)
            ->orderByDesc('tickets.updated_at')
            ->select(Ticket::TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }

    public function getAdvancedTickets(){

        try {
            request()->validate([
                'status' => ['nullable',Rule::in(self::CAN_STATUS)],
                'per_page'=>['nullable','integer','min:1'],
                'order_by'=>['nullable',Rule::in(self::CAN_ORDER_BY)],
            ]);
        }catch (ValidationException $e){
            return response(['errors'=>$e->errors()],400);
        }
        $query = Ticket::query()
            ->join('users','tickets.user','=','users.id');

        $req = request()->all();

        if ( isset($req['user_like']) ){
            $query = $query->where(function($query) use($req){
                return $query->where('users.email','like','%'.$req['user_like'].'%')
                    ->orWhere('users.name','like','%'.$req['user_like'].'%');
            });
        }
        if ( isset($req['content_like']) ){
            $query = $query->where(function($query) use($req){
                return $query->where('tickets.subject','like','%'.$req['content_like'].'%')
                    ->orWhere('tickets.content','like','%'.$req['content_like'].'%');
            });
        }

        if (strcmp($req['status']?? "" , "open") == 0 ){
            $query = $query->where('tickets.status','=',false);
        }
        if (strcmp($req['status']?? "" , "closed") == 0 ){
            $query = $query->where('tickets.status','=',true);
        }

        $orderBy = "tickets.updated_at";
        if (isset($req['order_by'])){
            $orderBy = self::ORDER_BY[$req['order_by']] ?? $orderBy;
        }



        if (request()->boolean('ascending')){
            $query = $query->orderBy($orderBy);
        }else {
            $query = $query->orderByDesc($orderBy);
        }

        $perPage = 3;
        if (isset($req['per_page'])) {
            $perPage = (int) $req['per_page'];
        }
        return $query->select(Ticket::TICKETS_USER)
            ->paginate($perPage);

    }


    /** A duty ticket is a ticket that the admin has
     * responded to, but is still open, and whose most
     * recent message is from the user.
     */
    public function getDutyTickets()
    {
        $admin = Auth::user();
        $sql = "";
        try {
            $myMessages = TicketMessage::query()
                ->select("ticket_id")
                ->where("user_id","=",$admin->id)
                ->groupBy("ticket_id");

            $lastUser = TicketMessage::lastUserForMessages();
            $query = Ticket::query()
                ->where("status", "=", false)
                ->joinSub($myMessages,'my_messages', function($join){
                    $join->on("my_messages.ticket_id","=","tickets.id");
                })
                ->joinSub($lastUser,'last_messages', function($join){
                    $join->on("last_messages.ticket_id","=","tickets.id")
                        ->on("last_messages.user_id","=","tickets.user");
                })
                ->join("users","users.id","=","tickets.user")
                ->select(Ticket::TICKETS_USER + ["last_messages.user_id"]);

            $sql = $query->toSql();
            return $query->paginate(3);

        } catch (Exception $exception){
            return response(['errors'=>[
                "message"=>[$exception->getMessage()],
                "trace"=>$exception->getTrace(),
                "query" => [$sql],
                ]
            ],400);
        }
    }
}


