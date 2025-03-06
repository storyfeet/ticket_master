<?php
namespace App\Http\Controllers;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Ticket;
use App\Models\EmailVerificationCode;
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
            'verified' => $user->email_verified_at,
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

    public function requestVerificationEmail(){
        $user = Auth::user();
        if ($user->email_verified_at != null) {
            return Helper::errResponse(422,"err-verify","err-already-verified");
        }
        $code = str()->random(32);
        EmailVerificationCode::factory()->create([
            'user_id'=>$user->id,
            'code'=>$code,
            'ttl_minutes'=> 15,
        ]);
        Mail::mailer("resend")
            ->to($user)
            ->send(new VerifyEmail($user,$code));
        //TODO send email

        return response(['status'=>'msg-check_email_for_code'],200);
    }

    public function verifyEmail($email,$code){
        $dbCode = EmailVerificationCode::query()
            ->join('users','email_verification_codes.user_id','=','users.id')
            ->where('email_verification_codes.code','=',$code)
            ->where('users.email','=',$email)
            ->select([
                'users.id as id',
                'email_verification_codes.created_at as created_at',
                'email_verification_codes.ttl_minutes as ttl'])
            ->first();
        if ($dbCode === null) {
            return view("could_not_verify",["error"=>"No Record"]);
        }

        $expires =$dbCode['created_at']->addMinutes( $dbCode['ttl']);
        if ($expires->lt(now()) ) {
            return view("could_not_verify",["error"=>"Expired"]);
        }

        User::where("id","=",$dbCode['id'])
            ->update(["email_verified_at"=>now()]);

        return view("email_verified");

    }
}
