<?php

namespace Database\Factories;

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
        $award = Award::inRandomOrder()->first() ?? Award::factory()->create();
        $submissionData = collect($award->form_schema)->mapWithKeys(function ($field) {
            $key = $field['key'];
            $value = '';

            switch ($field['type']) {
                case 'select':
                    $value = $this->faker->randomElement($field['options'] ?? ['N/A']);
                    break;
                case 'date':
                    $value = $this->faker->date();
                    break;
                default:
                    $value = $this->faker->sentence(3);
                    break;
            }

            return [$key => $value];
        })->toArray();
        return [
            'student_id' => User::inRandomOrder()->first()?->id ?? 'STD-TEMP',
            'event_id' => Event::inRandomOrder()->first()?->id ?? $this->faker->uuid(),
            'award_id' => Award::inRandomOrder()->first()?->id ?? $this->faker->uuid(),

            'submission_data' => $submissionData,
            'status' => 'SUBMITTED',
        ];
    }
}
