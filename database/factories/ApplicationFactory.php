<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $submissionData = [
            "req_001" => ["file_path" => "options.pdf"],
            "req_002" => ["file_path" => "futures.pdf"]
        ];
        return [
            'student_id' => User::whereNotNull('student_id')->inRandomOrder()->first()?->id,
            'event_id' => Event::inRandomOrder()->first()?->id ?? $this->faker->uuid(),
            'award_id' => Award::inRandomOrder()->first()?->id ?? $this->faker->uuid(),
            'grade' => $this->faker->randomFloat(2, 2, 4),
            'path' => "form_1.pdf",
            'documents' => $submissionData,
            'status' => ApplicationStatus::SUBMITTED->value,
            'year' => $this->faker->numberBetween(1, 4)
        ];
    }
}
