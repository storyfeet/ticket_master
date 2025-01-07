<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

class ProcessTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    function processRandomTicket():void
    {
        Ticket::query()
            ->where('status',false)
            ->orderByRaw("RAND()")
            ->take(1)
            ->update([
                'status'=>true,
            ]);
    }

    /**
     * Execute the console command.
     */
    public function handle():void
    {

        DB::table('globalflags')->updateOrInsert([
            'id'=>"process_tickets_continue",
            'value'=>true,
        ]);

        while (true){
            $this->processRandomTicket();
            sleep(5*60);

            $stc = DB::table('globalflags')->where('id','process_tickets_continue')->get();
            if ($stc->count() ==0) return;
            if ($stc[0]->value == false ) return;
        }

    }
}
