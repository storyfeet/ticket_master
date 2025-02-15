<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;
// The home page of the webste.
// This acts as dashboard whether logged in or not.
// But if logged in, there are a few extra options available.
Route::get('/',
    'App\Http\Controllers\HomeController@home'
)->middleware(LanguageMiddleware::class);


// Logs a user into the system.
Route::post('/login',
    'App\Http\Controllers\HomeController@loginJSON'
);

// Logs any user out of the system.
Route::get('/logout',
   'App\Http\Controllers\HomeController@logout'
);




// A paginated Json response showing all open tickets
Route::get('/admin/get_open',
    'App\Http\Controllers\AdminController@getOpen'
);

// A paginated Json response showing all closed tickets
Route::get('/admin/get_closed',
    'App\Http\Controllers\AdminController@getClosed'
);

// Close the ticket from the admin perspective
Route::post('/admin/close_ticket',
    'App\Http\Controllers\AdminController@closeTicket'
)->middleware('auth');

// A paginated Json response showing all tickets associated with a user
Route::get('/admin/get_user_tickets/{email}',
   'App\Http\Controllers\AdminController@getUser'
);

Route::get('/user/get_all',
            'App\Http\Controllers\UserController@getAll');

// Allows a logged in user to create a new ticket
Route::post('/user/new_ticket',
    'App\Http\Controllers\UserController@newTicket'
)->middleware('auth');

// If the user has the authority, will close the requested ticket
Route::post('/user/close_ticket',
        'App\Http\Controllers\UserController@closeTicket'
)->middleware('auth');





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

