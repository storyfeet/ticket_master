<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Hash;

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

    public function createUser()
    {
        try {
            request()->validate([
                    'name' => ['required','string','max:50','unique:users.name'],
                    'email'=>['required','email','unique:users.email'],
                    'password' => ['required'],
                ]);
        }catch (ValidationException $e){
            return response(['errors'=>$e->errors()],400);
        }
        $created = User::factory()->unverified()->create(
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


