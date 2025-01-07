<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StopTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stop-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop Tickets Being auto-generated';

    /**
     * Execute the command to stop tickets being created;
     */
    public function handle():Void
    {
        $GLOBALS["slow_tickets_continue"] = false;
    }
}
