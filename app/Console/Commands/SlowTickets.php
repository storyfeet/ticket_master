<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class SlowTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:slow-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new tickets once per minute until stop-tickets is called ';

    /**
     * Execute the console command.
     */
    public function handle():void
    {
        $GLOBALS["slow_tickets_continue"] = true;

        Ticket::factory()
            ->count(1)
            ->create();

        while ($GLOBALS["slow_tickets_continue"]){
            Log::debug("Slow tickets loop");
            Ticket::factory()
                ->count(1)
                ->create();
            sleep(60);
        }

        Log::debug("Slow tickets reach end");
    }
}
