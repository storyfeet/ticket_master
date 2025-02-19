<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Helpers\Helper;


class UserController extends Controller{

    public static function userInfo(){
        $user = Auth::user();
        if ($user === null) return null;
        $isAdmin = Helper::isAdmin($user);
        return [
            'id'=>$user->id,
            'name'=> $user->name ,
            'email'=> $user->email,
            'isAdmin' => $isAdmin,
        ];
    }

    /**
    * return the list of tickets associated with a single user,
    */
    public function getAll(){
        $email = Auth::user()->email;

        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email',$email)
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);
        return $tickets;
    }
    public function getOpen(){
        $email = Auth::user()->email;

        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email',$email)
            ->where('tickets.status','=','0')
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);
        return $tickets;
    }

    public function getClosed(){
        $email = Auth::user()->email;

        $tickets = Ticket::query()
            ->join('users','tickets.user','=','users.id')
            ->where('users.email',$email)
            ->where('tickets.status','=','1')
            ->orderByDesc('tickets.updated_at')
            ->select(TicketController::TICKETS_USER)
            ->paginate(3);
        return $tickets;
    }
}
