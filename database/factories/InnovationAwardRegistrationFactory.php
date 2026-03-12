<?php

namespace Database\Factories;

use App\Models\InnovationAwardRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InnovationAwardRegistration>
 */
class InnovationAwardRegistrationFactory extends Factory
{
    protected $model = InnovationAwardRegistration::class;

    public function definition(): array
    {
        return [
            'award_date' => fake()->date(),

            'project_name' => fake()->sentence(3),
            'team_name' => fake()->word(),
            'work_name' => fake()->sentence(2),
            'award_name' => 'รางวัลดีเด่น',
            'organizer' => 'มหาวิทยาลัยเกษตรศาสตร์',
        ];
    }
}
