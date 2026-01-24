<?php

namespace Database\Seeders;

use App\Models\Award;
use App\Models\AwardRegistration;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AwardRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        $award = Award::find(1);
        $event = Event::find(1);

        // 1. สร้างรางวัลประเภทกิจกรรม 10 รายการ
        \App\Models\ActivityAwardRegistration::factory(10)->create()->each(function ($item) use ($user, $award, $event) {
            AwardRegistration::factory()->create([
                'user_id' => $user->id,
                'award_id' => $award->id,
                'event_id' => $event->id,
                'awardable_id' => $item->id,
//                'awardable_type' => \App\Models\ActivityAwardRegistration::class,
                'awardable_type' => 'activity',
//                'award_type' => 'activity',
            ]);
        });

        // 2. สร้างรางวัลประเภทพฤติกรรม 10 รายการ
        \App\Models\BehaviorAwardRegistration::factory(10)->create()->each(function ($item) use ($user, $award, $event) {
            AwardRegistration::factory()->create([
                'user_id' => $user->id,
                'award_id' => $award->id,
                'event_id' => $event->id,
                'awardable_id' => $item->id,
//                'awardable_type' => \App\Models\BehaviorAwardRegistration::class,
                'awardable_type' => 'behavior',
//                'award_type' => 'behavior',
            ]);
        });

        // 3. สร้างรางวัลประเภทนวัตกรรม 10 รายการ
        \App\Models\InnovationAwardRegistration::factory(10)->create()->each(function ($item) use ($user, $award, $event) {
            AwardRegistration::factory()->create([
                'user_id' => $user->id,
                'award_id' => $award->id,
                'event_id' => $event->id,
                'awardable_id' => $item->id,
//                'awardable_type' => \App\Models\InnovationAwardRegistration::class,
                'awardable_type' => 'innovation',
//                'award_type' => 'innovation',
            ]);
        });
    }
}
