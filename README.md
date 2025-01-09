Ticket Master
=============

This application has been created as a coding test for Secure Screening Services UK.

(For the Laravel readme see README_LARAVEL.md)

It's purpose is to show that I am confident using the Laravel framework to build applications.

It is a simple ticket store, and tickets can be produced at random through the seeding mechanism. But users may also create and close tickets.  Admin users can close other people's tickets.


Running the Application
-------------

The primary application is run through Sail, which guards the Mysql Database.

To get everything running follow the steps below:

* To bring in vendor components run ```composer install```

* Make sure docker is running ```(sudo) systemctl start docker``` ? (If on windows the process will be different

* Start the mysql server: ``` vendor/bin/sail up -d```

* Seed the database: ```vendor/bin/sail artisan migrate:fresh --seed```

Using Console Commands
--------------

To run the background tasks to create and resolve tickets use the following commands.

### To Create Tickets

```vender/bin/sail artisan app:slow-tickets```

If you disown the thread (with &) you can stop the process with:

```vendor/bin/sail artisan app:stop-tickets```

### To Resolve Tickets

```vender/bin/sail artisan app:process-tickets```

If you disown the thread (with &) you can stop the process with:

```vendor/bin/sail artisan app:stop-process-tickets```

Accessing the public Endpoints
--------------

If sail is up and running, you should be able to access the following API Endpoints:

```
localhost/tickets/open
localhost/tickets/closed
localhost/users/{email}/tickets
localhost/stats
```

The routes: ```tickets/open```,```tickets/closed```,and ```users/{email}/tickets```: Will return a paginated JSON page in the same format.  The ```data``` element, will contain a list of up the three elements. The ```next_page_url```, how to get the following page.

The route: ```stats``` will list useful details about how the server is being used. This is not paginated

Using the Web Front End
------------

From the browser : ```localhost``` will return a homepage, where you can select to view "Open" or "Closed" tickets, these will be displayed beneath those buttons. The view next, and view previous buttons will indicate if there are more pages to see.

You will also see a text input and a "Tickets by Email" Button. If you input a user email, and select this, it will bring up the list of that users tickets, paginated as before.

The app will show tickets to all users, but will only allow the appropriate people to make/close tickets.

Normal Users can Create and Close Tickets their own tickets.
Admin Users can also close other people's tickets.

After the database is seeded, there will be 2 users available:

Name   | Email              | password
-----------------------------------
normal | normal@tickets.com | normalnormal
admin  | admin@tickets.com  | adminadmin

Follow the Login Link, and enter the appropriate details at the login page.

A logged in User gains can not click on "Raise a Ticket", and provide a subject and content to that ticket.

If they click on "my tickets" They will see a list of their own tickets.

If the ticket is open, there will be a button to close, it.

Admin Users may close any open ticket, the page should refresh automatically.




