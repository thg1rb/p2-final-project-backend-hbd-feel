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
            'activity_types' => [$this->faker->randomElement([
                'community',
                'competition',
                'leadership',
            ])],

            'award_date' => $this->faker->date(),

            'project_name' => $this->faker->sentence(3),
            'team_name' => $this->faker->word(),
            'work_name' => $this->faker->sentence(2),
            'award_name' => 'รางวัลดีเด่น',
            'organizer' => 'มหาวิทยาลัยเกษตรศาสตร์',
        ];
    }
}
