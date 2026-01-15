<?php

namespace Database\Factories;

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
        return [
            'name' => $this->faker->name(),
            'academic_year' => $this->faker->year(),
            'semester' => $this->faker->numberBetween(1, 2),
            'status' => $this->faker->randomElement(["OPENED", "CLOSED"]),
        ];
    }
}
