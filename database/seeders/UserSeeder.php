<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = \App\Models\Faculty::all();

        foreach (UserRole::cases() as $role) {
            $faculty = $faculties->random();
            $department = \App\Models\Department::where('faculty_id', $faculty->id)->get()->random();

            User::factory()->create([
                'student_id' => $role === UserRole::NISIT ? fake()->numerify("##########") : null,
                'firstName' => $role->value,
                'lastName' => 'Account',
                'username' => fake()->userName(),
                'email' => fake()->userName() . '@example.com',
                'password' => 'password',
                'role' => $role,
                'faculty_id' => $faculty->id,
                'department_id' => $department->id,
            ]);
        }

        // สำหรับนิสิต 10 คน
        for ($i = 0; $i < 10; $i++) {
            $faculty = $faculties->random();
            $department = \App\Models\Department::where('faculty_id', $faculty->id)->get()->random();

            User::factory()->create([
                'role' => UserRole::NISIT,
                'password' => 'password',
                'faculty_id' => $faculty->id,
                'department_id' => $department->id,
                'student_id' => fake()->numerify("##########"),
            ]);
        }
    }
}
