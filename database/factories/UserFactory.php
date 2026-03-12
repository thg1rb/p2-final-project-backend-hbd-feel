<?php

namespace Database\Factories;

use App\Enums\CampusType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $department = \App\Models\Department::inRandomOrder()->first();

        if (! $department) {
            $faculty = \App\Models\Faculty::firstOrCreate(['name' => 'คณะวิทยาศาสตร์']);
            $department = \App\Models\Department::firstOrCreate([
                'name' => 'ภาควิชาวิทยาการคอมพิวเตอร์',
                'faculty_id' => $faculty->id,
            ]);
        }

        return [
            'student_id' => fake()->unique()->numerify('##########'),
            'firstName' => fake()->firstName(),
            'lastName' => fake()->lastName(),
            'username' => fake()->unique()->username(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'role' => fake()->randomElement(['NISIT']),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'campus' => fake()->randomElement(CampusType::cases()),

            'faculty_id' => $department->faculty_id,
            'department_id' => $department->id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
