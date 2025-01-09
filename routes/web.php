<?php

use Illuminate\Support\Facades\Route;

Route::get('/',
        'App\Http\Controllers\HomeController@home'
    );

Route::get('/loginhome',
        'App\Http\Controllers\HomeController@loginhome'
);

Route::post('/login',
        'App\Http\Controllers\HomeController@login'
);

Route::get('/logout',
        'App\Http\Controllers\HomeController@logout'
);

Route::get('/tickets/open',
        'App\Http\Controllers\TicketController@open'
    );

Route::get('/tickets/closed',
        'App\Http\Controllers\TicketController@closed'
    );
Route::get('/users/{email}/tickets',
           'App\Http\Controllers\TicketController@user'
    );

Route::get('/stats',
           'App\Http\Controllers\TicketController@stats'
    );



