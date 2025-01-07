<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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


