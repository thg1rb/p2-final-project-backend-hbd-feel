<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $events = Event::all();

        foreach ($users as $user) {
            if ($user->role === "NISIT_DEV") {
                continue;
            }

            $user->events()->attach(
                $events->random(1)->pluck('id')->toArray(),
            );
        }
    }
}
