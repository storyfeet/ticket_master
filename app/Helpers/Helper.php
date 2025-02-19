<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use App\Models\Role;

class Helper{

    public static function errResponse($status,$title,$message):Response{
        return response(
            [ 'errors'=>[$title => [$message] ] ],
            $status
        );
    }


    public static function isAdmin($user):bool{
        return Role::query()
            ->where('user','=',$user->id)
            ->where('role','=','admin')
            ->count() > 0;
    }

    public static function canEdit($user,$ticket):bool{
        if ($user->id == $ticket->user_id){
            return true;
        }
        if (isAdmin($user)){
            return true;
        }
        return false;
    }

}
