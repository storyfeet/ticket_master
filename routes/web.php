<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;
// The home page of the webste.
// This acts as dashboard whether logged in or not.
// But if logged in, there are a few extra options available.
Route::get('/',
        'App\Http\Controllers\HomeController@home'
    )->middleware(LanguageMiddleware::class);

//Returns the login form so users can log in
Route::get('/loginhome',
        'App\Http\Controllers\HomeController@loginhome'
)->name('login')->middleware(LanguageMiddleware::class);

// Logs a user into the system.
Route::post('/login',
        'App\Http\Controllers\HomeController@login'
);

// Logs any user out of the system.
Route::get('/logout',
        'App\Http\Controllers\HomeController@logout'
);

// A webpage that allows users to create a new ticket.
// Auth is managed inside the controller as user name is needed.
Route::get('/ticketform',
        'App\Http\Controllers\HomeController@ticketform'
)->middleware(LanguageMiddleware::class);

// Allows a logged in user to create a new ticket
Route::post('/tickets/new',
        'App\Http\Controllers\TicketController@newTicket'
)->middleware('auth');

// If the user has the authority, will close the requested ticket
Route::post('/tickets/close_ticket',
        'App\Http\Controllers\TicketController@closeTicket'
)->middleware('auth');


// A paginated Json response showing all open tickets
Route::get('/tickets/open',
        'App\Http\Controllers\TicketController@open'
    );

// A paginated Json response showing all closed tickets
Route::get('/tickets/closed',
        'App\Http\Controllers\TicketController@closed'
    );

// A paginated Json response showing all tickets associated with a user
Route::get('/users/{email}/tickets',
           'App\Http\Controllers\TicketController@user'
    );

// A Json page showing site stats
Route::get('/stats',
           'App\Http\Controllers\TicketController@stats'
    );

// An html page with the site stats injected.
Route::get('/stats_page',
           'App\Http\Controllers\TicketController@statsPage'
    );

Route::post('/language/switch',
        'App\Http\Controllers\LanguageController@switchLanguage'
);

