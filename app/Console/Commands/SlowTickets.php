<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * This command produces a new ticket using ipsum lorem every munute.
 * Each minute it checks the database for the flag "slow_tickets_continue"
 * If the flag is gone, the process will end.
 */
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

        DB::table('globalflags')->updateOrInsert([
            'id'=>"slow_tickets_continue",
            'value'=>true,
        ]);

        while (true){
            Log::debug("Slow tickets loop");
            Ticket::factory()
                ->count(1)
                ->create();
            sleep(60);

            $stc = DB::table('globalflags')->where('id','slow_tickets_continue')->get();
            Log::debug("stc : " . $stc->count());
            if ($stc->count() ==0) return;
            if ($stc[0]->value == false ) return;
        }

        Log::debug("Slow tickets reach end");
    }
}
