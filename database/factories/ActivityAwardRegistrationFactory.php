<?php

namespace Database\Factories;

use App\Models\ActivityAwardRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityAwardRegistration>
 */
class ActivityAwardRegistrationFactory extends Factory
{
    protected $model = ActivityAwardRegistration::class;

    public function definition(): array
    {
        return [
            'activity_types' => [fake()->randomElement([
                'community',
                'competition',
                'leadership',
            ])],

            'award_date' => fake()->date(),

            'project_name' => fake()->sentence(3),
            'team_name' => fake()->word(),
            'work_name' => fake()->sentence(2),
            'award_name' => 'รางวัลดีเด่น',
            'organizer' => 'มหาวิทยาลัยเกษตรศาสตร์',
        ];
    }
}
