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
            'award_date' => $this->faker()->date(),

            'project_name' => $this->faker()->sentence(3),
            'team_name' => $this->faker()->word(),
            'work_name' => $this->faker()->sentence(2),
            'award_name' => 'รางวัลดีเด่น',
            'organizer' => 'มหาวิทยาลัยเกษตรศาสตร์',
        ];
    }
}
