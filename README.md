Ticket Master
=============

This application has been created as a coding test for Secure Screening Services UK.

(For the Laravel readme see README_LARAVEL.md)

It's purpose is to show that I am confident using the Laravel framework to build applications.

It is a simple ticket store, and tickets are largely produced at random through the seeding mechanism.

Running the Application
-------------

The primary application is run through Sail to guard the Mysql Database.

* To bring in vendor components run ```composer install```

* Make sure docker is running ```(sudo) systemctl start docker``` ? (If on windows the process will be different

* Start the mysql server: ``` vendor/bin/sail up -d```

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






