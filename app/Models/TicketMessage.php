<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class TicketMessage extends Model
{
    use HasFactory;

    /*
     * Get the last user who wrote a message for each ticket
     */
    public static function lastUserForMessages(): Builder {
        $lastMessages = self::query()
            ->selectRaw("ticket_id,MAX(created_at) as last_at")
            ->groupBy('ticket_id');
        return self::query()
            ->select(["ticket_messages.ticket_id", "ticket_messages.user_id"])
            ->joinSub($lastMessages, "last_messages", function ($join) {
                $join->on("ticket_messages.ticket_id", "=", "last_messages.ticket_id")
                    ->on("ticket_messages.created_at", "=", "last_messages.last_at");
            });
    }

}
