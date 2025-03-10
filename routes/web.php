<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ThrottleMiddleware;
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
)->middleware(AdminMiddleware::class)
 ->middleware(ThrottleMiddleware::class);

// A paginated Json response showing all closed tickets
Route::get('/admin/get_closed',
    'App\Http\Controllers\AdminController@getClosed'
)->middleware(AdminMiddleware::class)
 ->middleware(ThrottleMiddleware::class);

Route::post('admin/create_user',
    'App\Http\Controllers\AdminController@createUser'
)->middleware(AdminMiddleware::class)
 ->middleware(ThrottleMiddleware::class);


// A paginated Json response showing all tickets associated with a user
Route::get('/admin/get_user_tickets/{email}',
   'App\Http\Controllers\AdminController@getUserTickets'
)->middleware(AdminMiddleware::class)
 ->middleware(ThrottleMiddleware::class);

Route::get('/admin/get_advanced_tickets',
    'App\Http\Controllers\AdminController@getAdvancedTickets'
)->middleware(AdminMiddleware::class)
 ->middleware(ThrottleMiddleware::class);

Route::get('/user/get_all',
            'App\Http\Controllers\UserController@getAll')
    ->middleware(ThrottleMiddleware::class);

Route::get('/user/get_open',
            'App\Http\Controllers\UserController@getOpen')
    ->middleware(ThrottleMiddleware::class);

Route::get('/user/get_closed',
            'App\Http\Controllers\UserController@getClosed')
    ->middleware(ThrottleMiddleware::class);

// Allows a logged in user to create a new ticket
Route::post('/user/new_ticket',
    'App\Http\Controllers\TicketController@newTicket'
)->middleware('auth');

// If the user has the authority, will close the requested ticket
Route::post('/user/close_ticket',
        'App\Http\Controllers\UserController@closeTicket'
)->middleware('auth');


Route::post('/user/new_ticket_message',
    'App\Http\Controllers\TicketController@newTicketMessage'
)->middleware('auth')
 ->middleware(ThrottleMiddleware::class);

Route::post('/user/get_ticket_messages',
    'App\Http\Controllers\TicketController@getTicketMessages'
)->middleware('auth');


Route::post('/user/request_verification_email',
    'App\Http\Controllers\UserController@requestVerificationEmail')
    ->middleware('auth');

Route::get('/verify/{email}/{code}',
'App\Http\Controllers\UserController@verifyEmail');


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

