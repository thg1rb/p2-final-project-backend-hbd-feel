<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        // บังคับให้มัน Refresh ข้อมูลจาก DB จริงๆ อีกรอบ
        $students = \App\Models\User::query()
            ->whereNotNull('student_id')
            ->where('student_id', '!=', '')
            ->get();

        $this->command->warn("Actual students found in DB: " . $students->count());

        foreach ($students as $student) {
            \App\Models\Application::factory()->create([
                'student_id' => $student->student_id,
            ]);
        }
    }
}
