<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::factory()->create([
            'name' => 'normal',
            'email' => 'normal@tickets.com',
            'password' => Hash::make("normalnormal"),
        ]);
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@tickets.com',
            'password' => Hash::make("adminadmin"),
        ]);
        $admin = User::query()->where('name','=','admin')->get();
        Role::factory()->create([
            'user'=>$admin[0]->id,
            'role'=>'admin',
        ]);

        User::factory()
            ->count(30)
            ->create();
        Ticket::factory()
            ->count(20)
            ->create();
    }
}
