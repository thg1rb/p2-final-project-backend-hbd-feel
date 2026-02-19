<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $requirements = [
            [
                "id" => "req_001",
                "name" => "สำเนาบัตรประชาชน",
                "required" => true
            ],
            [
                "id" => "req_002",
                "name" => "Transcript",
                "required" => true
            ],
        ];
        return [
            'name' => $this->faker->words(3, true),
            'form_path' => "form_1.pdf",
            'requirements' => $requirements,
        ];
    }
}
