<?php

namespace App\Helpers;

use Illuminate\Http\Response;

function err_response($status,$title,$message):Response{
    return response(
        [ 'errors'=>[$title => [$message] ] ],
        $status
    );
}


