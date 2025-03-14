<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use App\Models\Role;

class Helper{

    public static function errResponse($status,$title,$message):Response{
        return response(
            [ 'errors'=>[$title => [$message] ] ],
            $status,
        );
    }



    public static function canEdit($user,$ticket):bool{
        if ($user->id == $ticket->user){
            return true;
        }
        if ($user->isAdmin()){
            return true;
        }
        return false;
    }

}
