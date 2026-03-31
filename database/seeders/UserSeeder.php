<?php

namespace Database\Seeders;

use App\Enums\CampusType;
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
            //

            User::query()->createOrFirst(
                ['email' => 'narakorn.th@ku.th',],
                [
                    'student_id' => 6610405905,
                    'firstName' => "Narakorn",
                    'lastName' => 'Thanapornpakdee',
                    'username' => 'narakorn',
                    'password' => 'password',
                    'role' => UserRole::NISIT,
                    'faculty_id' => 1,
                    'department_id' => 1,
                    'campus' => CampusType::BANGKHEN,
                ]
            );

            User::query()->firstOrCreate(
                ['email' => 'user01@example.com'],
                [
                    'student_id' => 6610400000,
                    'firstName' => "user",
                    'lastName' => 'user',
                    'username' => 'user01',
                    'password' => 'password',
                    'role' => UserRole::NISIT,
                    'faculty_id' => $faculty->id,
                    'department_id' => $department->id,
                    'campus' => CampusType::BANGKHEN,
                ]
            );

            User::query()->firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'student_id' => fake()->numerify("##########"),
                    'firstName' => "Adminstrator",
                    'lastName' => 'S',
                    'username' => 'admin',
                    'password' => 'password',
                    'role' => UserRole::NISIT_DEV,
                    'campus' => CampusType::BANGKHEN,
                ]
            );

            foreach (CampusType::cases() as $campus) {
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
                    'campus' => $campus
                ]);
            }
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
                'campus' => fake()->randomElement(CampusType::cases()),
            ]);
        }
    }
}
