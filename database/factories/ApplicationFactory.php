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
        $user = User::whereNotNull('student_id')->inRandomOrder()->first();
        return [
            'student_id' => fn () => User::whereNotNull('student_id')->inRandomOrder()->first()?->student_id,
            'event_id' => fn () => Event::inRandomOrder()->first()?->id ?? $this->faker->uuid(),
            'award_id' => fn () => Award::inRandomOrder()->first()?->id ?? $this->faker->uuid(),
            'grade' => $this->faker->randomFloat(2, 2, 4),
            'path' => 'form_1.pdf',
            'documents' => $submissionData,
            'year' => $this->faker->numberBetween(1, 4),
            'level' => fake()->randomElement(RoleLevel::cases()),
            'status' => $this->faker->randomElement(ApprovalStatus::cases())->value,
        ];
    }
}
