<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Validation\ValidationException;
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


//        $email = request()->get('email');
        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email','=',$email)
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);

        return $tickets;
    }

    public function getAdvancedTickets(){

        try {
            request()->validate([
                'per_page'=>['nullable','integer','min:1'],
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

        $perPage = 3;
        if (isset($req['per_page'])) {
            $perPage = (int) $req['per_page'];
        }
        return $query->select(TicketController::TICKETS_USER)
            ->paginate($perPage);


    }
}


