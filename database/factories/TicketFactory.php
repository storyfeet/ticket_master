<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uid = 1;
        $users = DB::table('users')->inRandomOrder()->take(1)->get();
        if ($users->count() != 0){
            $uid = $users[0]->id;
        }

        return [
            'subject' => fake()->text(10),
            'content' => fake()->text(40),
            'user' => $uid,
            'status' => false,
        ];
    }

    /**
    * Indicate that the ticket is resolved
    *
    * @return array<string,mixed>
    */
    public function resolved():array {
        return [
            'status' => true,
        ];
    }

}
