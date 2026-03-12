<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventAwardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $events = Event::all();
        $awards = Award::all();

        foreach ($awards as $award) {
            $award->events()->attach(
                $events->random(1)->pluck('id')->toArray(),
                // [
                //     'round' => '2569/1',
                // ]
            );
        }
    }
}
