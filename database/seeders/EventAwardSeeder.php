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
        $events = Event::all();
        $awards = Award::all();

        foreach ($awards as $award) {
            $matchingEvents = $events->where('campus', $award->campus);

            if ($matchingEvents->isNotEmpty()) {
                $award->events()->attach(
                    $matchingEvents->random(1)->pluck('id')->toArray()
                );
            }
        }
    }
}
