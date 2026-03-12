<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Award;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::query()
            ->whereNotNull('student_id')
            ->where('student_id', '!=', '')
            ->get();

        $this->command->warn("Actual students found in DB: " . $students->count());

        foreach ($students as $student) {
            // ค้นหารางวัลที่อยู่ใน Campus เดียวกับนิสิต
            // ใช้ inRandomOrder() เพื่อให้แต่ละคนได้รางวัลที่ไม่ซ้ำกัน (ในกรณีมีหลายรางวัลต่อวิทยาเขต)
            $award = Award::where('campus', $student->campus)
                ->inRandomOrder()
                ->first();

            if ($award) {
                Application::factory()->create([
                    'student_id' => $student->student_id,
                    'award_id'   => $award->id, // ส่ง ID ของรางวัลที่ Campus ตรงกันเข้าไป
                ]);
            } else {
                $this->command->error("No award found for campus: {$student->campus} (Student: {$student->student_id})");
            }
        }
    }
}
