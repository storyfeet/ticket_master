<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Simple mark a flag in the database to stop processing tickets
 */
class StopProcessTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stop-process-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('globalflags')->where('id','process_tickets_continue')->delete();
        //
    }
}
