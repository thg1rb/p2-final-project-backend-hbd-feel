<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, '+6 months');

        return [
            'name' => $this->faker->name(),
            'academic_year' => $this->faker->numberBetween(2565, 2569),
            'semester' => $this->faker->numberBetween(1, 2),
            'status' => $this->faker->randomElement([Status::OPENED, Status::CLOSED]),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
