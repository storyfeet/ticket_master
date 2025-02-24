<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Helpers\Helper;

Broadcast::channel('my_tickets.{user_id}', function (User $user, $user_id) {
    //return false;
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('all_tickets',function(User $user){
   return  Helper::isAdmin($user);
});
