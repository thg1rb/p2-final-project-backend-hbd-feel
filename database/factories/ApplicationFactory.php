<?php

namespace Database\Factories;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
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
            'student_id' => null,
            'event_id' => 46,
            'award_id' => Award::inRandomOrder()->first()?->id ?? 1,
            'grade' => $this->faker->randomFloat(2, 2, 4),
            'path' => 'form_1.pdf',
            'documents' => $submissionData,
            'year' => $this->faker->numberBetween(1, 4),
            'level' => 6,
            'status' => $this->faker->randomElement(ApprovalStatus::cases())->value,
        ];
    }
}
