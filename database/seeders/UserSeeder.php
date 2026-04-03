<?php

namespace Database\Seeders;

use App\Enums\CampusType;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fixedUsers = [
            [
                'email' => 'narakorn.th@ku.th',
                'student_id' => '6610405905',
                'firstName' => 'นรากร',
                'lastName' => 'ธนภรภักดี',
                'username' => 'narakorn',
                'role' => UserRole::NISIT,
            ],
            [
                'email' => 'dept.head@example.com',
                'student_id' => null,
                'firstName' => 'สมชาย',
                'lastName' => 'สายวิชาการ',
                'username' => 'dept_head_01',
                'role' => UserRole::DEPT_HEAD,
            ],
            [
                'email' => 'asso.dean@example.com',
                'student_id' => null,
                'firstName' => 'จิราพร',
                'lastName' => 'รักษ์การศึกษา',
                'username' => 'asso_dean_01',
                'role' => UserRole::ASSO_DEAN,
            ],
            [
                'email' => 'dean@example.com',
                'student_id' => null,
                'firstName' => 'วิชา',
                'lastName' => 'ปัญญาเลิศ',
                'username' => 'dean_01',
                'role' => UserRole::DEAN,
            ],
            [
                'email' => 'admin@example.com',
                'student_id' => null,
                'firstName' => 'พัฒนพงศ์',
                'lastName' => 'วงค์นิสิต',
                'username' => 'admin_dev',
                'role' => UserRole::NISIT_DEV,
            ],
            [
                'email' => 'board@example.com',
                'student_id' => null,
                'firstName' => 'อำนาจ',
                'lastName' => 'ตัดสินใจ',
                'username' => 'board_01',
                'role' => UserRole::BOARD,
            ],
        ];

        foreach ($fixedUsers as $userData) {
            User::query()->updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => 'password',
                    'faculty_id' => 1,
                    'department_id' => 1,
                    'campus' => CampusType::BANGKHEN,
                ])
            );
        }

        $faculties = Faculty::all();
        if ($faculties->isEmpty()) return;

        for ($i = 0; $i < 10; $i++) {
            $faculty = $faculties->random();
            $department = Department::where('faculty_id', $faculty->id)->get()->random();

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
